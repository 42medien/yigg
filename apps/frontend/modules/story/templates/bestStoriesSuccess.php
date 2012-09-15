<?php if($storyCount > 0): ?>

<!--
3 hours, 12 hours, 24 hours, 2 days, 7 days
-->
<table>
    <tr>
        <td>
            <a href="">7 days</a>
        </td>
        <td>
            <a href="">2 days</a>
        </td>
        <td>
            <a href="">24 hours</a>
        </td>
        <td>
            <a href="">12 hours</a>
        </td>
        <td>
            <a href="">3 hours</a>
        </td>
    </tr>
</table>
<br>
<div class="story-list-cont">
  <ol id="story-list" class="story-list hfeed ">
    <?php foreach($stories as $k => $story ): ?>
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
    <?php endforeach; ?>
  </ol>
</div>
<?php echo $pager->display(); ?>
<?php else: ?>
  <p class="note">Es wurden keine Nachrichten gefunden</p>
<?php endif; ?>

<?php slot('sidebar') ?>
  <?php include_component("comment","latestComments");?>
  <?php include_component("story","latestStoriesWidget"); ?>
<?php end_slot();?>

<?php slot("sidebar_sponsoring")?>
  <?php include_component("story","sponsorings"); ?>
<?php end_slot();?>