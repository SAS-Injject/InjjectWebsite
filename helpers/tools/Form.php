<?php

namespace App\Helpers\Tools;

class Form {

  public static function generateFormCSRFToken(string $form_id): string {

    $token = JWT::generateWebToken([
      'time' => time()
    ],[
      'form' => $form_id
    ], 0);
    $_SESSION['form_token_'.$form_id] = $token;
    return $token;
  }

  private static function isFormCSRFTokenValid(string $token, string $form_id): bool {
    return JWT::isTokenValid($token, ['form' => $form_id]) && $token === $_SESSION['form_token_'.$form_id];
  }

  private static function isCaptchaValid(string $code): bool {
    if(isset($_SESSION['captcha_code'])) {
      return strcmp($code, $_SESSION['captcha_code']) === 0;
    }
    return false;
  }

  public static function isValid(string $form_id): bool {
    if(isset($_POST['token'], $_POST['captcha']) && self::isFormCSRFTokenValid($_POST['token'], $form_id) && self::isCaptchaValid($_POST['captcha'])) {
      return true;
    } 

    return false;
  }

  public static function isSent(): bool {
    if(isset($_POST['token'], $_POST['captcha'])) {
      return true;
    } 

    return false;
  }

}