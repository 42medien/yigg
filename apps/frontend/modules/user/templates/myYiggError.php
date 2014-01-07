<?php include_partial("system/systemMessages")?>

<h2>Mit "Mein YiGG" erhälst Du einen persönlichen Blick auf YiGG</h2>
<p class="alert alert-danger error">Für diese Ansicht haben wir keine Nachrichten gefunden.</p>

<h3>Was kann ich tun?</h3>
<p class="alert alert-info note">Sobald du anderen YiGGern folgst, kannst Du hier ihre Nachrichten mitverfolgen.</p>

<?php slot("sidebar") ?>
  <?php include_partial("userWidgetStats", array("user" => $user));?>
<?php end_slot() ?>