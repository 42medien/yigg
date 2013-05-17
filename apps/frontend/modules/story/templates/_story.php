<?php use_helper("Date", "Text"); ?>
<article class="hentry post h-entry" id="story_{$story['id']}">  
  <div class="attachement">
    <?php
      if ($story->type == Story::TYPE_NORMAL):
        $source = $story->getStoryImageSource();
        if($source){
          echo img_tag($story->getStoryImageSource(), array(
		        'data-w' => 300,
		        'data-h' => 169,
		        'class' => 'js-resize'	
	            ));
        } else {
          echo img_tag("http://stromboli.yigg.de/?url=" . $story->external_url, array());
        }
      endif;
    ?>
  </div>
  <header>
    <h3 class="entry-title p-title">
      <?php echo link_to_story(truncate_text($story->title, 75), $story, array("title" => $story->title)); ?>
      <?php echo link_to('<i class="icon-external-link"></i>', $story["external_url"], array('rel' => 'nofollow', 'title' => $story->title, 'class' => 'logo', 'target' => '_blank')); ?>
    </h3>
    
    <div class="entry-meta">
      <i class="icon-calendar"></i> <time class="dt-published dt-updated published updated">14.12.2012 bla</time>
      <i class="icon-user"></i> <span class="author p-author vcard hcard h-card"><a href="">a</a></span>
    </div>
  </header>

  <div class="body e-summary entry-summary<?php echo ($story["type"] === Story::TYPE_VIDEO) ? " video":"";?>">
    <p>       
      <?php echo $story->getDescriptionSummary(600, ESC_RAW);?>
      <?php
        $external_url_title = parse_url(str_replace('www.','',$story["external_url"]))
      ?>
      <?php echo link_to('mehr bei '.$external_url_title['host'], url_for_story($story, "bar"), array("title" => $story->title, "rel" => "nofollow", 'target' => '_blank'));?>
    </p>
  </div>
</article>