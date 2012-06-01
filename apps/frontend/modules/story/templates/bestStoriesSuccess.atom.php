<?php echo'<?xml version="1.0" encoding="utf-8"?>'?>
<feed xmlns="http://www.w3.org/2005/Atom">
   <id>http://yigg.de/atom/beste-nachrichten</id>
   <title>Die besten Nachrichten</title>
   <author><name>Die YiGG Nutzer</name></author>
   <link href="<?php echo url_for("@best_stories", true);?>" />
   <updated><?php echo date(DATE_ATOM, strtotime($stories->getLast()->created_at)); ?></updated>
   <?php include_partial("story/listAtom", array("stories" => $stories)); ?>
</feed>