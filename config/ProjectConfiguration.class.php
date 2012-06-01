<?php

// Re-routing to included symfony directorys.
$sf_symfony_lib_dir  =  realpath( dirname(__FILE__)) .'/../lib/vendor/symfony';
require_once $sf_symfony_lib_dir . '/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

// Set up the callback
class ProjectConfiguration extends sfProjectConfiguration
{
  /**
   * Tries to guess the YiGG environment
   *
   * @return string YiGG environment
   */
  static public function getYiggEnvironment()
  {
    static $yiggEnvironment = null;
    if (null != $yiggEnvironment)
    {
      return $yiggEnvironment;
    }

    if (0 < preg_match('/www\.yigg\.de/', self::guessRootDir()) || 0 < preg_match('/backend\.yigg\.de/', self::guessRootDir())  )
    {
      return $yiggEnvironment = 'prod';
    }

    return $yiggEnvironment = 'dev';
  }

  /**
   * Checks if the given environment is the active one
   *
   * @param string $environment The environment to check for
   * @return true if the given environment is active or false otherwise
   */
  static public function isActiveYiggEnvironment($environment)
  {
    return self::getYiggEnvironment() == $environment;
  }

  /**
   * Returns a sfApplicationConfiguration configuration for a given application based on the YiGG environment
   *
   * @param string            $application    An application name
   * @param string            $rootDir        The project root directory
   * @param sfEventDispatcher $dispatcher     An event dispatcher
   *
   * @return sfApplicationConfiguration A sfApplicationConfiguration instance
   */
  static public function getYiggApplicationConfiguration($application, $forceEnv = null)
  {
    $environment = self::getYiggEnvironment();

    return parent::getApplicationConfiguration($application, $forceEnv === null ? $environment: $forceEnv, 'dev' == $environment);
  }

  public function setup()
  {
    mb_internal_encoding("UTF-8");
    $this->enablePlugins(
      array(
        "sfPaypalDirectPlugin",
        "sfThumbnailPlugin",
        "sfDoctrinePlugin"
      ));

    /**
     * Changing web dir from "web" to "htdocs".
     */
    $this->setWebDir(sfConfig::get('sf_root_dir') . DIRECTORY_SEPARATOR . 'htdocs');
  }
}