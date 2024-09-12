{% include  bodies/std_body_head.html.tpl %}
{% include  navigations/std_navigation.html.tpl %}

<main class="d-flex flex-column align-items-center pb-5">
  {% include  parts/articles.header.html.tpl %}

  <section class="legals-list my-5">
    <div>
      <h2 class="title-main-dark">Mentions Légales</h2>
      <p class="main-paragraph p-0">Informations légales d'Injject et utilisation des données utilisateurs</p>
      <p class="main-description">
        <a class=" link-dark link-underline link-underline-opacity-0 link-offset-2 link-underline-opacity-100-hover" 
          href="/legal/mentions-legales">Mentions légales
        </a>
      </p>
    </div>
    <div>
      <h2 class="title-main-dark">Conditions générales de ventes</h2>
      <p class="main-paragraph p-0">Informations et termes sur la vente de marchandises</p>
      <p class="main-description">
        <a class=" link-dark link-underline link-underline-opacity-0 link-offset-2 link-underline-opacity-100-hover" 
          href="/legal/conditions/vente">Conditions et termes
        </a></p>
    </div>
    <div>
      <h2 class="title-main-dark">Conditions générales d'utilisation</h2>
      <p class="main-paragraph p-0">Informations et termes sur la vente de marchandises</p>
      <p class="main-description">
        <a class=" link-dark link-underline link-underline-opacity-0 link-offset-2 link-underline-opacity-100-hover" 
          href="/legal/conditions/utilisation">Conditions et termes
        </a></p>
    </div>
  </section>

</main>

{% include navigations/std_footer.html.tpl %}
{% include  bodies/std_body_foot.html.tpl %}