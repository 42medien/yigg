<ol id="stories" class="story-list hfeed ">
  <?php foreach($stories as $k => $story ): ?>
  <li>
    <?php
      include_partial('story/story',
        array(
          'story' => $story,
          'summary' => true,
          'count' => $k,
          'inlist' => true
        )
      );
    ?>
  </li>
  <?php endforeach; ?>
</ol>