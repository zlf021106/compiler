<?php namespace lang\ast\unittest\emit;

use lang\IllegalArgumentException;
use unittest\Assert;

/**
 * Annotations support
 *
 * @see  https://github.com/xp-framework/compiler/issues/9
 * @see  https://docs.hhvm.com/hack/operators/null-safe
 * @see  https://wiki.php.net/rfc/nullsafe_calls (Draft)
 */
class NullSafeTest extends EmittingTest {

  #[@test]
  public function method_call_on_null() {
    $r= $this->run('class <T> {
      public function run() {
        $object= null;
        return $object?->method();
      }
    }');

    Assert::null($r);
  }

  #[@test]
  public function method_call_on_object() {
    $r= $this->run('class <T> {
      public function run() {
        $object= new class() {
          public function method() { return true; }
        };
        return $object?->method();
      }
    }');

    Assert::true($r);
  }

  #[@test]
  public function member_access_on_null() {
    $r= $this->run('class <T> {
      public function run() {
        $object= null;
        return $object?->member;
      }
    }');

    Assert::null($r);
  }

  #[@test]
  public function member_access_on_object() {
    $r= $this->run('class <T> {
      public function run() {
        $object= new class() {
          public $member= true;
        };
        return $object?->member;
      }
    }');

    Assert::true($r);
  }

  #[@test]
  public function chained_method_call() {
    $r= $this->run('
      class <T>Invocation {
        public static $invoked= [];
        public function __construct(private $name, private $chained) { }
        public function chained() { self::$invoked[]= $this->name; return $this->chained; }
      }

      class <T> {
        public function run() {
          $invokation= new <T>Invocation("outer", new <T>Invocation("inner", null));
          $return= $invokation?->chained()?->chained()?->chained();
          return [$return, <T>Invocation::$invoked];
        }
      }
    ');

    Assert::equals([null, ['outer', 'inner']], $r);
  }

  #[@test]
  public function dynamic_member_access_on_object() {
    $r= $this->run('class <T> {
      public function run() {
        $object= new class() {
          public $member= true;
        };
        $member= new class() {
          public fn name() => "member";
        };
        return $object?->{$member->name()};
      }
    }');

    Assert::true($r);
  }
}