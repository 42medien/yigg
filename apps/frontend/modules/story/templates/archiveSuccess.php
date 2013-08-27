<nav class="filter-list">Sortieren nach:
  <?php echo link_to("Datum", "spy/spy", array("query_string" => "show=all")); ?>,
  <?php echo link_to("letzte 24 Stunden", "spy/spy", array("query_string" => "show=new")); ?>,
  <?php echo link_to("zuletzt ge-yigged", "spy/spy", array("query_string" => "show=top-rated")); ?>,
  <?php echo link_to("zuletzt kommentiert", "spy/spy", array("query_string" => "show=latest-comments")); ?>,
  <?php echo link_to("zuletzt angeschaut", "spy/spy", array("query_string" => "show=last-read")); ?>
</nav>

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