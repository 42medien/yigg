<?php if($storyCount > 0): ?>
<div class="story-list-cont">
  <ol id="story-list" class="story-list hfeed ">
    <?php foreach($stories as $k => $story ): ?>
      <?php
        include_partial('story',
          array(
            'story' => $story,
            'summary' => true,
            'compacted' => false === (false === ($k != 0 && $k % round(($storyCount/4)) == 0)),
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
  <p class="error">Es wurden keine Nachrichten gefunden</p>
<?php endif; ?>

<?php slot('sidebar') ?>
  <?php include_component("comment","latestComments");?>
<?php end_slot();?>