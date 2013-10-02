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
<p class="alert alert-danger error">Es wurden keine Nachrichten gefunden</p>
<?php endif; ?>

<?php slot("sidebar")?>

  <?php if($following->count() > 0):?>
  <section id="widget-friends-online" class="widget">
    <h2>Freunde Online  <sup class='badge'><?php echo count($following)?></sup></h2>
    <?php include_partial("user/avatarList", array("users" => $following));?>
  </section>
  <?php endif;?>

  <?php if(count($user->Domains) > 0):?>
  <section id="widget-favorite-news" class="widget">
    <h2>Lieblings-Nachrichtenquellen</h2>
    <?php foreach($user->Domains as $domain):?>
      <?php echo now_later_button($domain->hostname,
                            "@domain_show?id=".$domain->id,
                            "@domain_subscribe?id=".$domain->id, array("class" => "subscribed"));?>
    <?php endforeach;?>
  </section>
  <?php endif;?>

  <?php if(count($user->Tags) > 0):?>
  <section id="widget-interests" class="widget">
    <h2 class="help_icon">Themen, die mich interessieren</h2>
    <?php foreach($user->Tags as $tag) { ?>
      <?php echo now_later_button(
        $tag->name,
        url_for("@tag?tags={$tag->name}"),
        "@tag_subscribe?tag_id={$tag->id}",
        array("class" => "subscribed"));?>
    <?php } ?>
  </section>
  <?php endif;?>
<?php end_slot()?>