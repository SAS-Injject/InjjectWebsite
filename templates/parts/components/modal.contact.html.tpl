<div 
  class="modal fade" 
  id="staticBackdrop" 
  data-bs-backdrop="static" 
  data-bs-keyboard="false" 
  tabindex="-1" 
  aria-labelledby="staticBackdropLabel" 
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title title-main-dark" id="exampleModalLabel">Formulaire de Contact</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>


      <div class="modal-body d-flex flex-column std-gap">
        <form method="POST" id="{{ quote_form_id }}" data-i-form="steps" enctype="multipart/form-data">
          <fieldset data-i-step="1">

            <div class="input-framed">
              <div class="d-flex flex-column justify-content-center std-gap">
                <label class="drop-container main-paragraph" for="files">
                  <img src="/assets/quotation_form.png">
                  <h2 class="title-sub-dark">Évaluer vos pièces</h2>
                  <span class="">Déposer vos fichier</span>
                  ou
                  <input class="input-file" type="file" id="files" name="files[]" multiple data-drop="true" accept=".png, .pdf, .stl"> 
                </label>
                <p class="main-paragraph text-center">Format de fichier accpeter: PNG | STL | PDF</p>
                <p class="main-paragraph text-center">Tous les téléchargements sont sécurisés et confidentiels</p>
              </div>
            </div>
            
            <div class="d-flex flex-row justify-content-center">
              <button class="btn-custom-main" data-i-destination="2">Suivant</button>
            </div>
          </fieldset>
          <fieldset data-i-step="2">
            <div class="flex-grow-1 d-flex flex-column">
              <label class="form-label main-paragraph p-0" for="message">Votre message <i>*</i></label>
              <textarea class="form-control form-control-sm flex-grow-1" required id="message" name="message" rows="7" 
                placeholder="Parlez-nous de votre projet." data-i-validity=""
              ></textarea>
            </div>
            <div class="d-flex flex-row justify-content-center std-gap">
              <button class="btn-custom-grey" data-i-destination="1">Précédent</button>
              <button class="btn-custom-main" data-i-destination="3">Suivant</button>
            </div>
          </fieldset>
          <fieldset class="flex-center" data-i-step="3">
            <section class="d-flex flex-column justify-content-center std-gap">
              <div>
                <label class="form-label main-paragraph p-0" for="email">Votre e-mail <i>*</i></label>
                <input class="form-control form-control-sm" type="email" id="email" name="email" required placeholder="Adresse mail">
              </div>
              <div>
                <label class="form-label main-paragraph p-0" for="name">Votre Nom <i>*</i></label>
                <input class="form-control form-control-sm" type="text" id="name" name="name" required placeholder="Nom">
              </div>
              <div>
                <label class="form-label main-paragraph p-0" for="tel">Votre Téléphone <i>(optionnel)</i></label>
                <input class="form-control form-control-sm" type="tel" id="tel" name="tel" placeholder="N° Téléphone">
              </div>
              {% include  parts/components/captcha.html.tpl %}
              <div class="d-flex align-items-center std-gap">
                <input class="form-check-input m-0" type="checkbox" id="rgpd" name="rgpd" required>
                <label class="form-check-label main-paragraph p-0" for="rgpd">J'accepte les CGU ainsi que le traitement de mes données personnelles par <br>Injject selon 
                  les normes régies par la RGPD <i>*</i>
                </label>
              </div>
              <p class="main-paragraph text-center pb-3">Les éléments notés d'un astérisque * sont obligatoire pour l'envoi du formulaire de contact</p>
            </section>
            <input type="hidden" id="token" name="token" value="{% call App\Helpers\Tools\Form::generateFormCSRFToken params ({{ quote_form_id }}) %}" required>
            <div class="d-flex flex-row justify-content-center std-gap">
              <button class="btn-custom-grey" data-i-destination="2">Précédent</button>
              <button class="btn-custom-main">Envoyer</button>
            </div>
          </fieldset>
        </form>

      </div>
    </div>
  </div>
</div>
