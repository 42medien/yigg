Hallo <?php echo $user->username; ?>

Du hast Dich bei YiGG angemeldet.

Bitte bestätige Deinen Account, indem Du auf den folgenden Link klickst:
<?php echo url_for("@user_register_key?key=" . $key, true); ?>


Vielen Dank für Deine Anmeldung und viel Spaß in unserer Community!
Dein YiGG-Team