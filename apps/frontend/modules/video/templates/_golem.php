<object width="<?php echo $width;?>" height="<?php echo $height;?>">
  <param name="movie" value="http://video.golem.de/player/videoplayer.swf?id=<?php echo $video->getVideoId();?>&autoPl=false"></param>
  <param name="allowFullScreen" value="true"></param>
  <param name="AllowScriptAccess" value="always">
  <embed src="http://video.golem.de/player/videoplayer.swf?id=5201&autoPl=false"
         type="application/x-shockwave-flash" allowfullscreen="true"
         AllowScriptAccess="always" width="<?php echo $width;?>" height="<?php echo $height;?>"></embed>
</object>