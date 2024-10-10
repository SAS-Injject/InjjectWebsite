<a class="flat-a" href="/articles?article={{ id }}"><article class="article">
  <img src="{{ url_res }}{{ thumbnail_file }}">
  <div class="infos">
    <div class="w-100 d-flex flex-row justify-content-start small-gap">
      <p class="category-card-article">{{ category.label }}</p>
      <p class="date-card-article">{{ date }}</p>
    </div>
    <p class="main-description title-card-article text-truncate">{{ article_title }}</p>
    <p class="main-paragraph desc-card-article">{{ summary }}</p>
  </div>
</article></a>
