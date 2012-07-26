<div id="bar_related_stories_label">
    <?php
    $story = $stories->get(0);
    echo link_to(
        avatar_tag($story->Author->Avatar, "icon.gif", 14,14),
        "@user_public_profile?username={$story->Author->username}");
    echo link_to($story['title'], url_for_story($story, "bar"), array("title" => $story->title, "rel" => "nofollow"));
    ?>
    <div id="show_stories">Toogle</div>
</div>
<div id="bar_related_stories_content">
    <ol class="avatar-list-style">
        <?php foreach ( $stories as $story): ?><li><?php
        echo link_to(
            avatar_tag($story->Author->Avatar, "icon.gif", 14,14),
            "@user_public_profile?username={$story->Author->username}");
        echo link_to($story['title'], url_for_story($story, "bar"), array("title" => $story->title, "rel" => "nofollow"));
        ?></li>
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