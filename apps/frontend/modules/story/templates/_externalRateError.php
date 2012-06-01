<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <title></title>
      <base href="http://<?php echo $sf_request->getHost() . $sf_request->getRelativeUrlRoot();  ?>/" />
      <style>
        *{
          padding:0;
          margin:0;
          border:0;
        }
       </style>
      </head>
    <body>
<?php
echo link_to(img_tag('externalRateError.gif', array('alt' => '')), '@about_faq');
?>
	</body>
</html>