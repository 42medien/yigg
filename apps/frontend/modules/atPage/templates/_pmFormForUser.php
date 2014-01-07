<section id="widget-private-message" class="widget">
  <div id="pmHolder_<?php echo $user->id;?>">
    <form method="post" action="<?php echo url_for('@notification_index');?>" id="PostboxCreateMessage" class="post-box ninjaForm ninjaAjaxSubmit pmHolder_<?php echo $user->id; ?>">
      <?php echo $form->render(); ?>
      <div class="actions"><input type="submit" name="commit" value="PM versenden"></div>
    </form>
  </div>
<section>