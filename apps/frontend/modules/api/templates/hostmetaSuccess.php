<?xml version='1.0' encoding='UTF-8'?>
<XRD xmlns='http://docs.oasis-open.org/ns/xri/xrd-1.0'
     xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>

  <?php foreach($host_meta['links'] as $link) { ?>
  <Link rel="<?php echo $link["rel"]; ?>" href="<?php echo $link["href"]; ?>" type="<?php echo $link["type"]; ?>" />
  <?php } ?>
</XRD>