<h2 class="heading-right">Weitere Informationen rund um YiGG</h2>
<ul>
  <li><?php echo link_to('Kontakt', '@about_pages?template=kontakt', array('title' => 'Impressum')); ?></li>
  <li><?php echo link_to('Impressum', '@about_pages?template=impressum', array('title' => 'Impressum')); ?></li>
  <li><?php echo link_to("YiGG-Blog","http://blog.yigg.de",array("title" => "Neuigkeiten über YiGG")); ?></li>
  <li><?php echo link_to('YiGG Hilfecenter', 'http://help.yigg.de', array('rel' => 'help', 'title' => 'Oft gestellte Fragen')); ?></li>
  <li><?php echo link_to('YiGGSponsorings-FAQ', '@about_pages?template=yigg-sponsoring', array('title' => 'YiggSponsorings')) ?></li>
  <li><?php echo link_to('Verhaltensrichtlinien', '@legal_pages?template=verhaltensrichtlinien', array('title' => 'Verhaltensrichtlinien')); ?></li>
  <li><?php echo link_to('Nutzungsbedingungen' ,'@legal_pages?template=nutzungsbedingungen', array('title' => 'Nutzungsbedingungen')); ?></li>
  <li><?php echo link_to('Datenschutzrichtlinien' ,'@legal_pages?template=datenschutzrichtlinien', array('title' => 'Datenschutzrichtlinien')); ?></li>
</ul>