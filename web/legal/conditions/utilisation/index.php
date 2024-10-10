<?php

use App\Controllers\Pages\Legals;

require_once "../../../../autoload_web.php";

$html = (new Legals('Mentions Légales', ['/css/style-legals.css'], []))->cgu();

echo $html;