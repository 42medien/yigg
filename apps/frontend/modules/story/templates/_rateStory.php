<?php if( (false === $sf_request->isAjaxRequest()) && (isset($external) && $external == true)): ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title></title>
      <base href="http://<?php echo $sf_request->getHost() . $sf_request->getRelativeUrlRoot();  ?>/" />
      <style>
        * { padding:0; margin:0;}
      
        body{ font-family:Arial,Helvetica,sans-serif; font-size:62.5%; color:#3F352F; position:relative;   font-size: 11px;}
        a img, fieldset{ border:none}
        
        
        input, h4 {float: left; display: block; margin: 0;}
        input {height: 20px; padding: 0 10px; text-decoration: underline; border: 0; color: #551b8c; max-width: 54px;
        background: #d8d6d7; /* Old browsers */
        background: -moz-linear-gradient(top,  #d8d6d7 0%, #959595 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#d8d6d7), color-stop(100%,#959595)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top,  #d8d6d7 0%,#959595 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top,  #d8d6d7 0%,#959595 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top,  #d8d6d7 0%,#959595 100%); /* IE10+ */
        background: linear-gradient(to bottom,  #d8d6d7 0%,#959595 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d8d6d7', endColorstr='#959595',GradientType=0 ); /* IE6-9 */
        }
        h4 {color: #fff; background: #000; padding: 0 2px;}
       </style>
    </head>
  <body>
<?php endif; ?>
      <!-- RatingForm -->
      <?php $hasRated = ( null === $story->hasRated() ? $sf_user->hasRated($story['id']) : $story->hasRated() );
        if (false === $hasRated ):?>
        <form id="StoryRate<?php echo $story->id ?>" 
              class="rating-form ninjaForm ninjaAjaxSubmit" 
              action="<?php echo url_for_story($story, "rate"); ?>" 
              method="post">
              <fieldset>
<!--                <p>YiGGs</p>*/-->
                <input type="hidden" 
                       name="StoryRate[_csrf_token]" 
                       value="<?php echo $form[$form->getCSRFFieldName()]->getValue() ?>" 
                       id="StoryRate<?php echo $story->id; ?>__csrf_token" />
                <input name="StoryRate[ratingtoken]" 
                       id="StoryRate_ratingtoken<?php echo $story->id; ?>" 
                       type="hidden" 
                       value="<?php echo md5(rand(1,10000));?>" />
                <input type="submit" 
                       value="YiGG it!" 
                       class="button" 
                       id="Rate<?php echo $story->id?>" />
                <h4><label></label><?php echo $story->currentRating(); ?></h4>
              </fieldset></form>
      <?php else: ?>
       <div class="<?php if(false === $sf_request->isAjaxRequest() || true === isset($completeStory)):?>rating-form <?php endif;?>voted">   
<!--      <p>YiGGs</p>-->
          <span class="voted">YiGGed</span>
          <h4><label></label><?php echo $story->currentRating() ?></h4>
        </div>
      <?php endif; ?>
      
                        
<?php if(false === $sf_request->isAjaxRequest() && isset($external) && $external == true): ?>
    </body>
  </html>
<?php endif; ?>