<ul id="sub-navi">
  <li <?php if($sf_request->getModule() == "spy"): ?>class="selected"<?php endif; ?>><?php echo link_to("YiGGspion","@spy",array("title" =>"betrachte den YiGGspion")); ?></li>
  <li <?php if($sf_request->getModule() == "worldspy"): ?>class="selected"<?php endif; ?>><?php echo link_to("Weltspion","@worldspy_top",array("title" =>"Betrachte den WeltSpion")); ?></li>
  <li <?php if($sf_request->getAction() == "archive"): ?>class="selected"<?php endif; ?>><?php echo link_to('Archiv', 'story/archive', array('title' => 'Betrachte die besten Nachrichten der Vergangenheit')); ?></li>
  <?php if($sf_user->hasUser()):?>
    <li <?php if($sf_request->getAction() == "publicProfile" && $sf_request->getParameter("username") === $sf_user->getUser()->username): ?>class="selected"<?php endif;?>>
      <?php echo link_to("Profil", "@user_public_profile?username={$sf_user->getUser()->username}");?>
    </li>
  <?php endif;?>
  <!--<li><?php echo link_to("Hilfe", 'http://hilfe.yigg.de/', array('title' => 'Hilfe', "rel" => "external")); ?></li>-->
  <?php if($sf_user->hasUser() && $sf_user->getUser()->isSponsor()):?>
    <li <?php if($sf_request->getModule() == "sponsoring"): ?>class="selected"<?php endif; ?>><?php echo link_to("Sponsorings", '@sponsoring', array('title' => 'Informationen zu Deinen YiGG-Sponsorings')); ?></li>
  <?php endif; ?>
</ul>

<ul id="categories-nav">
    <h3>Kategorien</h3>
    <?php $categories = Doctrine_Core::getTable('Category')->getCategories(); if(count($categories)):?>            
    <?php foreach($categories as $category):?>
        <li><?php echo link_to($category->getName(), 'category_stories', $category); ?></li>
        <?php endforeach;?>
    <?php endif;?>
</ul>
