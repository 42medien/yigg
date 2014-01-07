<?php use_helper("Date");?>
  <h3><i class="icon-comment"></i> <?php echo
    link_to(
      mb_substr($obj->Story->getTitle(ESC_RAW),0, (true === isset($email)) ? 160 : 40,'UTF-8'),
      $sf_data->getRaw('obj')->Story->getLink(),
      array("absolute" => true, 'class' => 'alert-link')
    ); ?></h3>

<div class="notification-details">
  <p>
    <?php $comment = $obj->Comment;?>
    <?php
      $desc = $comment->getPresentationDescription(ESC_RAW);
      $avatar = !is_null($comment['Author']['avatar_id']) ? Doctrine::getTable("File")->findOneById( $comment['Author']['avatar_id'] ): false;
    echo
      content_tag(
        "span",
          link_to(
            avatar_tag($avatar, "noavatar-48-48.png", 48, 48, array("class" => "avatar")),
           '@user_public_profile?view=live-stream&username='. urlencode($comment['Author']['username']),
           array(
             "title" => "Profil von {$comment['Author']['username']} besuchen",
             "class" => "alert-link"
           )
          ) . " von " .
          link_to(
            $comment['Author']['username'],
            '@user_public_profile?view=live-stream&username='. urlencode($comment['Author']['username']),
             array('class' => 'fn url alert-link')
          ) . " - " .
          content_tag(
             "abbr",
               format_date(strtotime($obj['created_at']),"g","de",'utf-8'),
               array(
                 "title" => date(DATE_ATOM, strtotime($obj['created_at'])),
                 "class" => "updated published"
               )
          ),
          array("class"=>"vcard author")
      );
      
      echo " " . htmlspecialchars_decode($desc);
      ?>
    </p>
    <ul class="list-style">
      <li class="story"><i class="icon-external-link"></i> <?php echo link_to(
          "Ansehen",
          $sf_data->getRaw('obj')->Story->getLink(),
          array("class" => "ico zoom alert-link", "absolute" => true)
        ); ?></li>
<?php if(false === isset($email)): ?>
      <li class="delete"><i class="icon-trash"></i> <?php echo link_to(
          "Löschen",
          $notification->getTaskLink("delete", ESC_RAW),
          array(
            "class" => "ico delete alert-link ninjaUpdater notification_{$notification['id']}"
          )
        ); ?></li>
<?php endif; ?>
    </ul>
</div>