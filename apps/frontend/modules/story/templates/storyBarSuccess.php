<div id="bar_wraper">
    <div id="bar_content">
        <div id="bar_logo">
            <?php
            echo link_to(img_tag('YiGG-Logo.png', array(
                    'alt' => 'YiGG Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
                    'width' => 191,
                    'height' => 54
                )),
                '@best_stories',
                array(
                    'title' => 'YiGG Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
                    'rel' => 'home',
                    'class' => 'logo'
                ));
            ?>
        </div>
        <div id="bar_rate_story_wraper">
            <?php include_component( 'story', 'rateStory',  array('story' => $story, 'completeStory' => true)); ?>
        </div>
        <div id="bar_comments">
            <div id="bar_comments_label"><img src="/htdocs/images/yig-comments.png"/><?php echo $sf_request->isAjaxRequest();?></div>
            <div id="bar_comments_content">
                <?php include_component("comment", "commentList", array("obj" => $story, "inlist" => isset($inlist)  ? $inlist : false)); ?>
            </div>
        </div>
        <div class="spreadly-button">
            <iframe src="http://button.spread.ly/?url=<?php echo $story->external_url;?>&social=1&title=<?php echo $story->title;?>"
                    style="overflow:hidden; width: 420px; height: 30px; padding: 0px 35px 0 0;"
                    frameborder="0"
                    scrolling="no"
                    marginheight="0"
                    allowTransparency="true">
            </iframe>
        </div>
        <div class="close" onclick="redirect()">Close</div>
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
    </div>
</div>
<div id="iframe-content">
    <iframe height="100%" frameborder="0" width="100%" name="sFrame" src="<?php echo $story->external_url; ?>">
        <p>Your browser does not support iframes.</p>
    </iframe>
</div>