<?php namespace lang\ast;

use lang\ast\nodes\Assignment;
use lang\ast\nodes\BinaryExpression;
use lang\ast\nodes\Literal;
use lang\ast\nodes\UnaryExpression;
use lang\reflect\Package;

abstract class Language {
  private static $instance= [];

  public $symbols= [];

  public function symbol($id, $lbp= 0) {
    if (isset($this->symbols[$id])) {
      $symbol= $this->symbols[$id];
      if ($lbp > $symbol->lbp) {
        $symbol->lbp= $lbp;
      }
    } else {
      $symbol= new Symbol();
      $symbol->id= $id;
      $symbol->lbp= $lbp;
      $this->symbols[$id]= $symbol;
    }
    return $symbol;
  }

  public function constant($id, $value) {
    $const= $this->symbol($id);
    $const->nud= function($parse, $node) use($value) {
      return new Literal($value, $node->line);
    };
  }

  public function assignment($id) {
    $infix= $this->symbol($id, 10);
    $infix->led= function($parse, $node, $left) use($id) {
      return new Assignment($left, $id, $this->expression($parse, 9), $node->line);
    };
  }

  public function infix($id, $bp, $led= null) {
    $infix= $this->symbol($id, $bp);
    $infix->led= $led ? $led->bindTo($this, static::class) : function($parse, $node, $left) use($id, $bp) {
      return new BinaryExpression($left, $id, $this->expression($parse, $bp), $node->line);
    };
  }

  public function infixr($id, $bp, $led= null) {
    $infix= $this->symbol($id, $bp);
    $infix->led= $led ? $led->bindTo($this, static::class) : function($parse, $node, $left) use($id, $bp) {
      return new BinaryExpression($left, $id, $this->expression($parse, $bp - 1), $node->line);
    };
  }

  public function infixt($id, $bp) {
    $infix= $this->symbol($id, $bp);
    $infix->led= function($parse, $node, $left) use($id, $bp) {
      return new BinaryExpression($left, $id, $this->expressionWithThrows($parse, $bp - 1), $node->line);
    };
  }

  public function prefix($id, $nud= null) {
    $prefix= $this->symbol($id);
    $prefix->nud= $nud ? $nud->bindTo($this, static::class) : function($parse, $node) use($id) {
      return new UnaryExpression($this->expression($parse, 0), $id, $node->line);
    };
  }

  public function suffix($id, $bp, $led= null) {
    $suffix= $this->symbol($id, $bp);
    $suffix->led= $led ? $led->bindTo($this, static::class) : function($parse, $node, $left) use($id) {
      return new UnaryExpression($left, $id, $node->line);
    };
  }

  public function stmt($id, $func) {
    $stmt= $this->symbol($id);
    $stmt->std= $func->bindTo($this, static::class);
  }

  public function expression($parse, $rbp) {
    $t= $parse->token;
    $parse->forward();
    $left= $t->symbol->nud ? $t->symbol->nud->__invoke($parse, $t) : $t;

    while ($rbp < $parse->token->symbol->lbp) {
      $t= $parse->token;
      $parse->forward();
      $left= $t->symbol->led ? $t->symbol->led->__invoke($parse, $t, $left) : $t;
    }

    return $left;
  }

  public function expressions($parse, $end= ')') {
    $arguments= [];
    while ($end !== $parse->token->value) {
      $arguments[]= $this->expression($parse, 0);
      if (',' === $parse->token->value) {
        $parse->forward();
      } else if ($end === $parse->token->value) {
        break;
      } else {
        $parse->expecting($end.' or ,', 'argument list');
        break;
      }
    }
    return $arguments;
  }

  /**
   * Returns a single statement
   *
   * @param  lang.ast.Parse $parse
   * @return lang.ast.Node
   */
  public function statement($parse) {
    if ($parse->token->symbol->std) {
      $t= $parse->token;
      $parse->forward();
      return $t->symbol->std->__invoke($parse, $t);
    }

    $expr= $this->expression($parse, 0);

    // Check for semicolon
    if (';' !== $parse->token->symbol->id) {
      $parse->raise('Missing semicolon after '.$expr->kind.' statement', null, $expr->line);
    } else {
      $parse->forward();
    }

    return $expr;
  }

  /**
   * Returns a list of statements
   *
   * @param  lang.ast.Parse $parse
   * @return lang.ast.Node[]
   */
  public function statements($parse) {
    $statements= [];
    while ('}' !== $parse->token->value) {
      if (null === ($statement= $this->statement($parse))) break;
      $statements[]= $statement;
    }
    return $statements;
  }

  public static function named($name) {
    if (!isset(self::$instance[$name])) {
      self::$instance[$name]= Package::forName('lang.ast.language')->loadClass($name)->newinstance();
    }
    return self::$instance[$name];
  }
}