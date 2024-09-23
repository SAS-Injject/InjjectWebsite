<div>
  <label class="form-label main-paragraph p-0" for="captcha">Pour continuer, veuillez saisir le code correctement. <i>*</i></label>
  <div class="captcha">
    <input type="text" name="captcha" required class="form-control form-control-sm">
    <label><img src="/captcha.php" onClick="this.src='/captcha.php?'+ Math.random()" alt=captcha style="cursor:pointer"></label>
  </div>
</div>