<?php if($place !== false && $place !== null ): ?>
  <?php echo include_component("sponsoring","sponsoring", array( 'place_id' => $place ) ); ?>
<?php endif; ?>