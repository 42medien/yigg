<?php echo'<?xml version="1.0" encoding="utf-8"?>'?>
<?php $comments = $story->Comments;?>
<feed xmlns="http://www.w3.org/2005/Atom">
   <title>Kommentare aus <?php echo $story->title;?></title>
   <link href="<?php echo url_for($story->getLink(ESC_RAW),true);?>" />
   <id>tag:yigg.de,<?php echo date(DATE_ATOM,strtotime($story->created_at)) .":" . $story->internal_url; ?></id>
   <?php if(count($comments)>0): ?>
   <updated><?php echo date(DATE_ATOM, strtotime($comments->getLast()->created_at)); ?></updated>
     <?php foreach ($comments as $k => $comment): ?>
      <entry>
        <id>tag:yigg.de,<?php echo date(DATE_ATOM,strtotime($comment->created_at)) .":" . $story->internal_url . "_kommentar_". $comment->id; ?></id>
        <title>Kommentar von: <?php echo htmlspecialchars($comment->Author->username); ?></title>
        <link href="<?php echo url_for($comment->getParentObj()->getLink(ESC_RAW),true); ?>" />
        <summary><?php echo trim(strip_tags( $comment->getPresentationDescription(ESC_RAW))); ?></summary>
        <updated><?php echo date(DATE_ATOM, strtotime($comment->updated_at)); ?></updated>
      </entry>
    <?php endforeach; ?>
   <?php endif;?>
</feed>