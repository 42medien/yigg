  <?php if(true === $sf_request->hasParameter("rss")): ?>
    <?php echo link_to(
      img_tag(
        "rss-1.gif",
        array(
          'width' => 45,
          'height' => 50,
          "alt"=>"atom-feed icon"
        )
      ),
      $sf_request->forceParams(
        array(
          "sf_format" => "atom"
        )
      ),
      array(
        'title' => 'Diese Seite als Atom-Feed abonnieren',
        "class" => "rss"
      )
     );?>
  <?php endif; ?>

<div class="userinfo">
  <?php if(true === $sf_user->hasUser()):?>
    <?php echo link_to(
      avatar_tag($sf_user->getUser()->Avatar, "noavatar-48-48.png", 48, 48),
      '@user_public_profile?username='.$sf_user->getUser()->username,
      array(
       "class"=> "avatar"
      )

    );?>
    <?php
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
    if(false === $sf_user->getUser()->hasAvatar())
    {
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
       <li class="pm"><?php echo link_to(
       "Pinnwand ({$sf_user->getUser()->getNotificationCount()})",
       "@notification_index",
         array(
           "title" => "Zu meiner Pinwand",
         )
       );?></li>
       <li class="logout"><?php echo link_to("Abmelden","@user_logout");?></li>
     </ul>
  <?php else: ?>

    <form action="<?php echo url_for('@user_login'); ?>" method="post">
    <fieldset>
     <?php
        $form = new FormUserLogin();
        $form->offsetUnset("remember");
        echo $form['username']->render();
        echo $form['password']->render();
        echo $form->renderHiddenFields();
      ?>
      <input type="submit" value="login" class="button" />
    </fieldset>
    </form>
    <?php echo link_to(
        "Noch kein Mitglied? Jetzt registieren!",
        "@user_register",
        array("title" => "Lege Dir einen neuen Account bei uns an.", "class" => "register")
    ); ?>
  <?php endif;?>
  <div class="clr"></div>
</div>