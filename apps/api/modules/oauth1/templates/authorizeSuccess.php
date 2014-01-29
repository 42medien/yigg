      <h5><?php echo __('HEADLINE_OAUTH_AUTHORISATION'); ?></h5>

      <p><?php echo __('TEXT_OAUTH_AUTHORISATION', array('%1' => $pCommunity->getName())); ?></p>

      <div class="errors">This website is registered with <strong>yigg</strong> to make authorization requests,
      but has not been configured to send requests securely. If you grant access but you did not initiate
      this request at <strong><?php echo $pCommunity->getName(); ?></strong>, it may be possible for other users of <strong><?php echo $pCommunity->getName(); ?></strong> to access
      your data. We recommend you deny access unless you are certain that you initiated this request
      directly with <strong><?php echo $pCommunity->getName(); ?></strong>.</div>

      <form action="<?php echo url_for('api/approve_authorization') ?>" method="POST">
        <div class="form_row clearfix">
          <div class="input-button input-distance"><input type="submit" value="<?php echo __("ACCEPT") ?>" name="accept" class="button green small" /></div>
          <?php echo link_to(__('CANCEL'), 'api/decline_authorization'); ?>
        </div>
      </form>