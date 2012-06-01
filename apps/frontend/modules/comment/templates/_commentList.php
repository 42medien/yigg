<?php use_helper("Date");?>
<?php if(false === $sf_request->isAjaxRequest() || "show" === $sf_request->getAction()):?>
  <div id="<?php echo strtolower(get_class($sf_data->getRaw("obj"))); ?>-comments-<?php echo $obj['id']; ?>" class="clr">
<?php endif; ?>
<?php if(count($comments) > 0): ?>
  <ul class="comments-list <?php if(false === $inlist): ?>expanded<?php endif;?>">
    <?php foreach($comments as $k => $comment):  ?>
      <li id="comment-<?php echo $comment['id']; ?>" <?php if($k == $comments->count()- 1):?>class="last"<?php endif;?>>
        <?php if( false === $inlist && false === isset($hideactionitems)): ?>
          <?php if($comment['user_id'] == $sf_user->getUserId() || true === $sf_user->isModerator()): ?>
            <ul class="comment-actions ico">
              <li class="delete">
                <?php echo link_to("delete",
                    "@comment_actions?action=delete&object=".strtolower(get_class($sf_data->getRaw("obj")))."&id={$comment['id']}",
                    array("class" => "delete ninjaUpdater comment-{$comment['id']}", "rel" => "nofollow")); ?>
              </li>
            </ul>
          <?php endif;?>
        <?php endif; ?>

        <p>
          <span>
            <?php if(1 != $comment->user_id):?>
              <?php echo link_to(avatar_tag($comment->Author->Avatar, "noavatar-48-48.png", (true === $inlist ? 14 : 48), (true === $inlist ? 14 : 48)),
                           '@user_public_profile?view=live-stream&username='. urlencode($comment['Author']['username']),
                           array("title" => "Profil von {$comment['Author']['username']} besuchen", "class" => "comment-avatar"));?>
              von <?php echo link_to($comment['Author']['username'], '@user_public_profile?view=live-stream&username='. urlencode($comment['Author']['username']));?>
            <?php else:?>
              <?php echo img_tag("http://www.gravatar.com/avatar/".md5($comment->email)."?s=".(true === $inlist ? 14 : 48), array("class" => "comment-avatar")); ?>
              von <?php echo $comment->name;?>
            <?php endif;?>
            -
            <?php if(false === $inlist):?>
              <?php echo content_tag(
                  "abbr",
                  format_date(strtotime($comment['created_at']),"g","de",'utf-8'),
                  array("title" => date(DATE_ATOM, strtotime($comment['created_at'])),"class" => "updated published"));?>
            <?php endif;?>
          </span>
          <?php echo $comment->getPresentationDescription(ESC_RAW);?>
        </p>
       <span id="actions-<?php echo $comment['id']; ?>"></span>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<?php if(false === $inlist && $sf_user->hasUser()):?>
  <?php include_partial("comment/formComments",array( "form"=> $form, "obj"=> $obj)); ?>
<?php endif;?>
<?php if(false === $sf_request->isAjaxRequest() || "show" === $sf_request->getAction()):?>
  </div>
<?php endif; ?>