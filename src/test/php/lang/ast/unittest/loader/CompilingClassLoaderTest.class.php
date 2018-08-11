<?php namespace lang\ast\unittest\loader;

use io\File;
use io\FileUtil;
use io\Folder;
use lang\ClassFormatException;
use lang\ClassLoader;
use lang\Environment;
use lang\ast\CompilingClassLoader;
use unittest\TestCase;

class CompilingClassLoaderTest extends TestCase {
  private static $runtime;

  static function __static() {
    self::$runtime= defined('HHVM_VERSION') ? 'HHVM.'.HHVM_VERSION : 'PHP.'.PHP_VERSION;
  }

  /**
   * Loads a class from source
   *
   * @param  string $type
   * @param  string $source
   * @return lang.XPClass
   */
  private function load($type, $source) {
    $namespace= 'ns'.uniqid();
    $folder= new Folder(Environment::tempDir(), $namespace);
    $folder->exists() || $folder->create();

    FileUtil::setContents(new File($folder, $type.'.php'), sprintf($source, $namespace));
    $cl= ClassLoader::registerPath($folder->path);

    $loader= new CompilingClassLoader(self::$runtime);
    try {
      return $loader->loadClass($namespace.'.'.$type);
    } finally {
      ClassLoader::removeLoader($cl);
      $folder->unlink();
    }
  }

  #[@test]
  public function can_create() {
    new CompilingClassLoader(self::$runtime);
  }

  #[@test]
  public function load_class() {
    $this->assertEquals('Tests', $this->load('Tests', '<?php namespace %s; class Tests { }')->getSimpleName());
  }

  #[@test, @expect(
  #  class= ClassFormatException::class,
  #  withMessage= '/Syntax error in .+Errors.php, line 2: Expected ";", have "Syntax"/'
  #)]
  public function load_class_with_syntax_errors() {
    $this->load('Errors', "<?php\n<Syntax error in line 2>");
  }

  #[@test]
  public function triggered_errors_filename() {
    $t= $this->load('Triggers', '<?php namespace %s; class Triggers { 
      public function trigger() {
        trigger_error("Test");
      }
    }');

    $t->newInstance()->trigger();
    $name= (defined('HHVM_VERSION') ? 'src://' : '').strtr($t->getName(), '.', '/').'.php';
    $this->assertEquals($name, key(\xp::$errors));
    \xp::gc();
  }
}
