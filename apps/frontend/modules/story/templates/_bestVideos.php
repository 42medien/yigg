<?php if(false !== $video):?>
  <?php echo $sf_data->get('video', ESC_RAW)->getSizedCode($width,$height); ?>
<?php else:?>
  <div class="sidead">      
    <!-- Yigg-Button-1L -->
    <div id='div-gpt-ad-1342004948184-0' style='width:150px; height:125px;'>
    <script type='text/javascript'>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1342004948184-0'); });
    </script>
    </div>

    <!-- Yigg-Button-1R -->
    <div id='div-gpt-ad-1342004948184-1' style='width:150px; height:125px;'>
    <script type='text/javascript'>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1342004948184-1'); });
    </script>
    </div>

    

</div>
<?php endif;?>