<script type="text/javascript" src="/js/protoaculous_min.js"></script>
<script type="text/javascript" src="/js/ninjitsu_min.js?v=2"></script>
<?php //include_component('system','vibrantAds'); ?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-19326817-1']);
  _gaq.push(['_trackPageview']);
  _gaq.push(['_gat._anonymizeIp']);
  _gaq.push(['_trackPageLoadTime']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

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
  
  var container = document.querySelector('#stories');
  var msnry;
  // initialize Masonry after all images have loaded
  imagesLoaded( container, function() {
    msnry = new Masonry( container );
  });
</script>
    
<!-- START FACEBOOK JAVASCRIPT SDK -->
<div id="fb-root"></div>