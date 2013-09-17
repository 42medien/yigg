<?php if($sf_user->hasFlash("success_msg")):?>
  <p class="alert alert-success"><?php echo $sf_user->getFlash("success_msg")?></p>
<?php endif; ?>

<?php if($sf_user->hasFlash("error_msg")):?>
  <p class="alert alert-danger error"><?php echo $sf_user->getFlash("error_msg")?></p>
<?php endif; ?>

<?php if($sf_user->hasFlash("alert alert-info note")):?>
  <p class="alert alert-info note"><?php echo $sf_user->getFlash("note")?></p>
<?php endif; ?>