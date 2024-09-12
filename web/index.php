<?php

use App\Controllers\Pages\Main;
use App\Controllers\AbstractController;
use App\Helpers\Tools\Dump;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "../autoload_web.php";

$html = (new Main('Injject', [
    '/css/style-main.css', 
  ], [
  ]
))->index();

echo $html;
