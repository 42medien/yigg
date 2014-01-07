<?php

class backendConfiguration extends sfApplicationConfiguration
{
  public function configure()
  {
    // Directory for image uploads.
    sfConfig::add(
      array(
        'sf_image_upload_dir_name'      => 'images',
        'sf_image_upload_dir'           => sfConfig::get('sf_upload_dir') . DIRECTORY_SEPARATOR . 'images'
      )
    );

    // WARNING: quick hack
    $rootDir     = explode(DIRECTORY_SEPARATOR, sfConfig::get('sf_root_dir'));
    $vhost       = array_pop($rootDir);

    sfConfig::add(
      array(
        'static_base_uri' => 'http://yigg.it'
      )
    );

   // URL containing static content
    sfConfig::add(
      array(
        'static_host' => ProjectConfiguration::isActiveYiggEnvironment('prod') ? sfConfig::get('static_base_uri') : ''
      )
    );

    sfConfig::add(
      array(
        'static_host_images' => ProjectConfiguration::isActiveYiggEnvironment('prod') ? sfConfig::get('static_base_uri') . '/images/' : ''
      )
    );
  }
}