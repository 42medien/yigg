<section id="widget-last-stories" class="widget">
  <h2>Letzte Nachrichten</h2>
  <ol class="avatar-list-style"><?php foreach ($stories as $story): ?><li><?php
   echo link_to(
    avatar_tag($story->Author->Avatar, "icon.gif", 14,14),
    "@user_public_profile?username={$story->Author->username}");
    echo link_to_story(
      " " .
      $story['title'],
      $story,
      array(
        "title"=> "Lese die Nachricht zu {$story['title']}",
        "class" => "title"
      )
    );
  ?></li><?php endforeach; ?></ol>
</section>