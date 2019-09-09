<?php namespace lang\ast\unittest\emit;

class PrecedenceTest extends EmittingTest {

  #[@test, @values([
  #  ['2 + 3 * 4', 14],
  #  ['2 + 8 / 4', 4],
  #  ['2 + 3 ** 2', 11],
  #  ['2 + 5 % 2', 3],
  #])]
  public function mathematical($input, $result) {
    $this->assertEquals($result, $this->run(
      'class <T> {
        public function run() {
          return '.$input.';
        }
      }'
    ));
  }

  #[@test]
  public function concatenation() {
    $t= $this->type(
      'class <T> {
        public function run() {
          return "(".self::class.")";
        }
      }'
    );
    $this->assertEquals('('.$t->getName().')', $t->newinstance()->run());
  }
}