<?php if($sf_request->getModule() == "story" && $sf_request->getAction() =="show"){ include_partial("system/systemMessages");} ?>

<?php
  include_partial('story',
    array(
      'story' => $story,
      'count' => 0,
      'summary' => false,
      'inlist' => false,
      'compacted' => false
    )
  );
?>
<?php //if(false === $sf_request->isAjaxRequest()): ?>
   <?php //echo adsense_ad_tag(7012733842, 468, 60);?>
<?php //endif; ?>


<?php slot('sidebar') ?>
  <?php if(false == cache("story-detail-{$story['id']}-votes{$story->currentRating()}")): ?>
    <?php $usernames = array();?>
    <h3 class="heading-right">Letzte YiGGs und TwiGGs</h3>
    <?php $ratings = Doctrine::getTable("StoryRating")->findByDql("story_id = ? AND user_id <> 1 LIMIT 20 ORDER BY id DESC",array($story->id)) ?>
     <ul class="avatarList">
     <?php foreach($ratings as $k => $rating):?>
         <li><?php echo
         link_to(
           avatar_tag( $rating["User"]->Avatar, "noavatar-48-48.png", 48,48,
             array(
               "class" => "avatar",
               "alt"=> "Profil von {$rating["User"]->username} besuchen")
            ),
           '@user_public_profile?username='.$rating["User"]->username,
           array(
            "title" => "Profil von {$rating["User"]->username} besuchen"
           )
         );?>
         </li>
       <?php endforeach;?>

      <?php $query = Doctrine_Query::create()
                      ->from("StoryTweet st")
                      ->leftJoin("st.Tweet t")
                      ->where("story_id = ?", $story->id);?>
                      <?php if(count($usernames) > 0):?>
                         <?php $query->whereNotIn("t.username", $usernames);?>
                      <?php endif; ?>
                      <?php $tweets = $query->limit(20)->execute();?>

      <?php if(false !== count($tweets) > 0):?>
        <?php foreach($tweets as $tweet):?>
           <li><?php echo
           link_to(
             img_tag(
               $tweet["Tweet"]->profile_image_url,
               array(
                 "width" => 48,
                 "height" => 48,
                 "class" => "avatar",
                 "alt"=> "Profil von {$tweet["Tweet"]->username} besuchen")
             ),
             $tweet["Tweet"]->getYiggTwitterProfileLink(),
             array(
              "title" => "Profil von {$tweet["Tweet"]->username} besuchen",
              "rel" => "nofollow"
             )
           );?></li>
         <?php endforeach;?>
       <?php endif;?>

     </ul>
   <?php cache_save(); ?>
 <?php endif; ?>

  <h3>Jetzt und später mehr von dieser Quelle:
    <?php echo link_to(image_tag("silk-icons/help.png", array("alt" => "Hilfe")), "http://hilfe.yigg.de/doku.php?id=grundlagen", array("title" => "Zur Hilfe", "rel" => "external"));?>
  </h3>
  <?php if($sf_user->hasUser() && $story->Domain->isSubscriber($sf_user->getUser())):?>
    <?php echo now_later_button($story->Domain->hostname,
                                "@domain_show?id=".$story->Domain->id,
                                "@domain_subscribe?id=".$story->Domain->id,
                                array("class" => "subscribed"));?>
  <?php else:?>
    <?php echo now_later_button($story->Domain->hostname,
                                "@domain_show?id=".$story->Domain->id,
                                "@domain_subscribe?id=".$story->Domain->id);?>
  <?php endif;?>
  <div class="clr"></div>

  <?php if(count($story->Tags) > 0):?>
    <h3>
      Mehr lesen zu diesen Themen:
      
      
      <ul class="avatarList">
     <?php foreach($ratings as $k => $rating):?>
         <li>
             
             
             <?php /*echo
         link_to(
           avatar_tag( $rating["User"]->Avatar, "noavatar-48-48.png", 48,48,
             array(
               "class" => "avatar",
               "alt"=> "Profil von {$rating["User"]->username} besuchen")
            ),
           '@user_public_profile?username='.$rating["User"]->username,
           array(
            "title" => "Profil von {$rating["User"]->username} besuchen"
           )
         ); */ ?>
             <h1>PhpRiot: Twitter API - User Timeline</h1>
             <?php 
                //echo 'Uname: <b>' . $rating["User"]->username . '</b>';
                //$username = 'PhpRiot';
                $username = 'PhpRiot';
                
                $endpoint = sprintf(
                                    'http://api.twitter.com/1/statuses/user_timeline/%s.json?count=10',
                                    $username
                                    );
                $ch = curl_init($endpoint);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                $data = curl_exec($ch);
                $info = curl_getinfo($ch);
                
                curl_close($ch);
                
                if ($info['http_code'] == 200) {
                    $tweets = json_decode($data, true);
                }
                else if ($info['http_code'] == 401) {
                    die('Invalid credentials');
                }
                else {
                    die('Invalid response');
                }


                
             ?>
             <?php foreach($tweets as $tweet) { ?>
                <div class="tweet_side">
                    <img src="<?php echo htmlSpecialChars($tweet['user']['profile_image_url']) ?>" />             
                </div>
             <?php } ?>

             
         </li>
       <?php endforeach;?>     
     </ul>
      
      
      
      <?php echo link_to(image_tag("silk-icons/help.png", array("alt" => "Hilfe")), "http://hilfe.yigg.de/doku.php?id=grundlagen", array("title" => "Zur Hilfe", "rel" => "external"));?>
    </h3>
    <?php include_partial('tag/subscribe', array("tags" => $story->Tags));?>
  <?php endif;?>

  <?php if(count($story->Comments) > 3):?>
    <?php include_partial("comment/latestComments");?>
  <?php endif;?>
<div class="clr"><!--  --></div>
<?php end_slot() ?>

<?php slot("sidebar_sponsoring")?>
  <?php include_component("story","sponsorings", array("story"=> $story)); ?>
<?php end_slot();?>