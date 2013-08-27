

<?php if(isset($stories)&& count($stories) > 0): ?>
<div class="story-list-cont">
  <ol id="stories" class="story-list hfeed">
    <?php foreach($stories as $k => $story ): ?>
      <li>
      <?php
        include_partial('story/story',
          array(
            'story' => $story,
            'summary' => true,
            'count' => $k,
            'total' => count($stories),
            'compacted' => true,
            'inlist' => true
          )
        );
      ?>
    </li>
    <?php endforeach; ?>
  </ol>
</div>
<?php endif;?>