<?php use_helper("Date"); ?>
<h2>Sponsoring bearbeiten</h2>
<p class="alert alert-info note">Fragen? Schreibe uns eine <?php echo mail_to("info@yigg.de", "E-Mail!");?></p>

<form method="post" enctype="multipart/form-data" action="<?php url_for("@sponsoring_edit?id={$sponsoring->id}")?>">
  <?php echo $form->render();?>
  <input type="submit" value="Sponsoring bearbeiten" />
</form>

<?php slot("sidebar") ?>
  <p class="alert alert-info note"><?php echo link_to('Buche ein weiteres Sponsoring!', '@sponsoring_order?id=1') ?> </p>
<?php end_slot()?>