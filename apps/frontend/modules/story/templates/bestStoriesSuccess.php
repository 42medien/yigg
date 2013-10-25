<?php $filter_options = Doctrine_Core::getTable('StoryFilterOption')->getStoryFilterOptions(); if(count($filter_options)):?>
    <div class="news-filter">
        <?php foreach($filter_options as $filter_option):?>
            <?php
                if(sfContext::getInstance()->getRequest()->getParameter('story_filter_option_slug')) {
                  $get_parameter = sfContext::getInstance()->getRequest()->getParameter('story_filter_option_slug');
                  $get_parameter = str_replace("-"," ",$get_parameter);
                } else {
                  $get_parameter = '12 Std'; // By Default Search Criteria
                }

                if(trim(strtolower($filter_option->getName())) == trim(strtolower($get_parameter))) {
                  $class_name = 'active';
                } else {
                  $class_name = 'normal';
                }

                $filter_option['class'] = 'button';
            ?>

            <span class="<?php echo $class_name; ?>">
              <?php echo link_to(strtoupper($filter_option->getName()), 'filter_stories', $filter_option); ?>
            </span>
        <?php endforeach;?>
    </div>
<?php endif;?>
<?php if($storyCount > 0): ?>
<ol id="stories" class="story-list hfeed ">
<?php foreach($stories as $k => $story ): ?>
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