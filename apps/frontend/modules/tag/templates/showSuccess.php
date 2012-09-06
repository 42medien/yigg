<?php if( true === $sf_user->hasUser()): ?>
   <div class="top_action_row">
       <?php if( false === $sf_user->getUser()->followsTag($tag->getRawValue()) ): ?>
         <?php echo button_to("Abonnieren", "@tag_subscribe?tag_id={$tag->id}", array("class" => "follow"));?>
       <?php else:?>
         <?php echo button_to("Abonniert!", "@tag_subscribe?tag_id={$tag->id}", array("class" => "unfollow"));?>
       <?php endif;?>
   </div>
<?php endif;?>
<div class="story-list-cont">
<ol class="story-list hfeed">
  <?php foreach($stories as $k => $story ): ?>
    <?php include_partial('story/story',
        array(
          'story' => $story,
          'summary' => true,
          'count' => $k,
          'total' => count($stories),
          'inlist' => true
        )
      );
    ?>
  <?php endforeach; ?>
</ol>
</div>
<?php echo $pager->display();?>

<?php slot("sidebar")?>
  <?php if(count($video_stories) > 0):?>
     <h3>Die neusten Videos zum Tag</h3>
     <?php foreach($video_stories as $video):?>
         <?php include_partial('video/storyVideo', array("story" => $video, "width" => 370, "height"=> 285)) ?>
     <?php endforeach;?>
  <?php endif;?>
<?php end_slot()?>