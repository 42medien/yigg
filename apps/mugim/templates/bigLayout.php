<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
  <head>
    <base href="http://<?php echo $sf_request->getHost() ?>/" />
    <?php include_http_metas(); ?>
    <?php include_metas() ?>
    <?php include_stylesheets() ?>
    <?php include_title() ?>
    <style type="text/css">
        #sitemenu-container { background-image: url(http://yigg.de/css/mugim/mug.im.<?php echo rand(1,10);?>.jpg) !important; }
     </style>
  </head>
	<body class="sandvox no-sidebar has-custom-banner home-page IR" id="www_miniuri_at" >
		<div id="page-container" class="text-page">
			<div id="page">
				<div id="page-top" class="no-logo">
                    <?php include_partial("pages/header")?>
					<?php include_partial("pages/mainMenu"); ?>
				</div> <!-- page-top -->
				<div class="clear"></div>
				<div id="page-content" class="no-navigation">
					<div id="main">
						<div id="main-top"></div>
						<div id="main-content">
                            <?php echo $sf_content ?>
                        </div> <!-- main-content -->
						<div id="main-bottom"></div>
					</div> <!-- main -->
				</div> <!-- content -->
				<div class="clear"></div>
				<div id="page-bottom">
					<div id="page-bottom-contents">
						<div>Copyright 2009 by Robert Curth</div>
						<div class="hidden"> <a rel="nofollow" href="#title">[Nach oben]</a></div>
					</div>
				</div> <!-- page-bottom -->
			</div> <!-- container -->
			<div id="extraDiv1"><span></span></div><div id="extraDiv2"><span></span></div><div id="extraDiv3"><span></span></div><div id="extraDiv4"><span></span></div><div id="extraDiv5"><span></span></div><div id="extraDiv6"><span></span></div>
		</div> <!-- specific body type -->
		<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-10211355-1");
pageTracker._trackPageview();
} catch(err) {}</script>
	</body>
</html>