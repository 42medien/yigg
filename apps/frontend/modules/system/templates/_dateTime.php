<abbr class="updated published" title="<?php echo date(DATE_ATOM, strtotime($time))?>">
<?php switch($time)
{
  case strtotime($time) > strtotime("-1 day"):?>
    vor <?php echo yiggTools::timeDiff( $time ); ?>
  <?php break;

  case strtotime($time) > strtotime("-2 day"):?>
   gestern <?php echo date("H:i", strtotime($time)); ?>
  <?php break;

  case (strtotime($time) < strtotime("-2 day") && strtotime($time) > strtotime("-1 week")): ?>
  <?php use_helper("Date"); ?>
  <?php echo format_date(strtotime($time),"EEEE","de",'utf-8');?>, <?php echo date("H:i", strtotime($time)); ?>
  <?php break;

  case strtotime($time) < strtotime("-1 week"):?>
    <?php echo date("d.m.Y", strtotime($time)); ?>, <?php echo date("H:i", strtotime($time)); ?>
  <?php break;
}
?>
</abbr>