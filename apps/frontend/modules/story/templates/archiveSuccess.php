<nav class="filter-list">Sortieren nach:
  <form method="get" action="<?php echo url_for("@story_archive_alternate"); ?>" id="archive-form">
    <select name="year">
      <?php for ($i = date("Y",time()); $i >= 2006; $i--) { ?>
        <option<?php if ($i == $year) { echo ' selected="selected"'; } ?>><?php echo $i; ?></option>
      <?php } ?>
    </select>
    <select name="month">
      <option value="">--</option>
      <?php for ($i = 12; $i >= 1; $i--) { ?>
        <option<?php if ($i == $month) { echo ' selected="selected"'; } ?>><?php echo $i; ?></option>
      <?php } ?>
    </select>
    <select name="day">
      <option value="">--</option>
      <?php for($i = 31; $i >= 1; $i--) { ?>
        <option<?php if ($i == $day) { echo ' selected="selected"'; } ?>><?php echo $i; ?></option>
      <?php } ?>
    </select>
    
    <input type="submit">
  </form>
</nav>

<?php if(isset($stories)&& count($stories) > 0): ?>
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
            'compacted' => true,
            'inlist' => true
          )
        );
      ?>
    </li>
    <?php endforeach; ?>
  </ol>
</div>
<?php endif;?>