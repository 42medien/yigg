<?php
/**
 * This file contains the template for the nice right-side boxes that are located under the navigation
 */

$titleAccess = isset( $titleAccess) ? $titleAccess : "title";
$link = isset($link) ? $link : "getLink";

?>
<div class="infobox <?php echo (isset($big)&&($big===true))?" big" : ""; ?>">
  <h3><?php echo $title; ?></h3>
  <ol><?php foreach ($collection as $obj): ?><li><?php echo link_to( trim( mb_substr( html_entity_decode($obj->$titleAccess,ENT_NOQUOTES,'UTF-8'),0, 40, "UTF-8")), $obj->$link(), array("rel" => "nofollow") ) ?></li><?php endforeach; ?></ol>
</div>