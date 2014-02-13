<?php
if($storyCount > 0):
  $ad_place = mt_rand(1, 4);
  $lap = 0;
?>
<ol id="stories" class="story-list hfeed ">
<?php
  foreach($stories as $k => $story ): ?>
    if ($lap == $ad_place) {
?>
  <li>
    <?php include_partial('sponsoring/story'); ?>
  </li>
<?php } ?>
  <li>
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

  $lap++;
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
<?php end_slot();?>