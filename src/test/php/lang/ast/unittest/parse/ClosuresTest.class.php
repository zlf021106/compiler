<?php namespace lang\ast\unittest\parse;

use lang\ast\FunctionType;
use lang\ast\Type;
use lang\ast\nodes\BinaryExpression;
use lang\ast\nodes\ClosureExpression;
use lang\ast\nodes\Literal;
use lang\ast\nodes\Parameter;
use lang\ast\nodes\ReturnStatement;
use lang\ast\nodes\Signature;
use lang\ast\nodes\Variable;

class ClosuresTest extends ParseTest {
  private $returns;

  /** @return void */
  public function setUp() {
    $this->returns= new ReturnStatement(
      new BinaryExpression(
        new Variable('a', self::LINE),
        '+',
        new Literal('1', self::LINE),
        self::LINE
      ),
      self::LINE
    );
  }

  #[@test]
  public function with_body() {
    $this->assertParsed(
      [new ClosureExpression(new Signature([], null), null, [$this->returns], self::LINE)],
      'function() { return $a + 1; };'
    );
  }

  #[@test]
  public function with_param() {
    $params= [new Parameter('a', null, null, false, false, null, [])];
    $this->assertParsed(
      [new ClosureExpression(new Signature($params, null), null, [$this->returns], self::LINE)],
      'function($a) { return $a + 1; };'
    );
  }

  #[@test]
  public function with_use_by_value() {
    $this->assertParsed(
      [new ClosureExpression(new Signature([], null), ['$a', '$b'], [$this->returns], self::LINE)],
      'function() use($a, $b) { return $a + 1; };'
    );
  }

  #[@test]
  public function with_use_by_reference() {
    $this->assertParsed(
      [new ClosureExpression(new Signature([], null), ['$a', '&$b'], [$this->returns], self::LINE)],
      'function() use($a, &$b) { return $a + 1; };'
    );
  }

  #[@test]
  public function with_return_type() {
    $this->assertParsed(
      [new ClosureExpression(new Signature([], new Type('int')), null, [$this->returns], self::LINE)],
      'function(): int { return $a + 1; };'
    );
  }

  #[@test]
  public function with_nullable_return_type() {
    $this->assertParsed(
      [new ClosureExpression(new Signature([], new Type('?int')), null, [$this->returns], self::LINE)],
      'function(): ?int { return $a + 1; };'
    );
  }
}