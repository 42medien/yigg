<?php
  include_partial('story',
    array(
      'story' => $story,
      'count' => 0,
      'summary' => false,
      'inlist' => false,
      'compacted' => true
    )
  );
?>

<?php if(false === $sf_request->isAjaxRequest()): ?>
   <?php echo adsense_ad_tag(7012733842, 468, 60);?>
<?php endif; ?>

<?php slot("sidebar") ?>
  <?php if(false == cache("story-detail-{$story['id']}-votes{$story->currentRating()}")): ?>
    <h3 class="heading-right">Letzte YiGGs von</h3>
    <?php $ratings = Doctrine::getTable("StoryRating")->findByDql("story_id = ? AND user_id <> 1 LIMIT 20 ORDER BY id DESC",array($story->id)) ?>
    <ul class="avatarList">
    <?php foreach($ratings as $k => $rating):?>
        <li><?php echo
        link_to(
         avatar_tag( $rating["User"]->Avatar, "noavatar-48-48.png", 48,48,
           array(
             "class" => "avatar",
             "alt"=> "Profil von {$rating["User"]->username} besuchen")
          ),
          '@user_public_profile?username='.$rating["User"]->username,
          array(
           "title" => "Profil von {$rating["User"]->username} besuchen"
          )
        );?></li>
      <?php endforeach;?>
    </ul>
    <?php cache_save(); ?>
  <?php endif;?>
<?php end_slot()?>

<?php slot("sidebar_sponsoring")?>
  <?php include_component("story","sponsorings", array("story"=> $story)); ?>
<?php end_slot();?>