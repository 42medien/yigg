<h2>Profil bearbeiten</h2>
<?php include_partial("system/systemMessages")?>
<form enctype="multipart/form-data" method="post" action="<?php echo url_for("@user_my_profile?view=profil"); ?>"  id="new_form" class="profileForm">
  <fieldset>
    <?php echo $profile_form->render() ?>
  </fieldset>
  <div class="actions">
    <input type="submit" value="Profil bearbeiten" class="button" id="save" />
  </div>
</form>

<?php slot("sidebar")?>
    <h2>Dein Avatar:</h2>
    <p><?php echo avatar_tag($user->Avatar, "noavatar.gif", 75, 75); ?></p>
<?php end_slot()?>