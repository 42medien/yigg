<li class="hentry post wspy" >
  <div class="screenshot">
        <?php echo link_to("Nachricht einstellen", '@worldspy_create_story?node_id=' . $entry->id ,
			      array(
			        'rel' => 'nofollow',
			        'title' => 'Diese Nachricht auf YiGG einstellen.',
              'class' => 'add_wspy_entry ninjaUpdater AddForm_' . $entry->id));?>
		<?php echo img_tag("http://stromboli.yigg.de/?url=".$entry->long_link, array("height" => 88, "width" => 120));?>
  </div>
  <h2 class="entry-title"><?php
		$title = strip_tags(substr( preg_replace("/\s+/"," ", $entry->title), 0, 128));
		echo link_to($title, (string) $entry->long_link , array("class" => "storylink", "title" => $title, "rel" => "nofollow", "rel"=> "external"));?></h2>
    <div class="body entry-content">
      <p>
        <span class="vcard author"><strong><?php echo $entry->getFeedName() ?></strong> - <?php include_partial("system/dateTime", array("time" => $entry->created )) ?> - </span>
        <?php echo strip_tags(substr($entry->getDescription(ESC_RAW),0,400)); ?>
      </p>
      <?php if($sf_user->hasUser() && $sf_user->isAdmin()):?>
        <?php echo button_to("Eintrag löschen", "@worldspy_delete_entry?id={$entry->id}");?>
      <?php endif;?>
    </div>
  <div id="AddForm_<?php echo $entry->id; ?>"><!--  --></div>
   <div class="clr bth"></div>
</li>