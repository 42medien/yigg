<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php for($i = 1; $i <= $sitemap_count; $i++):?>
   <sitemap>
      <loc>http://yigg.de/sitemap<?php echo $i?>.xml</loc>
      <lastmod><?php echo date("Y-m-d");?></lastmod>
   </sitemap>
<?php endfor;?>
   <sitemap>
      <loc>http://yigg.de/sitemapArchive.xml</loc>
      <lastmod><?php echo date("Y-m-d");?></lastmod>
   </sitemap>
   <sitemap>
      <loc>http://yigg.de/sitemapTags.xml</loc>
      <lastmod><?php echo date("Y-m-d");?></lastmod>
   </sitemap>
</sitemapindex>