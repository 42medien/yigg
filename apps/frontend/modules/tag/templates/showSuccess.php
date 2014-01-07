<h1 class="page-title">Tag: <?php echo $tag->getRawValue(); ?>
<?php if( true === $sf_user->hasUser()): ?>
   <?php if( false === $sf_user->getUser()->followsTag($tag->getRawValue()) ): ?>
     <?php echo button_to("Abonnieren", "@tag_subscribe?tag_id={$tag->id}", array("class" => "follow"));?>
   <?php else:?>
     <?php echo button_to("Abonniert!", "@tag_subscribe?tag_id={$tag->id}", array("class" => "unfollow"));?>
   <?php endif;?>
<?php endif;?>
</h1>

<ol id="stories" class="story-list hfeed">
  <?php foreach($stories as $k => $story ): ?>
  <li>
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
  </li>
  <?php endforeach; ?>
</ol>
<?php echo $pager->display();?>