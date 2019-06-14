<?php namespace lang\ast\unittest\emit;

class InvocationTest extends EmittingTest {

  #[@test]
  public function instance_method() {
    $this->assertEquals('instance', $this->run(
      'class <T> {

        public function instanceMethod() { return "instance"; }

        public function run() {
          return $this->instanceMethod();
        }
      }'
    ));
  }

  #[@test]
  public function instance_method_dynamic_variable() {
    $this->assertEquals('instance', $this->run(
      'class <T> {

        public function instanceMethod() { return "instance"; }

        public function run() {
          $method= "instanceMethod";
          return $this->{$method}();
        }
      }'
    ));
  }

  #[@test]
  public function instance_method_dynamic_expression() {
    $this->assertEquals('instance', $this->run(
      'class <T> {

        public function instanceMethod() { return "instance"; }

        public function run() {
          $method= fn() => "instanceMethod";
          return $this->{$method()}();
        }
      }'
    ));
  }

  #[@test]
  public function static_method() {
    $this->assertEquals('static', $this->run(
      'class <T> {

        public function staticMethod() { return "static"; }

        public function run() {
          return self::staticMethod();
        }
      }'
    ));
  }

  #[@test]
  public function static_method_dynamic() {
    $this->assertEquals('static', $this->run(
      'class <T> {

        public static function staticMethod() { return "static"; }

        public function run() {
          $method= "staticMethod";
          return self::$method();
        }
      }'
    ));
  }

  #[@test]
  public function closure() {
    $this->assertEquals('closure', $this->run(
      'class <T> {

        public function run() {
          $f= function() { return "closure"; };
          return $f();
        }
      }'
    ));
  }

  #[@test]
  public function global_function() {
    $this->assertEquals('function', $this->run(
      'function fixture() { return "function"; }
      class <T> {

        public function run() {
          return fixture();
        }
      }'
    ));
  }
}