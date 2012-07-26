<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xml:lang="de" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>
    <link rel="shortcut icon" href="/favicon.ico" />
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
            height: 58px;
            background-color: #dddddd;
            display:block;
        }
        
        #bar_content{
            margin: 0 auto;
            width: 1400px;
        }

        #bar_logo{
            padding-left: 10px;
            width: 200px;
            float: left;
        }
        
        #bar_rate_story{
            float: left;
            padding: 16px;
        }

        #bar_comments{
            float: left;
            padding: 16px;
        }

        #bar_comments_content{
            border: 2px solid #ccc;
        }

        .close{
            float: right;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php echo $sf_content ?>
</body>
</html>
