<?php
  $year = substr($node["story_created_at"], 0, 4);
  $month = substr($node["story_created_at"], 5, 2);
  $day = substr($node["story_created_at"], 8, 2);
?>
<div id="Event<?php echo str_replace('.','-', $node['epoch_time']); ?>" class="spy node" <?php if($hidden):?> style="display:none;" <?php endif; ?> ><span class="vcard"><?php echo link_to( $node['username'] ,"@user_public_profile?view=live-stream&username=" . $node['username'],array("class"=> "fn url nickname", "title" => 'Betrachte '. $node['username'] . 's Profil')); ?></span><?php
  $fileReference = "uploads/images/avatars/avatar-".$node['username']."/" . "avatar-".$node["username"]."-thumbnail-14-14" .".png";
  if( file_exists( $fileReference  ) )
  {
    echo img_tag($fileReference , array("height"=> 14, "width"=>14, "class" => "tinyavatar", "alt" => $node['username']."'s Avatar"));
  }
  else
  {
    echo img_tag("icon.gif", array("height"=> 14, "width"=>14, "class" => "tinyavatar", "alt" => ""));
  }
?><div class="votes"><?php echo $node['currentRating']; ?> Stimmen</div><div class="icon"><?php echo img_tag( $node['tablename'].'.png', array('alt'=>$node['tablename'],'height'=>'16','width'=>'16')); ?></div><strong class="title"><?php echo link_to( mb_substr(htmlspecialchars_decode($node['story_title']),0,60,'utf-8') , "@story_show?year=$year&month=$month&day=$day&slug=" . $node['story_internal_url'], array("class" => "storylink", "title" => htmlspecialchars_decode($node['story_title']))); ?></strong></div>