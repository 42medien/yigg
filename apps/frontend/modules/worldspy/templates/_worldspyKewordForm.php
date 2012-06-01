<form id="pageFilter" class="ninjaForm Filter wspy" method="post" action="<?php echo url_for("@worldspy"); ?>">
  <div class="actions">
    <input type="submit" name="commit" value="anwenden" />
  </div>
  <fieldset>
    <?php echo $form->render(); ?>
  </fieldset>
</form>