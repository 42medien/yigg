<?php if(count($entries) > 0):?>
<div class="story-list-cont">
  <ol class="story-list hfeed">
    <?php foreach($entries as $entry):?>
      <?php include_partial("worldspy/expandedNode", array("entry" => $entry)); ?>
    <?php endforeach; ?>
  </ol>
</div>
<?php endif;?>