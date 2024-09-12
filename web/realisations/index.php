<?php

use App\Controllers\Articles;
use App\Controllers\Realizations;

require_once "../../autoload_web.php";

$limit_article_by_page = 8;
$current_page = 1;

if(isset($_GET['limit'])) {
  $limit = $_GET['limit'];
  $limit = intval($limit);

  if($limit > 30) $limit = 30;
  if($limit < 8) $limit = 8;

  $limit_article_by_page = $limit;
}

if(isset($_GET['page'])) {
  $page = $_GET['page'];
  $page = intval($page);

  if($page > 100) $limit = 100;
  if($page < 1) $limit = 1;

  $current_page = $page;
}

if(isset($_GET['realisation']) && is_numeric($_GET['realisation'])) {
  $id = intval($_GET['realisation']);
  echo (new Realizations('Réalisations Injject', ['/css/style-articles.css']))->item($id);
} else {
  echo (new Realizations('Réalisations Injject', ['/css/style-articles.css']))->index($limit_article_by_page, $current_page);
}
