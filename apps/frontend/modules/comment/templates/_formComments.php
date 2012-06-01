<form action="<?php echo url_for("@comment_actions?action=create&object=".strtolower(get_class($sf_data->getRaw("obj")))."&id={$obj['id']}");?>"
      id="<?php echo substr( $schema = $form->getWidgetSchema()->getNameFormat(), 0, strpos($schema,'[')); ?>"
      class="comment<?php if(get_class($form) === "FormCommentSimple"):?>simple<?php endif;?> ninjaForm <?php if($sf_request->getAction() !== "edit"): ?>ninjaAjaxSubmit <?php echo strtolower(get_class($sf_data->getRaw("obj"))); ?>-comments-<?php echo $obj['id']; ?><?php endif;?>" method="post">
    <fieldset>
      <?php echo $form->render(); ?>
    </fieldset>
    <div class="actions">
      <input value="Kommentar hinzufügen" id=SaveChanges class="button" type="submit"/>
    </div>
</form>
