<?php slot("page-title"); ?><?php printf("%s hat die Datenschutzfunktion aktiviert", $user->username); ?><?php end_slot(); ?>

<h3>Was bedeutet das?</h3>
<p><?php printf ('Nur wenn %s Dich als Freund speichert, kannst Du', $user->username); ?></p>
<ul>
  <li><?php printf('das Profil von %s sehen', $user->username); ?></li>
  <li><?php printf('Nachrichten und Bewertungen von %s als RSS-Feeds abonnieren.', $user->username); ?></li>
</ul>

<?php slot("sidebar"); ?>
<section id="widget-follow" class="widget">
	<h2>Wie weiter?</h2>
  
  <?php echo link_to( sprintf('Folge %s', $user->username),'@user_friends_modify?list=add&username='.$user->username, array("class" => "button") );	?>
  <p><?php echo $user->username ?> wird darüber informiert und entscheidet seinerseits, ob er dich in die Liste seiner Freune aufnehmen will.</p>
	
  <h3>Respektiere <?php echo $user->username ?>'s Privatsphäre</h3>
	<p>Wenn keine Rückmeldung kommt, zieht es der Benutzer wahrscheinlich vor dich nicht als Freund hinzuzufügen. Bitte respektiere diese Entscheidung.</p>
</section>
<?php end_slot(); ?>