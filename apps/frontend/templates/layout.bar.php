<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xml:lang="de" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>
    <link rel="shortcut icon" href="/favicon.ico" />

    <?php if(true === has_slot("canonical")): ?>
        <?php include_slot("canonical"); ?>
    <?php endif; ?>

    <base href="http<?php echo $sf_request->isSecure() ? "s" :"" ?>://<?php echo $sf_request->getHost() . $sf_request->getRelativeUrlRoot();  ?>/" />

    <?php include_http_metas(); ?>
    <?php include_metas() ?>
    <?php include_component('system','Feeds') ?>
    <?php include_title() ?>
    <?php use_javascript('jquery-1.7.1.js') ?>
    <?php include_javascripts() ?>

    <link href="/css/yigg-styles-v8.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
        html, body, iframe, #iframe-content { margin:0; padding:0; height:100%; }
        body{
            overflow: hidden;
        }
        iframe { display:block; width:100%; border:none; clear: both;}

        #bar_wraper{
            background-color: #dddddd;
            display:block;
        }
        
        #bar_content{
            margin: 0 auto;
            width: 1400px;
        }

        #bar_logo{
           float: left;
        }
        
        #bar_rate_story{
            float: left;
            padding: 16px;
        }

        #bar_comments{
            float: left;
            padding: 16px 16px 0;
        }

        #bar_comments_content{
            border: 2px solid #ccc;
        }

        .close, .close:hover {
            float: left;
            margin-left: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php echo $sf_content ?>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-19326817-1']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
</body>
</html>
