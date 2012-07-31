<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xml:lang="de" xmlns="http://www.w3.org/1999/xhtml">
  <head profile='http://www.w3.org/2006/03/hcard'>
    <script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>
     
    <script type='text/javascript'>                        
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

    <script type='text/javascript'>
        googletag.cmd.push(function() {
        googletag.defineSlot('/1043423/Yigg-Button-1L', [170, 125], 'div-gpt-ad-1342004948184-0').addService(googletag.pubads());
        googletag.defineSlot('/1043423/Yigg-Button-1R', [170, 125], 'div-gpt-ad-1342004948184-1').addService(googletag.pubads());
        googletag.defineSlot('/1043423/Yigg-Button-2L', [170, 125], 'div-gpt-ad-1342004948184-2').addService(googletag.pubads());
        googletag.defineSlot('/1043423/Yigg-Button-2R', [170, 125], 'div-gpt-ad-1342004948184-3').addService(googletag.pubads());
        googletag.defineSlot('/1043423/Yigg-Button-3L', [170, 125], 'div-gpt-ad-1342004948184-4').addService(googletag.pubads());
        googletag.defineSlot('/1043423/Yigg-Button-3R', [170, 125], 'div-gpt-ad-1342004948184-5').addService(googletag.pubads());
        googletag.defineSlot('/1043423/Yigg-Button-4L', [170, 125], 'div-gpt-ad-1342004948184-6').addService(googletag.pubads());
        googletag.defineSlot('/1043423/Yigg-Button-4R', [170, 125], 'div-gpt-ad-1342004948184-7').addService(googletag.pubads());
        googletag.defineSlot('/1043423/Yigg-Button-5L', [170, 125], 'div-gpt-ad-1342004948184-8').addService(googletag.pubads());
        googletag.defineSlot('/1043423/Yigg-Button-5R', [170, 125], 'div-gpt-ad-1342004948184-9').addService(googletag.pubads());
        googletag.pubads().enableSingleRequest();
        //googletag.pubads().collapseEmptyDivs();
        googletag.collapseEmptyDivs();
        googletag.enableServices();
        });
    </script>
    <link rel="shortcut icon" href="/favicon.ico" />
    <base href="http<?php echo $sf_request->isSecure() ? "s" :"" ?>://<?php echo $sf_request->getHost() . $sf_request->getRelativeUrlRoot();  ?>/" />

    <?php include_http_metas(); ?>
    <?php include_metas() ?>
    <?php include_component('system','Feeds') ?>
    <?php include_title() ?>
    <?php use_javascript('jquery-1.7.1.js') ?>
    <?php include_javascripts() ?>
    
    <script type='text/javascript'>
    $(document).ready(function(){
       // alert('test jquery');       
        
    });
    </script>
    
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
          <script type="text/javascript" src="http://a.ligatus.com/?ids=33680&t=js"></script>  
          <!--  google_ad_section_start -->
          <?php echo $sf_data->getRaw('sf_content'); ?>
          <!--  google_ad_section_end -->          
        </div>
        <div class="twoThree-right">
          <?php include_partial("user/userinfo"); ?>
          <?php include_component("story", "bestVideos", array( "height"=> 285, "width" => 370)); ?>
            <script type='text/javascript'>
            $(document).ready(function(){
                //alert('test jquery');       
                var body_html = $('#div-gpt-ad-1342004948184-0').val();
                alert(body_html);
            });
            </script>
            <?php //if(true === has_slot("sidebar_sponsoring")): ?>
            <?php //include_slot("sidebar_sponsoring"); ?>
          <?php //endif; ?>
          <?php if(true === has_slot("sidebar")): ?>
            <?php include_slot("sidebar"); ?>
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

        <script type="text/javascript">
            var uvOptions = {};
            (function() {
                var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
                uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://' ) + 'widget.uservoice.com/ivhHCap8jZkAWPJveHWCaw.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
            })();
        </script>                
  </body>
</html>
