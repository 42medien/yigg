=================================================<br />
yigg.de - Rechnung Sponsoring <?php echo $deal->Sponsoring->id; ?><br />
=================================================<br />

Rechnungssteller<br />
YiGG GmbH<br />
Steinkirchner Strasse 31<br />
81475 München<br />
USt-IdNr.: DE254496480<br />

Rechnungsempfänger<br />
<?php echo $deal->buyer_last_name . ', ' . $Deal->buyer_first_name; ?><br />
<br />
<?php echo $deal->buyer_street1 . ' ' . $Deal->buyer_street2 ?><br />
<br />
<?php echo $deal->buyer_zip . ' ' . $Deal->buyer_city ?><br />
<br />
<br />
<br />
Sehr geehrte Damen und Herren,<br />
<br />
hiermit erlauben wir uns Ihnen eine Rechnung für folgende Posten zustellen:<br />
<pre>
-------------------------------------------------------
Pos. | Beschreibung                        | Preis    |
-------------------------------------------------------
1    | yigg Sponsoring                     |<?php echo $deal->getClear() ?> EUR|
     |                                     |          |
-------------------------------------------------------
zzgl. Mwst.                                | <?php echo number_format($deal->debit - $deal->getClear(),2) ?> EUR|
=======================================================
Gesamt zu bezahlen                         |<?php echo number_format($deal->debit, 2) ?> EUR|
=======================================================
</pre>
Wir haben Ihre Zahlung durch PayPal erhalten.<br />

Vielen Dank!<br />

yigg.de<br />

Mit freundlichen Grüssen<br />
Michael Reuter<br />
Geschäftsführer<br />
YiGG GmbH<br />