<?php 
$username = $notification->getReferencedObject()->Comment->Author->username;
$title = $notification->getReferencedObject()->Story->title;
$controller = sfContext::getInstance()->getController(); ?>

<p>Hallo, <br /><br />

YIGG-Benutzer 
<a href="<?php echo "http://yigg.de/profil".$username ?>"><?php echo $username;?></a>
hat dich in einem <a href="<?php $notification->getReferencedObject()->Story->getLinkWithDomain() ?>">Kommentar</a> auf die Nachricht 
<?php echo $notification->getReferencedObject()->Story->title ?> direkt angesprochen.</p>

<p><a href="<?php echo $notification->getReferencedObject()->Story->getLinkWithDomain()?>">Besuche die Nachricht auf YiGG</a></p>

<p><a href="<?php echo "http://yigg.de/profil/einstellungen" ?>">E-Mails abbestellen</a></p>


       