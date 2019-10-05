<?php namespace lang\ast\syntax\php\unittest;

use lang\Enum;
use lang\ast\unittest\emit\EmittingTest;

class EnumsTest extends EmittingTest {

  /**
   * Assertion helper
   *
   * @param  [:int] $expected
   * @param  lang.Type $type
   * @throws unittest.AssertionFailedError
   */
  private function assertEnum($expected, $type) {
    $this->assertTrue($type->isEnum(), 'type is enum');

    $actual= [];
    foreach (Enum::valuesOf($type) as $value) {
      $actual[$value->name()]= $value->ordinal();
    }
    $this->assertEquals($expected, $actual);
  }

  #[@test]
  public function weekday_enum() {
    $t= $this->type('enum <T> {
      MON, TUE, WED, THU, FRI, SAT, SUN;
    }');

    $this->assertEnum(
      ['MON' => 0, 'TUE' => 1, 'WED' => 2, 'THU' => 3, 'FRI' => 4, 'SAT' => 5, 'SUN' => 6],
      $t
    );
  }

  #[@test]
  public function coin_enum() {
    $t= $this->type('enum <T> {
      penny(1), nickel(2), dime(10), quarter(25);
    }');

    $this->assertEnum(
      ['penny' => 1, 'nickel' => 2, 'dime' => 10, 'quarter' => 25],
      $t
    );
  }

  #[@test]
  public function os_enum() {
    $t= $this->type('enum <T> {
      WIN {
        public function root() { return "C:"; }
      },
      UNIX {
        public function root() { return "/"; }
      };

      public abstract function root();
    }');

    $this->assertEquals('C:', Enum::valueOf($t, 'WIN')->root());
  }
}