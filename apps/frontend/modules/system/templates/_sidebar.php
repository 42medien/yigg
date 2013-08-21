<?php
  if (true === $sf_user->hasUser()) {
    include_partial("user/userinfo");
  }

  if (has_slot("sidebar")) {
    include_slot("sidebar");
  }
  
  include_partial("user/anzeigeBottom");
  include_component("story", "bestVideosBottom", array( "height"=> 285, "width" => 370));
?>