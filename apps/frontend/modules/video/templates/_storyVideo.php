<?php $video =  yiggExternalVideoFactory::createFromUrl($story->external_url);?>
<?php if(false === empty($video)):?>
    <div class="videoListAd">
        <div id="Toolbar">
              <h3><?php echo link_to($story['title'],$story->getLink(ESC_RAW)); ?></h3>
              <?php include_component( 'story', 'rateStory',  array('story' => $story, 'completeStory' => true)); ?>
        </div>
        <?php echo $video->setWidthHeight($width, $height)->render(); ?>
      </div>
<?php endif;?>
