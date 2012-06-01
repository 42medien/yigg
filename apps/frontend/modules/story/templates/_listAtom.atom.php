<?php foreach ($stories as $k => $story): ?>
  <entry>
    <id><?php echo $story->getLinkWithDomain();?></id>
    <title><?php echo strip_tags(html_entity_decode($story->title,ENT_COMPAT,"UTF-8")); ?></title>
    <author><name><?php echo $story->Author->username;?></name></author>
    <link href="<?php echo url_for($story->external_url, true); ?>" />
    <summary><?php echo strip_tags(html_entity_decode($story->getPlainTextDescription(),ENT_COMPAT,"UTF-8")); ?></summary>
    <updated><?php echo date(DATE_ATOM, strtotime($story->created_at)); ?></updated>
  </entry>
<?php endforeach; ?>