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
    
    <?php
      include_http_metas();
      include_semantic_metas();
      include_component('system','Feeds');
      include_title();
      use_javascript('jquery-1.7.1.js');
      include_javascripts();
      include_stylesheets();
    ?>
  </head>
  <body class="<?php echo ( $sf_user->isAuthenticated() ? "user-auth" : "user-anon" ); ?> module-<?php echo $sf_context->getModuleName(); ?> action-<?php echo $sf_context->getActionName(); ?>">
    <div id="page">
      <header id="branding">
        <?php include_partial("system/header"); ?>
      </header>
    
      <div id="main">
        <main id="content" class="h-entry hentry page" itemscope itemtype="http://schema.org/WebPage">
          <?php if (has_slot("page-title")) { ?>
            <h1 class="page-title p-name entry-title" itemprop="name headline"><?php include_slot("page-title"); ?></h1>
          <?php } ?>
          
          <article class="e-description entry-description" itemprop="description text">
            <?php echo $sf_data->getRaw('sf_content'); ?>
          </article>
        </main>
        
        <aside id="sidebar">
          <?php include_partial("system/sidebar"); ?>
        </aside>
                
      </div>
      
      <footer class="site-footer">
        <?php include_partial("system/footer");?>
      </footer>
    </div>
  </body>
</html>