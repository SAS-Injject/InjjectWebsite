<?php

namespace App\Helpers\Tools;


enum CaptchaType: string {
  case ALPHA = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  case ALPHA_NUM = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  case MIN_ALPHA = "abcdefghijklmnopqrstuvwxyz";
  case MIN_ALPHA_NUM = "abcdefghijklmnopqrstuvwxyz0123456789";
  case FULL_ALPHA = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  case FULL_ALPHA_NUM = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
}

class Captcha {

  public static function generateImage(int $w = 80, int $h = 25, int $lines = 10, CaptchaType $characters = CaptchaType::ALPHA) {
    header('Content-Type: image/png');
    $image = imagecreatetruecolor($w, $h);
    imagefilledrectangle($image, 0, 0, $w, $h, imagecolorallocate($image, 255, 255, 255));
    $hex = "ABCDEF0123456789";

    for($limit = 0; $limit <= $lines; $limit++) {
      $rgb = self::hexargb(substr(str_shuffle($hex),0,6));
      imageline(
        $image,
        rand(1, $w-25),
        rand(1, $h),
        rand(1, $w + 25),
        rand(1, $h),
        imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b'])
      );
    }

    $code_session = substr(str_shuffle($characters->value), 0, 4);
    $_SESSION['captcha_code']= $code_session;

    $code = '';
    for($len = 0; $len <= strlen($code_session); $len++) {
      $code .= substr($code_session, $len, 1) . ' ';
    }

    imagestring($image, 5, 10, 5, $code, imagecolorallocate($image, 0, 0, 0));
    imagepng($image);
    // $base64_png = 'data:image/png;base64,'. base64_encode($image);
    imagedestroy($image);
  }

  private static function hexargb($hex) {
    return [
      'r' => hexdec(substr($hex,0,2)),
      'g' => hexdec(substr($hex,2,2)),
      'b' => hexdec(substr($hex,4,2)),
    ]; 
  }

}