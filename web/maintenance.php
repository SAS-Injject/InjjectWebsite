<?php

use App\Controllers\Pages\Upkeep;
use App\Helpers\Tools\Dump;
use App\Helpers\Tools\JWT;

$_SERVER['already_check_upkeep'] = true;

require_once "../autoload_web.php";
if($_SERVER['configuration']['upkeep'] == true) {

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
