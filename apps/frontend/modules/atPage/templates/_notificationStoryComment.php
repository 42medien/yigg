<?php use_helper("Date");?>
  <h3><i class="icon-comment"></i> <?php echo
    link_to(
      mb_substr($obj->Story->getTitle(ESC_RAW),0, (true === isset($email)) ? 160 : 40,'UTF-8'),
      $sf_data->getRaw('obj')->Story->getLink(),
      array("absolute" => true, 'class' => 'alert-link')
    ); ?></h3>

<div class="notification-details">
    <?php $comment = $obj->Comment;?>

    <?php
      $desc = $comment->getPresentationDescription(ESC_RAW);
      $avatar = !is_null($comment['Author']['avatar_id']) ? Doctrine::getTable("File")->findOneById( $comment['Author']['avatar_id'] ): false;
    echo
    "<p>".
      content_tag(
        "span",
          link_to(
            avatar_tag($avatar, "noavatar-48-48.png", 48, 48),
           '@user_public_profile?view=live-stream&username='. urlencode($comment['Author']['username']),
           array(
             "title" => "Profil von {$comment['Author']['username']} besuchen",
             "class" => "comment-avatar"
           )
          ) . " von " .
          link_to(
            $comment['Author']['username'],
            '@user_public_profile?view=live-stream&username='. urlencode($comment['Author']['username']),
             array('class' => 'fn url alert-link')
          ) . " - ",
          array("class"=>"vcard author")
      );
      echo " " . str_replace("<p>", "", htmlspecialchars_decode($desc));
      ?>
<?php if(false === isset($email)): ?>
    <ul class="list-style">
      <li class="story"><?php echo link_to(
          "Ansehen",
          $sf_data->getRaw('obj')->Story->getLink(),
          array("class" => "ico zoom")
        ); ?></li>
      <li class="delete"><?php echo link_to(
          "Löschen",
          $notification->getTaskLink("delete", ESC_RAW),
          array(
            "class" => "ico delete ninjaUpdater notification_{$notification['id']}"
          )
        ); ?></li>
    </ul>
    <?php else : ?>
      <?php echo link_to(
          "Ansehen",
          $sf_data->getRaw('obj')->Story->getLink(),
          array("class" => "ico zoom", "absolute" => true)
        ); ?>
  <?php endif; ?>
</div>