<h2>YiGG Administration Login</h2>

<div class="boxtext shadedBg">
  <form id="LoginForm" action="<?php echo url_for("@user_login"); ?>"  method="post" class="ninjaForm ">
    <?php if ($sf_user->hasFlash("login:error")): ?>
      <span class="error_message"><strong><?php echo $sf_user->getFlash("login:error"); ?></strong></span>
    <?php endif; ?>
    <fieldset>
      <?php echo $form->renderGlobalErrors(); ?>
      <?php echo $form->render(); ?>
    </fieldset>
    <div class="actions"><input type="submit" value="Login" /></div>
  </form>
</div>