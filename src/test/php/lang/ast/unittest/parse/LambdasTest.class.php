<?php namespace lang\ast\unittest\parse;

class LambdasTest extends ParseTest {

  #[@test]
  public function short_closure() {
    $block= ['+' => [['(variable)' => 'a'], ['(literal)' => '1']]];
    $this->assertNodes(
      [['(' => [[[['a', false, null, false, null, null]], null], $block]]],
      $this->parse('($a) ==> $a + 1;')
    );
  }

  #[@test]
  public function short_closure_as_arg() {
    $block= ['+' => [['(variable)' => 'a'], ['(literal)' => '1']]];
    $this->assertNodes(
      [['(' => [['exec' => 'exec'], [
        ['(' => [[[['a', false, null, false, null, null]], null], $block]]
      ]]]],
      $this->parse('exec(($a) ==> $a + 1);')
    );
  }
}