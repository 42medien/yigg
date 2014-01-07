<?php use_helper("Date"); $follower = Doctrine::getTable("User")->findOneById($obj['user_id']); ?>
  <h3><i class="icon-group"></i> <?php echo link_to(
    "{$follower['username']} folgt dir ab jetzt.",
    "@user_public_profile?username={$follower['username']}", array("absolute" => true, 'class' => 'alert-link')
  ); ?></h3>

<div class="notification-details">
  <p>
   <?php
      $avatar = !is_null($follower['avatar_id']) ? Doctrine::getTable("File")->findOneById( $follower['avatar_id'] ): false;
    echo
      content_tag(
        "span",
          link_to(
            avatar_tag($avatar, "noavatar-48-48.png", 48, 48, array("class" => "avatar")),
           '@user_public_profile?view=live-stream&username='. urlencode($follower['username']),
           array(
             "title" => "Profil von {$follower['username']} besuchen",
             "class" => "alert-link"
            )
          ) .
          content_tag(
            "abbr",
            format_date(strtotime($notification['created_at']),"g","de",'utf-8'),
            array(
              "title" => date(DATE_ATOM, strtotime($notification['created_at'])),
              "class" => "updated published"
            )
          ),
          array("class"=>"vcard author")
      );
     ?>
     Der YiGG Nutzer <?php echo link_to(
      $follower['username'],
      "@user_public_profile?username={$follower['username']}", array('absolute' => true, 'class' => 'alert-link')
    );?> hat ab jetzt Deine Nachrichten abonniert.</p>
  <?php if(false === isset($email)): ?>
  <ul class="list-style">
    <?php if( false === $sf_user->getUser()->follows($follower) ): ?>
      <li class="follow"><i class="icon-ok-sign"></i> <?php
        echo link_to(
          "Abonniere  " . $follower['username'],
          '@user_friends_modify?list=add&username='.$follower['username'],
          array(
            "title" => sprintf("Abonniere %s", $follower['username']),
            "class" => "ico follow alert-link")
       );?></li>
    <?php elseif( $sf_user->getUserId() !== $follower['id']): ?>
      <li class="unfollow"><i class="icon-remove-sign"></i> <?php
        echo link_to(
          "Unfollow " . $follower['username'],
          '@user_friends_modify?list=remove&username='.$follower['username'],
          array(
            "title" => sprintf("Unfollow %s", $follower['username']),
            "class" => "ico unfollow alert-link")
       );?></li>
    <?php endif; ?>
    <li class="delete"><i class="icon-trash"></i> <?php echo link_to(
        "Löschen",
        $notification->getTaskLink("delete", ESC_RAW),
        array(
          "class" => "ico delete alert-link ninjaUpdater notification_{$notification['id']}"
        )
      ); ?></li>
  </ul>
  <?php endif;?>
</div>