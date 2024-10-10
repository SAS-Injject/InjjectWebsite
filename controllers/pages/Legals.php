<?php

namespace App\Controllers\Pages;

use App\Controllers\AbstractController;
use App\Helpers\Database\DatabaseUtils;
use App\Helpers\Templates\TemplateUtils;
use App\Helpers\Tools\Dump;

class Legals extends AbstractController {

  public function index() {
    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/legals.html.tpl', [

    ]);
  }

  public function legals() {

    $legals = DatabaseUtils::get_entities("configuration", where:"legal", where_criteria:"name");
    if(count($legals) > 0 ) {
      $legal = $legals[0];
      $legal = json_decode($legal['value'], true);
    }
    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/notices.html.tpl', [
      "legal" => $legal,
    ]);
  }

  public function cgu() {

    $legals = DatabaseUtils::get_entities("configuration", [], "cgu", "name");
    if(count($legals) > 0 ) {
      $legal = $legals[0];
      $legal = json_decode($legal['value'], true);
    }
    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/notices.html.tpl', [
      "legal" => $legal,
    ]);
  }

  public function cgv() {

    $legals = DatabaseUtils::get_entities("configuration", [], "cgv", "name");
    if(count($legals) > 0 ) {
      $legal = $legals[0];
      $legal = json_decode($legal['value'], true);
    }

    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/notices.html.tpl', [
      "legal" => $legal,
    ]);
  }

}