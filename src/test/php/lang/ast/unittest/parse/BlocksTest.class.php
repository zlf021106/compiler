<?php namespace lang\ast\unittest\parse;

use lang\ast\nodes\Block;
use lang\ast\nodes\InvokeExpression;
use lang\ast\nodes\Literal;

class BlocksTest extends ParseTest {

  #[@test]
  public function empty_block() {
    $this->assertParsed(
      [new Block([], self::LINE)],
      '{ }'
    );
  }

  #[@test]
  public function with_invoke() {
    $this->assertParsed(
      [new Block([new InvokeExpression(new Literal('block', self::LINE), [], self::LINE)], self::LINE)],
      '{ block(); }'
    );
  }
}