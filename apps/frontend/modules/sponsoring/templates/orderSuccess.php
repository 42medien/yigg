<?php  use_helper('JavascriptBase','Number');?>
<h2>Sponsor werden</h2>

<form id="FormSponsoring" action="<?php echo url_for( $place->getOrderLink()) ;?>" method="post" class="ninjaForm" enctype="multipart/form-data">

    <fieldset>
        <?php if( $sf_user->hasFlash('Sponsoring:error') ):?>
        <span id="alert alert-danger error" class="error_message"><?php echo $sf_user->getFlash('Sponsoring:error'); ?></span>
        <?php endif; ?>

        <?php if( $sf_user->hasFlash('Sponsoring:notice') ):?>
        <span id="alert alert-info note"><?php echo $sf_user->getFlash('Sponsoring:error'); ?></span>
        <?php endif; ?>

        <?php if( isset($invitation) ):?>
        <span id="alert alert-info note">Willkommen, <?php echo $invitation->name;?> Deine Einladung von <?php echo $invitation->User->username; ?>  wurde geladen.</span>
        <?php endif; ?>

        <span id="alert alert-info note">Bildformate werden automatisch angepasst</span>

        <?php if( isset($image) && $image !== false): ?>
        <div class="field sponsoring image">
            <?php echo avatar_tag($image, "sponsoring-bg.png", $place->width, $place->height);?>
        </div>
        <?php endif; ?>

        <?php echo $form->render(); ?>

        <?php /*?>
    <div id="sponsoring_place_price" class="field" >
      <label>Preis:</label>
      <div id="sponsoring_place_price_id">
         €
        <?php foreach($places as $sPlace): ?>
          <span id="price_<?php echo $sPlace->id; ?>" style="display:none;"><?php echo format_currency($sPlace->price); ?></span>
        <?php endforeach; ?>

        <span id="total"></span>
        (<span id="weeks"></span> Wochen x € <span id="total2"></span> inklusive <span id="discount"></span> % Rabatt)
      </div>
    </div>
      <?php
 */
        $max_id = SponsoringPlace::getLastId();

        $multipliers = '';
        foreach( sfConfig::get("app_sponsoring_discount") as $k => $v )
        {
            $multipliers .= "multipliers[$k] = $v; \n";
        }

        echo javascript_tag(
            '
            function updatePrice()
            {
            /*
              var multipliers = new Array();
              ' . $multipliers .'

              try {
                document.getElementById("notice").style.display = "none";
              } catch (e)
              {
              }

              var el = document.getElementById("FormSponsoring_place_id").value;
              var weeks = document.getElementById("FormSponsoring_weeks").value;
              document.getElementById("weeks").innerHTML = weeks;

              var price = document.getElementById("price_" + el);
              document.getElementById("total2").innerHTML = price.innerHTML;

              price = parseFloat( parseFloat( price.innerHTML.replace(",",".") ) * weeks *  multipliers[ weeks ]).toFixed(2).replace(".",",");

              document.getElementById("discount").innerHTML = 100 - (multipliers[ weeks ] * 100 );

              document.getElementById("total").innerHTML = price;
              */
            }

            function showSponsoringPlace()
            {
              var el = document.getElementById("FormSponsoring_place_id").value;
              url = "'.url_for("@sponsoring_place?id=").'" + el;
              window.open(url);
            }

            //updatePrice();
          '
        );
        ?>

        <div class="actions" style="margin-left:150px;">
            <input type="submit" name="preview" value="Vorschau " id="submit" class="button" onclick="doPreview()" />
            <input type="submit" name="order" value="Mit PayPal bezahlen" id="submit" class="button" />
        </div>

    </fieldset>
</form>