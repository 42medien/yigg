<?php use_helper("Date", "Text"); ?>
<article class="hentry post h-entry" id="<?php echo "story_{$story['id']}"; ?>">
  <div class="attachement">
    <?php
    if($source = $story->getStoryImageSource()){
      echo link_to_story(img_tag($source), $story);
    }
    ?>
  </div>
  
  <header>
    <h3 class="entry-title p-title">
      <?php echo link_to_story(truncate_text($story->title, 75), $story, array("title" => $story->title)); ?>
    </h3>
    
    <div class="entry-meta">
      <i class="icon-calendar"></i> <time class="dt-published dt-updated published updated" datetime="<?php echo date(DATE_ATOM, strtotime($story['created_at'])); ?>"><?php echo format_date($story->getCreatedAt(),"g","de",'UTF-8'); ?></time> | 
      <i class="icon-user"></i> <span class="author p-author vcard hcard h-card"><?php echo link_to(
                  $story['Author']['username'],
                  "@user_public_profile?username={$story['Author']['username']}",
                   array('class' => 'url u-url fn n p-name', 'title' => "Profil von {$story['Author']['username']} besuchen"));?></span> |
      <i class="icon-comment"></i> <?php echo link_to_story($story->currentCommentCount(), $story); ?>
    </div>
  </header>

  <div class="body p-summary entry-summary<?php echo ($story["type"] === Story::TYPE_VIDEO) ? " video":"";?>">
    <p>       
      <?php echo $story->getDescriptionSummary(600, ESC_RAW);?>
      <?php
        $external_url_title = parse_url(str_replace('www.','',$story["external_url"]))
      ?>
    </p>
  </div>
</article>