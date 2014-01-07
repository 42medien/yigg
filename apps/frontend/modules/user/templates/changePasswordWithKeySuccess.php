<?php slot("page-title")?>Passwort zurücksetzen<?php end_slot()?>

<?php if(isset($form) && $form): ?>
  <form action="<?php echo url_for('@user_change_password_with_key?ResetKey='.$key->reset_key);?>" id="ChangePasswordWithKey" class="ninjaForm" method="post">
    <fieldset>
      <p>Hallo <strong><?php echo $key->User->username; ?></strong>, bitte trage Dein Passwort ein!</p>
      <?php echo $form->render() ?>
    </fieldset>
    <div class="actions">
      <input type="submit" name="commit" value="Passwort ändern" class="button" />
    </div>
  </form>
<?php else: ?>
    <p>Sorry - dieser Link ist nicht gültig. <?php echo link_to("Bitte trage Deinen Nutzernamen und Dein Passwort","@user_reset_password"); ?> erneut ein.</p>
<?php endif; ?>