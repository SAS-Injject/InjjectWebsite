<?php

use App\Controllers\Pages\Upkeep;
use App\Helpers\Tools\Dump;
use App\Helpers\Tools\JWT;

$_ENV['already_check_upkeep'] = true;

require_once "../autoload_web.php";
if($_ENV['configuration']['upkeep'] == true) {

  $html = (new Upkeep('Maintenance', [
      '/css/style-upkeep.css', 
    ], [
    ]
  ))->index();
  echo $html;
} else {
  header('Location: /');
  exit;
}
