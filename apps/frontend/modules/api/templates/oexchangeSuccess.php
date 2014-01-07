<?xml version='1.0' encoding='UTF-8'?>
<XRD xmlns="http://docs.oasis-open.org/ns/xri/xrd-1.0">
  <Subject><?php echo url_for("@story_create", true); ?></Subject>

  <Property type="http://www.oexchange.org/spec/0.8/prop/vendor">ekaabo GmbH</Property>
  <Property type="http://www.oexchange.org/spec/0.8/prop/title">Die besten Nachrichten</Property>
  <Property type="http://www.oexchange.org/spec/0.8/prop/name">YiGG</Property>
  <Property type="http://www.oexchange.org/spec/0.8/prop/prompt">Send to YiGG</Property>

  <Link rel= "icon" href="<?php echo image_path("favicon.ico", true); ?>" type="image/vnd.microsoft.icon" />
  <Link rel= "http://www.oexchange.org/spec/0.8/rel/offer" href="<?php echo url_for("@story_create", true); ?>" type="text/html" />
</XRD>