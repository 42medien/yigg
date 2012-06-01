<h2><?php printf("%s hat die Datenschutzfunktion aktiviert.", $user->username); ?></h2>
<h3>Was bedeutet das?</h3>
<p><?php printf ('Nur wenn %s Dich als Freund speichert, kannst Du', $user->username); ?></p>
<ul>
  <li><?php printf('das Profil von %s sehen', $user->username); ?></li>
  <li><?php printf('Nachrichten und Bewertungen von %s als RSS-Feeds abonnieren.', $user->username); ?></li>
</ul>

<?php use_slot("sidebar")?>
	<h3>Wie weiter?</h3>
	<dl>
		<dt><?php echo link_to(sprintf('Folge %s', $user->username),'@user_friends_modify?list=add&username='.$user->username);	?></dt>
		<dd><?php echo $user->username ?> wird darüber informiert und entscheidet seinerseits, ob er dich in die Liste seiner Freune aufnehmen will.</dd>
		<dt>Respektiere <?php echo $user->username ?>'s Privatsphäre</dt>
		<dd>Wenn keine Rückmeldung kommt, zieht es der Benutzer wahrscheinlich vor dich nicht als Freund hinzuzufügen. Bitte respektiere diese Entscheidung.</dd>
	</dl>
<?php end_slot();?>