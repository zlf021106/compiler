<?php namespace lang\ast\unittest;

use io\streams\MemoryOutputStream;
use lang\ast\nodes\Variable;
use lang\ast\{Emitter, Node, Code, Result};
use lang\{IllegalStateException, IllegalArgumentException};
use unittest\{Assert, Expect, Test, TestCase};

class EmitterTest {

  private function newEmitter() {
    return Emitter::forRuntime('PHP.'.PHP_VERSION)->newInstance();
  }

  #[Test]
  public function can_create() {
    $this->newEmitter();
  }

  #[Test, Expect(IllegalArgumentException::class)]
  public function cannot_create_for_unsupported_php_version() {
    Emitter::forRuntime('PHP.4.3.0');
  }

  #[Test]
  public function transformations_initially_empty() {
    Assert::equals([], $this->newEmitter()->transformations());
  }

  #[Test]
  public function transform() {
    $function= function($class) { return $class; };

    $fixture= $this->newEmitter();
    $fixture->transform('class', $function);
    Assert::equals(['class' => [$function]], $fixture->transformations());
  }

  #[Test]
  public function remove() {
    $first= function($codegen, $class) { return $class; };
    $second= function($codegen, $class) { $class->annotations['author']= 'Test'; return $class; };

    $fixture= $this->newEmitter();
    $transformation= $fixture->transform('class', $first);
    $fixture->transform('class', $second);
    $fixture->remove($transformation);
    Assert::equals(['class' => [$second]], $fixture->transformations());
  }

  #[Test]
  public function remove_unsets_empty_kind() {
    $function= function($codegen, $class) { return $class; };

    $fixture= $this->newEmitter();
    $transformation= $fixture->transform('class', $function);
    $fixture->remove($transformation);
    Assert::equals([], $fixture->transformations());
  }

  #[Test, Expect(IllegalStateException::class)]
  public function emit_node_without_kind() {
    $this->newEmitter()->emitOne(new Result(new MemoryOutputStream()), new class() extends Node {
      public $kind= null;
    });
  }

  #[Test]
  public function transform_modifying_node() {
    $fixture= $this->newEmitter();
    $fixture->transform('variable', function($codegen, $var) { $var->name= '_'.$var->name; return $var; });
    $out= new MemoryOutputStream();
    $fixture->emitOne(new Result($out), new Variable('a'));

    Assert::equals('<?php $_a', $out->bytes());
  }

  #[Test]
  public function transform_to_node() {
    $fixture= $this->newEmitter();
    $fixture->transform('variable', function($codegen, $var) { return new Code('$variables["'.$var->name.'"]'); });
    $out= new MemoryOutputStream();
    $fixture->emitOne(new Result($out), new Variable('a'));

    Assert::equals('<?php $variables["a"]', $out->bytes());
  }

  #[Test]
  public function transform_to_array() {
    $fixture= $this->newEmitter();
    $fixture->transform('variable', function($codegen, $var) { return [new Code('$variables["'.$var->name.'"]')]; });
    $out= new MemoryOutputStream();
    $fixture->emitOne(new Result($out), new Variable('a'));

    Assert::equals('<?php $variables["a"];', $out->bytes());
  }

  #[Test]
  public function transform_to_null() {
    $fixture= $this->newEmitter();
    $fixture->transform('variable', function($codegen, $var) { return null; });
    $out= new MemoryOutputStream();
    $fixture->emitOne(new Result($out), new Variable('a'));

    Assert::equals('<?php $a', $out->bytes());
  }
}