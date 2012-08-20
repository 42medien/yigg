<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title>YiGG it!</title>
      <base href="http://<?php echo $sf_request->getHost() . $sf_request->getRelativeUrlRoot();  ?>/" />
      <style>
        * { padding:0; margin:0;}
      <?php if( !isset($flat) || isset($flat) && $flat === false ): ?>
        body{ font-family:Arial,Helvetica,sans-serif; font-size:62.5%; color:#3F352F; position:relative;   font-size: 11px;}
        a img, fieldset{ border:none}
       .rating-form{ float:left;  margin-top:4px; text-align:center; background:/*url(images/newbutton.png) no-repeat*/ transparent;  color:#fff;  overflow:hidden;  width:56px;  height:54px; }
       .rating-form h4{ font-size:1.9em !important;  overflow:hidden;  padding-right:6px;  text-align:right; margin:0 !important}
       .rating-form p{ margin:0;  padding-right:6px; text-align:right}
       .rating-form fieldset{ padding:0px}
       .rating-form input,  .voted span{ display:inline; background:none;  border:0; line-height:1.7em;  text-align:center;  color:#105B1B;  cursor:pointer}
       .voted{ background:/*url(images/newbuttonvoted.png) no-repeat 1px;*/ transparent;}
       .voted span {display:block; background: #ffc851; border: 1px solid #c7c7c7; border-radius: 3px; float: left; line-height: 18px; padding: 0 2px;}
       .voted span a {text-decoration: none; color: #f5f5f5; text-shadow: 0 1px #DFAB3C;}
       .voted h4 label {
    -moz-transform: rotate(-45deg);
    background: none repeat scroll 0 0 #EFEFEF;
    border-color: #CCCCCC transparent transparent #CCCCCC;
    border-right: 1px solid transparent;
    border-style: solid;
    border-width: 1px;
    box-shadow: 0 1px 0 0 #FFFFFF inset;     height: 8px;
    left: -6px;
    position: absolute;
    top: 4px;
    width: 8px;
}
.voted h4 {    -moz-transition: color 2s ease-out 0s;
    background: none repeat scroll 0 0 #EFEFEF;
    border: 1px solid #CCCCCC;
    border-radius: 3px 3px 3px 3px;
    box-shadow: 0 1px 0 0 #FFFFFF inset;
    color: #AAAAAA;
    cursor: default;
    font-weight: normal;
    margin-left: 9px;
    padding: 2px 4px 2px 6px;
    position: relative;
    text-shadow: 0 1px 0 #FFFFFF, 0 -1px 0 rgba(0, 0, 0, 0.4);
    top: -6px;}
      <?php else: ?>
        body{ font-family:Arial,Helvetica,sans-serif; font-size:62.5%; color:#3F352F; position:relative;   font-size: 11px;}
        a img, fieldset{ border:none}
        .rating-form fieldset, form .rating-form.voted, .rating-form.voted{ padding-left:2px; background: url(images/toolbar-digits-left.gif) 0% 50% no-repeat}
        .rating-form h4{ float:left; color:#fff; font-size:15px; line-height:20px; padding-right:3px; height:20px; text-align:right; background: url(images/toolbar-digits-long.gif) 100% 50% no-repeat}
        .rating-form p{display:none}.rating-form input.Rate,
        .rating-form.voted span{ float:left; background: url(images/toolbar-yiggit.gif) 0% 50% no-repeat; border:0; height:20px; width:50px; font-size:11px; line-height:20px; text-align:center; color:#105B1B; cursor:pointer}
        .rating-form.voted span{ color:white; cursor:none; background: url(images/toolbar-yigged.gif) 0% 50% repeat-x}
      <?php endif; ?>
       </style>
    </head>
  <body>
        <div class="rating-form voted">
          <div>            
<!--        <p>Stimmen</p>-->
            <span><?php echo link_to("YiGG", "@story_create", array("target" => "_blank"));?></span>
            <h4><label></label>0</h4>
          </div>
        </div>
    </body>
  </html>
