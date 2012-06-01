<h1>E-Mail Adresse bestätigen</h1>

<p>Bitte bestätige Deine E-Mail-Adresse durch klicken auf folgenden Link:<br />
  <?php echo link_to("E-Mail bestätigen",
    "@user_verify_changed_email?hash={$user->mclient_salt}&secret={$sf_data->getRaw("user")->getConfig()->get("secret", null, "new_email")}",
    array("absolute" => true));?></p>