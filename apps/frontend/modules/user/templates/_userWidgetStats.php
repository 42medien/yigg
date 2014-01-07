<?php if($user->UserStats->yipps != NULL): ?>
<?php $stats = $user->UserStats;?>
<section class="widget" id="widget-user-stats">
  <ul class="user-about">
    <li class="rang label">Rang: <?php printf('%d/10', $user->UserStats->rank) ?></li>
    <li class="stories label">Nachrichten: <?php echo $user->UserStats->storys_total ?></li>
    <li class="comments label">Kommentare: <?php echo $user->UserStats->comments_total ?></li>
    <li class="awards label">
      Awards:
      <?php if($user->isAdmin()): ?>
        <?php echo img_tag('moderator.png' , array('width' => '12', 'height'=>'12','alt' => 'Moderator', 'title' => 'Moderator') );?>
      <?php endif;?>
      <?php $comment_award = (int) $user->UserStats->comment_award;
           echo ( $comment_award > 0)? img_tag('comment_'.$comment_award.'.png' , array('width' => '12', 'height'=>'12','alt' => 'Kommentarauszeichnung Stufe '. $comment_award, 'title' => 'Kommentarauszeichnung Stufe '. $comment_award) ): '';
           $read_award = (int) $user->UserStats->reading_award;
           echo ( $read_award > 0)? img_tag('lesehamster_'.$read_award.'.png' , array('width' => '12', 'height'=>'12','alt' => 'Lesehamster Stufe '. $read_award, 'title' => 'Lesehamster Stufe '. $read_award) ): '';?>
    </li>
  </ul>
</section>
<?php endif;?>
