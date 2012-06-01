<?php if($sf_user->hasFlash("success_msg")):?>
  <p class="success"><?php echo $sf_user->getFlash("success_msg")?></p>
<?php endif; ?>

<?php if($sf_user->hasFlash("error_msg")):?>
  <p class="error"><?php echo $sf_user->getFlash("error_msg")?></p>
<?php endif; ?>

<?php if($sf_user->hasFlash("note")):?>
  <p class="note"><?php echo $sf_user->getFlash("note")?></p>
<?php endif; ?>