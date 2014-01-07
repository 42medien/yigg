<span class="vcard author"><?php
  $avatar = $user->getAvatarTiny();
  echo link_to(
      $avatar ? img_tag("../". $avatar->getFileReference() ) : img_tag("icon.gif", "Kein Bild vorhanden" ,'alt="" width=14 height=14'),
      '@user_public_profile?view=live-stream&username='. urlencode($user['username']),
      array( 'title' => sprintf('Profil von %s besuchen', $user['username']) )
    );
  ?> von
<?php include_partial('user/userAwardShort', array("user" => $user)); ?>
<?php  echo link_to(
    $user['username'],
    '@user_public_profile?view=live-stream&username='. urlencode($user['username']),
     array('class' => 'fn url', 'title' => sprintf('Profil von %s besuchen', $user['username'] ))
  );
?></span>