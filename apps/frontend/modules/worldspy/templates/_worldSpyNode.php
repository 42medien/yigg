<div id="Event<?php  echo str_replace('.','-', $node->epoch_time ); ?>" class="spy node"<?php if($hidden):?> style="display:none;"<?php endif; ?>><!-- Node --><span class="link worldspy"><?php
    echo mb_substr($node->getFeedName(), 0, 20, "UTF-8")?></span><div class="icon worldspy"><?php echo img_tag( 'worldspy_story.png', array('alt'=>'Weltspion story','height'=>'16','width'=>'16')); ?></div><strong class="title worldspy"><?php
  echo link_to(
    mb_substr( strip_tags($node->title), 0, 120, "UTF-8"),
    '@worldspy_node?node_id=' . $node->id, array("class" => "storylink", "title" => strip_tags($node->title) )
  ); ?></strong></div>