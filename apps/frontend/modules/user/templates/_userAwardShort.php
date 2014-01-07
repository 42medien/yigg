<?php $stats = $user->UserStats;
  if( $stats instanceof UserStats ): ?>
    <?php if($user->isAdmin()): ?>
      <?php echo img_tag('moderator.png' , array('width' => '12', 'height'=>'12','alt' => 'Moderator', 'title' => 'Moderator') );?>
    <?php else:?>
      <?php
      // Award for comments
      $comment_award = (int) $user->UserStats->comment_award;
      $read_award = (int) $user->UserStats->reading_award;
        echo ( $comment_award > 0)? img_tag('comment_'.$comment_award.'.png' , array('width' => '12', 'height'=>'12','alt' => 'Kommentarauszeichnung Stufe '. $comment_award, 'title' => 'Kommentarauszeichnung Stufe '. $comment_award) ): '';
       echo ( $read_award > 0)? img_tag('lesehamster_'.$read_award.'.png' , array('width' => '12', 'height'=>'12','alt' => 'Lesehamster Stufe '. $read_award, 'title' => 'Lesehamster Stufe '. $read_award) ): '';
      ?>
  <?php endif; ?>
<?php endif; ?>