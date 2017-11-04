<?php namespace lang\ast\emit;

/**
 * PHP 7.0 syntax
 *
 * @see  https://wiki.php.net/rfc/generator-delegation - Not yet implemented
 * @see  https://wiki.php.net/rfc/generator-return-expressions - Not yet implemented
 * @see  https://wiki.php.net/rfc/anonymous_classes
 * @see  https://wiki.php.net/rfc/return_types
 * @see  https://wiki.php.net/rfc/isset_ternary
 * @see  https://wiki.php.net/rfc/uniform_variable_syntax
 * @see  https://wiki.php.net/rfc/group_use_declarations
 * @see  https://wiki.php.net/rfc/scalar_type_hints_v5
 */
class PHP70 extends \lang\ast\Emitter {
  protected $unsupported= [
    'object'   => 72,
    'void'     => 71,
    'iterable' => 71
  ];

  protected function catches($catch) {
    $last= array_pop($catch->types);
    $label= sprintf('c%u', crc32($last));
    foreach ($catch->types as $type) {
      $this->out->write('catch('.$type.' $'.$catch->variable.') { goto '.$label.'; }');
    }

    $this->out->write('catch('.$last.' $'.$catch->variable.') { '.$label.':');
    $this->emit($catch->body);
    $this->out->write('}');
  }

  protected function emitAssignment($kind) {
    if ('array' === $kind->variable->arity) {
      $this->out->write('list(');
      foreach ($kind->variable->value as $pair) {
        $this->emit($pair[1]);
        $this->out->write(',');
      }
      $this->out->write(')');
      $this->out->write($kind->operator);
      $this->emit($kind->expression);
    } else {
      parent::emitAssignment($kind);
    }
  }

  protected function emitConst($kind) {
    $this->out->write('const '.$kind->name.'=');
    $this->emit($kind->expression);
    $this->out->write(';');
  }
}