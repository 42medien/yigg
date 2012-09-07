<?php if(false !== $video):?>
  <?php echo $sf_data->get('video', ESC_RAW)->getSizedCode($width,$height); ?>
<?php else:?>
  <div class="sidead" style="width: auto !important; display: block !important; text-align: center !important;">
    <!-- rectangle2 -->
    <div id='div-gpt-ad-1347010073546-1' style='width:300px; height:250px;'>
    <script type='text/javascript'>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1347010073546-1'); });
    </script>
    </div>
  </div> 
<?php endif;?>