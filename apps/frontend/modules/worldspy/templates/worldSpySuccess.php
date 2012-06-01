<?php if(false === $sf_request->isAjaxRequest() ):?>
  <ul class="filters">
	  <li class="selected"><?php
	    echo link_to(
	      "Live-Ansicht",
	      "@worldspy",
	      array('title' => 'Nachrichten, die gerade vom Wordspy gescannt werden.')
	    );
	  ?></li>
	  <li><?php
	  echo link_to(
	    "Social Media Newsroom",
	    "@worldspy_top",
	    array('title' => "Aktuelle Vorschläge für die Neuesten Nachrichten.")
	  );
	  ?></li>
  </ul>

  <?php echo link_to("laden","@worldspy",array('id'=>'toggleAnimation')); ?>
  <form action="<?php echo url_for("@worldspy"); ?>" method="post" id="pageFilter" class="spy-filter ninjaForm ninjaUpdater NodeList ninjaCallbackSpy">
    <fieldset>
      <?php echo $form->render(); ?>
      <input type="submit" value="Filtern" class="button" id="SaveChanges" />
    </fieldset>
  </form>
 <?php endif; ?>
<!-- Spy Node List -->
<?php if (count($nodeList) > 0): ?>
  <?php if( !$sf_request->isAjaxRequest() ):?><ol id="NodeList" class="ninjaUpdater NodeList ninjaCallbackSpy"><?php endif; ?><?php $i=0; foreach( $nodeList as $node ): ?><li class="spyNode<?php echo ($i++ %2 ? " odd" : ""); ?>"><!-- node parital --><?php include_partial('worldSpyNode',  array( 'node' => $node, 'hidden' => false ) );  ?></li><?php endforeach; ?><?php if( !$sf_request->isAjaxRequest() ):?></ol>
  <?php endif; ?>
<?php else: ?>
  <p class="error clr bth">Die Suche ergab keine Ergebnisse!</p>
<?php endif; ?>

<?php slot("sidebar_sponsoring")?>
   <?php echo include_component("sponsoring","sponsoring", array( 'place_id' => 23 ) ); ?>
<?php end_slot()?>