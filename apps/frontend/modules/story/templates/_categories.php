<?php if(count($categories) > 0):?>
<section id="widget-categories" class="widget">
  <h2>Kategorien</h2>

  <?php
  foreach($categories as $category) {
    echo link_to($category->name, "@category_stories?id=".$category->id."&category_slug=".$category->name);
  }
  ?>
</section>
<?php endif;?>