<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xml:lang="de" xmlns="http://www.w3.org/1999/xhtml">
  <head profile='http://www.w3.org/2006/03/hcard'>
    <script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>
    <link rel="shortcut icon" href="/favicon.ico" />
    <base href="http<?php echo $sf_request->isSecure() ? "s" :"" ?>://<?php echo $sf_request->getHost() . $sf_request->getRelativeUrlRoot();  ?>/" />

    <?php include_http_metas(); ?>
    <?php include_metas() ?>
    <?php include_component('system','Feeds') ?>
    <?php include_title() ?>
    <?php include_javascripts() ?>

    <link href="/css/yigg-styles-v8.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <div id="container">
      <div class="header">
        <a tabindex="1" href="#content" class="hidden">Direkt zum Inhalt </a>
        <?php
          echo link_to(img_tag('yigg_logo.png', array(
                  'alt' => 'YiGG Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
                  'width' => 191,
                  'height' => 54
              )),
             '@best_stories',
             array(
              'title' => 'YiGG Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
              'rel' => 'home',
              'class' => 'logo'
             ));?>
        <?php if(true === has_slot("sponsoring")): ?>
          <?php include_slot("sponsoring"); ?>
        <?php endif; ?>
        <?php include_partial("system/navigation");?>
      </div>
      <div id="content">
      <div class="twoThree clr">
        <div class="twoThree-left">
          <!--  google_ad_section_start -->
          <?php echo $sf_data->getRaw('sf_content'); ?>
          <!--  google_ad_section_end -->          
        </div>
        <div class="twoThree-right">
          <?php include_partial("user/userinfo"); ?>
          <?php include_component("story", "bestVideos", array( "height"=> 285, "width" => 370)); ?>
          <?php if(true === has_slot("sidebar_sponsoring")): ?>
            <?php include_slot("sidebar_sponsoringBigSection"); ?>
          <?php endif; ?>  
          <?php if(true === has_slot("sidebar")): ?>
            <?php include_slot("sidebar"); ?>
          <?php endif; ?>
          <?php if(true === has_slot("sidebar_sponsoring")): ?>
            <?php include_slot("sidebar_sponsoring"); ?>
          <?php endif; ?>
        </div>
      </div>

        <br />
      </div>
  <?php include_partial("system/footer");?>
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
                window.onload = (typeof window.onload != 'function') ?
                    loadChartbeat : function() { oldonload(); loadChartbeat(); };
            })();

        </script>
  </body>
</html>
