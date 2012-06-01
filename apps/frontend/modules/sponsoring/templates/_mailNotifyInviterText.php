Hallo <?php echo $user->username; ?>,

Sie haben <?php echo $name; ?> ein Sponsoring auf der Nachrichtencommunity YiGG empfohlen.

<?php echo $name; ?> hat Ihre Empfehlung angenommen und ein Sponsoring-Paket auf YiGG gebucht - herzlichen Glückwunsch!
Ihr Vertrauen in YiGG wollen wir belohnen: Sie erhalten für Ihre Empfehlung <?php echo number_format($deal->credit, 2); ?> Euro, die
wir auf Ihr YiGG-Konto gutschreiben. Den Stand Ihres YiGG-Kontos und weiterführende
Informationen dazu können Sie jederzeit hier abrufen:

<?php echo url_for('@sponsoring',true); ?>

Vielen Dank für Ihre Empfehlung und viel Spaß beim YiGGen!
Mit freundlichen Grüßen
Michael Reuter
Geschäftsführer
YiGG GmbH
