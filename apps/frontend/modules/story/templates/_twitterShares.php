<section id="widget-tweets" class="widget">
  <h2 class="heading-right">Passende Tweets</h2>
  <ul class="avatar-list">                 
    <?php foreach($tweets->getRawValue() as $tweet_res) { ?>
    <li>
      <?php 
        $twitter_username = htmlSpecialChars($tweet_res->user->screen_name);
        $twitter_status = htmlSpecialChars($tweet_res->id);
        $tweer_url = 'https://twitter.com/' . $twitter_username . '/status/' . $twitter_status;
            
        echo link_to(
               img_tag(
                 htmlSpecialChars($tweet_res->user->profile_image_url),
                 array("width" => 48, "height" => 48, "class" => "avatar", "alt"=> "Profil von {$twitter_username} besuchen")
               ),
               $tweer_url,
               array(
                 "title" => "Tweet von {$twitter_username} besuchen",
                 "rel" => "nofollow",
                 "target" => "_blank"
               )
             );
      ?>
    </li>
    <?php } ?>
  </ul>
</section>