<table id="statisticContent">
<tr><th>Benutzer</th><th>Storys</th><th>Kommentare</th><th>Freunde</th><th>Votes</th><th>Tags</th><th>Zeit</th></tr>
<?php foreach ($stats as $stat): ?>
  <?php printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>",
     $stat["total_user"],
     $stat["total_story"],
     $stat["total_comments"],
     $stat["total_friends"],
     $stat["total_votes"],
     $stat["total_tags"],
     $stat["calculated"]); ?>
<?php endforeach; ?>
</table>