<?php if(false !== $video):?>
  <?php echo $sf_data->get('video', ESC_RAW)->getSizedCode($width,$height); ?>
<?php else:?>
  <div class="sidead">
<script type="text/javascript"><!--
google_ad_client = "pub-1406192967534280";
/* 336x280, Erstellt 24.05.11 */
google_ad_slot = "3232167360";
google_ad_width = 336;
google_ad_height = 280;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
  </div>
<?php endif;?>