<ul class="avatar-list">
<?php foreach($users as $user):?>
    <li>
     <?php echo
      link_to(
        avatar_tag($user->Avatar, "noavatar-48-48.png", 48, 48,
          array("class" => "avatar", "alt"=> "Profil von {$user->username} besuchen"
        )),
        '@user_public_profile?username='.$user->username,
        array(
          "title" => "Zu {$user->username}'s Profil"
        )
      );?>
    </li>
<?php endforeach;?>
</ul>
<?php if(isset($view) && $view !== "all"):?>
<?php echo link_to("Alle abonnierten Benutzer sehen", "@user_welcome_ajax?widget=friendsWidget", array("class" => "ninjaUpdater friendsWidget"))?>
<?php endif;?>


