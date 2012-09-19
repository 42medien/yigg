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
       .rating-form{ float:left;  margin-top:4px; text-align:center; background:/*url(images/newbutton.png) no-repeat*/ transparent;  color:#fff;  overflow:hidden;  width:200px;  height:54px; }
/*     .rating-form h4{ font-size:1.9em !important;  overflow:hidden;  padding-right:6px;  text-align:right; margin:0 !important}*/
       .rating-form p{ margin:0;  padding-right:6px; text-align:right}
       .rating-form fieldset{ padding:0px}
       .rating-form input,  .voted span{ display:inline; background:none;  border:0; line-height:1.7em;  text-align:center;  color:#105B1B;  cursor:pointer}
       .voted{ background:/*url(images/newbuttonvoted.png) no-repeat 1px;*/ transparent;}
       .voted span {display:block; background: #ffc851; border: 1px solid #c7c7c7; border-radius: 3px; float: left; line-height: 18px; padding: 0 2px;}
       .voted span a {text-decoration: none; color: #000;}
       .voted h4 label {-moz-transform: rotate(-45deg); 
                        -webkit-transform: rotate(-45deg); /* Safari 3.1+ и Chrome 2.0+ */
                        -o-transform: rotate(-45deg); /* Opera 10.5+ */
                        -ms-transform: rotate(-45deg); /* IE 9.0 */
                       background: #FFFFFF; border-color: #CCCCCC transparent transparent #CCCCCC; border-style: solid; border-width: 1px; display: block; height: 3px; left: -3px; position: absolute; top: 6px; width: 3px;}
       .voted h4 {color: #666666; float: left; font-weight: normal; text-align: center; margin-left: 5px; padding: 2px 3px; position: relative; background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #CCCCCC; border-radius: 3px 3px 3px 3px;}
      <?php else: ?>
        body{ font-family:Arial,Helvetica,sans-serif; font-size:62.5%; color:#3F352F; position:relative;   font-size: 11px;}
        a img, fieldset{ border:none}
/*        .rating-form fieldset, form .rating-form.voted, .rating-form.voted{ padding-left:2px; background: url(images/toolbar-digits-left.gif) 0% 50% no-repeat}
        .rating-form h4{ float:left; color:#fff; font-size:15px; line-height:20px; padding-right:3px; height:20px; text-align:right; background: url(images/toolbar-digits-long.gif) 100% 50% no-repeat}
        .rating-form p{display:none}.rating-form input.Rate,
        .rating-form.voted span{ float:left; background: url(images/toolbar-yiggit.gif) 0% 50% no-repeat; border:0; height:20px; width:50px; font-size:11px; line-height:20px; text-align:center; color:#105B1B; cursor:pointer}
        .rating-form.voted span{ color:white; cursor:none; background: url(images/toolbar-yigged.gif) 0% 50% repeat-x}*/
        .rating-form span {background: none repeat scroll 0 0 #FFC851; border: 1px solid #C7C7C7; border-radius: 3px 3px 3px 3px; cursor: pointer; font: 13px/30px 'Arial',sans-serif; height: 30px; padding: 0 3px; max-width: 54px; text-align: center; display: block; margin: 40px 0 0;}
        .rating-form span a {text-decoration: none;}
        .rating-form h4 {background: none repeat scroll 0 0 #FFFFFF;
            border: 1px solid #CCCCCC;
            border-radius: 3px 3px 3px 3px;
            color: #666666;
            font-family: 'Arial';
            font-size: 12px;
            font-weight: normal;
            left: 3px;
            line-height: 19px;
            margin: 0;
            max-width: 46px;
            padding: 0 3px;
            position: relative;
            text-align: center;
            top: -63px;
        }
        .rating-form h4 label {-moz-transform: rotate(-45deg);
                -ms-transform: rotate(-45deg);
            -webkit-transform: rotate(-45deg);
            -o-transform: rotate(-45deg);
            transform: rotate(-45deg);
            background: none repeat scroll 0 0 #FFFFFF;
            border-color: transparent transparent #CCCCCC #CCCCCC;
            border-style: solid;
            border-width: 1px;
            bottom: -5px;
            display: block;
            height: 7px;
            left: 41%;
            padding: 0;
            position: absolute;
            width: 7px;
        }
        .rating-form {position: relative;}
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
