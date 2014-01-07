<?php use_helper('Number') ?>
<h2>Mein YiGGSponsoring</h2>

<form id="SponsorForm" action="<?php echo url_for( $place->getOrderLink() );?>" method="post" enctype="multipart/form-data">
  <fieldset>
    <p><span class="error_message">Ihre Zahlung via PayPal konnte nicht durchgeführt werden. Bitte starten Sie den Zahlprozess erneut.</span></p>
    <p><span class="error_message">Error: <?php echo $paypal->getErrorString(); ?></span></p>

    <div class="hidden">
      <?php echo $form->render(); ?>
    </div>

    <div class="field">
     <?php echo img_tag(
      "../{$image->getThumbnailFile( $place->width, $place->height, $image->file_type)->getFileReference()}",
      array(
        "width" => $place->width,
        "height" => $place->height,
      )
     );?>
    </div>

    <div class="field">
      <?php echo $form['image_title']->renderLabel(); ?>
      <?php echo $form['image_title']->getValue(); ?>
    </div>

    <div class="field">
      <?php echo $form['sponsor_url']->renderLabel(); ?>
      <?php echo link_to($form['sponsor_url']->getValue(),$form['sponsor_url']->getValue(),array('target'=>'_blank' )); ?>
    </div>

    <div class="field">
      <?php echo $form['place_id']->renderLabel(); ?>
      <?php echo link_to( $place->description, $place->getPreviewLink( $image, $form['sponsor_url']->getValue() )  ,array('target'=>'_blank','absolute' => true)); ?> -
      <?php echo link_to( 'Preview', $place->getPreviewLink( $image, $form['sponsor_url']->getValue() )  ,array('target'=>'_blank','absolute' => true)); ?>
    </div>

    <div class="field">
      <label>Preis</label>
      <span>€ <?php echo format_currency($place->price);?> </span>
    </div>

    <div class="actions">
      <input type="submit" name="edit" value="Bearbeiten" id="submit" class="button" />
      <input type="submit" name="preview" value="Vorschau auf Seite" id="submit" class="button" onclick="doPreview()" />
      <input type="submit" name="order" value="Mit PayPal bezahlen" id="submit" class="button" />
    </div>
  </fieldset>
</form>