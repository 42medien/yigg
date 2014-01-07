<div id="main-navigation">
  <ol>
    <li <?php if($sf_request->getAction() == 'bestStories'): ?>class="selected"<?php endif;?>>
      <?php echo link_to('Beste Nachrichten','@best_stories',array('title' => 'Beste Nachrichten von heute'));?>
    </li>
    <li <?php if($sf_request->getAction() == 'newStories'): ?>class="selected"<?php endif;?>>
      <?php echo link_to('Neueste Nachrichten',"@new_stories","title=Neueste Nachrichten von heute"); ?>
    </li>
    <li <?php if($sf_request->getAction() == 'spy'): ?>class="selected"<?php endif;?>>
      <?php echo link_to('Spion',"@spy","title=Entdecke Nachrichten die dich interessieren"); ?>
    </li>
    <li class="sub-nav<?php if($sf_request->getAction() == 'archive'): ?> selected<?php endif;?>">
      <?php echo link_to('Archiv',"@story_archive","title=Alle Nachrichten nach Tagen sortiert"); ?>

      <ol>
        <li><?php echo link_to('Archiv',"@story_archive","title=Neueste Nachrichten von heute"); ?></li>
        <li><?php echo link_to('Tags',"@tag_show","title=Neueste Nachrichten von heute"); ?></li>
        <li><?php echo link_to('Kategorien',"@categories","title=Neueste Nachrichten von heute"); ?></li>
      </ol>
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