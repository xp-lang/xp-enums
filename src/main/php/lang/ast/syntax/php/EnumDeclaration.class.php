<?php namespace lang\ast\syntax\php;

use lang\ast\nodes\TypeDeclaration;

class EnumDeclaration extends TypeDeclaration {
  public $kind= 'enum';
  public $name, $modifiers, $parent, $implements, $members, $body, $annotations, $comment;

  public function __construct($modifiers, $name, $parent, $implements, $members, $body, $annotations= [], $comment= null, $line= -1) {
    $this->modifiers= $modifiers;
    $this->name= $name;
    $this->parent= $parent;
    $this->implements= $implements;
    $this->members= $members;
    $this->body= $body;
    $this->annotations= $annotations;
    $this->comment= $comment;
    $this->line= $line;
  }
}