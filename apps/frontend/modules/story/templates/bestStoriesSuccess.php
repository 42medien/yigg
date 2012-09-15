<?php $filter_options = Doctrine_Core::getTable('StoryFilterOption')->getStoryFilterOptions(); if(count($filter_options)):?>
    <?php foreach($filter_options as $filter_option):?>               
        <?php 
        if(sfContext::getInstance()->getRequest()->getParameter('story_filter_option_slug'))
        {
            $get_parameter = sfContext::getInstance()->getRequest()->getParameter('story_filter_option_slug');
            $get_parameter = str_replace("-"," ",$get_parameter);            
        }
        else
            $get_parameter = '12 Std';
        if(trim(strtolower($filter_option->getName())) == trim(strtolower($get_parameter)))
        {
            $highlight = 'bold';            
            $font_size = '14px !important';
        }
        else
        {
            $highlight = 'normal';            
            $font_size = '12px !important';
        }
        ?>
        <span style="font-weight:<?php echo $highlight; ?>; 
                     font-size:<?php echo $font_size; ?>;">
        <?php echo link_to($filter_option->getName(), 'filter_stories', $filter_option);?>
        </span>&nbsp;&nbsp;&nbsp;
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