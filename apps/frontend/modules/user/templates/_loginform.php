<div id="LoginContainer">
  <form id="LoginForm"
        class="ninjaForm<?php if(true === $sf_request->isAjaxRequest() || isset($forceAjax)): ?> ninjaAjaxSubmit LoginContainer<?php endif;?>"
        action="<?php echo url_for("user_login") ?>" method="post" >
    <fieldset>
        <p class="log_title">Login with Yigg Account</p>
       <?php if(true === $sf_user->hasFlash('error_msg')): ?>
          <p class="error"><?php echo $sf_user->getFlash('error_msg')?> </p>
        <?php endif; ?>
        <div class="field">
          <?php echo $form["username"]->renderRow();?>
          <?php echo $form->renderGlobalErrors(); ?>         
        </div>
        <div class="field">
          <?php echo $form["password"]->renderRow();?>
        </div>
        <?php echo link_to("Passwort vergessen?", '@user_reset_password', array('id' => 'user_reset_password')); ?>
        <?php echo $form->renderHiddenFields();?>
        <div class="field remember-me">
          <?php echo $form["remember"]->render();?> <?php echo $form["remember"]->renderLabel();?>
        </div>
        <div class="actions">          
          <input type="submit" name="commit" value="Anmelden" class="button" />          
        </div>
          <h3 class="j_reg">Jetzt <?php echo link_to(
                "registrieren",
                '@user_register')?>, und kostenlos von vielen Funktionen!
          </h3>
    </fieldset>
  </form>
</div>