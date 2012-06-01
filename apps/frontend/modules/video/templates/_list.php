<div class="sf_admin_list">
  <?php if (!$pager->count()): ?>
    <p>'No result</p>
  <?php else: ?>
    <?php foreach ($pager as $i => $video_ad): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
      <?php include_partial('video/list_td_tabular', array('video_ad' => $video_ad)) ?>
      <?php if(fmod(++$i, 2)):?>
        <div class="clr spacer"></div>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
