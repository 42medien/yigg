<?php use_helper("Date", "Text", "SocialShare"); ?>
<header id="branding">
  <nav id="access" role="navigation">
    <a tabindex="1" href="#content" class="skip-link screen-reader-text">Direkt zum Inhalt </a>
    <?php
      echo link_to(img_tag('yigg_logo.png', array(
                'alt' => 'YiGG Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
                'width' => 90,
                'height' => 53
            )), '@best_stories', array(
        'title' => 'YiGG Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
        'rel' => 'home',
        'class' => 'logo'
      ));
    ?>
    
    <div class="spreadly-link" data-spreadly-url="<?php echo $story->external_url; ?>">
      <?php social_counter_simple($story->external_url); ?>
    </div>
    
    <div style="float: right;"><?php include_component( 'story', 'rateStory',  array('story' => $story, 'completeStory' => true)); ?></div>
  </nav>
</header>

<div id="iframe-content">
  <iframe height="100%" frameborder="0" width="100%" name="sFrame" src="<?php echo $story->external_url; ?>">
    <p>Your browser does not support iframes.</p>
  </iframe>
</div>