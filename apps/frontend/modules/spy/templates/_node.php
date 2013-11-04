<?php
var_dump($node);

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

<?php use_helper("Date", "Text"); ?>
<article class="hentry post h-entry" id="<?php echo "story_{$story['id']}"; ?>">
  <div class="attachement">
    <?php
      if($source = $story->getStoryImageSource()){
        echo img_tag($source);
      }
    ?>
  </div>

  <header>
    <h3 class="entry-title p-name">
      <?php echo link_to_story(truncate_text($story->title, 75), $story, array("title" => $story->title)); ?>
      <?php echo link_to('<i class="icon-external-link"></i>', $story["external_url"], array('rel' => 'nofollow external', 'title' => 'direkt besuchen', 'class' => 'external', 'target' => '_blank')); ?>
    </h3>

    <div class="entry-meta">
      <i class="icon-calendar"></i> <time class="dt-published dt-updated published updated">14.12.2012 bla</time> |
      <i class="icon-user"></i> <span class="author p-author vcard hcard h-card"><?php echo link_to(
                  $story['Author']['username'],
                  "@user_public_profile?username={$story['Author']['username']}",
                   array('class' => 'url u-url fn n p-name', 'title' => "Profil von {$story['Author']['username']} besuchen"));?></span>
    </div>
  </header>

  <div class="body p-summary entry-summary<?php echo ($story["type"] === Story::TYPE_VIDEO) ? " video":"";?>">
    <p>
      <?php echo $story->getDescriptionSummary(600, ESC_RAW);?>
      <?php
        $external_url_title = parse_url(str_replace('www.','',$story["external_url"]))
      ?>
      <?php echo link_to('mehr bei '.$external_url_title['host'], url_for_story($story, "bar"), array("title" => $story->title, "rel" => "nofollow", 'target' => '_blank'));?>
    </p>
  </div>

  <?php //include_component( 'story', 'rateStory',  array('story' => $story, 'completeStory' => false)); ?>
</article>