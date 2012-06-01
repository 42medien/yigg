<?php include_component('comment', 'commentList',
    array(
      'obj' => $obj,
      'inlist' => (isset($inlist)) ? $inlist : false,
    )
  );
?>