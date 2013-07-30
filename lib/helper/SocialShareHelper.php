<?php

function social_counter($url, $options = array()) {
  $smc = new yiggSocialNetworkCounter($url);
  
  $facebook_count = $smc->get_facebook();
  $twitter_count = $smc->get_twitter();
  $google_count = $smc->get_google();
  $yigg_count = $smc->get_yigg();
  $spreadly_count = $smc->get_spreadly();
?>
<ul class="social-share-counter">
  <li class="spreadly count">shares <?php echo $spreadly_count; ?></li>
  <li class="twitter count">tweets <?php echo $twitter_count; ?></li>
  <li class="facebook count">likes <?php echo $facebook_count; ?></li>
  <li class="google count">+1 <?php echo $google_count; ?></li>
  <li class="yigg count">yiggs <?php echo $yigg_count; ?></li>
</ul>
<?php
}