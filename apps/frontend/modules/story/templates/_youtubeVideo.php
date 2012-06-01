<?php $videos = Doctrine_Query::create()
                ->from("CommentLink cl")
                ->innerJoin("cl.Comment c")
                ->innerJoin("c.Stories s")
                ->where("s.id = ?", $story->id)
                ->addWhere("cl.url RLIKE 'youtube|vimeo|collegehumor|onion|arte.tv'")
                ->execute(array(), Doctrine::HYDRATE_ARRAY); 
?>
<?php if(count($videos) > 0):?>
	<h3 class="heading-left">Videos zur Nachricht</h3>
	<?php foreach($videos as $video):?>
		<?php $video = yiggExternalVideoFactory::createFromUrl($video["url"]); ?>
        <?php if(empty($video)){continue;}?>
        <?php echo $video->render();?>
	<?php endforeach; ?>
<?php endif;?>
<div class="clr"></div>