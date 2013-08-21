<?php use_helper("Date", "Text"); ?>
<article class="hentry post h-entry" id="<?php echo "story_{$story['id']}"; ?>">
  <div class="attachement">
    <?php
      if ($story->type == Story::TYPE_NORMAL) {
        if($source = $story->getStoryImageSource()){
          echo img_tag($source);
        }
      }
    ?>
  </div>
  
  <header>
    <h3 class="entry-title p-title">
      <?php echo link_to_story(truncate_text($story->title, 75), $story, array("title" => $story->title)); ?>
      <?php echo link_to('<i class="icon-external-link"></i>', $story["external_url"], array('rel' => 'nofollow external', 'title' => 'direkt besuchen', 'class' => 'external', 'target' => '_blank')); ?>
    </h3>
    
    <div class="entry-meta">
      <i class="icon-calendar"></i> <time class="dt-published dt-updated published updated">14.12.2012 bla</time> | 
      <i class="icon-user"></i> <span class="author p-author vcard hcard h-card"><?php echo link_to(
                  $story['Author']['username'],
                  "@user_public_profile?username={$story['Author']['username']}",
                   array('class' => 'url u-url fn n p-name', 'title' => "Profil von {$story['Author']['username']} besuchen"));?></span>
    </div>
  </header>

  <div class="body p-summary entry-summary<?php echo ($story["type"] === Story::TYPE_VIDEO) ? " video":"";?>">
    <p>       
      <?php echo $story->getDescriptionSummary(600, ESC_RAW);?>
      <?php
        $external_url_title = parse_url(str_replace('www.','',$story["external_url"]))
      ?>
      <?php echo link_to('mehr bei '.$external_url_title['host'], url_for_story($story, "bar"), array("title" => $story->title, "rel" => "nofollow", 'target' => '_blank'));?>
    </p>
    <br style="clear: both;" />
  </div>
</article>