<?php slot("page-title")?>Nachricht bearbeiten<?php end_slot()?>

<form action="<?php url_for_story($story, "edit"); ?>" id="new_form" class="form-large <?php echo ($story->getStoryType() !== "Payed") ? "ninjaForm " : "";?><?php echo ("Article" === $story->getStoryType() ? "article": "" ); ?>" enctype="multipart/form-data" method="post">
  <fieldset>
     <p><strong>Bitte alle Felder ausfüllen!</strong> </p>
    <?php
      $errorStack = $story->getErrorStack();
      if(count($errorStack) > 0):?>
        <p class="message error_message">Hallo, leider hat es einen Fehler beim hinzufügen deiner Nachricht gegeben.
            Wir wurden bereits darüber informiert und treten mit dir in Kontakt, wenn der Fehler behoben ist.</p>

      <?php endif;?>

      <?php echo $form->render(); ?>
  </fieldset>
  <div class="actions">
     <input type="submit" name="formAction[save]" value="<?php echo $story->id > 0 ? 'Änderungen speichern' : 'Erstellen'; ?>" id="Save" class="button" style="font-size:1.4em;">
  </div>
</form>