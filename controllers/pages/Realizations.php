<?php


namespace App\Controllers;

use App\Helpers\Database\DatabaseUtils;
use App\Helpers\Templates\TemplateUtils;
use App\Helpers\Tools\Dump;
use DateTime;

class Realizations extends AbstractController{

  public function index(int $limit_articles_by_page = 10, int $page = 1) {

    $realizations_arr = [];

    if(DatabaseUtils::is_alive()) {

      $offset = ($page-1)*$limit_articles_by_page;
      $realizations = DatabaseUtils::get_paginated_entities(
        'realization', 
        ["id", "title", "period", "is_published"], 
        $limit_articles_by_page, $offset, 'period',
        1, "is_published"
      );

      
      if(null !== $realizations || count($realizations) > 0) {
        
        $number_of_pages = (int) floor(1+DatabaseUtils::entries('realization')/$limit_articles_by_page);

        $realizations = array_reverse($realizations);

        foreach($realizations as $realization) {
          $thumbnail = DatabaseUtils::get_entity($realization["id"], 'realization_photo', ["file"], 'realization_id');

          $realizations_arr[$realization['id']] = [
            'id' => $realization['id'],
            'realisation_title' => $realization['title'],
            'thumbnail_file' => $thumbnail['file'],
          ];

        }
      }
    }

    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/realizations.html.tpl', [
      'realisations' => $realizations_arr,
      'pages' => $number_of_pages,
      'current_page' => $page,
    ]);
  }

  public function item(int $id) {

    if(DatabaseUtils::is_alive()) {
      $realization = DatabaseUtils::get_entity($id, 'realization');
      $realization_content = DatabaseUtils::get_entity($id, 'realization_jectech');
      $photos = DatabaseUtils::get_entities('realization_photo', ['file'], $id, 'realization_id');
      $client = DatabaseUtils::get_entity($realization['client_id'], 'client', ['logo_id']);
      $client_logo = DatabaseUtils::get_entity($client['logo_id'], 'logos', ['file']);

      if(null !== $realization && null !== $realization_content) {


        $data = array_merge($realization, $realization_content);
        foreach($photos as $photo) {
          $data['photos'][] = $photo;
        }
        $data['client_logo']['file'] = $client_logo['file'];

        $data['period'] = date('F Y', (new DateTime($data['period']))->getTimestamp());
      }
    }

    return TemplateUtils::sing($this, TEMPLATES_PATH.'/pages/realization.html.tpl', [
      'data' => $data
    ]);
  }

}