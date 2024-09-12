<?php

namespace App\Controllers\Pages;

use App\Controllers\AbstractController;
use App\Helpers\Templates\TemplateUtils;

class Legals extends AbstractController {

  public function index() {
    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/legals.html.tpl', [

    ]);
  }

  public function legals() {
    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/mentions_legales.html.tpl', [

    ]);
  }

  public function cgu() {
    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/cgu.html.tpl', [

    ]);
  }

  public function cgv() {
    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/cgv.html.tpl', [

    ]);
  }

}