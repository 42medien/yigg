<nav id="access" role="navigation">
  <a tabindex="1" href="#content" class="skip-link screen-reader-text">Direkt zum Inhalt </a>
  <?php
    echo link_to(img_tag('yigg_logo.png', array(
              'alt' => 'YiGG Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
              'width' => 90,
              'height' => 53
          )), '@best_stories', array(
      'title' => 'YiGG Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
      'rel' => 'home',
      'class' => 'logo'
    ));
  ?>

  <?php include_partial("system/main_navigation"); ?>                         
</nav>