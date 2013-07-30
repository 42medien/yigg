<h1 class="page-title">Einstellungen</h1>

<?php include_partial("system/systemMessages")?>

<article class="hentry post h-entry page">
  <div class="body e-summary entry-summary">
    <h2>Allgemeine Einstellungen</h2>
    <form method="post" action="<?php echo url_for("@user_settings?view=general")?>" class="settings">
      <fieldset><?php echo $settings_form->render(); ?></fieldset>
      <div class="actions">
        <input type="submit" class="button" value="Einstellungen ändern" />
      </div>
    </form>

    <h2>Benachrichtungen</h2>
    <form method="post" action="<?php echo url_for("@user_settings?view=notifications")?>" class="settings">
      <fieldset><?php echo $notification_form->render(); ?></fieldset>
      <div class="actions">
        <input type="submit" class="button" value="Benachrichtigungen ändern" />
      </div>
    </form>
    <p class="error"><?php echo link_to("YiGG Account löschen", "@user_settings?view=delete");?></p>
  </div>
</article>

<?php slot("sidebar")?>
  <div class="clear"></div>
  <h2 class="heading-right">Passworteinstellungen</h2>
  <form method="post" action="<?php echo url_for("@user_settings?view=password")?>" class="right-settings">
    <fieldset><?php echo $password_form->render(); ?></fieldset>
    <div class="actions">
      <input type="submit" class="button" value="Passwort ändern" />
    </div>
  </form>
<?php end_slot()?>