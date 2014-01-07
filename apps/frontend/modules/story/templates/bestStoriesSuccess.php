<?php $filter_options = Doctrine_Core::getTable('StoryFilterOption')->getStoryFilterOptions(); if(count($filter_options)):?>
    <div class="news-filter">
        <?php foreach($filter_options as $filter_option):?>
            <?php
                if (trim($filter_option->getValue()) == trim($value)) {
                  $class_name = 'active';
                } else {
                  $class_name = 'normal';
                }
            ?>

            <?php echo link_to($filter_option->getName(), 'filter_stories', $filter_option, array("class" => "button $class_name")); ?>
        <?php endforeach;?>
    </div>
<?php endif;?>
<?php if ($storyCount > 0): ?>
  <?php
    $ad_place = mt_rand(1, 4);
    $lap = 0;
  ?>
<ol id="stories" class="story-list hfeed ">

<?php foreach($stories as $k => $story ): ?>
  <?php if ($lap == $ad_place) { ?>
  <li>
    <?php include_partial('sponsoring/story'); ?>
  </li>
  <?php } ?>

  <li>
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

    $lap++;
    ?>
  </li>
  <?php endforeach; ?>
</ol>
<?php echo $pager->display(); ?>
<?php else: ?>
  <p class="alert alert-info note">Es wurden keine Nachrichten gefunden</p>
<?php endif; ?>

<?php slot('sidebar') ?>
  <?php include_component("comment","latestComments");?>
  <?php include_component("story","latestStoriesWidget"); ?>
<?php end_slot();?>