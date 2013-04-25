<?php if( $user['block_post'] !== null):?>
  <h2>Benutzer erfolgreich deaktiviert!</h2>

<?php else: ?>
  <h2>Benutzer deaktivieren.</h2>
  <p class="note">Achtung! Es werden alle Nachrichten und Kommentare des Benutzers deaktiviert.</p>

  <form action="<?php echo url_for("@user_suspend?username=".urlencode($user['username'])); ?>" method="post" class="ninjaValidate">
    <fieldset>
      <?php echo $form->render(); ?>
    </fieldset>
    <input type="submit" name="commit" value="Benutzer deaktivieren" class="button ninjaSubmit" />
  </form>
<?php endif; ?>

<?php slot("sidebar")?>
  <h2>Letze Nachrichten des Benutzers.</h2>
  <?php $stories = Doctrine_Query::create()
           ->from("Story")
           ->where("user_id = ?", $user->id)
           ->limit(10)
           ->orderBy("id DESC")
           ->execute();?>

  <h2>Statistiken</h2>
  <?php include_partial("user/userWidgetStats", array("user" => $user));?>
<?php end_slot()?>