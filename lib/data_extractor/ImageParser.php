<?php
class ImageParser {
  /**
   * detects all images embeded in a site
   *
   * @param string $html
   * @return array
   */
  public static function detect($html, $url) {
    preg_match_all('/<img.*src[\s]*=[\s]*[\"\']?([^\'\"\s]*)[\"\']?[\s]*[^>]+>/i', $html, $images);

    $result = array();

    if (!isset($images[1])) {
      return $result;
    }

    foreach( $images[1] as $image ) {
      if (!$image) {
        continue;
      }

      if (!preg_match("~^(?:f|ht)tps?://~i", $image)) {
        $image = "http:" . $image;
      }

      $size = @getimagesize($image);
      $size = $size[0] + $size[1];

      $result[] = array('size' => $size, 'image' => $image);
    }

    usort($result, array("ImageParser", "sort"));
    $result = array_unique($result);
    $result = array_slice($result, 0, 6);

    // return only the images and crop the size
    $flat = array();
    foreach ($result as $image) {
      $flat[] = $image['image'];
    }

    return $flat;
  }

  /**
   * sorts the array by image size
   *
   * @param array $a
   * @param array $b
   * @return int
   */
  public static function sort($a, $b) {
    if ($a['size'] == $b['size']) {
      return 0;
    }
    return ($a['size'] > $b['size']) ? -1 : 1;
  }
}
?>