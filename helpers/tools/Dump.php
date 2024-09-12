<?php

namespace App\Helpers\Tools;

class Dump {

  public static function d(mixed ...$dumps) {
    echo "<pre>";
    foreach($dumps as $dump) {
      var_dump($dump);
    } 
    echo "</pre>";
    echo "<br>";
  }

  public static function dd(mixed ...$dumps) {
    echo "<pre>";
    foreach($dumps as $dump) {
      var_dump($dump);
    } 
    echo "</pre>";
    echo "<br>";

    die;
  }

}