<?php use_helper('JavascriptBase','Number') ?>
<?php if(true === $sf_user->hasFlash('notice')): ?>
  <p class="note"><?php echo $sf_user->getFlash('notice'); ?></p>
<?php endif; ?>

<h2>Sponsor werden (Vorschau)</h2>

<?php if( $sf_user->hasFlash('Sponsoring:error') ):?>
  <p class="error"><?php echo $sf_user->getFlash('Sponsoring:error'); ?></p>
<?php endif; ?>

<?php if( $sf_user->hasFlash('Sponsoring:notice') ):?>
  <p class="note"><?php echo $sf_user->getFlash('Sponsoring:error'); ?></p>
<?php endif; ?>

<p><?php echo link_to("Züruck","@sponsoring_order")?></p>