<div id="archive">
  <?php if(false == $day):?>
    <p class="note">Wähle ein Datum für die besten Nachrichten dieses Tages.</p>
  <?php endif;?>

  <ol class="year">
    <?php for($i= 2006; $i <= date("Y",time()); $i++): ?>
      <li><?php echo link_to($i,"@story_archive?year=".$i, ($i == $year ? array('class'=>'selected'):array())); ?></li>
    <?php endfor;?>
  </ol>

  <?php if(false != $year ): ?>
    <ol>
      <?php for($i= 1; $i <= date("m",($year < date("Y",time()) ? strtotime("dec" . $year) : time() )) ; $i++): ?>
        <li><?php echo link_to($i,"@story_archive?month=". $i . "&year=". $year, ($i == $month ? array('class'=>'selected'):array())); ?></li>
      <?php endfor;?>
    </ol>
  <?php endif; ?>

  <?php if(false != $year && false != $month ): ?>
    <ol class="days">
      <?php for($i= 1; $i <= (
        strtotime($i . "." .$month. ".".$year) <= time() ?
          false !== strtotime($i . "." .$month. ".".$year) &&
          $i == date("d,",strtotime($i . "." .$month. ".".$year)):
        date("d",time())); $i++): ?><li><?php echo link_to(
        $i,
        "@story_archive?day=".$i."&month=".$month. "&year=". $year,
        ($i == $day ? array('class'=>'selected'):array()));
        ?></li>
      <?php endfor;?>
    </ol>
  <?php endif; ?>
  <div class="clr"><!--  --></div>
</div>
<?php if(isset($stories)&& count($stories) > 0): ?>
<div class="story-list-cont">
  <ol id="story-list" class="story-list hfeed">
    <?php foreach($stories as $k => $story ): ?>
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
    <?php endforeach; ?>
  </ol>
</div>
<?php elseif(false != $day): ?>
  <p class="error">Es wurden keine Nachrichten gefunden.</p>
<?php endif; ?>