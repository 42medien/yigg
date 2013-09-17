<?php use_helper("Date");?>
    <h3><i class="icon-envelope"></i> <?php echo
        link_to(
          $obj['Sender']['username'],
           '@user_public_profile?view=live-stream&username='. urlencode($obj['Sender']['username']),
           array(
             "title" => "Profil von {$obj['Sender']['username']} besuchen",
             "class" => "comment-avatar alert-link",
             "absolute" => true               
            )
        ); ?> hat Dir eine private Mitteilung geschickt.</h3>
<div class="notification-details">
   <?php
      $desc = $obj->getText(ESC_RAW);
      echo
      "<p>".
      content_tag(
        "span",
          link_to(
           avatar_tag($obj->Sender->Avatar, "noavatar-48-48.png", 48, 48, array("class" => "avatar")),
           '@user_public_profile?view=live-stream&username='. urlencode($obj['Sender']['username']),
           array(
             "title" => "Profil von {$obj['Sender']['username']} besuchen",
             "class" => "alert-link",
             "absolute" => true
            )
          ) . " von " .
          link_to(
            $obj['Sender']['username'],
            '@user_public_profile?view=live-stream&username='. urlencode($obj['Sender']['username']),
             array('class' => 'fn url alert-link', 'absolute' => true)
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
  <ul class="list-style">
    <li class="comment"><i class="icon-reply"></i> <?php echo
      link_to(
        "Antworten",
        '@notification_index?id=' . $notification->id,
        array(
          "class" => "ninjaUpdater pm_" . $obj->id ."_replyHolder alert-link",
          "absolute" => true            
        )
      );?></li>
    <li class="delete"><i class="icon-trash"></i> <?php echo
      link_to(
        "Löschen",
        "@notification_tasks?task=delete&id=".$notification->id,
        array(
          "class" => "ninjaUpdater alert-link notification_".$obj->id,
          "absolute" => true            
        )
      );?></li>
  </ul>
</div>