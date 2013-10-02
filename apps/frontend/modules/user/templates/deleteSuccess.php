<?php if( $user['deleted_at'] !== null):?>
  <h2>Benutzer erfolgreich gelöscht!</h2>

<?php else: ?>
  <h2>Benutzer löschen.</h2>
  <p class="alert alert-info note">Achtung! Es werden alle Nachrichten und Kommentare des Benutzers gelöscht.</p>

  <form action="<?php echo url_for("@user_delete?username=".urlencode($user['username'])); ?>" method="post" class="ninjaValidate">
    <fieldset>
      <?php echo $form->render(); ?>
    </fieldset>
    <input type="submit" name="commit" value="Benutzer löschen" class="button ninjaSubmit" />
  </form>
<?php endif; ?>

<?php slot("sidebar")?>
<section class="widget" id="widget-messages">
  <h2>Letze Nachrichten des Benutzers.</h2>
  <?php $stories = Doctrine_Query::create()
           ->from("Story")
           ->where("user_id = ?", $user->id)
           ->limit(10)
           ->orderBy("id DESC")
           ->execute();?>
</section>

<section class="widget" id="widget-statistics">
  <h2>Statistiken</h2>
  <?php include_partial("user/userWidgetStats", array("user" => $user));?>
</section>
<?php end_slot()?>