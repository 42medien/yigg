<?php use_helper("Date"); ?>
  <h3><?php echo "Deine Nachricht &quot;{$obj->Story->title}&quot; ist auf der Startseite";?></h3>
  <p>Deine Nachricht <?php echo $obj->Story->title;?>  hat es auf die Startseite geschafft! Herzlichen Glückwunsch!</p>
  <ul class="list-style clr">
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
  <div class="clr"></div>