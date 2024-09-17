<?php

use App\Helpers\Config\Dotenv;

define('FULL_PATH', dirname(__FILE__));
define('WEB_PATH', dirname(__FILE__).'/web');
define('TEMPLATES_PATH', dirname(__FILE__).'/templates');

ini_set('display_errors', 'Off');

session_start();

if(file_exists(__DIR__.'/vendor/autoload.php')) {
  if (true === (require_once __DIR__.'/vendor/autoload.php') || empty($_SERVER['SCRIPT_FILENAME'])) {
    return;
  }
}

autoload::register();

(new Dotenv(FULL_PATH.'/.env'))->load();

class autoload {

  static function register() {
    spl_autoload_register(array(__CLASS__, 'autoload'));
  }

  static function autoload ($class_name) {

    $class_name = explode('\\', $class_name);
    $class_name = array_pop($class_name);

    $dirs = self::list_directories(FULL_PATH);

    foreach ( $dirs as $dir) {
      
      self::require_file($dir, $class_name);
    }
    
  }

  static function require_file (string $path, string $class_name) {

    if (!is_dir($path)) {
      throw new Exception($path.' is not a directory', 3001);
    }
    
    if(file_exists($path.'/'.$class_name.'.php')) {
      require_once $path.'/'.$class_name.'.php';
    }

  }

  static function list_directories(string $directory) {
    $directories = [];

    if(is_dir($directory)) {
      $directories[] = $directory;

      $subdirectories = glob($directory . '/*', GLOB_ONLYDIR);

      foreach($subdirectories as $subdirectory) {
        $directories = array_merge($directories, self::list_directories($subdirectory));
      }
    }

    return $directories;
  }

}