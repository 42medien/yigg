<?php $controller = sfContext::getInstance()->getController(); ?>

<p>Hallo <?php echo $user->username; ?>, <br /><br />

YIGG-Benutzer <a href="<?php echo $controller->genUrl($obj->Author->getProfileLink(), true) ?>"><?php echo $obj->Author->username ?></a> hat dich in einem <a href="<?php echo $controller->genUrl($obj->getLink(), true); ?>">Kommentar</a> direkt angesprochen.</p>

<p><a href="<?php echo $controller->genUrl($obj->getLink(), true); ?>">Besuche die Nachricht auf YiGG</a></p>

<p><a href="<?php echo $controller->genUrl("@user_profile_action", true) ?>">Diese E-Mail-Benachrichtigung abbestellen</a></p>



