<?php if ($nodeList): ?>
  <?php foreach( $nodeList as $node ): ?><li class="spyNode new" style="display:none;"><!-- node parital --><?php include_partial('spy/node',  array( 'node' => $node , 'hidden' => true) );  ?></li><?php endforeach; ?>
<?php endif; ?>