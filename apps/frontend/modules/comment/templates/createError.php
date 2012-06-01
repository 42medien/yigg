<?php if(false === $sf_request->isAjaxRequest()): ?>
  <div class="twoThree">
    <div class="twoThree-left">
<?php endif; ?>
    <?php include_partial('comment/commentList',
        array(
          'comments' => $comments,
          'obj' => $obj,
          'form' => $form,
          'inlist' => (isset($inlist)) ? $inlist : false,
        )
      );
    ?>
<?php if(false === $sf_request->isAjaxRequest()): ?>
    </div>
  </div>
<?php endif; ?>