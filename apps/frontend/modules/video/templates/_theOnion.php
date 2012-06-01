<object width="<?php echo $width;?>" height="<?php echo $height;?>"><param name="allowfullscreen" value="true" />
    <param name="allowscriptaccess" value="always" />
    <param name="movie" value="http://media.theonion.com/flash/video/embedded_player.swf?videoid=<?php echo $video->getVideoId();?>" />
    <param name="wmode" value="transparent" /><embed src="http://media.theonion.com/flash/video/embedded_player.swf" type="application/x-shockwave-flash" allowScriptAccess="always" allowFullScreen="true" wmode="transparent" width="<?php echo $width;?>" height="<?php echo $height;?>" flashvars="videoid=<?php echo $video->getVideoId();?>"> 
    </embed>
</object>