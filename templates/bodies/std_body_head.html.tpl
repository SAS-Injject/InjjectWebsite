<!DOCTYPE html>
  <html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Entreprise de conception et fabrication de pièces résines et plastiques.">
    <meta property="og:title" content="Explorons ensemble le champs des possibles">
    <meta property="og:description" content="Entreprise de conception et fabrication de pièces résines et plastiques.">
    <meta property="og:site_name" content="Injject">
    <meta property="og:image" content="/assets/favicon-256x256.png">
    <meta property="og:type" content="website">
    
    <title>{{title}}</title>


    <script src="/css/bootstrap/js/bootstrap.min.js"></script>

    {% call App\Controllers\AbstractController::import_js params ({{ js_files_path }}) %}
    {% call App\Controllers\AbstractController::import_defer_js params ({{ js_defer_files_path }}) %}

    <link rel="stylesheet" href="/css/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="/css/style.css">
    {% call App\Controllers\AbstractController::import_css params ({{ main_css_path }}) %}

    <script src="/scripts/load.js"></script>
    <script defer src="/scripts/topShortcut.js"></script>

  </head>
  <body>
  
  