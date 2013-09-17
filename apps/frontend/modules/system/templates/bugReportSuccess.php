<h1>Einen Fehler melden</h1>

<p class="alert alert-info note">Bitte gib kurz an, was Du gemacht hast und was passiert ist.</p>
<form action="<?php echo url_for("@bug_report");?>" method="post">
  <?php echo $form->render();?>
  <div class="actions">
    <input type="submit" value="Fehler melden!" />
  </div>
</form>

<h2>Folgende Dinge werden uns auch mitgeteilt:</h2>
<ul>
 <li><strong>Referer:</strong> <?php echo $details["referer"]; ?></li>
 <li><strong>Browser:</strong> <?php echo $details["user_agent"]; ?></li>
</ul>