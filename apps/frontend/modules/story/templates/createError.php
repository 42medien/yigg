<p class="alert alert-info note">Bevor Du aktiv Nachrichten einstellst, bitten wir Dich YiGG etwas besser kennen zu lernen.</p>

<h2>Was kannst Du tun?</h2>
<ul>
  <?php if($sf_user->getUser()->UserStats->yiggs_total <  sfConfig::get("app_user_minYiggs", 100)):?>
    <li><strong>Bewerte 100 Nachrichten</strong>, um ein Gefühl für YiGG zu bekommen.</li>
  <?php endif;?>
  <?php if($sf_user->getUser()->UserStats->friends_total < sfConfig::get("app_user_minFriends", 3)):?>
    <li><strong>Folge 3 anderen YiGGern</strong>, deren Nachrichten Du interessant findest.</li>
  <?php endif;?>
</ul>

<h2>Bald hast Du es geschafft. Es fehlen nur noch:</h2>
<ul>
  <li>Bewertungen: <?php echo $sf_user->getUser()->UserStats->yiggs_total;?> / 100</li>
  <li>Freunde: <?php echo $sf_user->getUser()->UserStats->friends_total;?> / 3 </li>
</ul>
<p class="alert alert-info note">Die Statistik wird regelmäßig, alle paar Stunden aktualisiert.</p>