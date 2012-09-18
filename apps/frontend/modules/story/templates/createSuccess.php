<?php use_stylesheet('carousel.css') ?>
<form action="<?php echo url_for($story->getStoryType() === "Normal" ? "@story_create" : "@story_create_article");?>" id="new_form" class="ninjaForm <?php echo ("Normal" === $view ? "": "article" ); ?>" <?php if(true === $form->isMultipart()):?>enctype="multipart/form-data"<?php endif; ?> method="post">
  <fieldset>
    <?php $errorStack = $story->getErrorStack();
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


<script type="text/javascript">
    //$('*').ready(function() {
        //if($("#external_url").val()){
            //$('#Description').focus().select();
            //$('#external_url').blur();            
            //setTimeout("prepareOnLoad()", 2000);
        //}
    //});
    
    
    var $j = jQuery.noConflict();
    $j(document).ready(
      function() {
        //alert($j("#Description").val()); //object is properly initialized
        //$j('#Description').focus().select();
        //$j('#external_url').focus();
        //$j('#Title').focus().click();
        alert($j('#external_url').val());
        //$j('#Title').focus().select();
        var external_url = $j('#external_url').val(); 
        var postData = "external_url=" + external_url + "&rand=" + Math.random();
        var baseUrl = '/neu/checkExternal_url?exturl=' + external_url;
        $.ajax({
            type: "POST",
            url: baseUrl,
            data: postData,
            beforeSend: function() {

            },
            success: function() {
               $j('#Title').focus().click();
            },
            error: function() {
               //alert("<?php //echo $this->translate('Dev: Ajax Failure!');?>"); // Development Stage
            }
         });
        
      }
    );
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
