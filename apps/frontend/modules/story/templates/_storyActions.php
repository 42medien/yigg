<?php if(true === $sf_user->hasUser() && true === $sf_user->isModerator()): ?>
    <ul id="admin-actions-<?php echo $story['id'];?>" style="display:none;" class="admin-actions ico">
      <li><?php echo link_to_story(
           "löschen",
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
         "Blacklist domain",
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
           "Benutzer löschen",
           '@user_delete?username='.$story->Author->username,
           array(
              "rel" => "external",
             "title" => 'Benutzer löschen',
             "rel" => "nofollow",
             "class" => "logout"
           )
          );?></li>
    </ul>
  <?php endif;?>

  <ul id="story-actions_<?php echo $story['id']; ?>" class="story-actions ico">
    <?php if(true === $story->canEdit($sf_user)): ?>
      <li><?php echo link_to_story(
       "Nachricht {$story['title']} bearbeiten",
       $story,
       array(
         "view" => "edit",
         "title" => 'Nachricht Bearbeiten: '  . $story->title,
         "rel" => "nofollow",
         "class" => "edit"
       )
      );
      ?></li>
    <?php endif; ?>

    <?php if(true === $sf_user->hasUser() && true === $sf_user->isModerator()): ?>
     <li><?php echo content_tag("a",
       "Administrate {$story['title']}",
       array(
         "href"=> "javascript:void(0);",
         "title" => 'Administrate: '  . $story->title,
         "rel" => "external",
         "onclick" => "$('admin-actions-{$story['id']}').toggle();",
         "class" => "admin",
         "anchor" => true,
       )
      );
     ?></li>
    <?php endif; ?>

  </ul>