<?php
if (true === $sf_user->hasUser()) {
  include_partial("user/userinfo");
}
?>

<?php if(true === $sf_request->hasParameter("rss")): ?>
<section id="widget-feed">
  <h2>Seite abonnieren</h2>
  <ul>
    <li><i class="icon-rss-sign"></i>
<?php
  echo link_to(
    "Atom-Feed",
    $sf_request->forceParams(
      array(
        "sf_format" => "atom"
      )
    ),
    array(
      'title' => 'Diese Seite als Atom-Feed abonnieren',
      'class' => 'rss atom feed',
      'rel' => 'feed'
    )
  );
?>
    </li>
    <li>
      <input type="button" onclick="(function(){var z=document.createElement('script');z.src='https://www.subtome.com/load.js';document.body.appendChild(z);})()" value="Subscribe" />
    </li>
  </ul>
</section>
<?php endif; ?>

<?php // include_component("search", "form"); ?>

<?php
if (has_slot("sidebar")) {
  include_slot("sidebar");
}
?>