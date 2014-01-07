<?php
class frontendConfiguration extends sfApplicationConfiguration
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


    // Directory containing video librarys
    sfConfig::add(
      array(
        'sf_video_lib_dir_name' => 'lib',
        'sf_video_lib_dir' => sfConfig::get('sf_app_module_dir') . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . 'lib'
      )
    );

    // WARNING: quick hack

    $rootDir     = explode(DIRECTORY_SEPARATOR, sfConfig::get('sf_root_dir'));
    $vhost       = array_pop($rootDir);
    $domainParts = explode('.', $vhost);
    $subDomain   = array_shift($domainParts);

    if ('www' === $subDomain)
    {
      $staticSubDir = 'v6';
    }
    else
    {
      $staticSubDir = $subDomain;
    }


    sfConfig::add(
      array(
        'static_base_uri' => 'http://yigg.it'
      )
    );

    // URL containing static content
    sfConfig::add(
      array(
        'static_host' => (ProjectConfiguration::isActiveYiggEnvironment('prod') ? sfConfig::get('static_base_uri') : '')
      )
    );

    sfConfig::add(
      array(
        'static_host_images' => (ProjectConfiguration::isActiveYiggEnvironment('prod') ? sfConfig::get('static_base_uri') . '/images/' : '')
      )
    );

  }
}
