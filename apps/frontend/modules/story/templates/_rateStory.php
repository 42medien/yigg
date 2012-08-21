<?php if( (false === $sf_request->isAjaxRequest()) && (isset($external) && $external == true)): ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title></title>
      <base href="http://<?php echo $sf_request->getHost() . $sf_request->getRelativeUrlRoot();  ?>/" />
      <style>
        * { padding:0; margin:0;}
      <?php if( !isset($flat) || isset($flat) && $flat === false ): ?>
        body{ font-family:Arial,Helvetica,sans-serif; font-size:62.5%; color:#3F352F; position:relative;   font-size: 11px;}
        a img, fieldset{ border:none}
       .rating-form{ float:left;  margin-top:4px; text-align:center; background:/*url(images/newbutton.png) no-repeat*/ transparent;  color:#fff;  overflow:hidden;  width:200px;  height:54px; }
/*     .rating-form h4{ font-size:1.9em !important;  overflow:hidden;  padding-right:6px;  text-align:right; margin:0 !important}*/
       .rating-form p{ margin:0;  padding-right:6px; text-align:right}
       .rating-form fieldset{ padding:0px}
       .rating-form input,  .voted span{ display:inline; background:none;  border:0; line-height:1.7em;  text-align:center;  color:#105B1B;  cursor:pointer}
       .voted{ background:/*url(images/newbuttonvoted.png) no-repeat 1px;*/ transparent;}
       .voted span, .rating-form input {display:block; background: #ffc851; border: 1px solid #c7c7c7; border-radius: 3px; float: left; line-height: 18px; padding: 0 2px;}
       .rating-form input, .voted span, .voted span a {text-decoration: none; color: #f5f5f5; text-shadow: 0 1px #DFAB3C;}
       .voted h4 label {-moz-transform: rotate(-45deg); background: #FFFFFF; border-color: #CCCCCC transparent transparent #CCCCCC; border-style: solid; border-width: 1px; display: block; height: 3px; left: -3px; position: absolute; top: 6px; width: 3px;}
       .voted h4 {color: #666666; float: left; font-weight: normal; text-align: center; margin-left: 5px; padding: 2px 3px; position: relative; background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #CCCCCC; border-radius: 3px 3px 3px 3px;}
      <?php else: ?>
        body{ font-family:Arial,Helvetica,sans-serif; font-size:62.5%; color:#3F352F; position:relative;   font-size: 11px;}
        a img, fieldset{ border:none}
        .rating-form fieldset, form .rating-form.voted, .rating-form.voted{ padding-left:2px; background: url(images/toolbar-digits-left.gif) 0% 50% no-repeat}
        .rating-form h4{ float:left; color:#fff; font-size:15px; line-height:20px; padding-right:3px; height:20px; text-align:right; background: url(images/toolbar-digits-long.gif) 100% 50% no-repeat}
        .rating-form p{display:none}.rating-form input.Rate,
        .rating-form.voted span{ float:left; background: url(images/toolbar-yiggit.gif) 0% 50% no-repeat; border:0; height:20px; width:50px; font-size:11px; line-height:20px; text-align:center; color:#105B1B; cursor:pointer}
        .rating-form.voted span{ color:white; cursor:none; background: url(images/toolbar-yigged.gif) 0% 50% repeat-x}
        .button {border: 0;}
      <?php endif; ?>
       </style>
    </head>
  <body>
<?php endif; ?>
      <!-- RatingForm -->
      <?php $hasRated = ( null === $story->hasRated() ? $sf_user->hasRated($story['id']) : $story->hasRated() );
        if (false === $hasRated ):?>
        <form id="StoryRate<?php echo $story->id ?>" class="rating-form ninjaForm ninjaAjaxSubmit" action="<?php echo url_for_story($story, "rate"); ?>" method="post">
            <fieldset>
<!--                /*<p>YiGGs</p>*/-->
                <input type="hidden" name="StoryRate[_csrf_token]" value="<?php echo $form[$form->getCSRFFieldName()]->getValue() ?>" id="StoryRate<?php echo $story->id; ?>__csrf_token" /><input name="StoryRate[ratingtoken]" id="StoryRate_ratingtoken<?php echo $story->id; ?>" type="hidden" value="<?php echo md5(rand(1,10000));?>" /><input type="submit" value="YiGG it!" class="button" id="Rate<?php echo $story->id?>" />
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