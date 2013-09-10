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
    <?php /* ?>
    <li <?php if($sf_request->getAction() == 'worldSpy'): ?>class="selected"<?php endif;?>>
      <?php echo link_to('Weltspion',"@worldspy","title=Neueste Nachrichten von heute"); ?>
    </li>
    <?php */ ?>
    <li <?php if($sf_request->getAction() == 'archive'): ?>class="selected"<?php endif;?>>
      <?php echo link_to('Archiv',"@story_archive","title=Neueste Nachrichten von heute"); ?>
    </li>
  </ol>
</div>

<div id="main-actions">
  
  <?php
  if ($sf_user->hasUser()) {
    echo link_to('Nachricht erstellen', '@story_create', array('title' => 'Neue Nachricht erstellen', 'class' => 'button'))."\n";
    echo link_to('Artikel erstellen', '@story_create_article', array('title' => 'Neuen Artikel schreiben', 'class' => 'button'));
  } else {
    echo link_to('<i class="icon-signin"></i> Einloggen', '@user_login', array('class' => 'button'))."\n";
    echo link_to('<i class="icon-pencil"></i> Registrieren', '@user_register', array('class' => 'button'));
  }
  ?>
</div>