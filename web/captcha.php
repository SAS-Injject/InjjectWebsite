<?php

use App\Helpers\Tools\Captcha;
use App\Helpers\Tools\CaptchaType;
use App\Helpers\Tools\JWT;

require_once "../autoload_web.php";

if(isset($_GET['token']) && JWT::isTokenValid($_GET['token'], ['id' => 'captcha']) || isset($_GET['reload'])) {
  Captcha::generateImage(characters: CaptchaType::ALPHA_NUM);
} else {
  header('Location: /');
  exit;
}
