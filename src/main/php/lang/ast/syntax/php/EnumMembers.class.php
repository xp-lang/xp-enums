<?php namespace lang\ast\syntax\php;

use lang\ast\Node;

class EnumMembers extends Node {
  public $kind= 'enummembers';
  public $members= [];

  public function __construct($line= -1) {
    $this->line= $line;
  }

  public function add($name, $ordinal, $body) {
    $this->members[$name]= [$ordinal, $body];
  }

  public function all() { return $this->members; }
}