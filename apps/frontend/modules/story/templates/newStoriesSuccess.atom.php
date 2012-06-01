<?php echo'<?xml version="1.0" encoding="utf-8"?>'?>
<feed xmlns="http://www.w3.org/2005/Atom" rel="self">
<id>tag:yigg.de,2009-10-1:neueste-nachrichten</id>
<title>Alle neu eingestellten Beiträge der letzten 24 Stunden</title>
<link href="<?php echo url_for("@new_stories", true);?>" />
<updated><?php echo date(DATE_ATOM, strtotime($stories->getFirst()->created_at)); ?></updated>
<?php include_partial("story/listAtom", array("stories" => $stories));