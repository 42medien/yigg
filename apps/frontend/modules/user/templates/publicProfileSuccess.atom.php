<?php $time = time(); if( false == cache("user-centric-rss"."-". $user->username , strtotime( yiggTools::getRoundedTime($time) ) - $time)): ?>
<?php echo'<?xml version="1.0" encoding="utf-8"?>'?>
<feed xmlns="http://www.w3.org/2005/Atom" rel="self">
   <id>tag:www.yigg.de,2009-10-1:user-centric-<?php echo $user->getUsername(); ?></id>
   <title>Letzte Activität von <?php echo $user->getUsername(); ?></title>
   <link href="<?php echo url_for("@best_stories", true);?>" />
   <?php if($stories): ?>
     <?php foreach( $stories as $story ): ?>
        <entry>
          <id>tag:www.yigg.de,<?php echo date( "Y-m-d", strtotime( $story->getEpochTime() ) ) .":" . $story->getId(); ?></id>
          <title><?php echo htmlspecialchars($story->getTitle()); ?></title>
          <link href="<?php echo url_for_story( $story, false, true ); ?>" />
          <updated><?php echo date( DATE_ATOM, $story->getEpochTime() ); ?></updated>
        </entry>
      <?php endforeach; ?>
   <?php endif;?>
</feed><?php cache_save(); ?>
<?php endif; ?>