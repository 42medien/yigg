<h3 class="heading-left">TwiGGs zu dieser Nachricht</h3>
      <ol class="comments-list expanded">
      <?php $tweets = Doctrine_Query::create()
                      ->from("Tweet t")
                      ->leftJoin("t.Story s")
                      ->where("s.id = ?", $story->id)
                      ->orderBy("t.created_at ASC")
                      ->limit(10)
                      ->execute();
             $tweet_ids = array();?>
      
        <?php foreach($tweets as $tweet):?>
          <li>
            <ul class="comment-actions ico">
              <li><?php echo link_to("Reply", $tweet->getReplyLink(), array( "class"=>"reply", "title" => "Auf Twitter antworten", "target" => "_blank"));?></li>
              <li><?php echo link_to("Retweet", $tweet->getRewtweetLink(), array("class"=>"tweet", "title" => "Retweeten", "target" => "_blank"));?></li>
            </ul>

            <?php  echo content_tag(
             "p",
              content_tag(
                "span",
                  link_to(
                    img_tag(
                      $tweet->profile_image_url,
                      array(
                        "alt"=> "Profil bild von {$tweet->username} auf Twitter besuchen",
                        "height" => "48",
                        "width" => "48",
                      )
                    ),
                    $tweet->getYiggTwitterProfileLink(),
                    array(
                     "title" => "Profil  von {$tweet->username} auf Twitter besuchen",
                     "class" => "comment-avatar"
                    )
                  ) . " von " .
                  link_to(
                    $tweet->username,
                    $tweet->getYiggTwitterProfileLink(),
                     array(
                       'class' => 'fn url',
                       'title' => "Profil  von {$tweet->username} auf Twitter besuchen"
                     )
                  ) . " - " .
                  content_tag(
                    "abbr",
                    format_date(strtotime($tweet->created_at),"g","de",'utf-8'),
                    array(
                      "title" => date(DATE_ATOM, strtotime($tweet->created_at)),
                      "class" => "updated published"
                    )
                  ),
                  array(
                    "class"=>"vcard author"
                 )
              ) . " ". $tweet->text
             );
             $tweet_ids[] = $tweet->id;
            ?>
           </li>
         <?php endforeach;?>
       </ol>

<?php if(count($tweet_ids) == 10):?>
  <?php $tweets = Doctrine_Query::create()
                   ->from("Tweet t")
                   ->leftJoin("t.Story s")
                   ->where("s.id = ?", $story->id)
                   ->whereNotIn('t.id', $tweet_ids)
                   ->orderBy("t.created_at ASC")
                   ->limit(20)
                   ->execute();?>
  <?php if(count($tweets) > 0):?>
    <h3 class="heading-left">Mehr Twiggs</h3>
    <?php foreach($tweets as $tweet):?>
        <?php echo link_to(
              img_tag($tweet->profile_image_url, array("height" => 30, "width" => 30)),
              $tweet->getYiggTwitterProfileLink(),
              array("title" => $tweet->text));?>
    <?php endforeach;?>
  <?php endif;?>
<?php endif;?>

<div class="clr"></div>