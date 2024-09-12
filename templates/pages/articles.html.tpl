{% include  bodies/std_body_head.html.tpl %}
{% include  navigations/std_navigation.html.tpl %}

<main class="contained d-flex flex-column align-items-center pb-5">
  {% include  parts/articles.header.html.tpl %}

  <section class="section-margin d-flex flex-column align-items-center std-gap">
    <h2 class="title-main-dark">Actualités</h2>
    <div class="articles std-gap m-auto">
      {% foreach articles template parts/components/article.card.html.tpl %}
    </div>
  </section>

  {% include navigations/std_pagination.html.tpl %}

</main>

{% include navigations/std_footer.html.tpl %}
{% include bodies/std_body_foot.html.tpl %}