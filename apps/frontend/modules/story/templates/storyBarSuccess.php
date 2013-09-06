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
        <?php include_component( 'story', 'rateStory',  array('story' => $story, 'type' => 'small')); ?>
      </li>
      <li>
        <a class="spreadly-button" style="float: left;" href="<?php echo $story->external_url; ?>"></a>
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
  $(function() {
    $("#comments-label").click(function() {
      $("#comments-content").toggle();
    });

    $('#comments-content').delegate('form', 'submit',function(event){

      $.post(
        $('.comment').attr('action'),
        $('.comment').serialize(),
        function(data) {
          $('#comments-content').html(data);
        }
      );
      
      event.preventDefault();
      return false;
    });

    $('#comments-content').delegate('a', 'click',function(event){
      var href_action =  $(this).attr('href');
      var chunks = href_action.split('/');

      $.post(
        $(this).attr('href'),
        function(data) {
          $('#li-comment-'+chunks[chunks.length - 1]).remove();
        }
      );
      
      event.preventDefault();
      return false;
    });
  });
  //-->
</script>