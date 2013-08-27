<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
    FB.init({
        appId  : <?php echo sfConfig::get('app_facebook_app_id') ?>,
        frictionlessRequests: true
    });

    function sendFbFriendRequest() {
        FB.ui({
            method: 'apprequests',
            display: 'iframe',
            message: 'Möchtest Du Yigg auch beitreten? Na dann los, denn bei Yigg findest Du die wichtigsten News auf einen Blick!'
        });
    }
</script>

<?php if($fb_friends):?>
<script>
    setTimeout("sendFbFriendRequest()",3000);
</script>
<?php endif; ?>

<div class="profile-card h-card hcard vcard">
  <?php echo avatar_tag($user->Avatar, "noavatar.gif", 150, 150, array("alt" => $user->username, "class" => "u-photo photo avatar"));?>
  <h1 class="fn n p-name"><?php echo $user['username']; ?><?php echo ( $user->getAge() ) ? " ({$user->getAge()})" : ''; ?></h1>
  
  <?php if(true === $sf_data->getRAW("user")->getConfig()->has("about_me", "profile")):?>
   <h2 class="note p-summary"><?php echo mb_substr($sf_data->getRAW('user')->getConfig()->get("about_me", null, "profile"),0,160); ?></h2>
  <?php endif;?>

  <ul>
    <?php if ($sf_data->getRAW("user")->getConfig()->get('website', false, 'profile')): ?>
    <li><i class="icon-link"></i>
    <?php
      echo content_tag("a", $sf_data->getRAW("user")->getConfig()->get('website', false, 'profile'),
        array(
          "title" => $sf_data->getRAW("user")->getConfig()->get('website', false, 'profile'),
          "href"=> $sf_data->getRAW("user")->getConfig()->get('website', false, 'profile'),
          "rel" => "external", 
          "class" => "url u-url",
        )
      );
    ?>
    </li>
    <?php endif; ?>
    <?php if($sf_data->getRAW("user")->getConfig()->get('city', null, 'profile')): ?> 
      <li><i class="icon-globe"></i> <?php echo $sf_data->getRAW("user")->getConfig()->get('city', null, 'profile'); ?></li>
    <?php endif; ?>
    <?php if(true === $sf_data->getRAW("user")->getConfig()->has('website','profile') && true === yiggTools::isProperURL($sf_data->getRAW("user")->getConfig()->get('website', false, 'profile'))):?>
    <?php endif; ?>
  </ul>
  
  <?php if( true === $sf_user->hasUser()): ?>
     <div class="top_action_row">
       <?php if($sf_user->getUserId() !== $user->id):?>
         <?php if( false === $sf_user->getUser()->follows($user) ): ?>
           <?php echo button_to("Abonnieren", '@user_friends_modify?list=add&username='.$user->username, array("class" => "follow"));?>
         <?php else:?>
           <?php echo button_to("Abonniert!", '@user_friends_modify?list=remove&username='.$user->username, array("class" => "unfollow"));?>
         <?php endif;?>
         <?php if($sf_user->hasUser() && false === $user->isAdmin() && $sf_user->getUser()->isAdmin()):?>
           <?php echo button_to("Benutzer löschen", '@user_delete?username='.$user->username, array("class" => "delete"));?>
           <?php echo button_to("Benutzer deaktivieren", '@user_suspend?username='.$user->username, array("class" => "suspend"));?>
         <?php endif;?>
       <?php else:?>
         <?php echo button_to("Profil bearbeiten", "@user_my_profile", array("class" => "profile"));?>
         <?php echo button_to("Einstellungen", "@user_settings", array("class" => "settings"));?>
       <?php endif;?>
       <?php if($user->facebook_id):?>
         <input type="button" onclick="sendFbFriendRequest();" value="Lade Deine Facebook Freunde ein" class="facebook">
       <?php endif;?>
     </div>
  <?php endif;?> 
</div>

<?php if(count($stories) > 0): ?>
<div class="story-list-cont">
  <h1 class="page-title"><?php echo $user['username']; ?>s Nachrichten:</h1>
  
  <ol id="stories" class="story-list hfeed ">
    <?php foreach($stories as $k => $story ): ?>
    <li>
    <?php
      include_partial('story/story',
        array(
          'story' => $story,
          'summary' => true,
          'count' => $k,
          'total' => count($stories),
          'inlist' => true
        )
      );
    ?>
    </li>
    <?php endforeach; ?>
  </ol>
</div>
<?php echo $pager->display(); ?>
<?php else: ?>
  <p class="error">Es wurden keine Nachrichten gefunden.</p>
<?php endif; ?>


<?php slot("sidebar")?>
  <?php include_partial("userWidgetStats", array("user" => $user))?>
  
  <?php if(count($followedUsers) > 0):?>
    <h3 class="avtr">Abonnements</h3>
    <?php include_partial("user/avatarList", array("users" => $followedUsers));?>
  <?php endif;?>

  <?php if(count($followingUsers) > 0):?>
    <h3 class="avtr">Abonnenten</h3>
    <?php include_partial("user/avatarList", array("users" => $followingUsers));?>
  <?php endif;?>

  <?php if( true === $sf_user->hasUser() && $sf_user->getUserId() !== $user->id):?>
    <?php include_component("atPage", "pmFormForUser", array("user" => $user));?>
  <?php endif; ?>

  <?php if(count($user->Tags) > 0):?>
    <h3 class="help_icon"><?php echo $user->username;?> interessiert sich für:
      <?php echo link_to(image_tag("silk-icons/help.png", array("alt" => "Hilfe")), "http://hilfe.yigg.de/doku.php?id=grundlagen", array("title" => "Zur Hilfe", "rel" => "external"));?>
    </h3>
    <?php echo include_partial("tag/subscribe", array("tags" => $user->Tags));?>
  <?php endif;?>
<?php end_slot()?>