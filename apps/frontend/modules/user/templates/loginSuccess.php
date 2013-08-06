<?php slot("page-title")?>Login<?php end_slot()?>

<?php include_partial("user/loginform", array("form" => $form)); ?>


<?php slot("sidebar"); ?>
  <h2>Noch keinen Benutzernamen?</h2>
  <p>Jetzt <?php echo link_to(
    "registrieren",
    '@user_register')?>,
    und kostenlos von vielen Funktionen und Einstellungen profitieren.
  <?php echo link_to(
    "Registrieren",
    '@user_register',
    array('title' => 'Jetzt bei YiGG registrieren')); ?></p>

   <?php echo link_to(
     img_tag(
       "no-username-".(rand(0,1)).".jpg",
      array(
        "alt" => "Noch keinen Benutzernamen?",
        "width"=> 300
      )),
     "@user_register",
     array(
       "title" => "Jetzt registrieren und kostenlos von vielen Funktionen und Einstellungen profitieren."
      )
    );
  ?>
  <div class="clr bth">&nbsp;</div>
<?php end_slot(); ?>