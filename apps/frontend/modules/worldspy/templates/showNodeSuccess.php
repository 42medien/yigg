<?php if(false === $sf_request->isAjaxRequest() ):?>
  <ul class="filters">
    <li><?php
      echo link_to(
        "Live-Ansicht",
        "@worldspy",
        array('title' => 'Nachrichten, die gerade vom Wordspy gescannt werden.')
      );
    ?></li>
    <li><?php
    echo link_to(
      "Top-Stories",
      "@worldspy_top",
      array('title' => "Aktuelle Vorschläge für die Neuesten Nachrichten.")
    );
    ?></li>
  </ul>
<?php endif; ?>
<div class="story-list-cont">
<ol class="story-list hfeed">
<?php include_partial("expandedNode", array("entry" => $node))?>
</ol>
</div>
<?php slot("sidebar_sponsoring")?>
   <?php echo include_component("sponsoring","sponsoring", array( 'place_id' => 23 ) ); ?>
<?php end_slot()?>