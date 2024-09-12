<!DOCTYPE html>
  <html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{title}}</title>

    <script src="/css/bootstrap/js/bootstrap.min.js"></script>

    {% call App\Controllers\AbstractController::import_js params ({{ js_files_path }}) %}

    <link rel="stylesheet" href="/css/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="/css/style.css">
    {% call App\Controllers\AbstractController::import_css params ({{ main_css_path }}) %}

  </head>
  <body>
  
  