<?php
if (!isset($type)) {
  $type = 'full';
}

$hasRated = ( null === $story->hasRated() ? $sf_user->hasRated($story['id']) : $story->hasRated() );
if (false === $hasRated ) {
?>
<form id="StoryRate<?php echo $story->id ?>" 
      class="ninjaForm ninjaAjaxSubmit" 
      action="<?php echo url_for_story($story, "rate"); ?>" 
      method="post">
  <div class="rating-form unvoted <?php echo $type; ?>">
    <div class="counter"><?php echo $story->currentRating(); ?></div>
    <input type="hidden" 
           name="StoryRate[_csrf_token]" 
           value="<?php echo $form[$form->getCSRFFieldName()]->getValue() ?>" 
           id="StoryRate<?php echo $story->id; ?>__csrf_token" />
    <input name="StoryRate[ratingtoken]" 
           id="StoryRate_ratingtoken<?php echo $story->id; ?>" 
           type="hidden" 
           value="<?php echo md5(rand(1,10000));?>" />
    <input type="submit" 
           value="YiGG it!" 
           class="button button-small"
           id="Rate<?php echo $story->id?>" />
  </div>
</form>
<?php
  
} else {
  
?>
<div class="rating-form voted <?php echo $type; ?>">   
  <div class="counter"><?php echo $story->currentRating() ?></div>
  <div class="button-small" title="Du hast schon geYiGGed">YiGGed</div>
</div>
<?php } ?>