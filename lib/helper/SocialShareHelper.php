<?php

function social_counter($url, $options = array()) {
  $smc = new yiggSocialNetworkCounter($url);

  $facebook_count = $smc->get_facebook();
  $twitter_count = $smc->get_twitter();
  $yigg_count = $smc->get_yigg();
?>
<ul class="social-share-counter">
  <li class="yigg"><span class="counter"><?php echo $yigg_count; ?></span> yiggs</li>
  <li class="twitter"><span class="counter"><?php echo $twitter_count; ?></span> tweets</li>
  <li class="facebook"><span class="counter"><?php echo $facebook_count; ?></span> likes</li>
</ul>
<?php
}

function social_counter_simple($url, $options = array()) {
  $smc = new yiggSocialNetworkCounter($url);

  $counter = $smc->get_facebook() + $smc->get_twitter() + $smc->get_yigg();
?>
<div class="social-share-counter">Insgesamt <span class="counter"><?php echo $counter; ?></span> mal geteilt</div>
<?php
}