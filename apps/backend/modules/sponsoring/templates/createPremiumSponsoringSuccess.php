<?php if(empty($user)):?>
<h2>1. Anlegen eines Premium-Sponsorings</h2>
<div class="help">
  <p>Bitte das Formular unten ausfüllen. Nach erfolgreichem anlegen des neuen Benutzeraccounts erscheint ein
     Text auf der nächsten Seite, der abgeändert an den Kunden geschickt werden soll.</p>
</div>
     

  <form action="<?php echo url_for("@premium_invitation") ?>" method="POST">
      <?php echo $form->render(); ?>
      <input type="submit" value="Abschicken" />
  </form>
<?php else: ?>
<h2>2. Text abgeändert an den Kunden senden</h2>
<div class="help">
  <p>Das System hat eine E-Mail an den Kunden mit Zugangsdaten und einem Link zum bearbeiten des neuen Sponsorings gesendet.</p>
</div>
<?php endif; ?>