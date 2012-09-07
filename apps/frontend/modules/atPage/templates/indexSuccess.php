<ul class="filters">
  <li <?php if($sf_request->getAction() === "index"): ?>class="selected"<?php endif;?>>
    <?php echo link_to(sprintf("Pinnwand (%s)",$sf_user->getUser()->getNotificationCount()),
          "@notification_index",array( "title" => "Lies deine privaten Nachrichten")); ?></li>

  <li <?php if($sf_request->getParameter("view",false) === "all"): ?>class="selected"<?php endif;?>>
    <?php echo link_to(sprintf("Benachrichtigungen (%s)",$sf_user->getUser()->getNotificationCount($sf_user->getUser()->id,array("New", "NewAt"))),
          "@notification_views?view=all&username={$sf_user->getUser()->username}"); ?></li>

  <li <?php if($sf_request->getParameter("view",false) === "replies"): ?>class="selected"<?php endif;?>>
  <?php echo link_to(sprintf("PM Eingang (%s)",$sf_user->getUser()->getPmCount()),
       "@notification_views?view=inbox&username={$sf_user->getUser()->username}"
      );?></li>
  <li <?php if($sf_request->getParameter("view",false) === "outbox"): ?>class="selected"<?php endif;?>>
  <?php echo link_to("PM Ausgang","@notification_views?view=outbox&username={$sf_user->getUser()->username}");?></li>
</ul>
<?php if(isset($notifications) AND count($notifications) > 0):?>
  <ol class="notification-list">
    <?php foreach($notifications as $notification): ?>
      <?php $object = $notification->getReferencedObject(); ?>
      <?php if(false !== $object && null !== $object || $notification->ref_object_type == "NotificationMessage"): ?>
        <li id="notification_<?php echo $notification->id?>" class="<?php echo $notification->isRead() ? 'read' : 'new'; ?> clr">
          <?php include_partial(
            "notification" . $notification->ref_object_type ,
            array(
              "notification" => $notification,
              "obj" => $notification->ref_object_type == "NotificationMessage" ? $notification : $object
            )
          ); ?>
        </li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ol>
    <?php echo $pager->display(); ?>
<?php else: ?>
   <!-- <p class="note">Keine neuen Benachrichtigungen.</p> -->
<?php endif; ?>

<?php if($sf_user->getUser()->getNotificationCount() > 0):?>
    <p class="note"><?php printf("Noch %s weitere Benachrichtigungen im Ordner Benachrichtigungen.", $sf_user->getUser()->getNotificationCount())?></p>
<?php endif;?>

<?php slot("sidebar")?>
  <?php $following =  UserFollowingTable::getOnlineFollowedUsers($sf_user->getUserId()); ?>
  <h3 class="help_icon">Freunde Online (<?php echo count($following)?>)</h3>
  <?php include_partial("user/avatarList", array("users" => $following));?>

  <h3 class="help_icon">Private Mitteilung schreiben</h3>
  <form <?php if(isset($reply_pm) AND $reply_pm): ?>id="pm_<?php echo $reply_pm->id; ?>_reply"<?php endif; ?> method="post" action="<?php echo url_for( "@notification_index"); ?>" class="post-box ninjaForm ninjaAjaxSubmit <?php if(isset($reply_pm) AND $reply_pm): ?>pm_<?php echo $reply_pm->id; ?>_reply<?php endif?>">
    <?php if( true === $sf_request->isMethod("post")):?>
       <?php if(true === $sf_request->isAjaxRequest() && true === $form->isValid()): ?>
        <p class="success">Deine PM wurde erfolgreich versendet!</p>
       <?php elseif((true === $sf_request->isAjaxRequest() && false === $form->isValid() || true === $sf_request->isAjaxRequest() && isset($this->recipients) && count($this->recipients) < 1)): ?>
        <p class="note">Füge unten einen Text + @Empfänger ein und schon wird eine PM versendet.</p>
      <?php endif; ?>
    <?php endif; ?>
    <?php echo $form->render(); ?>
    <div class="actions">
      <input type="submit" name="commit" value="PM versenden">
    </div>
  </form>
  <?php include_component("comment","latestComments");?>
  <?php include_component("story","latestStoriesWidget",array("sf_cache_key"=> "last_stories")); ?>
<?php end_slot();?>