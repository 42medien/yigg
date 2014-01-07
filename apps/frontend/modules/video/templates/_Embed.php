<object data="<?php echo $video->getPlayerUrl(); ?>" type="application/x-shockwave-flash" quality="high" wmode="transparent" pluginspage="http://www.adobe.com/go/getflashplayer" height="<?php echo $height;?>" width="<?php echo $width;?>">
  <param name="movie" value="<?php echo $video->getPlayerUrl(); ?>">
  <param name="allowFullScreen" value="true">
</object>