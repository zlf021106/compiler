<?php namespace lang\ast\unittest\emit;

/**
 * Using statement and disposables
 *
 * @see  https://docs.hhvm.com/hack/disposables/introduction
 */
class UsingTest extends EmittingTest {

  #[@test]
  public function dispose_called() {
    $r= $this->run('use lang\ast\unittest\emit\Handle; class <T> {
      public function run() {
        Handle::$called= [];

        using ($x= new Handle(1)) {
          $x->read();
        }

        return Handle::$called;
      }
    }');
    $this->assertEquals(['read@1', '__dispose@1'], $r);
  }

  #[@test]
  public function dispose_called_for_all() {
    $r= $this->run('use lang\ast\unittest\emit\Handle; class <T> {
      public function run() {
        Handle::$called= [];

        using ($x= new Handle(1), new Handle(2)) {
          $x->read();
        }

        return Handle::$called;
      }
    }');
    $this->assertEquals(['read@1', '__dispose@1', '__dispose@2'], $r);
  }

  #[@test]
  public function dispose_called_even_when_exceptions_occur() {
    $r= $this->run('use lang\{IllegalArgumentException, IllegalStateException}; use lang\ast\unittest\emit\Handle; class <T> {
      public function run() {
        Handle::$called= [];

        try {
          using ($x= new Handle(1)) {
            $x->read(-1);
          }
        } catch (IllegalArgumentException $expected) {
          return Handle::$called;  
        }

        throw new IllegalStateException("No exception caught");
      }
    }');
    $this->assertEquals(['read@1', '__dispose@1'], $r);
  }

  #[@test]
  public function supports_closeables() {
    $r= $this->run('use lang\ast\unittest\emit\FileInput; class <T> {
      public function run() {
        FileInput::$open= false;

        using ($f= new FileInput(__FILE__)) {
          $f->read();
        }

        return FileInput::$open;
      }
    }');
    $this->assertFalse($r);
  }

  #[@test]
  public function can_return_from_inside_using() {
    $r= $this->run('use lang\ast\unittest\emit\Handle; class <T> {
      private function read() {
        using ($x= new Handle(1)) {
          return $x->read();
        }
      }

      public function run() {
        Handle::$called= [];
        $returned= $this->read();
        return ["called" => Handle::$called, "returned" => $returned];
      }
    }');
    $this->assertEquals(['called' => ['read@1', '__dispose@1'], 'returned' => 'test'], $r);
  }

  #[@test]
  public function variable_undefined_after_using() {
    $r= $this->run('use lang\ast\unittest\emit\Handle; class <T> {
      public function run() {
        using ($x= new Handle(1)) {
          // NOOP
        }
        return isset($x);
      }
    }');
    $this->assertFalse($r);
  }

  #[@test]
  public function variable_undefined_after_using_even_if_previously_defined() {
    $r= $this->run('use lang\ast\unittest\emit\Handle; class <T> {
      public function run() {
        $x= new Handle(1);
        using ($x) {
          // NOOP
        }
        return isset($x);
      }
    }');
    $this->assertFalse($r);
  }
}