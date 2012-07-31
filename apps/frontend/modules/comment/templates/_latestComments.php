<h2 class="heading-right">Debatte</h2>
<ol class="avatar-list-style">
  <?php foreach ($comments as $comment): ?>
    <li>
      <?php
        $story = $comment->getParentObj();
        echo link_to(avatar_tag($comment->Author->Avatar, "icon.gif", 14,14), "@user_public_profile?username={$comment->Author->username}");
        echo link_to($story['title'], $story->getLink(ESC_RAW), array("title"=> "Lies die Kommentare zu {$story['title']}","class" => "title"));
      ?>
    </li>
  <?php endforeach; ?>
</ol>