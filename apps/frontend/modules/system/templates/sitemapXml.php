<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<urlset
     xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
           http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?php foreach ($urls as $story):?>
  <?php
  $year = substr($story["created_at"], 0, 4);
  $month = substr($story["created_at"], 5, 2);
  $day = substr($story["created_at"], 8, 2); ?>
  <url>
   <loc><?php echo "http://yigg.de/nachrichten/$year/$month/$day/{$story["internal_url"]}";?></loc>
   <changefreq>monthly</changefreq>
  </url>
<?php endforeach; ?>
</urlset>