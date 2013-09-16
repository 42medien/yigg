<?php use_helper("Date");?>
    <h3><?php echo
        link_to(
          $obj['Sender']['username'],
           '@user_public_profile?view=live-stream&username='. urlencode($obj['Sender']['username']),
           array(
             "title" => "Profil von {$obj['Sender']['username']} besuchen",
             "class" => "comment-avatar",
             "absolute" => true               
            )
        ); ?> hat Dir eine private Mitteilung geschickt.</h3>
   <?php
      $desc = $obj->getText(ESC_RAW);
      echo
      "<p>".
      content_tag(
        "span",
          link_to(
           avatar_tag($obj->Sender->Avatar, "noavatar-48-48.png", 48, 48),
           '@user_public_profile?view=live-stream&username='. urlencode($obj['Sender']['username']),
           array(
             "title" => "Profil von {$obj['Sender']['username']} besuchen",
             "class" => "comment-avatar",
             "absolute" => true
            )
          ) . " von " .
          link_to(
            $obj['Sender']['username'],
            '@user_public_profile?view=live-stream&username='. urlencode($obj['Sender']['username']),
             array('class' => 'fn url', 'absolute' => true)
          ) . " - " .
          content_tag(
            "abbr",
            format_date(strtotime($obj['created_at']),"g","de",'utf-8'),
            array(
              "title" => date(DATE_ATOM, strtotime($obj['created_at'])),
              "class" => "updated published"
            )
          ),
          array("class"=>"vcard Sender")
      ) . "  " . $desc;
      ?>
  <ol class="list-style clr">
    <li class="comment"><?php echo
      link_to(
        "Antworten",
        '@notification_index?id=' . $notification->id,
        array(
          "class" => "ninjaUpdater pm_" . $obj->id ."_replyHolder",
          "absolute" => true            
        )
      );?></li>
    <li class="delete"><?php echo
      link_to(
        "Löschen",
        "@notification_tasks?task=delete&id=".$notification->id,
        array(
          "class" => "ninjaUpdater notification_".$obj->id,
          "absolute" => true            
        )
      );?></li>
  </ol>
  <div id="pm_<?php echo $obj->id; ?>_replyHolder" class="clr"></div>