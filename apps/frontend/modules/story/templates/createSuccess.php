<?php use_stylesheet('carousel.css') ?>

<h1 class="page-title">Nachricht erstellen</h1>

<article class="hentry post h-entry page">
  <div class="body e-summary entry-summary">
    <form action="<?php echo url_for($story->getStoryType() === "Normal" ? "@story_create" : "@story_create_article");?>" id="new_form" class="ninjaForm <?php echo ("Normal" === $view ? "": "article" ); ?>" <?php if(true === $form->isMultipart()):?>enctype="multipart/form-data"<?php endif; ?> method="post">
      <fieldset>
        <?php
        $errorStack = $story->getErrorStack();
        if(count($errorStack) > 0): ?>
        <p class="error">Hallo, leider hat es einen Fehler beim hinzufügen deiner Nachricht gegeben.
          Wir wurden bereits darüber informiert und treten mit dir in Kontakt, wenn der Fehler behoben ist.</p>
        <?php endif; ?>
        <?php echo $form->render(); ?>
      </fieldset>
      <div class="actions">
        <input type="submit" name="formAction[save]" value="Erstellen" id="Save" class="button" style="font-size:1.4em;" />
      </div>
    </form>
  </div>
</article>

<script type="text/javascript">
  var $j = jQuery.noConflict();
  $j(document).ready(
    function() {
      if($j("#external_url").val() != '')
        setTimeout("setFocusAction()", 1000);
    }
  );

  function setFocusAction() {
    $j("#external_url").trigger("focus");
    setTimeout("setBlurAction()", 1000);
  }

  function setBlurAction() {
    $j("#external_url").trigger("blur");
  }
</script>


<?php slot('sidebar') ?>
  <p class="note"> Ich möchte <?php echo ("Normal" === $view ? "einen eigenen Artikel ohne Link": "eine eigene Nachricht"); ?> einstellen.
  <?php echo
      link_to(
        "Normal" === $view ? "Eigenen Artikel erstellen": "Nachricht erstellen",
        "Normal" === $view ? '@story_create_article': '@story_create',
        'id=changetype class=button'
      );
    ?>
  </p>
<?php end_slot();?>
