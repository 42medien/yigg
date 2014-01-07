
<?php include_partial("system/systemMessages"); ?>

<?php if(false === $form->isValid()): ?>
<form action="<?php echo url_for('user/resetPassword');?>" method="POST" name="ResetPasswordForm" id="ResetPasswordForm" class="ninjaForm"?>
  <fieldset>
    <h1 class="log_title">Passwort vergessen?</h1>
    <?php if(isset($action_message)):?>
      <p class="alert alert-danger error"><?php echo $action_message; ?></p>
    <?php endif;?>
    <?php echo $form->render();?>
    <div class="actions">
      <input type="submit" value="Passwort zurücksetzen" />
    </div>
   </fieldset>
</form>
<?php endif;?>