<!DOCTYPE html>
<html lang="de-DE" prefix="og: http://ogp.me/ns#">
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

      googletag.cmd.push(function() {
        googletag.defineSlot('/1043423/rectangle', [300, 250], 'div-gpt-ad-1346766539123-0').addService(googletag.pubads());
        googletag.defineSlot('/1043423/rectangle2', [300, 250], 'div-gpt-ad-1347010073546-1').addService(googletag.pubads());
        googletag.pubads().enableSingleRequest();
        googletag.enableServices();
      });
        
      window.fbAsyncInit = function() {
        FB.init({
          appId        : <?php echo sfConfig::get('app_facebook_app_id') ?>,
          status       : false,
          cookie       : true,
          xfbml        : true,
          oauth        : true
        });
      };

      // Load the SDK Asynchronously
      (function(d){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
      }(document));

      function onClickloginfb() {
        FB.login(function(response) {
          if (response.authResponse) {
            window.location = "/fb_login"
          }
        }, {perms:'email,user_interests,user_likes'});
      }
    </script>

    <link rel="shortcut icon" href="/favicon.png" />
    <link rel="profile" href="http://microformats.org/profile/specs" />
    <link rel="profile" href="http://microformats.org/profile/hatom" />

    <base href="http<?php echo $sf_request->isSecure() ? "s" :"" ?>://<?php echo $sf_request->getHost() . $sf_request->getRelativeUrlRoot();  ?>/" />

    <?php
      include_http_metas();
      include_metas();
      include_component('system','Feeds');
      include_title();
      use_javascript('jquery-1.7.1.js');
      use_javascript('jquery.wookmark.js');
      include_javascripts();
      include_stylesheets();
    ?>
  </head>
  <body class="<?php echo ( $sf_user->isAuthenticated() ? "user-auth" : "user-anon" )?>">
    <div id="page">
      <header id="branding">
        <?php include_partial("system/header"); ?>
      </header>
    
      <div id="main">
        <main id="content" class="h-entry page">
          <?php if (has_slot("page-title")) { ?>
            <h1 class="page-title"><?php include_slot("page-title"); ?></h1>
          <?php } ?>
          
          <article class="e-description entry-description">
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