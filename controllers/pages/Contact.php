<?php

namespace App\Controllers\Pages;

use App\Controllers\AbstractController;
use App\Helpers\Templates\TemplateUtils;
use App\Helpers\Tools\Dump;
use App\Helpers\Tools\Form;
use App\Helpers\Tools\JWT;
use App\Helpers\Tools\Mail;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Contact extends AbstractController {


  public function index() {

    $form_id = "contact_form";
    $error = '';
    // Form process
    if(Form::isSent() && Form::isValid($form_id)) {
      Mail::quote_form($this);
    } else if (Form::isSent()) {
      $error = 'Une erreur dans la saisie du code de vérification a bloqué l\'envoi du formulaire.';
    }
      

    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/contact.html.tpl', [
      'form_id' => $form_id,
      'form_error' => $error,
      'jwt_contact' => JWT::generateWebToken([], ['id' => 'captcha'])
    ]);
  }

}