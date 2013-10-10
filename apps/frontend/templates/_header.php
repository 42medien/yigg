<meta name="viewport" content="width=device-width" />
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