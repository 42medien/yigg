<h1 class="page-title">
  <?php echo $domain->getHostname(); ?> Nachrichten
  <?php if( true === $sf_user->hasUser()): ?>
     <?php if( false === $sf_user->getUser()->followsDomain($domain->getRawValue()) ): ?>
       <?php echo button_to("Abonnieren", "@domain_subscribe?id={$domain->id}", array("class" => "follow"));?>
     <?php else:?>
       <?php echo button_to("Abonniert!", "@domain_subscribe?id={$domain->id}", array("class" => "unfollow"));?>
     <?php endif;?>
  <?php endif;?>
</h1>

<?php if(count($stories) > 0): ?>
<div class="story-list-cont">
  <ol id="stories" class="story-list hfeed ">
    <?php foreach($stories as $k => $story ) { ?>
    <li>
      <?php
        include_partial('story/story',
          array(
            'story' => $story,
            'summary' => true,
            'compacted' => true,
            'count' => $k,
            'total' => count($stories),
            'inlist' => true
          )
        );
      ?>
    </li>
    <?php } ?>
  </ol>
</div>
<?php else: ?>
  <p class="error">Es wurden keine Nachrichten gefunden</p>
<?php endif; ?>
<?php echo $pager->display(); ?>
