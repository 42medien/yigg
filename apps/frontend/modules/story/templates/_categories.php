<?php if(count($categories) > 0):?>
<section id="widget-categories" class="widget">
  <h2>Eingeordnet in folgende Kategorien</h3>

  <?php
  foreach($categories as $category) {
    echo link_to($category->name, "@category_stories?id=".$category->id."&category_slug=".$category->name);
  }
  ?>
</section>
<?php endif;?>