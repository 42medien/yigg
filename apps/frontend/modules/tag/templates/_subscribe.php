<?php foreach($tags as $tag): ?>
  <?php $follows = $sf_user->hasUser() && true === $sf_user->getUser()->followsTag($tag->getRawValue());?>
  <?php echo now_later_button(
    $tag->name,
    url_for("@tag_show") . "?tags={$tag->name}",
    "@tag_subscribe?tag_id={$tag->id}",
    array("class" => $follows ? "subscribed" : "")
  );?>
<?php endforeach; ?>