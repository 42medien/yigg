<object width="<?php echo $width?>" height="<?php echo $height?>">
  <param name="movie" value="http://www.bild.de/BILD/System/video/embedplayer"/>
  <param name="allowFullScreen" value="true"/>
  <param name="allowScriptAccess" value="always"/>
  <param name="FlashVars" value="xmlsrc=<?php echo $video->getVideoUrl();?>"/>
  <embed width="<?php echo $width?>" height="<?php echo $height?>" src="http://www.bild.de/BILD/System/video/embedplayer"
         type="application/x-shockwave-flash" allowFullScreen="true" allowScriptAccess="always"
         FlashVars="xmlsrc=<?php echo $video->getVideoUrl();?>"></embed>
</object>