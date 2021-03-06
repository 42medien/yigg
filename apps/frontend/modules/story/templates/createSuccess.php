<?php use_stylesheet('carousel.css') ?>

<?php slot("page-title")?>
  <?php if ("Normal" === $view) { ?>
    Nachricht erstellen
  <?php } else { ?>
    Eigenen Artikel erstellen
  <?php } ?>
<?php end_slot()?>

<form action="<?php echo url_for($story->getStoryType() === "Normal" ? "@story_create" : "@story_create_article");?>" id="new_form" class="ninjaForm <?php echo ("Normal" === $view ? "": "article" ); ?>" <?php if(true === $form->isMultipart()):?>enctype="multipart/form-data"<?php endif; ?> method="post">
  <fieldset>
    <?php
    $errorStack = $story->getErrorStack();
    if(count($errorStack) > 0): ?>
    <p class="alert alert-danger error">Hallo, leider hat es einen Fehler beim hinzufügen deiner Nachricht gegeben.
      Wir wurden bereits darüber informiert und treten mit dir in Kontakt, wenn der Fehler behoben ist.</p>
    <?php endif; ?>
    <?php echo $form->render(); ?>
  </fieldset>
  <div class="actions">
    <input type="submit" name="formAction[save]" value="Erstellen" id="Save" class="button" />
  </div>
</form>

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

<?php slot('page-sponsoring') ?>
<!-- yigg_erstellen -->
<div id='div-gpt-ad-1384179239715-0' style='width:728px; height:90px;'>
  <script type='text/javascript'>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1384179239715-0'); });
  </script>
</div>
<?php end_slot();?>

<?php slot('sidebar') ?>
  <section id="widget-create-article" class="widget">
    <p class="alert alert-info note"> Ich möchte <?php echo ("Normal" === $view ? "einen eigenen Artikel ohne Link": "eine eigene Nachricht"); ?> einstellen.</p>
    <p>
      <?php echo
        link_to(
          "Normal" === $view ? "Eigenen Artikel erstellen": "Nachricht erstellen",
          "Normal" === $view ? '@story_create_article': '@story_create',
          'id=changetype class=button'
        );
      ?>
    </p>
  </section>
<?php end_slot();?>
