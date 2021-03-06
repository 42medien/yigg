<?php echo include_component("search","form");?>

<?php if(count($stories) > 0): ?>
<div class="story-list-cont">
  <ol id="stoties" class="story-list hfeed ">
    <?php foreach($stories as $k => $story ): ?>
      <li>
      <?php
        include_partial('story/story',
          array(
            'story' => $story,
            'summary' => true,
            'compacted' => false === (false === ($k != 0 && $k % round((count($stories) /4)) == 0)),
            'count' => $k,
            'total' => count($stories) ,
            'inlist' => true
          )
        );
      ?>
    </li>
    <?php endforeach; ?>
  </ol>
</div>
<?php else: ?>
  <p class="alert alert-danger error">Es wurden keine Nachrichten gefunden</p>
<?php endif; ?>
<?php echo $pager->display(); ?>

<?php slot("sidebar")?>
  <?php include_component("comment","latestComments");?>
<?php end_slot()?>