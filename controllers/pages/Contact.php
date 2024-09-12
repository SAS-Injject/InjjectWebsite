<?php

namespace App\Controllers\Pages;

use App\Controllers\AbstractController;
use App\Helpers\Templates\TemplateUtils;
use App\Helpers\Tools\JWT;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Contact extends AbstractController {


  public function index() {

    $form_id = "contact_form";
    $response_message = [];
    // Form process
    if(isset($_POST['token']) && JWT::isTokenValid($_POST['token'], ['form' => $form_id])) {

      $error = false;

      $mail_address = $_POST['email'];
      if(!filter_var($mail_address, FILTER_VALIDATE_EMAIL)) {
        $response_message['errors']['email'] = 'Votre saisie ne correspond pas une adresse mail.';
        $error = true;
      }

      $message = $_POST['message']. '<br> E-Mail : '.$mail_address;
      if(isset($_POST['tel']) && $_POST['tel'] !== "") {
        $message .= '<br> Téléphone : '. $_POST['tel'];
      }
      $message = htmlentities($message);

      $mail = new PHPMailer(false);
      $mail->CharSet = "UTF-8";

      if (!$error) {
        try {

          //Server Settings
          $mail->SMTPDebug  = SMTP::DEBUG_SERVER;
          $mail->isSMTP();
          $mail->Host       = 'smtp.orange.fr';
          $mail->SMTPAuth   = true;
          $mail->Username   = 'lucas.martignon';
          $mail->Password   = 'cracotte';
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
          $mail->Port       = 465;
  
          //Recipents
          $mail->setFrom('lucas.martignon@wanadoo.fr');
          $mail->addAddress('lucas.martignon@wanadoo.fr');
          // $mail->addAddress($mail_address); -> ajouter pour un retour de message
          
          //Attachements
          //No attachment here

          //Content
          $mail->isHTML(true);
          $mail->Subject    = 'Contact client';
          $mail->Body       = html_entity_decode($message);
          $mail->AltBody    = $message;

          $mail->send();
          $response_message['success']['mail'] = 'Le message a été envoyé';
  
  
        } catch(Exception $e) {
          $response_message['fail']['mail'] = 'Le message n\'a pas pu être envoyé.' . $e->getMessage() . ' // ' . $e->getCode();
        }

        header('Location: ' . $_SERVER['REQUEST_URI']);
      }
      


    }

    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/contact.html.tpl', [
      'form_id' => $form_id,
      'response' => $response_message,
    ]);
  }

}