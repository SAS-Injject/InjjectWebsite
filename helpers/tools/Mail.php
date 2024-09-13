<?php

namespace App\Helpers\Tools;

use App\Controllers\AbstractController;
use App\Helpers\Config\Dotenv;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mail {


  public static function process_mail(PHPMailer $mail, string $mail_address, string $message, array $files = []) {
    try {

      //Server Settings
      $mail->SMTPDebug  = SMTP::DEBUG_OFF; // SMTP::DEBUG_SERVER;
      $mail->isSMTP();
      $mail->Host       = Dotenv::getEnv('MAIL_HOST');
      $mail->SMTPAuth   = true;
      $mail->Username   = Dotenv::getEnv('MAIL_USER');
      $mail->Password   = Dotenv::getEnv('MAIL_PASS');
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port       = Dotenv::getEnv('MAIL_PORT');

      //Recipents
      $mail->setFrom(Dotenv::getEnv('MAIL'));
      $mail->addAddress($mail_address);
      // $mail->addAddress($mail_address); -> ajouter pour un autre destinataire
      
      //Attachements
      if(null !== $files || count($files) > 0) {
        foreach($files['tmp_name'] as $index => $file_path) {
          $mail->addAttachment($file_path, $files['name'][$index]);
        }
      }

      //Content
      $mail->isHTML(true);
      $mail->Subject    = 'Contact client';
      $mail->Body       = html_entity_decode($message);
      $mail->AltBody    = $message;

      $mail->send();
      $response_message = [
        'message' => 'Le message a été envoyé',
        'type' => 'success'
      ];


    } catch(Exception $e) {
      $response_message = [
        'message' => 'Le message n\'a pas pu être envoyé.' . $e->getMessage() . ' // ' . $e->getCode(),
        'type' => 'danger'
      ];
    }

    return $response_message;
  }

  public static function quote_form(AbstractController $controller) {

    $error = false;
    
    $mail_address = $_POST['email'];
    if(!filter_var($mail_address, FILTER_VALIDATE_EMAIL)) {
      $response = [
        'message' => 'Votre saisie ne correspond pas une adresse mail.',
        'type' => 'danger'
      ]; 
      $error = true;
    }

    $noreply_message = $_POST['message']. '<br> E-Mail : '.$mail_address;
    if(isset($_POST['tel']) && $_POST['tel'] !== "") {
      $noreply_message .= '<br> Téléphone : '. $_POST['tel'];
    }
    $noreply_message = htmlentities($noreply_message);

    $message = 'Ceci est un message de retour pour confirmation de votre prise de contact :<br>'. $_POST['message'];
    $message = htmlentities($message);


    $mail = new PHPMailer(false);
    $mail->CharSet = "UTF-8";
    
    if(isset($_FILES['files']) && count($_FILES['files'])) {
      foreach($_FILES['files']['error'] as $file_err) {
        if($file_err > 0) {
        }
      }
    }

    if (!$error) {

      $response = Mail::process_mail($mail, $mail_address, $message, ($_FILES['files'] ?? []));
      $response = Mail::process_mail($mail, Dotenv::getEnv('MAIL'), $noreply_message, ($_FILES['files'] ?? []));
      $controller->addFlash($response['message'], $response['type']);

      header('Location: ' . $_SERVER['REQUEST_URI']);
      exit;
    }

    return $response;
  }
}