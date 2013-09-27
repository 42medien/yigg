<!DOCTYPE html>
<html lang="de-DE" prefix="og: http://ogp.me/ns# article:http://ogp.me/ns/article#">
  <head>
    <script type='text/javascript'>
      var _sf_startpt=(new Date()).getTime()

      var googletag = googletag || {};
      googletag.cmd = googletag.cmd || [];

      (function() {
        var gads = document.createElement('script');
        gads.async = true;
        gads.type = 'text/javascript';
        var useSSL = 'https:' == document.location.protocol;
        gads.src = (useSSL ? 'https:' : 'http:') + 
        '//www.googletagservices.com/tag/js/gpt.js';
        var node = document.getElementsByTagName('script')[0];
        node.parentNode.insertBefore(gads, node);
      })();
    </script>

    <link rel="shortcut icon" href="/favicon.png" />
    <link rel="profile" href="http://microformats.org/profile/specs" />
    <link rel="profile" href="http://microformats.org/profile/hatom" />
    <link rel="search" type="application/opensearchdescription+xml" href="<?php echo url_for("@opensearch", true); ?>" />

    <base href="http<?php echo $sf_request->isSecure() ? "s" :"" ?>://<?php echo $sf_request->getHost() . $sf_request->getRelativeUrlRoot();  ?>/" />
    
    <!--[if lt IE 9]>
    <?php use_javascript('html5shiv.js'); ?>
    <![endif]-->
    
    <?php include_http_metas(); ?>
    <?php include_semantic_metas() ?>
    <?php include_component('system','Feeds') ?>
    <?php include_title() ?>
    <?php use_javascript('jquery-1.7.1.js') ?>
    <?php use_javascript('masonry.pkgd.min.js'); ?>
    <?php use_javascript('imagesloaded.pkgd.min.js'); ?>
    <?php include_javascripts() ?>
    <?php include_stylesheets(); ?>
  </head>
  <body class="<?php echo ( $sf_user->isAuthenticated() ? "user-auth" : "user-anon" )?>">
    <div id="page">
      <header id="branding">
        <?php include_partial("system/header"); ?>
      </header>
    
      <div id="main" class="full-width">
        <main id="content">
          <?php echo $sf_data->getRaw('sf_content'); ?>
        </main>
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