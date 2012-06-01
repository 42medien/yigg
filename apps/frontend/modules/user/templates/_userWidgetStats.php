<?php if($user->UserStats->yipps != NULL): ?>
<?php $stats = $user->UserStats;?>
<dl class="user-about">
    <dd class="rang"><?php printf('%d/10', $user->UserStats->rank) ?></dd>
    <dt class="rang">Rang</dt>
    <dd class="stories"><?php echo $user->UserStats->storys_total ?></dd>
    <dt class="stories">Nachrichten</dt>
    <dd class="comments"><?php echo $user->UserStats->comments_total ?></dd>
    <dt class="comments">Kommentare</dt>
    <dd class="awards">
      <?php if($user->isAdmin()): ?>
        <?php echo img_tag('moderator.png' , array('width' => '12', 'height'=>'12','alt' => 'Moderator', 'title' => 'Moderator') );?>
      <?php endif;?>
      <?php $comment_award = (int) $user->UserStats->comment_award;
           echo ( $comment_award > 0)? img_tag('comment_'.$comment_award.'.png' , array('width' => '12', 'height'=>'12','alt' => 'Kommentarauszeichnung Stufe '. $comment_award, 'title' => 'Kommentarauszeichnung Stufe '. $comment_award) ): '';
           $read_award = (int) $user->UserStats->reading_award;
           echo ( $read_award > 0)? img_tag('lesehamster_'.$read_award.'.png' , array('width' => '12', 'height'=>'12','alt' => 'Lesehamster Stufe '. $read_award, 'title' => 'Lesehamster Stufe '. $read_award) ): '';?>
    </dd>
    <dt class="awards">Awards</dt>
</dl>
<?php endif;?>