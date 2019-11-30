<?php namespace lang\ast\unittest;

use io\streams\MemoryOutputStream;
use lang\IllegalStateException;
use lang\ast\{Emitter, Node, Result};
use unittest\Assert;
use unittest\TestCase;

class EmitterTest {

  private function newEmitter() {
    return Emitter::forRuntime('PHP.'.PHP_VERSION)->newInstance();
  }

  #[@test]
  public function can_create() {
    $this->newEmitter();
  }

  #[@test]
  public function transformations_initially_empty() {
    Assert::equals([], $this->newEmitter()->transformations());
  }

  #[@test]
  public function transform() {
    $function= function($class) { return $class; };

    $fixture= $this->newEmitter();
    $fixture->transform('class', $function);
    Assert::equals(['class' => [$function]], $fixture->transformations());
  }

  #[@test]
  public function remove() {
    $first= function($class) { return $class; };
    $second= function($class) { $class->annotations['author']= 'Test'; return $class; };

    $fixture= $this->newEmitter();
    $transformation= $fixture->transform('class', $first);
    $fixture->transform('class', $second);
    $fixture->remove($transformation);
    Assert::equals(['class' => [$second]], $fixture->transformations());
  }

  #[@test]
  public function remove_unsets_empty_kind() {
    $function= function($class) { return $class; };

    $fixture= $this->newEmitter();
    $transformation= $fixture->transform('class', $function);
    $fixture->remove($transformation);
    Assert::equals([], $fixture->transformations());
  }

  #[@test, @expect(IllegalStateException::class)]
  public function emit_node_without_kind() {
    $this->newEmitter()->emitOne(new Result(new MemoryOutputStream()), new class() extends Node {
      public $kind= null;
    });
  }
}