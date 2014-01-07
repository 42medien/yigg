<?php if($sf_user->hasUser()):?>
	<h1>Deinen Benutzeraccount bei YiGG löschen.</h1>
	<p class="alert alert-danger error">Vorsicht! Gelöschte Accounts können nicht wiederhergestellt werden!</p>

	<h2>Hilf uns besser zu werden.</h2>
	<p class="note">Warum gehst Du?</p>

	<form method="post" action="<?php echo url_for("@user_settings?view=delete");?>">
	  <?php echo $delete_form->render();?>
	  <input class="button" type="submit" value="Account löschen!" />
	</form>
<?php else: ?>
  <h1>Schade, dass Du gehst.</h1>
<?php endif; ?>