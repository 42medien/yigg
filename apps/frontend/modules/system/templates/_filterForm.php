<form action="<?php echo url_for($filter->getLink());?>" method="post" id="pageFilter" class="Filter">
  <div class="actions">
    <input type="submit" value="Sortieren" class="button" id="SaveChanges" />
  </div>

  <fieldset>
    <?php echo $filter->render(); ?>
  </fieldset>
</form>