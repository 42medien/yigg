<h2>Ein Fehler ist aufgetreten</h2>
<p class="error">Die Zahlung via PayPal konnte nicht abgeschlossen werden. Sie haben kein Sponsoring gebucht.</p>
<p>Sponsorship: <?php echo $sponsoring->getTransactionDescription(); ?> </p>

<p><?php echo link_to('zurück zur Übersicht', '@sponsoring') ;?></p>

<?php if(isset($paypal)): ?>
  <p>Error: <strong><?php echo $paypal->getErrorString(); ?></strong></p>
<?php endif; ?>
