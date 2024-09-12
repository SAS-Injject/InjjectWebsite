{% include  bodies/std_body_head.html.tpl %}
{% include  navigations/std_navigation.html.tpl %}

<main class="contained d-flex flex-column align-items-center pb-5">
  {% include  parts/articles.header.html.tpl %}

  <section class="section-margin d-flex flex-column align-items-center std-gap">
    <h2 class="title-main-dark">Réalisations</h2>
    <div class="container-std m-auto d-flex flex-column std-gap pb-4">
      <p class="main-description">Bienvenue sure page dédiée à nos réalisations. ici, nous partageons quelques-uns des 
        projets qui illustrent notre engagement envers l'innovation industrielle. Ces exemples mettent en lumière la 
        manière dont notre expertise a contribué à créer des solutions pratiques et innovantes dans le domainre de la 
        conception de pièces résine sur-mesure.
      </p>
      <p class="main-paragraph">
        Nous sommes reconnaissant de la confiance que nos clients nous ont accordée dans ces domaines spécialisés, et 
        nous sommes impatients de mettre notre expérience au service de votre prochain projet.
      </p>
    </div>
    <div class="articles std-gap m-auto">
      {% foreach realisations template parts/components/realisation.card.html.tpl %}
    </div>
  </section>

  {% include navigations/std_pagination.html.tpl %}

</main>

{% include navigations/std_footer.html.tpl %}
{% include bodies/std_body_foot.html.tpl %}