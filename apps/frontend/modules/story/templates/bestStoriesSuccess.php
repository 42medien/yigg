<?php $filter_options = Doctrine_Core::getTable('StoryFilterOption')->getStoryFilterOptions(); if(count($filter_options)):?>
    <?php foreach($filter_options as $filter_option):?>
        
        <?php
            //if($this->getRequest()->getParameter("value"))
                //echo $this->getRequest()->getParameter("value");
        ?>

        <span style="font-weight: bold;">
        <?php echo link_to($filter_option->getName(), 'filter_stories', $filter_option);?>
        </span>
    <?php endforeach;?>
<?php endif;?>
<?php if($storyCount > 0): ?>
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