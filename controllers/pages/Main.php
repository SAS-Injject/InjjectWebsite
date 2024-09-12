<?php

namespace App\Controllers\Pages;

use App\Controllers\AbstractController;
use App\Helpers\Database\DatabaseUtils;
use App\Helpers\Templates\TemplateUtils;
use App\Helpers\Tools\Dump;
use App\Helpers\Tools\JWT;
use App\Helpers\Tools\Mail;
use DateTime;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Main extends AbstractController{

  public function index() {

    if(isset($_POST['token']) && JWT::isTokenValid($_POST['token'], ['form' => "quote_form"])) {
      Mail::quote_form();
    }

    $articles_arr = [];

    if(DatabaseUtils::is_alive()) {
      $articles = DatabaseUtils::get_last_entities('articles', ["id", "title", "thumbnail_id", "published_at", "summary"], 4);
      if(null !== $articles || count($articles) > 0) {
  
        foreach($articles as $article) {
          $thumbnail = DatabaseUtils::get_entity($article["thumbnail_id"], 'thumbnails', ["file"]);
  
          $category_id = DatabaseUtils::get_entity($article['id'], 'articles_articles_categories', ['articles_categories_id'], 'articles_id');
          $category = DatabaseUtils::get_entity($category_id['articles_categories_id'], 'articles_categories', ['label']);
  
          $articles_arr[$article['id']] = [
            'id' => $article['id'],
            'article_title' => $article['title'],
            'thumbnail_file' => $thumbnail['file'],
            'category' => $category['label'],
            'date' => date("d/m/y", (new DateTime($article['published_at']))->getTimestamp()),
            'summary' => $article['summary'],
          ];
        }
      }
    }

    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/main.html.tpl', [
      'main_css_path' => '/css/style.css',
      'articles' => $articles_arr,
    ]);
  }

}