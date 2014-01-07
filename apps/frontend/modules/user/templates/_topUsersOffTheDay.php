<h3>Top 10 Nutzer der letzten 24 Stunden</h3>

<ul class="recommended_users">
<?php foreach($users as $user):?>
    <li>
        <?php echo link_to(
          avatar_tag($user->Avatar, "noavatar-48-48.png", 30, 30),
          '@user_public_profile?username='.$user->username,
          array(
           "class"=> "avatar"
          ));?>
        <p><strong><?php echo link_to($user->username, '@user_public_profile?username='.$user->username);?></strong></p>
        <?php if(true === $sf_user->hasUser() && false === $sf_user->getUser()->follows($user) && $user->id !== $sf_user->getUserId()):?>
            <p><?php echo link_to("Folgen", '@user_friends_modify?list=add&username='.$user->username); ?></p>
        <?php endif;?>
    </li>
<?php endforeach;?>
</ul>
