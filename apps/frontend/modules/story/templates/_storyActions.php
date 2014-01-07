<?php if(true === $sf_user->hasUser() && true === $sf_user->isModerator()): ?>
  <section id="widget-admin-actions" class="widget">
    <h2>Administrator</h2>
    <ul id="admin-actions-<?php echo $story['id'];?>" class="admin-actions ico">
      <li><?php echo link_to_story(
           "<i class='icon-remove-sign'></i> löschen",
           $story,
           array(
             "view" => "delete",
             "rel" => "external",
             "title" => 'Nachricht Löschen: '  . $story->title,
             "rel" => "nofollow",
             "class" => "delete ninjaUpdater story_{$story['id']}"
           )
          );?></li>
       <li><?php echo link_to_story(
         "<i class='icon-ban-circle'></i> Blacklist domain",
         $story,
         array(
             "view" => "blacklist",
             "rel" => "external",
             "title" => "Blacklist domain {$story->getSourceHost()}",
             "rel" => "nofollow",
             "class" => "blacklist"
           )
          );?></li>
       <li><?php echo link_to(
           "<i class='icon-remove-sign'></i> Benutzer löschen",
           '@user_delete?username='.$story->Author->username,
           array(
              "rel" => "external",
             "title" => 'Benutzer löschen',
             "rel" => "nofollow",
             "class" => "delete"
           )
          );?></li>
    </ul>
  </section>
<?php endif;?>