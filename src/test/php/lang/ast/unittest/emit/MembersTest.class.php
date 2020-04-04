<?php namespace lang\ast\unittest\emit;

use unittest\Assert;

class MembersTest extends EmittingTest {

  #[@test]
  public function class_property() {
    $r= $this->run('class <T> {
      private static $MEMBER= "Test";

      public function run() {
        return self::$MEMBER;
      }
    }');

    Assert::equals('Test', $r);
  }

  #[@test]
  public function class_method() {
    $r= $this->run('class <T> {
      private static function member() { return "Test"; }

      public function run() {
        return self::member();
      }
    }');

    Assert::equals('Test', $r);
  }

  #[@test]
  public function class_constant() {
    $r= $this->run('class <T> {
      private const MEMBER = "Test";

      public function run() {
        return self::MEMBER;
      }
    }');

    Assert::equals('Test', $r);
  }

  #[@test]
  public function dynamic_class_property() {
    $r= $this->run('class <T> {
      private static $MEMBER= "Test";

      public function run() {
        $class= self::class;
        return $class::$MEMBER;
      }
    }');

    Assert::equals('Test', $r);
  }

  #[@test]
  public function dynamic_class_method() {
    $r= $this->run('class <T> {
      private static function member() { return "Test"; }

      public function run() {
        $class= self::class;
        return $class::member();
      }
    }');

    Assert::equals('Test', $r);
  }

  #[@test]
  public function dynamic_class_constant() {
    $r= $this->run('class <T> {
      private const MEMBER = "Test";

      public function run() {
        $class= self::class;
        return $class::MEMBER;
      }
    }');

    Assert::equals('Test', $r);
  }

  #[@test]
  public function object_class_constant() {
    $r= $this->run('class <T> {
      private const MEMBER = "Test";

      public function run() {
        return $this::MEMBER;
      }
    }');

    Assert::equals('Test', $r);
  }

  #[@test, @values(['variable', 'invocation', 'array'])]
  public function class_on_objects($via) {
    $t= $this->type('class <T> {
      private function this() { return $this; }

      public function variable() { return $this::class; }

      public function invocation() { return $this->this()::class; }

      public function array() { return [$this][0]::class; }
    }');

    $fixture= $t->newInstance();
    Assert::equals(get_class($fixture), $t->getMethod($via)->invoke($fixture));
  }

  #[@test]
  public function instance_property() {
    $r= $this->run('class <T> {
      private $member= "Test";

      public function run() {
        return $this->member;
      }
    }');

    Assert::equals('Test', $r);
  }

  #[@test]
  public function instance_method() {
    $r= $this->run('class <T> {
      private function member() { return "Test"; }

      public function run() {
        return $this->member();
      }
    }');

    Assert::equals('Test', $r);
  }

  #[@test]
  public function static_initializer_run() {
    $r= $this->run('class <T> {
      private static $MEMBER;

      static function __static() {
        self::$MEMBER= "Test";
      }

      public function run() {
        return self::$MEMBER;
      }
    }');

    Assert::equals('Test', $r);
  }

  #[@test]
  public function enum_members() {
    $r= $this->run('class <T> extends \lang\Enum {
      public static $MON, $TUE, $WED, $THU, $FRI, $SAT, $SUN;

      public function run() {
        return self::$MON->name();
      }
    }');

    Assert::equals('MON', $r);
  }

  #[@test]
  public function method_with_static() {
    $r= $this->run('class <T> {
      public function run() {
        static $var= "Test";
        return $var;
      }
    }');

    Assert::equals('Test', $r);
  }

  #[@test]
  public function method_with_static_without_initializer() {
    $r= $this->run('class <T> {
      public function run() {
        static $var;
        return $var;
      }
    }');

    Assert::null($r);
  }

  #[@test]
  public function chaining_sccope_operators() {
    $r= $this->run('class <T> {
      private const TYPE = self::class;

      private const NAME = "Test";

      private static $name = "Test";

      private static function name() { return "Test"; }

      public function run() {
        $name= "name";
        return [self::TYPE::NAME, self::TYPE::$name, self::TYPE::name(), self::TYPE::$name()];
      }
    }');

    Assert::equals(['Test', 'Test', 'Test', 'Test'], $r);
  }
}