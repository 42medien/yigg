<h1>Passwort vergessen?</h1>

<?php include_partial("system/systemMessages"); ?>

<?php if(false === $form->isValid()): ?>
<form action="<?php echo url_for('user/resetPassword');?>" method="POST" name="ResetPasswordForm" id="ResetPasswordForm" class="ninjaForm"?>
  <fieldset>
    <?php if(isset($action_message)):?>
      <p class="error"><?php echo $action_message; ?></p>
    <?php endif;?>
    <?php echo $form->render();?>
    <div class="actions">
      <input type="submit" value="Passwort zurücksetzen" />
    </div>
   </fieldset>
</form>
<?php endif;?>