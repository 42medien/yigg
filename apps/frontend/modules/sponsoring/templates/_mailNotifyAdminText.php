Hallo Admin,

das folgende YiGG Sponsoring-Paket wurde gebucht:

Sponsor:

          Vorname:  <?php echo $deal->buyer_first_name; ?>

          Nachname: <?php echo $deal->buyer_last_name; ?>

          E-Mail:   <?php echo $deal->buyer_email; ?>


          Strasse:  <?php echo $deal->buyer_street1; ?>

                    <?php echo $deal->buyer_street2; ?>

          Stadt:    <?php echo $deal->buyer_zip . ' ' .$deal->buyer_city; ?>

                    <?php echo $deal->buyer_state; ?>

                    <?php echo $deal->buyer_country; ?>




Einzelheiten der Buchung sind an dieser Stelle einsehbar: <?php echo $backendLink; ?>
