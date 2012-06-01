<?php include_partial("system/systemMessages")?>

<h2>Mit "Mein YiGG" erhälst Du einen persönlichen Blick auf YiGG</h2>
<p class="error">Für diese Ansicht haben wir keine Nachrichten gefunden.</p>

<h3>Was kann ich tun?</h3>
<p class="note">Sobald du anderen YiGGern folgst, kannst Du hier ihre Nachrichten mitverfolgen.</p>

<?php slot("sidebar")?>
  <?php echo include_component("sponsoring","sponsoring", array( 'place_id' => 27 ) ); ?>
  <div id="friendsWidget"><?php include_partial("userWidgetFollowing", array("following" => $following, "view" => "online"));?></div>
  <?php include_partial("userWidgetStats", array("user" => $user));?>
<?end_slot()?>