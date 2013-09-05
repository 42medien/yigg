<?php

function social_counter($url, $options = array()) {
  $smc = new yiggSocialNetworkCounter($url);
  
  $facebook_count = $smc->get_facebook();
  $twitter_count = $smc->get_twitter();
  $google_count = $smc->get_google();
  $yigg_count = $smc->get_yigg();
  $spreadly_count = $smc->get_spreadly();
?>
<ul class="social-share-counter" title="Share diesen Link">
  <li class="yigg"><span class="counter"><?php echo $yigg_count; ?></span> yiggs</li>
  <li class="spreadly"><span class="counter"><?php echo $spreadly_count; ?></span> shares</li>
  <li class="twitter"><span class="counter"><?php echo $twitter_count; ?></span> tweets</li>
  <li class="facebook"><span class="counter"><?php echo $facebook_count; ?></span> likes</li>
  <li class="google"><span class="counter"><?php echo $google_count; ?></span> +1s</li>
</ul>
<?php
}