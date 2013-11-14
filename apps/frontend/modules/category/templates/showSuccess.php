<h1 class="page-title">Kategorie: <?php echo $category->getRawValue(); ?></h1>

<?php if($storyCount > 0): ?>
<div class="story-list-cont">
  <ol id="stories" class="story-list hfeed ">
    <?php foreach($stories as $k => $story ): ?>
    <li>
      <?php
        include_partial('story/story',
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
    </li>
    <?php endforeach; ?>
  </ol>
</div>
  <?php echo $pager->display(); ?>
<?php else: ?>
  <p class="alert alert-danger error">Es wurden keine Nachrichten gefunden</p>
<?php endif; ?>

<?php slot('sidebar') ?>
  <?php include_component("comment","latestComments");?>
<?php end_slot();?>