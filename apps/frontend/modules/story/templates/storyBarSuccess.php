<?php
  use_helper("Date", "Text", "SocialShare");
  
  slot('links', auto_discovery_link_tag("html", $story->getExternalUrl(), array("rel" => "canonical", "type" => "text/html", "title" => "canonical url")));
?>

<header id="branding">
  <nav id="access" role="navigation">
    <a tabindex="1" href="#content" class="skip-link screen-reader-text">Direkt zum Inhalt </a>
    <?php
      echo link_to_story(img_tag('yigg_logo.png', array(
                'alt' => 'YiGG Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
                'width' => 90,
                'height' => 53
            )), $story,
                array(
        'title' => 'YiGG Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
        'rel' => 'home',
        'class' => 'logo'
      ));
    ?>
    
    <ul class="bar-items">
      <li>
        <div id="comments">
          <button id="comments-label"><i class="icon-comment"></i> Kommentieren</button>
          <div id="comments-content">
            <?php include_component("comment", "commentList", array("obj" => $story, "inlist" => isset($inlist)  ? $inlist : false, 'at_beginning' => true)); ?>
          </div>
        </div>
      </li>
      <li>
        <?php include_component( 'story', 'rateStory',  array('story' => $story, 'flat' => true)); ?>
      </li>
      <li>
        <a class="spreadly-button" href="<?php echo $story->external_url; ?>"></a>
      </li>
    </ul>
  </nav>
</header>

<div id="iframe-content">
  <iframe height="100%" frameborder="0" width="100%" name="sFrame" src="<?php echo $story->external_url; ?>">
    <p>Your browser does not support iframes.</p>
  </iframe>
</div>

<script type="text/javascript">
  <!--
  jQuery("#comments-label").click(function() {
    jQuery("#comments-content").toggle();
  });
  //-->
</script>