<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
    FB.init({
        appId  : <?php echo sfConfig::get('app_facebook_app_id') ?>,
        frictionlessRequests: true
    });

    function sendFbFriendRequest() {
        FB.ui({method: 'apprequests',
            message: 'Please join yigg.de'
        });
    }
</script>

<?php if($fb_friends):?>
<script>
    jQuery(document).ready(function() {
        jQuery("#facebook_friends").click();
    });
</script>
<?php endif; ?>

<div class="profile">
  <?php echo avatar_tag($user->Avatar, "noavatar.gif", 150, 150, array("alt" => $user->username));?>
  <h1><?php echo $user['username']; ?><?php echo ( $user->getAge() ) ? " ({$user->getAge()})" : ''; ?></h1>
  <dl>
    <dt><?php echo 'Website:' ?></dt>
    <dd><?php
     echo
       content_tag(
        "a",
         $sf_data->getRAW("user")->getConfig()->get('website', false, 'profile'),
       array(
         "title" => $sf_data->getRAW("user")->getConfig()->get('website', false, 'profile'),
         "href"=> $sf_data->getRAW("user")->getConfig()->get('website', false, 'profile'),
         "rel" => "external",
         "class" => "url",
       )
     );?>
    </dd>
    <?php if("" !== $sf_data->getRAW("user")->getConfig()->get('city','profile')):?> 
      <dt><?php echo 'Stadt:' ?></dt>
     <dd><?php echo $sf_data->getRAW("user")->getConfig()->get('city', null, 'profile'); ?> </dd>
    <?php endif; ?>
    <?php if(true === $sf_data->getRAW("user")->getConfig()->has('website','profile') && true === yiggTools::isProperURL($sf_data->getRAW("user")->getConfig()->get('website', false, 'profile'))):?>

    <?php endif; ?>
    <?php if(true === $sf_data->getRAW("user")->getConfig()->has("about_me", "profile")):?>
     <dt><?php echo 'Über mich:' ?></dt>
     <dd class="about"><?php echo mb_substr($sf_data->getRAW('user')->getConfig()->get("about_me", null, "profile"),0,160); ?> </dd>
    <?php endif;?>
  </dl>
</div>

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
       <?php endif;?>
     <?php else:?>
       <?php echo button_to("Profil bearbeiten", "@user_my_profile", array("class" => "profile"));?>
       <?php echo button_to("Einstellungen", "@user_settings", array("class" => "settings"));?>
     <?php endif;?>
     <?php if($user->facebook_id):?>
       <input type="button" id="facebook_friends" onclick="sendFbFriendRequest();" value="Add Facebook Friends" class="facebook">
     <?php endif;?>
   </div>
<?php endif;?>

 <?php if(count($stories) > 0): ?>
    <ol id="story-list" class="story-list hfeed">
      <?php foreach($stories as $k => $story ): ?>
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
      <?php endforeach; ?>
    </ol>
   <?php echo $pager->display(); ?>
  <?php else: ?>
    <p class="error">Es wurden keine Nachrichten gefunden.</p>
  <?php endif; ?>


<?php slot("sidebar")?>
  <?php include_partial("userWidgetStats", array("user" => $user))?>

  <?php if(count($followedUsers) > 0):?>
    <h3>Abonnements</h3>
    <?php include_partial("user/avatarList", array("users" => $followedUsers));?>
  <?php endif;?>

  <?php if(count($followingUsers) > 0):?>
    <h3>Abonnenten</h3>
    <?php include_partial("user/avatarList", array("users" => $followingUsers));?>
  <?php endif;?>

  <?php if( true === $sf_user->hasUser() && $sf_user->getUserId() !== $user->id):?>
    <?php include_component("atPage", "pmFormForUser", array("user" => $user));?>
  <?php endif; ?>

  <?php if(count($user->Tags) > 0):?>
    <h3><?php echo $user->username;?> interessiert sich für:
      <?php echo link_to(image_tag("silk-icons/help.png", array("alt" => "Hilfe")), "http://hilfe.yigg.de/doku.php?id=grundlagen", array("title" => "Zur Hilfe", "rel" => "external"));?>
    </h3>
    <?php echo include_partial("tag/subscribe", array("tags" => $user->Tags));?>
  <?php endif;?>
<?php end_slot()?>