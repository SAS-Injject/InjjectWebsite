<?php


namespace App\Controllers;

use App\Helpers\Database\DatabaseUtils;
use App\Helpers\Templates\TemplateUtils;
use App\Helpers\Tools\Dump;
use DateTime;

class Articles extends AbstractController{

  public function index(int $limit_articles_by_page = 10, int $page = 1) {

    $articles_arr = [];

    if(DatabaseUtils::is_alive()) {

      $offset = ($page-1)*$limit_articles_by_page;
      $articles = DatabaseUtils::get_paginated_entities(
        'articles', 
        ["id", "title", "thumbnail_id", "published_at", "summary", "is_published"], 
        $limit_articles_by_page, $offset, 'published_at',         
        "1", "is_published"
      );

      
      if(null !== $articles || count($articles) > 0) {
        
        $number_of_pages = (int) floor(1+DatabaseUtils::entries('articles')/$limit_articles_by_page);

        $articles = array_reverse($articles);
  
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

    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/articles.html.tpl', [
      'articles' => $articles_arr,
      'pages' => $number_of_pages,
      'current_page' => $page,
    ]);
  }

  public function item(int $id) {

    if(DatabaseUtils::is_alive()) {
      $article = DatabaseUtils::get_entity($id, 'articles');
      if(null !== $article) {

        $content = json_decode($article['content'], true);

        $table_of_contents = [];
        foreach($content['blocks'] as $block) {
          if($block['type'] === 'header' && $block['data']['level'] === 3){
            $table_of_contents[] = [
              'text' => $block['data']['text'],
              'text_id' => $block['id'],
            ];
          }
        }

        $thumbnail = DatabaseUtils::get_entity($article["thumbnail_id"], 'thumbnails', ["file"]);

        $category_id = DatabaseUtils::get_entity($article['id'], 'articles_articles_categories', ['articles_categories_id'], 'articles_id');
        $category = DatabaseUtils::get_entity($category_id['articles_categories_id'], 'articles_categories', ['label']);

        $data = [
          'id' => $article['id'],
          'article_title' => $article['title'],
          'thumbnail_file' => $thumbnail['file'],
          'category' => $category['label'],
          'date' => date("d/m/y", $content['time']),
          'summary' => $article['summary'],
          'table' => $table_of_contents,
          'html' => $content['html'],
        ];
      }
    }

    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/article.html.tpl', [
      'data' => $data,
    ]);
  }

}