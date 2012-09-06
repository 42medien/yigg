 <ul id="navi">
  <li <?php if($sf_request->getAction() == 'bestStories'): ?>class="selected"<?php endif;?>><?php echo link_to('Beste Nachrichten','@best_stories',array('title' => 'Beste Nachrichten von heute'));?></li>
  <li <?php if($sf_request->getAction() == 'newStories'): ?>class="selected"<?php endif;?>><?php echo link_to('Neueste Nachrichten',"@new_stories","title=Neueste Nachrichten von heute"); ?></li>

  <?php if(true === $sf_user->hasUser()):?>
    <li <?php if($sf_request->getModule() == 'user'): ?>class="selected"<?php endif;?>><?php echo link_to('Mein YiGG',"@user_welcome",array("title"=>"Dein Profil ansehen")); ?></li>
  <?php else:?>
    <li <?php if($sf_request->getAction() == 'register'): ?>class="selected"<?php endif;?>><?php echo link_to('Registrieren',"@user_register",array("title"=>"Benutzeraccount erstellen")); ?></li>
  <?php endif;?>

  <li class="create"><?php echo link_to('Nachricht erstellen', '@story_create', array('title' => 'Neue Nachricht erstellen')); ?></li></ul>
  <ul class="search_box">
   <li id="search">
      <?php include_component("search", "form"); ?>
   </li>
  </ul>
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
    <?php $categories = Doctrine_Core::getTable('Category')->getCategories(); if(count($categories)):?>
    <?php foreach($categories as $category):?>
        <li><?php echo link_to($category->getName(), 'category_stories', $category); ?></li>
        <?php endforeach;?>
    <?php endif;?>
</ul>
