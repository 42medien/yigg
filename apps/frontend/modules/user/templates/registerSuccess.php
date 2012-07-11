<h1 style="margin-left:160px;">Registrierung</h1>
<?php if( isset($result) && true === $result): ?>
  <p class="success">In Kürze erhältst Du von uns eine email mit einem Bestätigungslink. Mit Klick auf diesen
  Link aktivierst Du Dein Konto und Du kannst in unserer Community loslegen.</p>
<?php elseif(isset($result) && false === $result): ?>
  <p class="error">Wir konnten Dir keinen Bestätigungslink schicken. Bitte überprüfe und korrigiere Deine email-Adresse <?php echo $user->email; ?>
   Dein Konto kann nur nach Anmeldung mit einer bestätigten email-Adresse freigeschaltet werden</p>
<?php else: ?>
<!-- START FACEBOOK JAVASCRIPT SDK -->
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId        : <?php echo sfConfig::get('app_facebook_app_id') ?>, // stored in app.yml for convenience
            status       : false, // check login status (we don't make use of this)
            cookie       : true, // enable cookies to allow the server to access the session
            xfbml        : true,  // parse XFBML
            oauth        : true
        });
    };

    // Load the SDK Asynchronously
    (function(d){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
    }(document));

    function onClickloginfb() {
        //Use the FB object's login method from the Facebook Javascript SDK to authenticate the user
        //If the user has already approved your app, she is simply logged in
        //If not, the app authentication dialog box is shown
        FB.login(function(response) {

            //If the user is succesfully authenticated, we execute some code to handle the freshly
            //logged in user, if not, we do nothing
            if (response.authResponse) {
                window.location = "/fb_login"
            }
        }, {scope:'email'});  //we just ask for the email permission
    }
</script>
<!-- END FACEBOOK JAVASCRIPT SDK -->

<button onclick="onClickloginfb();">Login</button>
  <form id="RegisterForm" class="ninjaForm" action="<?php echo url_for("@user_register"); ?>" method="post">
    <fieldset>



      <?php echo $form->render();?>
      <h3 style="margin-left:150px;">Nutzungsbedingungen</h3>
      <div class="field" style="margin-left:160px;">
        <ul>
          <li><?php echo link_to("Nutzungsbedingungen","@legal_pages?template=nutzungsbedingungen"); ?></li>
          <li><?php echo link_to("Datenschutzbestimmungen","@legal_pages?template=datenschutzrichtlinien"); ?></li>
        </ul>
      </div>
   </fieldset>
    <div class="actions">
      <input type="submit" name="commit" value="Abschicken" class="button" style="font-size:1.2em; margin-left:125px" />
    </div>
  </form>
<?php endif; ?>

<?php slot("sidebar")?>
  <?php echo link_to(
     img_tag(
       "404.gif",
      array(
        "alt" => "marvin!",
      )),
     "@user_register",
     array(
       "title" => "Jetzt registrieren und kostenlos von vielen Funktionen und Einstellungen profitieren."
      )
    );
  ?>
<?php end_slot()?>