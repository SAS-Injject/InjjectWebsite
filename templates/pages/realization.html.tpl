{% include  bodies/std_body_head.html.tpl %}
{% include  navigations/std_navigation.html.tpl %}

<main class="contained d-flex flex-column align-items-center pb-5">
  {% include  parts/articles.header.html.tpl %}

  <article class="complete-realisation container-std m-auto d-flex flex-column std-gap">
    <h2 class="title-main-dark-medium text-center" >Projet : {{ data.title }}</h2>
    <img class="realisation_thumbnail m-auto" src="{{ url_res }}{{ data.photos.0.file }}">

    <ul class="d-flex flex-column std-gap m-3">
      <li>
        <p class="main-paragraph p-0"><b>Contexte : </b></p>
        <p class="main-paragraph p-0">{{ data.context }}</p>
      </li>
      <li>
        <p class="main-paragraph p-0"><b>Missions : </b></p>
        <p class="main-paragraph p-0">{{ data.task }}</p>
      </li>
      <li>
        <p class="main-paragraph p-0"><b>Solutions : </b></p>
        <p class="main-paragraph p-0">{{ data.answer }}</p>
      </li>
    </ul>
    <div>
      <p class="title-sub-orange text-center"> Avis client : <br>{{ data.client_view }}</p>
    </div>
    <div class="d-flex flex-column justify-content-center">
      <img class="realisation_client_logo" src="{{ url_res }}{{ data.client_logo.file }}">
    </div>
    <div class="d-flex flex-row justify-content-center std-gap">
      <div class="d-flex flex-row align-items-center small-gap"><img src="/assets/realization-hourglass-icon.png"><p class="main-paragraph p-0">{{ data.duration }}</p></div>
      <div class="d-flex flex-row align-items-center small-gap"><p class="main-paragraph p-0">{{ data.period }}</p><img src="/assets/realization-calendar-icon.png"></div>
    </div>
  </article>

</main>

{% include navigations/std_footer.html.tpl %}
{% include bodies/std_body_foot.html.tpl %}