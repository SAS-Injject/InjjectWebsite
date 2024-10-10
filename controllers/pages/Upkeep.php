<?php

namespace App\Controllers\Pages;

use App\Controllers\AbstractController;
use App\Helpers\Database\DatabaseUtils;
use App\Helpers\Templates\TemplateUtils;
use App\Helpers\Tools\Dump;

class Upkeep extends AbstractController {

  public function index() {

    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/upkeep.html.tpl', [

    ]);
  }
}