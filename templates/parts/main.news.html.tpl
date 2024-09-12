<section class="section-margin d-flex flex-column align-items-center std-gap">
  <h2 class="title-main-dark">Nos dernières nouvelles</h2>
  <div class="articles std-gap m-auto">
    {% foreach articles template parts/components/article.card.html.tpl %}
  </div>
  <a class="btn-custom-main" href="/articles">Voir plus</a>
</section>