<div id="pmHolder_<?php echo $user->id;?>">
  <form method="post" action="<?php echo url_for('@send_notification');?>" id="PostboxCreateMessage" class="post-box ninjaForm ninjaAjaxSubmit pmHolder_<?php echo $user->id; ?>">
      <div>
          <textarea name="send_notification">fgfgfd</textarea>
      </div>
    <div class="actions"><input type="submit" name="commit" value="PM versenden"></div>
  </form>
</div>