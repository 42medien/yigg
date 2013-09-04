<section id="widget-search">
  <?php include_component("search", "form"); ?>
</section>

<?php
  if (true === $sf_user->hasUser()) {
    include_partial("user/userinfo");
  }

  if (has_slot("sidebar")) {
    include_slot("sidebar");
  }
?>