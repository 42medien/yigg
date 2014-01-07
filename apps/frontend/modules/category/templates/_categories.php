<?php if(count($categories) > 0):?>
<section id="widget-categories" class="widget">
  <h2>Kategorien</h2>

  <ul>
  <?php
  foreach($categories as $category) {
    echo "<li>" . link_to($category->name, "@category_stories?id=".$category->id."&category_slug=".$category->getCategorySlug()) . "</li>";
  }
  ?>
  </ul>
</section>
<?php endif;?>