{% include  bodies/std_body_head.html.tpl %}
{% include  navigations/std_navigation.html.tpl %}

<main class="contained d-flex flex-column align-items-center pb-5">
  {% include  parts/articles.header.html.tpl %}

  <section class="container-std">
    <h2 class="w-100 title-main-white bg-main text-center py-2 my-2">Nous Contacter</h2>

    <p class="main_paragraph">{{ response.success.mail }}</p>
    <p class="main_paragraph">{{ response.fail.mail }}</p>

    <form class="d-flex flex-column std-gap" method="POST" id="{{ form_id }}">
      <div>
        <label class="form-label main-paragraph p-0" for="message">Votre message <i>*</i></label>
        <textarea class="form-control form-control-sm" required id="message" name="message" rows="7" 
          placeholder="A quelle question pourrions-nous vous répondre ?"
        ></textarea>
      </div>
      <div>
        <label class="form-label main-paragraph p-0" for="email">Votre e-mail <i>*</i></label>
        <input class="form-control form-control-sm" type="email" id="email" name="email" required placeholder="Adresse mail">
      </div>
      <div>
        <label class="form-label main-paragraph p-0" for="tel">Votre Téléphone <i>(optionnel)</i></label>
        <input class="form-control form-control-sm" type="tel" id="tel" name="tel" placeholder="N° Téléphone">
      </div>
      <div class="d-flex align-items-center std-gap">
        <input class="form-check-input m-0" type="checkbox" id="rgpd" name="rgpd" required>
        <label class="form-check-label main-paragraph p-0" for="rgpd">J'accepte les CGU ainsi que le traitement de mes données personnelles par <br>Injject selon 
          les normes régies par la RGPD <i>*</i>
        </label>
      </div>
      <p class="main-paragraph">Les éléments notés d'un astérisque * sont obligatoire pour l'envoi du formulaire de contact</p>
      <input type="hidden" id="token" name="token" value="{% call App\Helpers\Tools\Form::generateFormCSRFToken params ({{ form_id }}) %}" required>

      <input type="submit" class="btn-custom-main m-auto" value="Envoyer">
  </form>
  </section>
</main>

{% include navigations/std_footer.html.tpl %}
{% include bodies/std_body_foot.html.tpl %}