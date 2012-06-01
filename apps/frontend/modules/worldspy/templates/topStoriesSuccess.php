<ul class="filters">
  <li><?php
    echo link_to(
      "Live-Ansicht",
      "@worldspy",
      array('title' => 'Nachrichten, die gerade vom Wordspy gescannt werden.')
    );
  ?></li>
  <li class="selected"><?php
  echo link_to(
    "Social Media Newsroom",
    "@worldspy_top",
    array('title' => "Aktuelle Vorschläge für die Neuesten Nachrichten.")
  );
  ?></li>
  </ul>

<?php if(count($entries) > 0):?>
  <ol class="story-list hfeed">
    <?php foreach($entries as $entry):?>
      <?php include_partial("expandedNode", array("entry" => $entry)); ?>
    <?php endforeach; ?>
  </ol>
  <?php echo $pager->display(); ?>
<?php else:?>
  <p class="error">Es wurden keine Top-Nachrichten gefunden!</p>
<?php endif;?>

<?php slot("sidebar_sponsoring")?>
   <?php echo include_component("sponsoring","sponsoring", array( 'place_id' => 23 ) ); ?>
<?php end_slot()?>