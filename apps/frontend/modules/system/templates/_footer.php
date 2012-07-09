<div id="footer" class="thirds">
    <div class="clr" style="width: 100%"></div>

    <div class="one">
      <h4>Über YiGG</h4>
      <ul>
        <li><?php echo link_to('Kontakt', '@about_pages?template=kontakt', array('title' => 'Kontakt')); ?></li>
        <li><?php echo link_to('Impressum', '@about_pages?template=impressum', array('title' => 'Impressum')); ?></li>
        <li><?php echo link_to("YiGG-Blog","http://yiggblog.wordpress.com/",array("title" => "Neuigkeiten über YiGG")); ?></li>
      </ul>
    </div>
   <!--<li><?php echo link_to('YiGGSponsorings-FAQ', '@about_pages?template=yigg-sponsoring', array('title' => 'YiggSponsorings')) ?></li>
    <div class="two">
      <h4>Hilfe</h4>
      <ul>
        <li><?php echo link_to('YiGG Hilfecenter', 'http://hilfe.yigg.de', array('rel' => 'help', 'title' => 'Oft gestellte Fragen')); ?></li>
      </ul>
    </div>
    -->
    <div class="three">
      <h4>Nutzungsbestimmungen</h4>
      <ul class="end">
          <li><?php echo link_to('Verhaltensrichtlinien', '@legal_pages?template=verhaltensrichtlinien', array('title' => 'Verhaltensrichtlinien')); ?></li>
          <li><?php echo link_to('Nutzungsbedingungen' ,'@legal_pages?template=nutzungsbedingungen', array('title' => 'Nutzungsbedingungen')); ?></li>
          <li><?php echo link_to('Datenschutzrichtlinien' ,'@legal_pages?template=datenschutzrichtlinien', array('title' => 'Datenschutzrichtlinien')); ?></li>
      </ul>
    </div>
    <div class="clr bth"><!--  clearer --></div>
  </div>
</div>
<?php include_partial("system/javascript");?>
