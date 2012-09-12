<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xml:lang="de" xmlns="http://www.w3.org/1999/xhtml">
  <head profile='http://www.w3.org/2006/03/hcard'>
    <link rel="shortcut icon" href="<?php echo sfConfig::get('static_host_images')?>/favicon.ico" />
    <base href="http<?php echo $sf_request->isSecure() ? "s" :"" ?>://<?php echo $sf_request->getHost() . $sf_request->getRelativeUrlRoot();  ?>/" />

    <!-- metas -->
    <?php include_http_metas(); ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <?php include_javascripts() ?>
    <!-- tofo -->

    <!-- styles -->
     <link href="http://yigg.it/css/default.css" rel="stylesheet" type="text/css" />
     <link href="http://yigg.it/css/yigg-styles-v8.css" rel="stylesheet" type="text/css" />
     <link href="http://yigg.it/css/yigg-styles-backend.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
   <div class="bg_top">
   <div class="bg_bt">
   <div class="bg_mid">
    <div id="container">
      <div class="header">
        <a tabindex="1" href="#Content" class="hidden">Direkt zum Inhalt </a>
        <?php
          echo link_to(
            img_tag(
              'yigg_logo.png',
               array(
                'alt' => 'Yigg Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
                'width' => 166,
                'height' => 52
               )
             ),
             '@homepage',
             array(
              'title' => 'Yigg Nachrichten zum Mitmachen: Lesen - Bewerten - Schreiben',
              'rel' => 'home',
              'class' => 'logo'
            )
         );
        ?>
        <?php if(true === has_slot("sponsoring")): ?>
          <?php include_slot("sponsoring"); ?>
        <?php endif; ?>

        <ul id="navi"><?php
          echo
            content_tag('li',
              link_to('Sponsorings', 'sponsoring', array('title' => 'Sponsorings')),
              ($sf_request->getModule() == 'sponsoring' && $sf_request->getAction() == 'list') ? array('class' => 'selected') : array(), true
            );

           echo
            content_tag('li',
              link_to('Sponsoring Plätze', 'sponsoring_place', array('title' => 'Sponsoring Plätze')),
              ($sf_request->getModule() == 'sponsoring_place' && $sf_request->getAction() == 'list') ? array('class' => 'selected') : array(), true
            );

            echo
            content_tag('li',
              link_to('Video', 'video', array('title' => 'Video')),
              ($sf_request->getModule() == 'video') ? array('class' => 'selected') : array(),
              true
            );
          echo
            content_tag('li',
              link_to('Tasks', 'task_status', array('title' => 'WorldSpy-Feeds verwalten')),
              ($sf_request->getModule() == 'feed') ? array('class' => 'selected') : array(),
              true
            );
            echo
            content_tag('li',
              link_to('User', 'user', array('title' => 'User')),
              ($sf_request->getModule() == 'user' && $sf_request->getAction() == 'list') ? array('class' => 'selected') : array(), true
            );

           echo
            content_tag('li',
              link_to('Domains', 'domain/index', array('title' => 'Domains verwalten')),
              ($sf_request->getModule() == 'domain') ? array('class' => 'selected') : array(),
              true
            );
          echo
            content_tag('li',
              link_to('WSpy', 'wspy', array('title' => 'WorldSpy-Feeds verwalten')),
              ($sf_request->getModule() == 'feed') ? array('class' => 'selected') : array(),
              true
            );?>
         </ul>
        <ul id="sub-navi"></ul>
      </div>
      <div id="content">
          <?php echo $sf_data->getRaw('sf_content'); ?>
      </div>
      <div id="footer" class="thirds">
       <div class="clr bth"><!--  clearer --></div>
      </div>
    </div>
  </div>
  </div>
  </div>

    <?php if(false === $sf_request->isMobile()): ?>
      <script type="text/javascript" src="<?php echo sfConfig::get('static_host')?>js/prototype.js"></script>
      <script type="text/javascript" src="<?php echo sfConfig::get('static_host')?>js/scriptaculous/scriptaculous.js"></script>
      <script type="text/javascript" src="<?php echo sfConfig::get('static_host')?>js/ninjitsu.js?ver=1.8.8"></script>

    <?php endif; ?>
  </body>
</html>