<?php if(false === $sf_request->isAjaxRequest() ):?>
  <h1>YiGGspion: Live-Aktivität auf YiGG</h1>
  <p class="iefix"><?php echo link_to("laden","@worldspy",array('id'=>'toggleAnimation')); ?></p>
  <form action="<?php echo url_for($filterForm->getLink());?>" method="post" id="pageFilter" class="spy-filter ninjaForm ninjaUpdater NodeList ninjaCallbackSpy">
    <fieldset>
      <?php echo $filterForm->render(); ?>
      <input type="submit" value="Filtern" class="button" id="SaveChanges" />
    </fieldset>
  </form>
<?php endif; ?>

<?php if ($nodeList): ?><?php if( !$sf_request->isAjaxRequest() ):?><ol id="NodeList"><?php endif; ?><?php $i=0; foreach( $nodeList as $node ): ?><li class="spyNode<?php echo ($i++ %2 ? " odd" : ""); ?>"><!-- node parital --><?php include_partial('spy/node',  array( 'node' => $node, 'hidden' => false ) );  ?></li><?php endforeach; ?><?php if( !$sf_request->isAjaxRequest() ):?></ol><?php endif; ?>
<?php else: ?>
  <ol id="NodeList">
    <li> <!-- not semantic, but we have to for the javascript -->
    <!-- No results -->
    <div class="help">
      <div class="help-text">
        <h4>Keine Nachrichten gefunden</h4>
        <p>Sorry, wir haben leider keine Nachrichten gefunden</p>
      </div>
    </div>
    </li>
  </ol>
<?php endif; ?>

<?php slot("sidebar_sponsoring")?>
  <?php echo include_component("sponsoring","sponsoring", array( 'place_id' => 24 ) ); ?>
<?php end_slot();?>