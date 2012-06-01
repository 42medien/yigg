<?php $time = time(); if( false == cache("user-centric-rss"."-". $user->username , strtotime( yiggTools::getRoundedTime($time) ) - $time)): ?>
<?php echo'<?xml version="1.0" encoding="utf-8"?>'?>
<feed xmlns="http://www.w3.org/2005/Atom" rel="self">
   <id>tag:www.yigg.de,2009-10-1:user-centric-<?php echo $user->username?></id>
   <title>Letzte Activität von <?php echo $user->username; ?></title>
   <link href="<?php echo url_for("@best_stories", true);?>" />
   <?php if($activity): ?>
     <?php foreach( $activity as $node ): ?>
        <entry>
          <id>tag:www.yigg.de,<?php echo date("Y-m-d",strtotime($node['epoch_time'])) .":" . $node['story_reference_id']; ?></id>
          <title><?php echo htmlspecialchars($node['story_title']); ?></title>
          <link href="<?php echo url_for( "@story?categoryUrl=" .$node['category_url'] ."&internalUrl=" . $node['story_internal_url'],true); ?>" />
          <updated><?php echo date(DATE_ATOM, strtotime($node['created_at'])); ?></updated>
        </entry>
      <?php endforeach; ?>
   <?php endif;?>
</feed><?php cache_save(); ?>
<?php endif; ?>