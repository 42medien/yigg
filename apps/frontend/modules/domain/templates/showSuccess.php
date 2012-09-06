<?php if(count($stories) > 0): ?>
  <ol id="story-list" class="story-list hfeed ">
    <?php foreach($stories as $k => $story ): ?>
      <?php
        include_partial('story/story',
          array(
            'story' => $story,
            'summary' => true,
            'compacted' => true,
            'count' => $k,
            'total' => count($stories),
            'inlist' => true
          )
        );
      ?>
    <?php endforeach; ?>
  </ol>
<?php else: ?>
  <p class="error">Es wurden keine Nachrichten gefunden</p>
<?php endif; ?>
<?php echo $pager->display(); ?>
