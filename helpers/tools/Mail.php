<?php

namespace App\Helpers\Tools;

use App\Controllers\AbstractController;
use App\Helpers\Config\Dotenv;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mail {


  public static function process_mail(PHPMailer $mail, array $mail_addresses, string $message, array $files = []) {
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
      $mail->setFrom(Dotenv::getEnv('MAIL_USER'));
      foreach($mail_addresses as $mail_address) {
        $mail->addAddress($mail_address);
      }
      
      //Attachements
      if(isset($files['tmp_name']) && $files['tmp_name'] !== []) {
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

    $message = 'Ceci est un mail de retour contenant votre message pour confirmation de votre prise de contact :<br><br>'. $_POST['message'];
    $message = htmlentities($message);

    

    $mail_for_customer = new PHPMailer(false);
    $mail_for_customer->CharSet = "UTF-8";
    $mail_for_admin = new PHPMailer(false);
    $mail_for_admin->CharSet = "UTF-8";
    
    if(isset($_FILES['files']) && count($_FILES['files'])) {
      foreach($_FILES['files']['error'] as $file_err) {
        if($file_err > 0) {
          //TODO errors
        }
      }
    }

    if (!$error) {
      $response = Mail::process_mail($mail_for_customer, [$mail_address], $message, ($_FILES['files'] ?? []));
      $response = Mail::process_mail($mail_for_admin, explode(',', Dotenv::getEnv('MAIL')), $noreply_message, ($_FILES['files'] ?? []));
      $controller->addFlash($response['message'], $response['type']);

      // $content = "";
      // if(file_exists(FULL_PATH.'/logs/mail.log')) {
      //   $content = file_get_contents(FULL_PATH.'/logs/mail.log');
      // }

      // $content .= "[Mail Send] at ".date('h-i-s')." ".date('d/m/o')." as IP:".$_SERVER['REMOTE_ADDR']." with ".$_SERVER['HTTP_USER_AGENT']." with mail adress: ".$mail_address."\n"; 

      // file_put_contents(FULL_PATH.'/logs/mail.log', $content);

      header('Location: ' . $_SERVER['REQUEST_URI']);
      exit;
    } 

    return $response;
  }
}