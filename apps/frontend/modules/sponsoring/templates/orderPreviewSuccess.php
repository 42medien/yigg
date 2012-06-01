<?php use_helper('JavascriptBase','Number') ?>
<?php if(true === $sf_user->hasFlash('notice')): ?>
  <p class="note"><?php echo $sf_user->getFlash('notice'); ?></p>
<?php endif; ?>

<h2>Sponsor werden (Vorschau)</h2>

<form id="SponsorForm" action="<?php echo url_for( $place->getOrderLink(ESC_RAW) );?>" method="post" enctype="multipart/form-data">
  <fieldset>

    <div class="hidden">
      <?php echo $form->render(); ?>
    </div>

    <div class="field">
      <?php echo avatar_tag($image, "sponsoring-bg.png", $place->width, $place->height);?>
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
      <span>€ <?php echo format_currency( ($form['weeks']->getValue() * $place->price) * $discounts[$form['weeks']->getValue()] ); ?> (<?php echo $form['weeks']->getValue(); ?> Wochen x € <?php echo format_currency(  $place->price  );?> inklusive <?php echo 100 - $discounts[$form['weeks']->getValue()] * 100; ?> % Rabatt)</span>
    </div>

    <div class="actions">
      <input type="submit" name="edit" value="Bearbeiten" id="submit" class="button" />
      <input type="submit" name="preview" value="Vorschau auf Seite" id="submit" class="button" onclick="doPreview()" />
      <input type="submit" name="order" value="Mit PayPal bezahlen" id="submit" class="button" />
    </div>
  </fieldset>
</form>

<?php
  echo javascript_tag('
  function doPreview()
  {
    window.open(" ' . $place->getPreviewLink( $image, $form['sponsor_url']->getValue(), ESC_RAW) . '");
  }
  ');
?>