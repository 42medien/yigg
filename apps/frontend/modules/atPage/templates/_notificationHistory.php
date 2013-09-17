<?php use_helper("Date"); ?>
<h3><i class="icon-info-sign"></i> <?php echo "Deine Nachricht &quot;{$obj->Story->title}&quot; ist auf der Startseite";?></h3>

<div class="notification-details">
  <p>Deine Nachricht <?php echo $obj->Story->title;?>  hat es auf die Startseite geschafft! Herzlichen Glückwunsch!</p>

  <ul class="list-style">
    <li class="story"><?php
        echo link_to(
          "Startseite besuchen",
          '@best_stories',
          array("class" => "ico zoom"));?></li>
    <li class="delete"><?php echo link_to(
        "Löschen",
        $notification->getTaskLink("delete", ESC_RAW),
        array(
          "class" => "ico delete ninjaUpdater notification_{$notification['id']}"
        )
      ); ?></li>
  </ul>
</div>