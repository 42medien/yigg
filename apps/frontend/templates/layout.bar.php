<!DOCTYPE html>
<html lang="de-DE" prefix="og: http://ogp.me/ns# article:http://ogp.me/ns/article#">
<head>
    <script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>
    <link rel="shortcut icon" href="/favicon.ico" />

    <?php if(true === has_slot("links")): ?>
        <?php include_slot("links"); ?>
    <?php endif; ?>

    <base href="http<?php echo $sf_request->isSecure() ? "s" :"" ?>://<?php echo $sf_request->getHost() . $sf_request->getRelativeUrlRoot();  ?>/" />

    <?php include_http_metas(); ?>
    <?php include_semantic_metas(); ?>
    <?php include_component('system','Feeds'); ?>
    <?php include_title(); ?>
    <?php use_javascript('jquery-1.7.1.js'); ?>
    <?php use_javascript('http://button.spread.ly/js/v1/loader.js'); ?>
    <?php include_stylesheets(); ?>
    <?php include_javascripts(); ?>
</head>
<body id="bar">
<?php echo $sf_content ?>

<?php include_partial("system/javascript");?>
</body>
</html>
