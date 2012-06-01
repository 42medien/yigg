<?php if(count($entries) > 0):?>
  <ol class="story-list hfeed">
    <?php foreach($entries as $entry):?>
      <?php include_partial("worldspy/expandedNode", array("entry" => $entry)); ?>
    <?php endforeach; ?>
  </ol>
<?php endif;?>