<?php use_helper("Date", "Text", "SocialShare"); ?>
<article class="hentry post h-entry clearfix" id="<?php echo "story_{$story['id']}"; ?>">
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
    </p>
  </div>
  
  <footer>
    <div class="entry-meta spreadly-link" data-spreadly-url="<?php echo url_for_story($story, null, true); ?>">
      <?php social_counter(url_for_story($story, null, true)); ?>
    </div>
    
    <?php include_component( 'story', 'rateStory',  array('story' => $story, 'completeStory' => true)); ?>
  </footer>
</article>

<?php include_component("comment", "commentList", array("obj" => $story, "inlist" => isset($inlist)  ? $inlist : false, 'at_beginning' => true)); ?>

<?php slot("sidebar") ?>
<section id="widget-last-yiggs" class="widget">
  <?php if(false == cache("story-detail-{$story['id']}-votes{$story->currentRating()}")): ?>
    <h3 class="heading-right">Letzte YiGGs von</h3>
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
        );?></li>
      <?php endforeach;?>
    </ul>
    <?php cache_save(); ?>
  <?php endif;?>
</section>
<?php end_slot()?>