<p class="success">Das Sponsor-Pakets wurde aktiviert!</p>
<p class="note">Sie erhalten die Bestätigung der Buchung bzw. die Rechnung per email.</p>

<br />

<table id="statisticContent" summary="Deine neueste YiGGSponsorings" cellspacing="0">
  <tr>
    <th>Sponsoring:</th>
    <td><?php echo $sponsoring->SponsoringPlace->getLink(); ?></td>
  </tr>
  <tr>
    <th>Dauer:</th>
    <td><?php echo date('d.m.y H:i', strtotime($sponsoring->getCreatedAt())) . ' - ' . $sponsoring->getExpirationDate(); ?></td>
  </tr>
  <tr>
    <th>Preis:</th>
    <td>€<?php echo number_format($sponsoring->Deal->debit, 2) ?> (<?php echo $sponsoring->getWeeks(); ?> <?php echo $sponsoring->getWeeks() == 1 ? "Woche" : "Wochen"  ?> x €<?php echo number_format($sponsoring->getWeeks(),2);?>)</td>
  </tr>
  <tr>
    <th>(enthaltene USt.)</th>
    <td>€<?php echo number_format($sponsoring->Deal->debit - $sponsoring->Deal->getClear(), 2) ; ?></td>
  </tr>
</table>

<p class="note">Die Liste Ihrer Sponsorings finden Sie <?php echo link_to('hier', '@sponsoring') ?></p>