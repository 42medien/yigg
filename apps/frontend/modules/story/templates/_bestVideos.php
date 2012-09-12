<?php if(false !== $video):?>
  <?php echo $sf_data->get('video', ESC_RAW)->getSizedCode($width,$height); ?>
<?php else:?>
  <div class="sidead" style="width: auto !important; display: block !important; text-align: center !important; border-bottom: 1px solid #E3E3E3;">
    <!-- rectangle -->
    <div id='div-gpt-ad-1346766539123-0' style='width:300px; height:250px;'>
    <script type='text/javascript'>
    googletag.display('div-gpt-ad-1346766539123-0');
    </script>
    </div>
  </div> 
<?php endif;?>