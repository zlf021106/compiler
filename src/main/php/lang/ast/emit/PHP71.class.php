<?php namespace lang\ast\emit;

/**
 * PHP 7.1 syntax
 *
 * @see  https://wiki.php.net/rfc/nullable_types - Not yet implemented!
 * @see  https://wiki.php.net/rfc/short_list_syntax - Not yet implemented!
 * @see  https://wiki.php.net/rfc/multiple-catch
 * @see  https://wiki.php.net/rfc/void_return_type
 * @see  https://wiki.php.net/rfc/iterable
 */
class PHP71 extends \lang\ast\Emitter {

  protected function type($name) {
    return $name;
  }

  protected function catches($catch) {
    $this->out->write('catch('.implode('|', $catch[0]).' $'.$catch[1].') {');
    $this->emit($catch[2]);
    $this->out->write('}');
  }
}