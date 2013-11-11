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

<script type='text/javascript'>
  var googletag = googletag || {};
  googletag.cmd = googletag.cmd || [];
  (function() {
    var gads = document.createElement('script');
    gads.async = true;
    gads.type = 'text/javascript';
    var useSSL = 'https:' == document.location.protocol;
    gads.src = (useSSL ? 'https:' : 'http:') + '//www.googletagservices.com/tag/js/gpt.js';
    var node = document.getElementsByTagName('script')[0];
    node.parentNode.insertBefore(gads, node);
  })();
</script>

<script type='text/javascript'>
  googletag.cmd.push(function() {
    googletag.defineSlot('/1007584/yigg_news_details', [728, 90], 'div-gpt-ad-1384179156582-0').addService(googletag.pubads());
    googletag.defineSlot('/1007584/yigg_erstellen', [728, 90], 'div-gpt-ad-1384179239715-0').addService(googletag.pubads());
    googletag.defineSlot('/1007584/yigg_sidebar_unten', [200, 200], 'div-gpt-ad-1384179258343-0').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>