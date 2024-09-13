<?php

namespace App\Controllers;

use App\Helpers\Database\DatabaseUtils;
use App\Helpers\Tools\Dump;
use App\Helpers\Tools\JWT;
use App\Helpers\Tools\Mail;

class AbstractController {

  private string $title = "";
  private array $css_files_path = ['/css/style-quote.css'];
  private array $js_files_path = ['/scripts/stepped_form.js'];

  public function __construct(string $title, array $css_files_path = [], array $js_files_path = [])
  {
    $this->title = $title;
    foreach($css_files_path as $file_css) {
      array_push($this->css_files_path, $file_css);
    }
    foreach($js_files_path as $file_js) {
      array_push($this->js_files_path, $file_js);
    }

    $this->exec_common_duties();
  }

  public function exec_common_duties() {

    //Vérifie le token après envoie du formulaire de devis (commun à toutes les pages)
    if(isset($_POST['token']) ) {
      if(JWT::isTokenValid($_POST['token'], ['form' => "quote_form"])) {
        Mail::quote_form($this);
      } 
    }

  }

  public static function get_common_parameters(self $instance): array {

    // add all common parameters you want to have by default
    // all parameters are merged in TemplateUtils::sing method

    $data = [];

    $footer_data = [];
    if(DatabaseUtils::is_alive()) {
      $footer_data = DatabaseUtils::get_last_entities('footer_data', [], 1);
      if(count($footer_data) > 0) {
        $footer_data = $footer_data[0];
      }
    }
    $data['footer_data'] = $footer_data;

    if( $instance->getCssFilesPath() !== "") {
      $data['main_css_path'] = $instance->getCssFilesPath();
    }

    if( $instance->getJsFilesPath() !== []) {
      $data['js_files_path'] = $instance->getJsFilesPath();
    }

    if( $instance->getTitle() !== "") {
      $data['title'] = $instance->getTitle();
    }

    $data['url_api'] = 'https://api.injject.com';

    $data['quote_form_id'] = "quote_form";

    return $data;
  }

  public static function import_css($files): string {
    $html = "";
    foreach($files as $file) {
      $html .= '<link rel="stylesheet" href="'.$file.'">';
    }
    return htmlentities($html);
  }

  public static function import_js($files): string {
    $html = "";

    if(is_string($files)) {
      return $html;
    }
    
    foreach($files as $file) {
      $html .= '<script src="'.$file.'"></script>';
    }

    return htmlentities($html);
  }

  private function getTitle() {
    return $this->title;
  }

  private function getCssFilesPath() {
    return $this->css_files_path;
  }

  private function getJsFilesPath() {
    return $this->js_files_path;
  }

  public function addFlash(string $message, string $type) {
    $_SESSION['fleeting']['flash_messages'][] = [
      'message' => $message,
      'type' => $type
    ];
  }

}