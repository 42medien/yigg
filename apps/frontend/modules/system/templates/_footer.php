<div class="site-footer-widgets">
  <div class="about widget">
    <h4>Über YiGG</h4>
    <ul>
      <li><?php echo link_to('Kontakt', '@about_pages?template=kontakt', array('title' => 'Kontakt')); ?></li>
      <li><?php echo link_to('Impressum', '@about_pages?template=impressum', array('title' => 'Impressum')); ?></li>
      <li><?php echo link_to('YiGG-Blog', 'http://blog.yigg.de', array('title' => 'Neuigkeiten über YiGG', 'target' => '_blank')); ?></li>
    </ul>
  </div>
  <div class="legal widget">
    <h4>Nutzungsbestimmungen</h4>
    <ul class="end">
      <li><?php echo link_to('Verhaltensrichtlinien', '@legal_pages?template=verhaltensrichtlinien', array('title' => 'Verhaltensrichtlinien')); ?></li>
      <li><?php echo link_to('Nutzungsbedingungen' ,'@legal_pages?template=nutzungsbedingungen', array('title' => 'Nutzungsbedingungen')); ?></li>
      <li><?php echo link_to('Datenschutzrichtlinien' ,'@legal_pages?template=datenschutzrichtlinien', array('title' => 'Datenschutzrichtlinien')); ?></li>
    </ul>
  </div>
  <div class="facebook widget">
    <h4>Facebook</h4>
    <div class="fb-like-box"
      data-href="http://www.facebook.com/yiggde"
      data-width="200"
      data-show-faces="true"
      data-colorscheme="light"
      data-show-border="false"
      data-stream="false"
      data-header="false">
    </div>
  </div>

  <div class="twitter widget">
    <h4>Twitter</h4>
    <a class="twitter-timeline" href="https://twitter.com/YiGG" data-widget-id="369374603291807744">Tweets von @YiGG</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
  </div>
</div>
<?php include_partial("system/javascript");?>