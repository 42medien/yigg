<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<urlset
     xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
           http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

<?php for($i=$day; mktime(0,0,0,$month,$i,$year) < time(); $i++):?>
  <url>
   <loc><?php
     //This makes use off some properties off mktime. If you increment the days it
     //will still calculate a proper timestamp
     echo "http://yigg.de/nachrichten/".date("Y/n/j", mktime(0,0,0,$month,$i,$year))
   ?></loc>
   <changefreq>monthly</changefreq>
  </url>
<?php endfor;?>
</urlset>