<div id="LoginContainer">
  <?php echo link_to(
    img_tag(
      "no-username-".(rand(0,1)).".jpg",
     array(
       "alt" => "Noch keinen Benutzernamen?",
       "width"=> 300
     )),
    "@user_register",
    array(
      "title" => "Jetzt registrieren und kostenlos von vielen Funktionen und Einstellungen profitieren.",
      "class" => "signup-image"
     )
   );
 ?>
  
  <form id="LoginForm"
        class="ninjaForm<?php if(true === $sf_request->isAjaxRequest() || isset($forceAjax)): ?> ninjaAjaxSubmit LoginContainer<?php endif;?>"
        action="<?php echo url_for("user_login") ?>" method="post" >
    <fieldset>
      <h2>Einloggen:</h2>
      <?php echo $form->render();?>
      <div class="actions">          
        <input type="submit" name="commit" value="Anmelden" class="button" />          
      </div>
    </fieldset>
  </form>
</div>