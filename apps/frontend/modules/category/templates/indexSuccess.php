<?php slot("page-title")?>Kategorien<?php end_slot()?>

<?php foreach ($categories as $cat) { ?>
<div class="category-entry">
  <h2 class="category-title"><?php echo link_to($cat->getName(), "@category_stories?id={$cat->getId()}&category_slug={$cat->getCategorySlug()}") ?> <span class="category-meta">(<?php echo $cat->countStories(); ?> Nachrichten)</span></h2>

  <ul class="category-story-list">
    <?php foreach ($cat->getStories(5) as $story) { ?>
      <li><?php echo link_to_story($story->getTitle(), $story); ?></li>
    <?php } ?>
  </ul>

  <p class="category-meta"><?php echo link_to("mehr zum Thema '{$cat->getName()}'", "@category_stories?id={$cat->getId()}&category_slug={$cat->getCategorySlug()}"); ?></p>
</div>
<?php } ?>