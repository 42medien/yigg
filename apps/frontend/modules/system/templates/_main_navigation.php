<div id="main-navigation">
  <ol>
    <li <?php if($sf_request->getAction() == 'bestStories'): ?>class="selected"<?php endif;?>>
      <?php echo link_to('Beste Nachrichten','@best_stories',array('title' => 'Beste Nachrichten von heute'));?>
    </li>
    <li <?php if($sf_request->getAction() == 'newStories'): ?>class="selected"<?php endif;?>>
      <?php echo link_to('Neueste Nachrichten',"@new_stories","title=Neueste Nachrichten von heute"); ?>
    </li>
    <li <?php if($sf_request->getAction() == 'spy'): ?>class="selected"<?php endif;?>>
      <?php echo link_to('Spion',"@spy","title=Neueste Nachrichten von heute"); ?>
    </li>
    <li <?php if($sf_request->getAction() == 'worldSpy'): ?>class="selected"<?php endif;?>>
      <?php echo link_to('Weltspion',"@worldspy","title=Neueste Nachrichten von heute"); ?>
    </li>
    <li <?php if($sf_request->getAction() == 'archive'): ?>class="selected"<?php endif;?>>
      <?php echo link_to('Archiv',"@story_archive","title=Neueste Nachrichten von heute"); ?>
    </li>
    <li class="create"><?php echo link_to('Nachricht erstellen', '@story_create', array('title' => 'Neue Nachricht erstellen', 'class' => 'button')); ?></li>
  </ol>
</div>