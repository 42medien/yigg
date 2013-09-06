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
    
    <div id="bar_comments">
      <button id="bar_comments_label"><i class="icon-comment"></i> Kommentieren</button>
      <div id="bar_comments_content">
        <?php include_component("comment", "commentList", array("obj" => $story, "inlist" => isset($inlist)  ? $inlist : false, 'at_beginning' => true)); ?>
      </div>
    </div>
    
    <a class="spreadly-button" style="float: left;" href="<?php echo $story->external_url; ?>"></a>
    
    <div style="float: right;"><?php include_component( 'story', 'rateStory',  array('story' => $story, 'type' => 'small')); ?></div>
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
                $("#bar_comments_content").hide();

                $("#bar_comments_label").click(function() {
                    $("#bar_comments_content").toggle();
                });

                $('#bar_comments_content').delegate('form', 'submit',function(event){

                    $.post(
                        $('.comment').attr('action'),
                        $(".comment").serialize(),
                        function(data) {
                            $('#bar_comments_content').html(data);
                        }
                    );
                    event.preventDefault();
                    return false;
                });

                $('#bar_comments_content').delegate('a', 'click',function(event){
                    var href_action =  $(this).attr('href');
                    var chunks = href_action.split('/');

                    $.post(
                        $(this).attr('href'),
                        function(data) {
                            $('#comment-'+chunks[chunks.length - 1]).remove();
                        }
                    );
                    event.preventDefault();
                    return false;
                });
            });

            function redirect() {
                location.href = "<?php echo $story->external_url;?>";
            }
            //-->
        </script>