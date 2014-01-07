<?php
$video_params = array(
	"configFileUrl" => "http://videos.arte.tv/cae/static/flash/player/config.xml",
	"lang" => "de",
	"mode" => "prod",
	"videorefFileUrl" => "http://videos.arte.tv/de/do_delegate/videos/-{$video->getVideoId()},view,asPlayerXml.xml",
	"localizedPathUrl" => "http://videos.arte.tv/cae/static/flash/player/",
	"admin" => "false",
	"embed" => true);?>

<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" id="playerArte" allowScriptAccess="always" width="<?php echo $width?>" height="<?php echo $height;?>" ><param name="allowFullScreen" value="true" />
    <param name="allowScriptAccess" value="always" />
    <param name="quality" value="high">
    <param name="movie" value="http://videos.arte.tv/videoplayer.swf?<?php echo http_build_query($video_params);?>">

    <embed src="http://videos.arte.tv/videoplayer.swf?<?php echo http_build_query($video_params);?>"
           width="<?php echo $width;?>"
           height="<?php echo $height;?>"
           allowFullScreen="true"
           name="playerArte"
           quality="high"
           allowScriptAccess="always"
           pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>
</object>