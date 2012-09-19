<?php if($bar): ?>
<div id="bar_related_stories_label">
    <div class="related_stories">
        <?php
        $story = $stories->get(0);

        echo link_to(
            avatar_tag($story->Author->Avatar, "icon.gif", 35,35),
            "@user_public_profile?username={$story->Author->username}");

        echo "Read Related Stories:";
        echo link_to($story['title'], url_for_story($story, "bar"), array("title" => $story->title, "rel" => "nofollow"));
        ?>
    </div>
    <div id="show_stories">Toogle</div>
</div>
<div id="bar_related_stories_content">
    <ol class="avatar-list-style">
        <?php foreach ( $stories as $story): ?>
        <li>
            <?php
            if($bar){
                echo link_to(
                    avatar_tag($story->Author->Avatar, "icon.gif", 14,14),
                    "@user_public_profile?username={$story->Author->username}");
                echo link_to($story['title'], url_for_story($story, "bar"), array("title" => $story->title, "rel" => "nofollow"));
            }else{
                echo link_to($story['title'], url_for_story($story), array("title" => $story->title, "rel" => "nofollow"));
            }
            ?>
        </li>
        <?php endforeach; ?>
    </ol>
</div>

<script type="text/javascript">
    <!--
    $(function() {
        $("#bar_related_stories_content").hide();

        $("#show_stories").click(function() {
            $("#bar_related_stories_content").toggle();
        });
    });
    //-->
</script>
<?php else:?>
<br />
<div id="plista_widget_standard_1"></div>
<script type="text/javascript">
    PLISTA.items.push({
        objectid: "1137092b50",
        title: "News of the day",
        url: "http://example.net/news/2009/newsf0ad8173.html",
        text: "Das ist der Anfang dieses Artikels",
        img: "http://example.net/images/newsf0ad8173.jpg"
    });

    PLISTA.partner.init();
</script>
<!--<div style="font-size: 108% !important; font-weight: bold; margin-top: 10px; margin-bottom:3px;">Das könnte Sie auch interessieren:</div>-->
<div id="related_stories_content">
    <ol class="avatar-list-style">
        <?php foreach ( $stories as $story): ?>
        <li>
            <?php
            echo link_to($story['title'], url_for_story($story), array("title" => $story->title, "rel" => "nofollow"));
            ?>
        </li>
        <?php endforeach; ?>
    </ol>
</div>
<?php endif;?>