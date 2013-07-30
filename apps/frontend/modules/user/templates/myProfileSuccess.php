<h1 class="page-title">Profil bearbeiten</h1>

<article class="post page">
  <div class="body">
    <?php include_partial("system/systemMessages")?>
    <form enctype="multipart/form-data" method="post" action="<?php echo url_for("@user_my_profile?view=profil"); ?>"  id="new_form" class="profile_form">
      <fieldset>
        <?php echo $profile_form->render() ?>
      </fieldset>
      <div class="actions">
        <input type="submit" value="Profil bearbeiten" class="button" id="save" /> 
      </div>
    </form>
  </div>
</article>