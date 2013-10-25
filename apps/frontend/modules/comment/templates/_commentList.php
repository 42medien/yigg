<?php
    use_helper("Date");
    $at_beginning = isset($at_beginning)?$at_beginning:false;
?>
<?php if(false === $sf_request->isAjaxRequest() || "show" === $sf_request->getAction()):?>
  <section id="<?php echo strtolower(get_class($sf_data->getRaw("obj"))); ?>-comments-<?php echo $obj['id']; ?>" class="clr comment-section">
<?php endif; ?>

<?php if ($at_beginning === true && false === $inlist && $sf_user->hasUser()):?>
  <?php include_partial("comment/formComments",array( "form"=> $form, "obj"=> $obj)); ?>
<?php else : ?>
  Willst du mitdiskutieren? Dann <?php echo link_to("erstelle dir einfach ein Profil", "@user_register"); ?> oder <?php echo link_to("logge dich ein", "@user_login"); ?>.
<?php endif; ?>

<?php if(count($comments) > 0): ?>
<ul class="comments-list <?php if(false === $inlist): ?>expanded<?php endif;?>">
<?php foreach($comments as $k => $comment):  ?>
  <li class="li-comment" id="li-comment-<?php echo $comment['id']; ?>" <?php if($k == $comments->count()- 1):?>class="last"<?php endif;?>>
    <article class="comment">
      <footer class="comment-meta">
        <span class="vcard hcard h-card">
          <?php echo link_to(avatar_tag($comment->Author->Avatar, "noavatar-48-48.png", (true === $inlist ? 14 : 48), (true === $inlist ? 14 : 48)),
                             '@user_public_profile?view=live-stream&username='. urlencode($comment['Author']['username']),
                             array("title" => "Profil von {$comment['Author']['username']} besuchen", "class" => "comment-avatar url fn p-name u-url"));?>
          <?php echo link_to($comment['Author']['username'], '@user_public_profile?view=live-stream&username='. urlencode($comment['Author']['username']), array("class" => " url fn p-name u-url"));?></span> meint
          <?php if(false === $inlist):?>
            <?php echo content_tag(
                  "time",
                  format_date(strtotime($comment['created_at']),"g","de",'utf-8'),
                  array("datetime" => date(DATE_ATOM, strtotime($comment['created_at'])),"class" => "updated published dt-updated dt-published"));?>
          <?php endif;?>
      </footer>
      <div class="entry-title entry-content entry-summary p-name e-content p-summary">
        <?php echo $comment->getPresentationDescription(ESC_RAW);?>
        <span id="actions-<?php echo $comment['id']; ?>"></span>
      </div>

      <?php if ( false === $inlist && false === isset($hideactionitems) ): ?>
        <?php if ($comment['user_id'] == $sf_user->getUserId() || true === $sf_user->isModerator()): ?>
            <p><?php echo link_to("<i class='icon-remove-sign'></i> Löschen?",
                               "@comment_actions?action=delete&object=".strtolower(get_class($sf_data->getRaw("obj")))."&id={$comment['id']}",
                               array("class" => "delete ninjaUpdater comment-{$comment['id']}", "rel" => "nofollow", "title" => "Kommentar löschen")); ?></p>
        <?php endif;?>
      <?php endif; ?>
    </article>
  </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if($at_beginning !== true && false === $inlist && $sf_user->hasUser()):?>
  <?php include_partial("comment/formComments",array( "form"=> $form, "obj"=> $obj)); ?>
<?php else : ?>
  Willst du mitdiskutieren? Dann <?php echo link_to("erstelle dir einfach ein Profil", "@user_register"); ?> oder <?php echo link_to("logge dich ein", "@user_login"); ?>.
<?php endif; ?>

<?php if(false === $sf_request->isAjaxRequest() || "show" === $sf_request->getAction()):?>
  </section>
<?php endif; ?>