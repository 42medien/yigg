<?php use_helper("Date", "Text", "SocialShare"); ?>
<?php if($sf_request->getModule() == "story" && $sf_request->getAction() =="show"){ include_partial("system/systemMessages");} ?>
<?php use_javascript('http://button.spread.ly/js/v1/loader.js'); ?>

<article class="hentry post h-entry clearfix" id="<?php echo "story_{$story['id']}"; ?>">
  <div class="attachement">
    <?php if ($embed_code) { ?>
    <div class="media-container">
      <?php echo $sf_data->getRaw("embed_code"); ?>
    </div>
    <?php
    } elseif ($source = $story->getStoryImageSource()) {
      echo link_to(img_tag($source), url_for_story($story, "bar"), array("title" => $story->title, "rel" => "nofollow", 'target' => '_blank'));
    }
    ?>
  </div>

  <header>
    <?php include_component( 'story', 'rateStory',  array('story' => $story, 'type' => 'full')); ?>
    <h3 class="entry-title p-name">
      <?php echo link_to($story->title, url_for_story($story, "bar"), array("title" => $story->title, "rel" => "nofollow", 'target' => '_blank')); ?>
    </h3>

    <div class="entry-meta">
      <i class="icon-calendar"></i> <time class="dt-published dt-updated published updated" datetime="<?php echo date(DATE_ATOM, strtotime($story['created_at'])); ?>"><?php echo format_date($story->getCreatedAt(),"g","de",'UTF-8'); ?></time> |
      <i class="icon-user"></i> <span class="author p-author vcard hcard h-card"><?php echo link_to(
                  $story['Author']['username'],
                  "@user_public_profile?username={$story['Author']['username']}",
                   array('class' => 'url u-url fn n p-name', 'title' => "Profil von {$story['Author']['username']} besuchen"));?></span>
      <?php if(true === $story->canEdit($sf_user)): ?>
        | <i class="icon-edit"></i> <?php echo link_to_story(
                    "bearbeiten",
                      $story,
                      array(
                        "view" => "edit",
                        "title" => 'Nachricht Bearbeiten: '  . $story->title,
                        "rel" => "nofollow",
                        "class" => "edit"
                      )
                     );
                     ?>
      <?php endif; ?>
    </div>
  </header>

  <div class="body e-description entry-description<?php echo ($story["type"] === Story::TYPE_VIDEO) ? " video":"";?>">
    <p><?php echo $story->getPresentationDescription(ESC_RAW); ?></p>

    <p><span class="entry-domain"><?php echo link_to($story->getDomain()->getHostname(), "@domain_show?id=".$story->getDomain()->getId()); ?></span></p>
  </div>

  <footer>
    <div class="entry-meta">
      <?php include_partial("story/spreadlyButton", array("url" => $story->external_url)); ?>

      <!-- Place this tag in your head or just before your close body tag. -->
      <script type="text/javascript" src="https://apis.google.com/js/plusone.js">
        {lang: 'de'}
      </script>

      <!-- Place this tag where you want the +1 button to render. -->
      <div class="g-plusone" data-size="small" data-annotation="inline" data-width="300" data-href="<?php echo $story->external_url; ?>"></div>
    </div>
  </footer>
</article>

<?php include_partial("sponsoring/story_single"); ?>

<?php include_component("comment", "commentList", array("obj" => $story, "inlist" => isset($inlist)  ? $inlist : false, 'at_beginning' => true)); ?>

<?php slot('sidebar') ?>

<?php include_partial('story/storyActions', array("story" => $story));?>

<?php if(false == cache("story-detail-{$story['id']}-votes{$story->currentRating()}")): ?>
<section id="widget-last-yiggs" class="widget">
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

<?php include_component("story", "twitterShares", array("url" => $story["external_url"])); ?>

<section id="widget-sources" class="widget">
  <h2>Jetzt und später mehr von dieser Quelle</h2>

  <?php if($sf_user->hasUser() && $story->getDomain()->isSubscriber($sf_user->getUser())):?>
    <?php echo now_later_button($story->getDomain()->getHostname(),
                                "@domain_show?id=".$story->getDomain()->getId(),
                                "@domain_subscribe?id=".$story->getDomain()->getId(),
                                array("class" => "subscribed"));?>
  <?php else:?>
    <?php echo now_later_button($story->getDomain()->getHostname(),
                                "@domain_show?id=".$story->getDomain()->getId(),
                                "@domain_subscribe?id=".$story->getDomain()->getId());?>
  <?php endif;?>
</section>


<?php if(count($story->getTags()) > 0):?>
<section id="widget-tags" class="widget">
  <h2>Mehr lesen zu diesen Themen</h3>

  <?php include_partial('tag/subscribe', array("tags" => $story->getTags()));?>
</section>
<?php endif;?>

<?php include_partial('category/categories', array("categories" => $story->Categorys));?>

<?php if(count($story->Comments) > 3):?>
  <?php include_component("comment", "latestComments");?>
<?php endif;?>
<?php end_slot() ?>

<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js" data-pin-hover="true"></script>