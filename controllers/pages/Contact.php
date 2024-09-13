<?php

namespace App\Controllers\Pages;

use App\Controllers\AbstractController;
use App\Helpers\Templates\TemplateUtils;
use App\Helpers\Tools\JWT;
use App\Helpers\Tools\Mail;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Contact extends AbstractController {


  public function index() {

    $form_id = "contact_form";
    $response_message = [];
    // Form process
    if(isset($_POST['token']) && JWT::isTokenValid($_POST['token'], ['form' => $form_id])) {
      $response_message = Mail::quote_form($this);
    }
      

    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/contact.html.tpl', [
      'form_id' => $form_id,
      'response' => ($response_message ?? []),
    ]);
  }

}