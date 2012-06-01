<p class="error">Dieser Link ist veraltet oder die Inhalte wurden entfernt!</p>
<h1>Diese Seite existiert nicht!</h1>

<?php echo img_tag("404.gif", array("style" => "float: right;")) ?>

<h2>Was bedeutet das?</h2>
<p>Tut uns fürchterlich leid, aber diese Seite gibt es leider nicht. Wahrscheinlich bist du durch einen veralteten Link hier gelandet.</p>
<h2>Was kann ich tun?</h2>
<ul>
 <li>Eventuell helfen Dir die Alternativen von Google unten weiter?</li>
 <li>Probiere die Nachricht über die Suchbox oben rechts zu finden</li>
 <li>Frage einen Moderator oder das <?php echo link_to('YiGG Team', '@about_pages?template=kontakt', array('title' => 'Wir helfen gerne!')); ?> um Hilfe</li>
</ul>

<?php slot("sidbar")?>
  <style type="text/css">
    #goog-wm { }
    #goog-wm h3.closest-match { }
    #goog-wm h3.closest-match a { }
    #goog-wm h3.other-things { }
    #goog-wm ul li { }
    #goog-wm li.search-goog { display: block; }
  </style>
  <script type="text/javascript">
    var GOOG_FIXURL_LANG = 'de';
    var GOOG_FIXURL_SITE = 'http://www.yigg.de/';
  </script>
  <script type="text/javascript" src="http://linkhelp.clients.google.com/tbproxy/lh/wm/fixurl.js"></script>
<?php end_slot()?>