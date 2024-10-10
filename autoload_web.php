<?php

use App\Helpers\Config\Dotenv;
use App\Helpers\Database\DatabaseUtils;
use App\Helpers\Tools\Dump;
use App\Helpers\Tools\JWT;

define('FULL_PATH', dirname(__FILE__));
define('WEB_PATH', dirname(__FILE__).'/web');
define('TEMPLATES_PATH', dirname(__FILE__).'/templates');



//Exit if spam ip registered
$banned = false;

if(file_exists(FULL_PATH.'/data/ban_ip.json')) {
  $spam_ip = json_decode(file_get_contents(FULL_PATH.'/data/ban_ip.json'), true);
  if(isset($spam_ip['spam_ip']) && $spam_ip['spam_ip'] !== [] && isset($_SERVER['REMOTE_ADDR'])) {
    if(in_array($_SERVER['REMOTE_ADDR'], $spam_ip['spam_ip'])) {
      $banned = true;
    } 
  }
}

$ip = $_SERVER['REMOTE_ADDR'];

if($banned) {
  $content = "";
  if(file_exists(FULL_PATH.'/logs/spam.log')) {
    $content = file_get_contents(FULL_PATH.'/logs/spam.log');
  }

  date_default_timezone_set('Europe/Paris');
  $content .= "[Connection] at ".date('h-i-s')." ".date('d/m/o')." as IP:".$ip."\n"; 

  file_put_contents(FULL_PATH.'/logs/spam.log', $content);
  header('Location: '. $_SERVER['REMOTE_ADDR']);
  exit;
}
// End Spam redirection


session_start();

if(file_exists(__DIR__.'/vendor/autoload.php')) {
  if (true === (require_once __DIR__.'/vendor/autoload.php') || empty($_SERVER['SCRIPT_FILENAME'])) {
    return;
  }
}

autoload::register();

(new Dotenv(FULL_PATH.'/.env'))->load();

if($_ENV['ENV'] === "dev") {
  ini_set('display_errors', 'On');
} else {
  ini_set('display_errors', 'Off');
}

if(DatabaseUtils::is_alive()) {
  $configuration = DatabaseUtils::get_entities('configuration', where:"configuration", where_criteria:"field");
  $data = [];
  foreach($configuration as $config) {

    $data[$config['name']] = $config['value'];
  }

  if(null !== $data) {
    $_SERVER['configuration'] = $data;
  } else {
    $_SERVER['configuration'] = [];
  }
  if ((!isset($_SERVER['configuration']['upkeep']) || $_SERVER['configuration']['upkeep'] == true) && 
    !isset($_SERVER['already_check_upkeep'])) {
    // Page de maintenance
  
    header('Location: /maintenance.php');
    exit;
  }
} else {
  // Page de maintenance erreur
  $_SERVER['configuration']['upkeep'] = true;
  if (!isset($_SERVER['already_check_upkeep'])) {
    header('Location: /maintenance.php');
    exit; 
  }
}

$_SERVER['already_check_upkeep'] = false;

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