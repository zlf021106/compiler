<?php namespace lang\ast;

use io\streams\{MemoryOutputStream, StringWriter};

class Result {
  public $out;
  public $codegen;
  public $line= 1;
  public $meta= [];
  public $locals= [];
  public $stack= [];
  public $call= [];

  /**
   * Starts an result stream, including a preamble
   *
   * @param io.streams.Writer
   * @param string $preamble
   */
  public function __construct($out, $preamble= '<?php ') {
    $this->out= $out;
    $this->out->write($preamble);
    $this->codegen= new CodeGen();
  }

  /**
   * Creates a temporary variable and returns its name
   *
   * @return string
   */
  public function temp() {
    return '$'.$this->codegen->symbol();
  }

  /**
   * Collects emitted code into a buffer and returns it
   *
   * @param  function(lang.ast.Result): void $callable
   * @return string
   */
  public function buffer($callable) {
    $out= $this->out;
    $buffer= new MemoryOutputStream();
    $this->out= new StringWriter($buffer);

    try {
      $callable($this);
      return $buffer->getBytes();
    } finally {
      $this->out= $out;
    }
  }
}