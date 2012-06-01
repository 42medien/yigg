    <h3><?php echo link_to("VideoVote", 'video/index' ); ?></h3>
    <ul>
      <li  class="sub-nav">
        <ul>
           <li<?php if($sf_request->getAction() == "index"):?> class="selected" <?php endif;?>><?php echo link_to("Neueste Videos","video/index"); ?></li>
           <li<?php if($sf_request->getAction() == "beste-video"):?> class="selected" <?php endif;?>><?php echo link_to("Beste Videos","video/beste-video"); ?></li>
        </ul>
      </li>
      <?php if( array_key_exists( $sf_request->getAction() , array_flip( array("newStories")))): ?>
        <li <?php if($sf_request->getParameter("filter",false) === "meine-eigenen-nachrichten"): ?>class="sub-nav"<?php endif;?>><?php echo link_to("meine eigenen Nachrichten", $sf_request->forceParams(array("filter" => "meine-eigenen-nachrichten")), array("title" => "Deine eigenen Nachrichten der letzten 24 Stunden.")); ?> </li>
    <?php endif;?>
   </ul>
  <?php include_partial("system/navigationlinks", array( "hide" => "Video","sf_cache_key" => "nav_video" )); ?>