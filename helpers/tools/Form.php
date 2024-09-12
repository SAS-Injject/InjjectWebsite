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

  public static function isFormCSRFTokenValid(string $token, string $form_id): bool {
    return $token === $_SESSION['form_token_'.$form_id];
  }

}