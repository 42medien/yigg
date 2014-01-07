<?php

/**
 * Yiggs Image Helper, which adds the static host for the live site.
 *
 * @param file url $source
 * @param array $options
 * @return String
 */
function img_tag($source, $options = array())
{
  if((0 !== strpos($source, 'http')) && (false == (is_array($options) && isset($options['absolute']))))
  {
    $source  = sfConfig::get('static_host_images') . $source;
  }

  if(false === isset($options["alt"]))
  {
    $options["alt"] = "";
  }
  return image_tag($source, $options);
}

/**
 * Easy way to render an avatar. Removes logic from templates.
 * @param  $file
 * @param  $default
 * @param  $width
 * @param  $height
 * @param array $options
 * @return String
 */
function avatar_tag($file, $default, $width, $height, $options = array())
{
  $options["width"] = $width;
  $options["height"] = $height;
  if(false === is_object($file))
  {
    return img_tag($default, $options);
  }
  $avatar = $file->getThumbnail($width, $height);

  if(false == is_object($avatar))
  {
    return img_tag($default, $options);
  }
  return img_tag($avatar->getPathOnDisk(), $options);
}