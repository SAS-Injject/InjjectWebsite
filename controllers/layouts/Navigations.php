<?php

namespace App\Controllers\Layouts;

use App\Helpers\Templates\TemplateUtils;

class Navigations {

  public static function standart_navigation() {
    return TemplateUtils::sing(TEMPLATES_PATH.'/navigations/std_navigation.html.tpl', [
      'name' => 'Robert'
    ]);
  }

}