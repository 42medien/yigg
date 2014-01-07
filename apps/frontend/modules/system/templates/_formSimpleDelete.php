<form method="post" action="<?php echo url_for($route); ?>" >
  <fieldset>
    <?php echo $form->render(); ?>
  </fieldset>
  <div class="actions">
    <input type="submit" name="email" value="Löschen" id="SaveChanges" class="button">
  </div>
</form>