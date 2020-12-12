<?php namespace lang\ast\syntax\php\unittest;

use lang\Enum;
use lang\ast\unittest\emit\EmittingTest;
use unittest\{Assert, Test};

class EnumsTest extends EmittingTest {

  /**
   * Assertion helper
   *
   * @param  [:int] $expected
   * @param  lang.Type $type
   * @throws unittest.AssertionFailedError
   */
  private function assertEnum($expected, $type) {
    Assert::true($type->isEnum(), 'type is enum');

    $actual= [];
    foreach (Enum::valuesOf($type) as $value) {
      $actual[$value->name()]= $value->ordinal();
    }
    Assert::equals($expected, $actual);
  }

  #[Test]
  public function weekday_enum() {
    $t= $this->type('enum <T> {
      MON, TUE, WED, THU, FRI, SAT, SUN;
    }');

    $this->assertEnum(
      ['MON' => 0, 'TUE' => 1, 'WED' => 2, 'THU' => 3, 'FRI' => 4, 'SAT' => 5, 'SUN' => 6],
      $t
    );
  }

  #[Test]
  public function coin_enum() {
    $t= $this->type('enum <T> {
      penny(1), nickel(2), dime(10), quarter(25);
    }');

    $this->assertEnum(
      ['penny' => 1, 'nickel' => 2, 'dime' => 10, 'quarter' => 25],
      $t
    );
  }

  #[Test]
  public function suit_enum() {
    $t= $this->type('enum <T> {
      hearts, diamonds, clubs, spades;

      public function color() {
        return match ($this) {
          self::$hearts, self::$diamonds => "red",
          self::$clubs, self::$spades => "black",
        };
      }
    }');

    Assert::equals('red', Enum::valueOf($t, 'diamonds')->color());
    Assert::equals('black', Enum::valueOf($t, 'clubs')->color());
  }

  #[Test]
  public function os_enum() {
    $t= $this->type('abstract enum <T> {
      WIN {
        public function root() { return "C:"; }
      },
      UNIX {
        public function root() { return "/"; }
      };

      public abstract function root();
    }');

    Assert::equals('C:', Enum::valueOf($t, 'WIN')->root());
  }
}