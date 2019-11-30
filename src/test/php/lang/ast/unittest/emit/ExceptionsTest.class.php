<?php namespace lang\ast\unittest\emit;

use lang\IllegalArgumentException;
use unittest\Assert;

class ExceptionsTest extends EmittingTest {

  #[@test]
  public function catch_exception() {
    $t= $this->type('class <T> {
      public function run() {
        try {
          throw new \\lang\\IllegalArgumentException("test");
        } catch (\\lang\\IllegalArgumentException $expected) {
          return get_class($expected);
        }
      }
    }');

    Assert::equals(IllegalArgumentException::class, $t->newInstance()->run());
  }

  #[@test]
  public function line_number_matches() {
    $t= $this->type('class <T> {
      public function run() {
        try {
          throw new \\lang\\IllegalArgumentException("test");
        } catch (\\lang\\IllegalArgumentException $expected) {
          return $expected->getLine();
        }
      }
    }');

    Assert::equals(4, $t->newInstance()->run());
  }

  #[@test]
  public function catch_without_type() {
    $t= $this->type('class <T> {
      public function run() {
        try {
          throw new \\lang\\IllegalArgumentException("test");
        } catch ($e) {
          return get_class($e);
        }
      }
    }');

    Assert::equals(IllegalArgumentException::class, $t->newInstance()->run());
  }

  #[@test]
  public function finally_without_exception() {
    $t= $this->type('class <T> {
      public $closed= false;
      public function run() {
        try {
          // Nothing
        } finally {
          $this->closed= true;
        }
      }
    }');

    $instance= $t->newInstance();
    $instance->run();
    Assert::true($instance->closed);
  }

  #[@test]
  public function finally_with_exception() {
    $t= $this->type('class <T> {
      public $closed= false;
      public function run() {
        try {
          throw new \\lang\\IllegalArgumentException("test");
        } finally {
          $this->closed= true;
        }
      }
    }');

    $instance= $t->newInstance();
    try {
      $instance->run();
      $this->fail('Expected exception not caught', null, IllegalArgumentException::class);
    } catch (IllegalArgumentException $expected) {
      Assert::true($instance->closed);
    }
  }

  #[@test, @expect(IllegalArgumentException::class)]
  public function throw_expression_with_null_coalesce() {
    $t= $this->type('class <T> {
      public function run($user) {
        return $user ?? throw new \\lang\\IllegalArgumentException("test");
      }
    }');
    $t->newInstance()->run(null);
  }

  #[@test, @expect(IllegalArgumentException::class)]
  public function throw_expression_with_short_ternary() {
    $t= $this->type('class <T> {
      public function run($user) {
        return $user ?: throw new \\lang\\IllegalArgumentException("test");
      }
    }');
    $t->newInstance()->run(null);
  }

  #[@test, @expect(IllegalArgumentException::class)]
  public function throw_expression_with_normal_ternary() {
    $t= $this->type('class <T> {
      public function run($user) {
        return $user ? new User($user) : throw new \\lang\\IllegalArgumentException("test");
      }
    }');
    $t->newInstance()->run(null);
  }

  #[@test, @expect(IllegalArgumentException::class)]
  public function throw_expression_with_lambda() {
    $this->run('use lang\IllegalArgumentException; class <T> {
      public function run() {
        $f= fn() => throw new IllegalArgumentException("test");
        $f();
      }
    }');
  }

  #[@test, @expect(IllegalArgumentException::class)]
  public function throw_expression_with_lambda_throwing_variable() {
    $t= $this->type('class <T> {
      public function run($e) {
        $f= fn() => throw $e;
        $f();
      }
    }');
    $t->newInstance()->run(new IllegalArgumentException('Test'));
  }

  #[@test, @expect(IllegalArgumentException::class)]
  public function throw_expression_with_lambda_capturing_variable() {
    $this->run('use lang\IllegalArgumentException; class <T> {
      public function run() {
        $f= fn($message) => throw new IllegalArgumentException($message);
        $f("test");
      }
    }');
  }

  #[@test, @expect(IllegalArgumentException::class)]
  public function throw_expression_with_lambda_capturing_parameter() {
    $t= $this->type('use lang\IllegalArgumentException; class <T> {
      public function run($message) {
        $f= fn() => throw new IllegalArgumentException($message);
        $f();
      }
    }');
    $t->newInstance()->run('Test');
  }
}