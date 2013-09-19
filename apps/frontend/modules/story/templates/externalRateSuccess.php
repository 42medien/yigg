<!DOCTYPE html>
<html lang="de-DE">
  <head>
    <?php
      echo auto_discovery_link_tag("stylesheet", stylesheet_path("v9/button.css", true), array("rel" => "stylesheet", "type" => "text/css"));
    ?>
  </head>
  
  <body>
    <?php
    if ($story) {  
      include_component( 'story', 'rateStory',  array('story' => $story, 'flat' => $flat));
    } else {
      include_partial( 'story/createStoryButton',  array('flat' => $flat));
    }  
    ?>
  </body>
</html>