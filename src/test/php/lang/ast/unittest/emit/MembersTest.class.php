<?php namespace lang\ast\unittest\emit;

class MembersTest extends EmittingTest {

  #[@test]
  public function class_property() {
    $r= $this->run('class <T> {
      private static $MEMBER= "Test";

      public function run() {
        return self::$MEMBER;
      }
    }');

    $this->assertEquals('Test', $r);
  }

  #[@test]
  public function class_method() {
    $r= $this->run('class <T> {
      private static function member() { return "Test"; }

      public function run() {
        return self::member();
      }
    }');

    $this->assertEquals('Test', $r);
  }

  #[@test]
  public function instance_property() {
    $r= $this->run('class <T> {
      private $member= "Test";

      public function run() {
        return $this->member;
      }
    }');

    $this->assertEquals('Test', $r);
  }

  #[@test]
  public function instance_method() {
    $r= $this->run('class <T> {
      private function member() { return "Test"; }

      public function run() {
        return $this->member();
      }
    }');

    $this->assertEquals('Test', $r);
  }
}