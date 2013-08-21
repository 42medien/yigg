<div id="main-navigation">
  <ol>
    <li <?php if($sf_request->getAction() == 'bestStories'): ?>class="selected"<?php endif;?>>
      <?php echo link_to('Beste Nachrichten','@best_stories',array('title' => 'Beste Nachrichten von heute'));?>
    </li>
    <li <?php if($sf_request->getAction() == 'newStories'): ?>class="selected"<?php endif;?>>
      <?php echo link_to('Neueste Nachrichten',"@new_stories","title=Neueste Nachrichten von heute"); ?>
    </li>
  
    <li class="create"><?php echo link_to('Nachricht erstellen', '@story_create', array('title' => 'Neue Nachricht erstellen')); ?></li>
  </ol>
</div>

<div id="user-navigation">
  <ul>
    <?php if(true === $sf_user->hasUser()):?>
    <li class="login-link">
      <a href="<?php echo url_for('@user_logout');?>">Logout</a>                                
    </li>
    <?php else: ?>
    <li <?php if($sf_request->getAction() == 'register'): ?>class="selected"<?php endif;?>>
      <?php echo link_to('Registrieren',"@user_register",array("title"=>"Benutzeraccount erstellen")); ?>
    </li>
    <li class="login-link">
      <a href="<?php echo url_for('@user_login');?>">Login</a>
      <div class="login_fb" onclick="onClickloginfb(); return false;"></div>
      <div class="login_box">
        <a class="fb_cnct" href="#" onclick="onClickloginfb(); return false;"></a>
        <a class="yigg_cnct" href="<?php echo url_for('@user_login');?>"></a>
      </div>
    </li>
    <?php endif; ?>
  </ul>
  
  <div id="search">
  <?php include_component("search", "form"); ?>
  </div>
</div>