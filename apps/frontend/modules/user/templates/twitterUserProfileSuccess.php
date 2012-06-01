<h1 class="username"><?php echo $username; ?></h1>

<?php if(count($stories) > 0): ?>
  <ol id="story-list" class="story-list hfeed ">
    <?php foreach($stories as $k => $story ): ?>
      <?php
        include_partial('story/story',
          array(
            'story' => $story,
            'summary' => !($k === 0 && $story->type === 0),
            'count' => $k,
            'total' => count($stories),
            'inlist' => true
          )
        );
      ?>
    <?php endforeach; ?>
  </ol>
  <?php echo $pager->display(); ?>
<?php else: ?>
  <p class="note">Es wurden keine Nachrichten gefunden</p>
<?php endif; ?>

<?php slot("sidebar")?>
  <div class="halfHalf">
    <div class="right">
      <h2 class="heading-right">Quicklinks</h2>
      <ul class="list-style">
        <li class="pm"><?php echo link_to("Nachricht schreiben", $tweets->getFirst()->getReplyLink(), array("class" => "ico email", "rel" => "nofollow", "rel" => "external"))?></li>
        <li class="follow"><?php echo link_to("Follow $username", $tweets->getFirst()->getUserTwitterLink(), array("class" => "ico follow", "rel" => "nofollow", "rel" => "external"))?></li>
      </ul>
    </div>
    <div class="left">
      <?php echo img_tag($tweets->getFirst()->profile_image_url, array("height" => 150, "width" => 150));?>
    </div>
    <div class="clr"></div>
  </div>
<?php end_slot()?>

  