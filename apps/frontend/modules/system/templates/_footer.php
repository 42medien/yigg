<div id="footer" class="thirds">
  <div class="about">
    <h4>Über YiGG</h4>
    <ul>
      <li><?php echo link_to('Kontakt', '@about_pages?template=kontakt', array('title' => 'Kontakt')); ?></li>
      <li><?php echo link_to('Impressum', '@about_pages?template=impressum', array('title' => 'Impressum')); ?></li>
      <li><?php echo link_to('YiGG-Blog', 'http://yiggblog.wordpress.com/', array('title' => 'Neuigkeiten über YiGG', 'target' => '_blank')); ?></li>
    </ul>
  </div>
  <div class="legal">
    <h4>Nutzungsbestimmungen</h4>
    <ul class="end">
      <li><?php echo link_to('Verhaltensrichtlinien', '@legal_pages?template=verhaltensrichtlinien', array('title' => 'Verhaltensrichtlinien')); ?></li>
      <li><?php echo link_to('Nutzungsbedingungen' ,'@legal_pages?template=nutzungsbedingungen', array('title' => 'Nutzungsbedingungen')); ?></li>
      <li><?php echo link_to('Datenschutzrichtlinien' ,'@legal_pages?template=datenschutzrichtlinien', array('title' => 'Datenschutzrichtlinien')); ?></li>
    </ul>
  </div>
</div>
<?php include_partial("system/javascript");?>