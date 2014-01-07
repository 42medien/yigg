<?xml version="1.0" encoding="UTF-8"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
  <ShortName>YiGG Search</ShortName>
  <Description>Use YiGG to search the most import news.</Description>
  <Tags>YiGG social news</Tags>
  <Contact><?php echo sfConfig::get("app_mail_contact"); ?></Contact>
  <Url type="text/html" template="<?php echo url_for("@search", true); ?>?q={searchTerms}"/>
</OpenSearchDescription>