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

    <?php include_http_metas(); ?>
    <?php include_metas() ?>
    <?php include_component('system','Feeds') ?>
    <?php include_title() ?>
    <?php use_javascript('jquery-1.7.1.js') ?>
    <?php include_javascripts() ?>
    <?php include_stylesheets(); ?>
  </head>
  <body class="<?php echo ( $sf_user->isAuthenticated() ? "user-auth" : "user-anon" )?>">
    <div id="page">
      <header id="branding">
        <nav id="access" role="navigation">
          <a tabindex="1" href="#content" class="skip-link screen-reader-text">Direkt zum Inhalt </a>
          <?php
            echo link_to(img_tag('yigg_logo.png', array(
                      'alt' => 'YiGG Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
                      'width' => 90,
                      'height' => 53
                  )), '@best_stories', array(
              'title' => 'YiGG Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
              'rel' => 'home',
              'class' => 'logo'
            ));
          ?>

          <?php include_partial("system/main_navigation"); ?>                         
        </nav>
      </header>
    
      <div id="main">
        <main id="content">
          <?php echo $sf_data->getRaw('sf_content'); ?>
        </main>
        
        <aside id="sidebar">
          <?php if(true === has_slot("sponsoring")): ?>
            <?php //include_slot("sponsoring"); ?>
          <?php endif; ?>
          <?php include_partial("user/userinfo"); ?>
          <?php //include_component("story", "bestVideos", array( "height"=> 285, "width" => 370)); ?>
          <?php if(has_slot("sidebar")) { ?>
            <?php include_slot("sidebar"); ?>
          <?php } ?> 
          <div class="fb-like-box"
           data-href="http://www.facebook.com/yiggde"
           data-width="200"
           data-show-faces="true"
           data-show-border="false"
           data-stream="false"
           data-header="false">
         </div>
         <?php include_partial("user/anzeigeBottom"); ?>
         <?php include_component("story", "bestVideosBottom", array( "height"=> 285, "width" => 370)); ?>
        </aside>
                
      </div>
      
      <footer>
        <?php include_partial("system/footer");?>
      </footer>
    </div>

    <script type="text/javascript">
      var _sf_async_config={uid:23222,domain:"yigg.de"};
      (function(){
        function loadChartbeat() {
          window._sf_endpt=(new Date()).getTime();
          var e = document.createElement('script');
          e.setAttribute('language', 'javascript');
          e.setAttribute('type', 'text/javascript');
          e.setAttribute('src',
            (("https:" == document.location.protocol) ? "https://a248.e.akamai.net/chartbeat.download.akamai.com/102508/" : "http://static.chartbeat.com/") + "js/chartbeat.js");
          document.body.appendChild(e);
        }
        var oldonload = window.onload;
        window.onload = (typeof window.onload != 'function') ? loadChartbeat : function() { oldonload(); loadChartbeat(); };
      })();
    
      var uvOptions = {};
      (function() {
        var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
        uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://' ) + 'widget.uservoice.com/ivhHCap8jZkAWPJveHWCaw.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
      })();
    </script>
        
    <!-- START FACEBOOK JAVASCRIPT SDK -->
    <div id="fb-root"></div>
  </body>
</html>