<section id="widget-userinfo" class="h-card vcard hcard">
<?php echo link_to(
  avatar_tag($sf_user->getUser()->Avatar, "noavatar-48-48.png", 48, 48, array('class' => 'photo u-photo avatar')),
    '@user_public_profile?username='.$sf_user->getUser()->username,
    array(
      'class' => 'avatar'
    )
  );

  $messages = array(
    "Hi %s. Schön, dass Du da bist.",
    "%s - Marvin liebt dich!",
    "Heute schon geyiggt?",
    "Hey %s, folge uns auf <a rel='external' href='http://www.twitter.com/yigg'>Twitter</a>!",
    "Hey %s, die Antwort lautet <a href='http://de.wikipedia.org/wiki/42_(Antwort)' rel='external'>42. 42!</a>",
    'Hi %s. Marvin tut <a rel="external" href="http://www.youtube.com/watch?v=4YIj4rLYo0c&amp;feature=PlayList&amp;p=1C9682580BD23953&amp;index=19">sein bestes</a>.',
    '%s - es ist so heiß <a href="http://joecartoon.atom.com/cartoons/64-gerbil_in_a_microwave" rel="external">hier drin</a>!',
    '<a href="http://www.youtube.com/watch?v=yIN7cNnDJzc" rel="external">Marvin hat auch Gefühle</a>, %s.',
  );
  if(false === $sf_user->getUser()->hasAvatar()) {
    $messages[] = "%s - Du brauchst dringend <a href='http://yigg.de/profil/mein-profil'>einen Avatar</a>!";
  }
?>
  <h4><?php printf($messages[array_rand($messages)],
    link_to(
      $sf_user->getUser()->username,
      "@user_public_profile?view=livestream&username=".$sf_user->getUser()->username,
      array(
        "title" => "Betrachte Dein Profil.")
      )
    );
  ?></h4>
     <ul class="list-style">
       <li <?php if($sf_request->getModule() == 'user'): ?>class="selected"<?php endif;?>>
         <i class="icon-user"></i> <?php echo link_to('Mein YiGG',"@user_welcome",array("title"=>"Dein Profil ansehen")); ?>
       </li>
       <li><i class="icon-pushpin"></i> <?php echo link_to(
       "Pinnwand ({$sf_user->getUser()->getNotificationCount()})",
       "@notification_index",
         array(
           "title" => "Zu meiner Pinwand",
         )
       );?></li>
       <li><i class="logout icon-signout"></i> <?php echo link_to("Abmelden","@user_logout");?></li>
     </ul>
      <div class="clr"></div>
</section>

<?php if(true === $sf_request->hasParameter("rss")): ?>
<section id="widget-feed">
  <h2>Seite abonnieren</h2>
  <ul>
    <li>
<?php
  echo link_to(
    " Atom-Feed",
    $sf_request->forceParams(
      array(
        "sf_format" => "atom"
      )
    ),
    array(
      'title' => 'Diese Seite als Atom-Feed abonnieren',
      'class' => 'rss atom feed icon-rss-sign',
      'rel' => 'feed'
    )
  );
?>
    </li>
    <li>
      <input type="button" onclick="(function(){var z=document.createElement('script');z.src='https://www.subtome.com/load.js';document.body.appendChild(z);})()" value="Subscribe" />
    </li>
  </ul>
</section>
<?php endif; ?>