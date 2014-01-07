<?php $time = time(); if( false == cache("tag-rss-" . $tag_params , strtotime( yiggTools::getRoundedTime($time) ) - $time)): ?>
<?php echo'<?xml version="1.0" encoding="utf-8"?>'?>
<feed xmlns="http://www.w3.org/2005/Atom" rel="self">
   <id>tag:yigg.de,<?php echo $tag_params;?></id>
   <title>Alle Tags zum Thema <?php echo $tag_params;?></title>
   <link href="<?php echo url_for("@best_stories", true);?>" />
   <updated><?php echo date(DATE_ATOM, strtotime($stories->getLast()->created_at)); ?></updated>
    <?php foreach ($stories as $k => $story): ?>
      <entry>
        <id>tag:yigg.de,<?php echo date("Y-m-d",strtotime($story->created_at)) .":" . $story->internal_url; ?></id>
        <title><?php echo $story->getTitle(ESC_RAW); ?></title>
        <link href="<?php echo url_for($story->getLink(ESC_RAW),true); ?>" />
        <summary><?php echo strip_tags(htmlspecialchars_decode($story->getSummary(ESC_RAW))); ?></summary>
        <updated><?php echo date(DATE_ATOM, strtotime($story->last_edited)); ?></updated>
      </entry>
    <?php endforeach; ?>
</feed><?php cache_save(); ?>
<?php endif; ?>