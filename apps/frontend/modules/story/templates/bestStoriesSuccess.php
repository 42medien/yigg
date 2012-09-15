<?php if($storyCount > 0): ?>
<!--
3 hours, 12 hours, 24 hours, 2 days, 7 days
-->
<?php
$filter_options = array(
    "604800" => "7 Days",
    "172800" => "2 Days",
    "86400" => "24 Hours",
    "43200" => "12 Hours",
    "10800" => "3 Hours"
);
?>

<?php //echo link_to($category->getName(), 'category_stories', $category); ?>

<?php
    foreach ($filter_options as $key => $value) 
    {                
        echo link_to($value, 'best_stories', $filter_options).'&nbsp;&nbsp;&nbsp;';
        // $key = value
    }; 
?>
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