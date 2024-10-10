{% include  bodies/std_body_head.html.tpl %}
{% include  navigations/std_navigation.html.tpl %}

<main class="contained d-flex flex-column align-items-center pb-5">
  {% include  parts/articles.header.html.tpl %}

  <article class="complete-article d-flex flex-column std-gap">
    <h2 class="title-main-dark-medium text-center">{{ data.article_title }}</h2>
    <p class="main-paragraph text-center">{{ data.date }}</p>
    <img class="m-auto" src="{{ url_res }}{{ data.thumbnail_file }}">
    <div class="article_summary bg-grey-light">
      <p class="main-description p-0">{{ data.summary }}</p>
    </div>
    <div class="table_of_content bg-grey-light d-flex flex-column small-gap">
      <p class="main-description p-0">Sommaire</p>
      {% foreach data.table template parts/components/article.tableofcontent.html.tpl %}
    </div>
    <div class="article-content d-flex flex-column std-gap">{{ data.html }}</div>
  </article>

</main>

{% include navigations/std_footer.html.tpl %}
{% include bodies/std_body_foot.html.tpl %}