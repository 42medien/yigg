<?php slot("page-title")?>Tag-Cloud<?php end_slot()?>

<p>Die populärsten Tags:</p>

<ul class="tag-cloud">
<?php foreach ($tags as $tag) { ?>
  <li class="tag"><span style="font-size: <?php echo 10 + floor($tag['tag_count'] / (($max - $min) / (30 - 10))); ?>pt;"><?php echo link_to($tag['name'], "@tag?tags=".$tag['name']); ?></span></li>
<?php } ?>
</ul>

<p>Viel Spaß beim stöbern!</p>