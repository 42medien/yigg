<?php include_partial("system/systemMessages")?>

<?php if(count($stories) > 0): ?>
<div class="story-list-cont">
  <ol id="stories" class="story-list hfeed">
    <?php foreach($stories as $k => $story ): ?>
    <li>
      <?php
        include_partial('story/story',
          array(
            'story' => $story,
            'summary' => true,
            'count' => $k,
            'total' => count($stories),
            'inlist' => true
          )
        );
      ?>
    </li>
    <?php endforeach; ?>
  </ol>
</div>
  <?php echo $pager->display(); ?>
<?php else: ?>
<p class="error">Es wurden keine Nachrichten gefunden</p>
<?php endif; ?>

<?php slot("sidebar")?>
  <?php if($following->count() > 0):?>
    <h3 class="help_icon">Freunde Online (<?php echo count($following)?>)</h3>
    <?php include_partial("user/avatarList", array("users" => $following));?>
  <?php endif;?>

  <?php if(count($user->Domains) > 0):?>
    <h3 class="help_icon">Lieblings-Nachrichtenquellen:
      <?php echo link_to(image_tag("silk-icons/help.png", array("alt" => "Hilfe")), "http://hilfe.yigg.de/doku.php?id=grundlagen", array("title" => "Zur Hilfe", "rel" => "external"));?>
    </h3>
    <?php foreach($user->Domains as $domain):?>
      <?php echo now_later_button($domain->hostname,
                            "@domain_show?id=".$domain->id,
                            "@domain_subscribe?id=".$domain->id, array("class" => "subscribed"));?>
    <?php endforeach;?>
    <div class="clr"></div>
  <?php endif;?>

  <?php if(count($user->Tags) > 0):?>
    <h3 class="help_icon">Themen, die mich interessieren:
      <?php echo link_to(image_tag("silk-icons/help.png", array("alt" => "Hilfe")), "http://hilfe.yigg.de/doku.php?id=grundlagen", array("title" => "Zur Hilfe", "rel" => "external"));?>
    </h3>
    <?php foreach($user->Tags as $tag) { ?>
      <?php echo now_later_button(
        $tag->name,
        url_for("@tag_show") . "?tags={$tag->name}",
        "@tag_subscribe?tag_id={$tag->id}",
        array("class" => "subscribed"));?>
    <?php } ?>
    <div class="clr"></div>
  <?php endif;?>
<?php end_slot()?>

<?php slot("sidebar_sponsoring")?>
  <?php echo include_component("sponsoring","sponsoring", array( 'place_id' => 27 ) ); ?>
<?php end_slot();?>