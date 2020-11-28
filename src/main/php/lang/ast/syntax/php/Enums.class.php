<?php namespace lang\ast\syntax\php;

use lang\ast\nodes\{Assignment, ClassDeclaration, Literal, Method, NewClassExpression, NewExpression, Property, ScopeExpression, Signature, Variable};
use lang\ast\syntax\Extension;

class Enums implements Extension {

  public function setup($language, $emitter) {
    $language->stmt('enum', function($parse, $token) {
      $type= $parse->scope->resolve($parse->token->value);
      $parse->forward();

      $comment= $parse->comment;
      $parse->comment= null;
      $line= $parse->token->line;

      $parent= null;
      if ('extends' === $parse->token->value) {
        $parse->forward();
        $parent= $parse->scope->resolve($parse->token->value);
        $parse->forward();
      }

      $implements= [];
      if ('implements' === $parse->token->value) {
        $parse->forward();
        do {
          $implements[]= $parse->scope->resolve($parse->token->value);
          $parse->forward();
          if (',' === $parse->token->value) {
            $parse->forward();
            continue;
          } else if ('{' === $parse->token->value) {
            break;
          } else {
            $parse->expecting(', or {', 'interfaces list');
          }
        } while (null !== $parse->token->value);
      }

      $parse->expecting('{', 'enum');

      // Enum members
      $members= new EnumMembers($parse->token->line);
      $ordinal= -1;
      do {
        $name= $parse->token->value;
        $parse->forward();

        // Optional ordinal
        if ('(' === $parse->token->value) {
          $parse->forward();
          $ordinal= (int)$parse->token->value;
          $parse->forward();
          $parse->expecting(')', 'emum members');
        } else {
          $ordinal++;
        }

        // Body
        if ('{' === $parse->token->value) {
          $parse->forward();
          $body= $this->typeBody($parse, $name);
          $parse->expecting('}', 'enum members');
        } else {
          $body= [];
        }

        if (',' === $parse->token->value) {
          $parse->forward();
          $members->add($name, $ordinal, $body);
          continue;
        } else if (';' === $parse->token->value) {
          $members->add($name, $ordinal, $body);
          $parse->forward();
          break;
        } else {
          $parse->expecting(', or {', 'enum members');
        }
      } while (null !== $parse->token->value);

      // Type body
      $body= $this->typeBody($parse, $type);
      $parse->expecting('}', 'enum');

      $return= new EnumDeclaration([], $type, $parent, $implements, $members, $body, [], $comment, $line);
      $parse->scope->annotations= [];
      return $return;
    });

    $emitter->transform('enum', function($codegen, $node) {
      $body= $node->body;
      $static= new Method(['static'], '__static', new Signature([], null), []);

      // Create static intializer and properties
      $init= clone $static;
      foreach ($node->members->all() as $name => $member) {
        $body[]= new Property(['public', 'static'], $name, null);
        $args= [new Literal($member[0]), new Literal("'".$name."'")];

        if ($member[1]) {
          $child= ['__static()' => clone $static] + $member[1];
          $member= new NewClassExpression(new ClassDeclaration([], null, $node->name, [], $child), $args);
        } else {
          $member= new NewExpression('self', $args);
        }
        $init->body[]= new Assignment(new ScopeExpression('self', new Variable($name)), '=', $member);
      }
      $body[]= $init;

      return new ClassDeclaration(
        $node->modifiers,
        $node->name,
        $node->parent ?: '\lang\Enum',
        $node->implements,
        $body,
        $node->annotations,
        $node->comment,
        $node->line
      );
    });
  }
}