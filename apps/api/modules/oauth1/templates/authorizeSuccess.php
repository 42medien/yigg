      <h5>Authorize</h5>

      <div class="errors">This website is registered with <strong>yigg</strong> to make authorization requests,
      but has not been configured to send requests securely. If you grant access but you did not initiate
      this request at <strong><?php echo $consumer->getName(); ?></strong>, it may be possible for other users of <strong><?php echo $consumer->getName(); ?></strong> to access
      your data. We recommend you deny access unless you are certain that you initiated this request
      directly with <strong><?php echo $consumer->getName(); ?></strong>.</div>

      <form action="<?php echo url_for('@oauth_1_approve') ?>" method="POST">
        <div class="form_row clearfix">
          <div class="input-button input-distance"><input type="submit" value="Accept" name="accept" class="button green small" /></div>
          <?php echo link_to('Cancel', '@oauth_1_decline'); ?>
        </div>
      </form>