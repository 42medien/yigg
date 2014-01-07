<?php slot("page-title")?>Login<?php end_slot()?>

<?php include_partial("user/loginform", array("form" => $form)); ?>


<?php slot("sidebar"); ?>
<section id="widget-register" class="widget">
  <h2>Noch keinen Benutzernamen?</h2>
  <p>Jetzt <?php echo link_to(
    "registrieren",
    '@user_register')?>,
    und kostenlos von vielen Funktionen und Einstellungen profitieren.
  <?php echo link_to(
    "Registrieren",
    '@user_register',
    array('title' => 'Jetzt bei YiGG registrieren')); ?>
  </p>
</section>
<?php end_slot(); ?>