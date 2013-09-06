<?php
function link_to_story($text, $story, $options=array())
{
  $year = substr($story["created_at"], 0, 4);
  $month = substr($story["created_at"], 5, 2);
  $day = substr($story["created_at"], 8, 2);

  $route = "@story_show?slug={$story["internal_url"]}&year=$year&month=$month&day=$day";
  if(array_key_exists("view", $options))
  {
    $route .= "&view={$options["view"]}";
    unset($options["view"]);
  }

  return link_to($text, $route, $options);
}

function url_for_story($story, $view = false, $absolute = false)
{
  $year = substr($story["created_at"], 0, 4);
  $month = substr($story["created_at"], 5, 2);
  $day = substr($story["created_at"], 8, 2);
  $route = "@story_show?slug={$story["internal_url"]}&year=$year&month=$month&day=$day";

  if(false !== $view)
  {
    $route .= "&view=$view";
  }
  return url_for($route, $absolute);
}

function adsense_ad_tag($slot, $width, $height)
{
  $result = javascript_tag_open($javascript_tag_close);
  $result .= 'google_ad_client = "ca-pub-1406192967534280";';
  $result .= 'google_ad_slot = "'.$slot.'";';
  $result .= "google_ad_width = $width;";
  $result .= "google_ad_height = $height;";
  $result .= $javascript_tag_close;
  $result .= javascript_include_tag('http://pagead2.googlesyndication.com/pagead/show_ads.js');
  return $result;
}

/**
 * Creates a button for subscribing to sth
 * @param  $text
 * @param  $now_route
 * @param  $later_route
 * @param array $options
 * @return string
 */
function now_later_button($text, $now_route, $later_route, $options=array())
{
  if(array_key_exists("class", $options))
  {
    $options["class"] .= " now_later_button";
  }
  else
  {
    $options["class"] = "now_later_button";
  }

  return
    content_tag(
      "div",
      link_to($text, $now_route, array("class" => "now", "title" => "Jetzt mehr hierzu lesen."))

      . link_to("Abonnieren", $later_route, array("class" => "later", "title" => "Später mehr hierzu lesen.")),
      $options
    );
}