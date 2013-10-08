<!DOCTYPE html>
<html lang="de-DE" prefix="og: http://ogp.me/ns# article:http://ogp.me/ns/article#">
  <head>
    <?php include_partial("global/header"); ?>
  </head>
  <body class="<?php echo ( $sf_user->isAuthenticated() ? "user-auth" : "user-anon" )?>">
    <div id="page">
      <header id="branding">
        <?php include_partial("system/header"); ?>
      </header>
    
      <div id="main">
        <main id="content">
          <?php echo $sf_data->getRaw('sf_content'); ?>
        </main>
        
        <aside id="sidebar">
          <?php include_partial("system/sidebar"); ?>
        </aside>
                
      </div>
      
      <footer class="site-footer">
        <?php include_partial("system/footer");?>
        
        <script type='text/javascript'>
        var container = document.querySelector('#stories');
        var msnry;
        // initialize Masonry after all images have loaded
        imagesLoaded( container, function() {
          msnry = new Masonry( container );
        });
        </script>
      </footer>
    </div>
  </body>
</html>