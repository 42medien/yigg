<?php if($storyCount > 0): ?>
<!--
3 hours, 12 hours, 24 hours, 2 days, 7 days
-->

<?php $filter_options = Doctrine_Core::getTable('StoryFilterOption')->getStoryFilterOptions(); if(count($filter_options)):?>
<?php foreach($filter_options as $filter_option):?>
    <?php echo link_to($filter_option->getName(), 'filter_stories', $filter_option);?>
    <?php endforeach;?>
<?php endif;?>
          
<br>
<div class="story-list-cont">
  <ol id="story-list" class="story-list hfeed ">
    <?php foreach($stories as $k => $story ): ?>
      <?php
        include_partial('story',
          array(
            'story' => $story,
            'summary' => true,
            'compacted' => true,
            'count' => $k,
            'total' => $storyCount,
            'inlist' => true
          )
        );
      ?>
    <?php endforeach; ?>
  </ol>
</div>
<?php echo $pager->display(); ?>
<?php else: ?>
  <p class="note">Es wurden keine Nachrichten gefunden</p>
<?php endif; ?>

<?php slot('sidebar') ?>
  <?php include_component("comment","latestComments");?>
  <?php include_component("story","latestStoriesWidget"); ?>
<?php end_slot();?>

<?php slot("sidebar_sponsoring")?>
  <?php include_component("story","sponsorings"); ?>
<?php end_slot();?>