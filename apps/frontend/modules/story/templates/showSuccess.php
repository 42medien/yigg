<?php use_helper("Date", "Text", "SocialShare"); ?>
<?php if($sf_request->getModule() == "story" && $sf_request->getAction() =="show"){ include_partial("system/systemMessages");} ?>

<article class="hentry post h-entry clearfix" id="<?php echo "story_{$story['id']}"; ?>">
  <div class="attachement">
    <?php
      //if ($story->type == Story::TYPE_NORMAL) {
        $source = $story->getStoryImageSource();
      
        if($source){
          echo img_tag($story->getStoryImageSource());
        }
        //}
    ?>
  </div>
  
  <header>
    <h3 class="entry-title p-title">
      <?php echo link_to_story(truncate_text($story->title, 75), $story, array("title" => $story->title)); ?>
    </h3>
    
    <div class="entry-meta">
      <i class="icon-calendar"></i> <time class="dt-published dt-updated published updated">14.12.2012 bla</time> | 
      <i class="icon-user"></i> <span class="author p-author vcard hcard h-card"><?php echo link_to(
                  $story['Author']['username'],
                  "@user_public_profile?username={$story['Author']['username']}",
                   array('class' => 'url u-url fn n p-name', 'title' => "Profil von {$story['Author']['username']} besuchen"));?></span>
    </div>
  </header>
  
  <div class="body e-description entry-description<?php echo ($story["type"] === Story::TYPE_VIDEO) ? " video":"";?>">
    <p>       
      <?php echo $story->getDescription();?>
      <?php
        $external_url_title = parse_url(str_replace('www.','',$story["external_url"]))
      ?>
      <?php echo link_to('mehr bei '.$external_url_title['host'], url_for_story($story, "bar"), array("title" => $story->title, "rel" => "nofollow", 'target' => '_blank'));?>
    </p>
  </div>
  
  <footer>
    <div class="entry-meta">
      <?php social_counter($story->external_url); ?>
      <?php //include_component( 'story', 'rateStory',  array('story' => $story, 'completeStory' => true)); ?>
    </div>
  </footer>
</article>

<?php include_component("comment", "commentList", array("obj" => $story, "inlist" => isset($inlist)  ? $inlist : false, 'at_beginning' => true)); ?>

<?php slot('sidebar') ?>
<section id="widget-last-yiggs" class="widget">
  <?php if(false == cache("story-detail-{$story['id']}-votes{$story->currentRating()}")): ?>
    <?php $usernames = array();?>
    <h2 class="heading-right">Letzte YiGGs und TwiGGs</h2>
    <?php $ratings = Doctrine::getTable("StoryRating")->findByDql("story_id = ? AND user_id <> 1 LIMIT 20 ORDER BY id DESC",array($story->id)) ?>
     <ul class="avatar-list">
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
</section>
<?php endif; ?>

<section id="widget-tweets" class="widget">
  <h2 class="heading-right">Passende Tweets</h2>
  <ul class="avatar-list">
    <li>             
        <?php 
        //echo 'Uname: <b>' . $rating["User"]->username . '</b>';
        //echo $story["external_url"]; 
        $host_name = $story["external_url"];
        if($host_name != '')
        {
            $endpoint = sprintf(
                                'http://search.twitter.com/search.json?q=%s',
                                $host_name
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
                echo 'Invalid Credentials';
            }
            else {
                echo ''; // Invalid Response
            }
        ?>                
        <?php foreach($tweets['results'] as $tweet_res) { ?>

            <?php 
                                   
            $twitter_username = htmlSpecialChars($tweet_res['from_user']);
            $twitter_username_status = htmlSpecialChars($tweet_res['id']);
            $tweer_url = 'https://twitter.com/' . $twitter_username . '/status/' . $twitter_username_status;
            
            echo
                link_to(
                    img_tag(
                    htmlSpecialChars($tweet_res['profile_image_url']),
                    array(
                        "width" => 48,
                        "height" => 48,
                        "class" => "avatar",
                        "alt"=> "Profil von {$twitter_username} besuchen")
                    ),
                    $tweer_url,
                    array(
                    "title" => "Tweet von {$twitter_username} besuchen",
                    "rel" => "nofollow"
                    )
                );?>
        <?php } ?>
    <?php } ?>             
    </li>
  </ul>
</section>
    
<section id="widget-more-sources" class="widget">
  <h2 class="help_icon">Jetzt und später mehr von dieser Quelle:
    <?php echo link_to(image_tag("silk-icons/help.png", array("alt" => "Hilfe")), "http://hilfe.yigg.de/doku.php?id=grundlagen", array("title" => "Zur Hilfe", "rel" => "external"));?>
  </h2>
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
    <h3 class="help_icon">
      Mehr lesen zu diesen Themen:                                    
      <?php echo link_to(image_tag("silk-icons/help.png", array("alt" => "Hilfe")), "http://hilfe.yigg.de/doku.php?id=grundlagen", array("title" => "Zur Hilfe", "rel" => "external"));?>
    </h3>
      
    <?php include_partial('tag/subscribe', array("tags" => $story->Tags));?>
  <?php endif;?>

  <?php if(count($story->Comments) > 3):?>
    <?php include_partial("comment/latestComments");?>
  <?php endif;?>
<div class="clr"><!--  --></div>
</section>
<?php end_slot() ?>