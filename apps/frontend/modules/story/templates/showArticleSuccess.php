<?php use_helper("Date", "Text", "SocialShare"); ?>
<?php use_javascript('http://button.spread.ly/js/v1/loader.js'); ?>

<article class="hentry post h-entry clearfix" id="<?php echo "story_{$story['id']}"; ?>">
  <header>
    <?php include_component( 'story', 'rateStory',  array('story' => $story, 'completeStory' => true)); ?>
    <h3 class="entry-title p-name">
      <?php echo link_to_story(truncate_text($story->title, 75), $story, array("title" => $story->title)); ?>
    </h3>

    <div class="entry-meta">
      <i class="icon-calendar"></i> <time class="dt-published dt-updated published updated">14.12.2012 bla</time> |
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
                   )); ?>
      <?php endif; ?>
    </div>
  </header>

  <div class="body e-description entry-description<?php echo ($story["type"] === Story::TYPE_VIDEO) ? " video":"";?>">
    <p><?php echo $story->getPresentationDescription(ESC_RAW); ?></p>

    <p><span class="entry-domain"><?php echo link_to($story->Domain->hostname, "@domain_show?id=".$story->Domain->id); ?></span></p>
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

<?php include_component("comment", "commentList", array("obj" => $story, "inlist" => isset($inlist)  ? $inlist : false, 'at_beginning' => true)); ?>

<?php slot("sidebar") ?>

<?php include_partial('story/storyActions', array("story" => $story));?>

<section id="widget-last-yiggs" class="widget">
  <?php if(false == cache("story-detail-{$story['id']}-votes{$story->currentRating()}")): ?>
    <h2>Letzte YiGGs von</h2>
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