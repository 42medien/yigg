<object width="<?php echo $width;?>" height="<?php echo $height;?>"><param name="allowfullscreen" value="true" />
    <param name="allowscriptaccess" value="always" />
    <param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=<?php echo $video->getVideoId();?>&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=00adef&amp;fullscreen=1" />
    <embed src="http://vimeo.com/moogaloop.swf?clip_id=<?php echo $video->getVideoId();?>&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=00adef&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="<?php echo $width;?>" height="<?php echo $height;?>">
    </embed>
</object>