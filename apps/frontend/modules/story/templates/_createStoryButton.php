<?php
$type = ' full';

if (isset($flat) && $flat == true) {
  $type = ' small';
} else {
  $flat = false;
}
?>

<div class="rating-form <?php echo $type; ?>">   
  <div class="counter">0</div>
  <div class="button-small" title=""><?php echo link_to("YiGG", "@story_create", array("target" => "_blank", "query_string" => "exturl={$_GET['url']}", "absolute" => true)); ?></div>
</div>