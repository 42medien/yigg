<?php use_helper("Date"); ?>

<h2>Übersicht Deiner Sponsorings</h2>

<p class="alert alert-info note">Fragen? Schreibe uns eine <?php echo mail_to("info@yigg.de", "E-Mail!");?></p>

<?php if($sf_user->hasFlash("Sponsoring:notice")): ?>
  <p class="alert alert-success"><?php echo $sf_user->getFlash("Sponsoring:notice");?></p>
<?php endif; ?>

<table id="statisticContent" summary="Deine Aktivität auf YiGGSponsorings - Sponsoraktivitäten" cellspacing="0">
  <tr>
    <th>Vorschau</th>
    <th>Laufzeit</th>
    <th>Views</th>
    <th>Preis</th>
    <th>Aktiv?</th>
  </tr>

  <?php if( count($transactions) > 0 ):
      foreach($transactions as $transaction): ?>
        <tr>
          <td>
            <p></p></object><strong><?php echo $transaction->Sponsoring->image_title; ?></strong></p>
            <?php $image = $transaction->Sponsoring->Image; ?>
            <?php if($transaction->Sponsoring->isActive()):?>
              <?php echo
                link_to(avatar_tag($image, "sponsoring-bg.png", $transaction->Sponsoring->SponsoringPlace->width, $transaction->Sponsoring->SponsoringPlace->height),
                  $transaction->Sponsoring->SponsoringPlace->getIntern_url(ESC_RAW));?>
            <?php else:?>
              <?php echo avatar_tag($image, "sponsoring-bg.png", $transaction->Sponsoring->SponsoringPlace->width, $transaction->Sponsoring->SponsoringPlace->height);?>
            <?php endif;?>
            <br />
            <?php echo link_to( (strlen($transaction->Sponsoring->sponsor_url) > 20 ? (substr($transaction->Sponsoring->sponsor_url,0,20) . ".." ) : $transaction->Sponsoring->sponsor_url) , $transaction->Sponsoring->sponsor_url ); ?>
           <?php if($transaction->Sponsoring->isActive()):?>
              <br /><?php echo button_to("Bearbeiten", "@sponsoring_edit?id={$transaction->Sponsoring->id}");?>
            <?php endif;?>
          </td>
          <td><?php echo $transaction->Sponsoring->getFormattedCreatedAt() . ' - ' . $transaction->Sponsoring->getExpirationDate(true); ?></td>
          <td><?php echo $transaction->Sponsoring->clicks; ?></td>
          <td><?php echo number_format($transaction->debit, 2); ?>€</td>
          <td><?php echo $transaction->Sponsoring->isActive() ? link_to(img_tag("tick.gif"), $transaction->Sponsoring->SponsoringPlace->getLink(ESC_RAW), array('rel'=>'external')): img_tag("cancel.png");?> </td>
        </tr>
       <?php endforeach; ?>
  <?php else: ?>
    <tr>
      <td colspan="5">Es sind keine Sponsorings vorhanden.</td>
    </tr>
  <?php endif; ?>
</table>

<?php slot("sidebar")?>
<section class="widget">
  <p class="alert alert-info note"><?php echo link_to('Buche ein weiteres Sponsoring!', '@sponsoring_order?id=1') ?> </p>
</section>
<?php end_slot()?>
