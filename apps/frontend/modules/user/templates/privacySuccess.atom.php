<?php $time = time(); if( false == cache("user-rss"."-". $user->username , strtotime( yiggTools::getRoundedTime($time) ) - $time)): ?>
<?php echo'<?xml version="1.0" encoding="utf-8"?>'?>
<feed xmlns="http://www.w3.org/2005/Atom" rel="self">
   <id>tag:www.yigg.de,2009-10-1:user-<?php echo $user->username?></id>
   <title><?php echo $user->username?> hat die Datenschutsfunktion aktiviert.</title>
   <link href="<?php echo url_for("@best_stories", true);?>" />
   <updated><?php echo date(DATE_ATOM, strtotime($user->updated_at)); ?></updated>
    <?php foreach ($last_stories as $k => $story): ?>
      <entry>
        <id>tag:www.yigg.de,<?php echo date("Y-m-d",strtotime($story->created_at)) .":" . $story->internal_url; ?></id>
        <title><?php echo $user->username?> hat die Datenschutsfunktion aktiviert.</title>
        <link href="<?php echo url_for($user->getLink(),true); ?>" />
        <summary><?php echo $user->username?> hat die Datenschutsfunktion aktiviert.</summary>
        <updated><?php echo date(DATE_ATOM, strtotime($user->updated_at)); ?></updated>
      </entry>
    <?php endforeach; ?>
</feed><?php cache_save(); ?>
<?php endif; ?>