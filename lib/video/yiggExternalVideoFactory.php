<?php

/**
 * Factory creating external video support objects
 *
 * @package    yigg
 * @subpackage video
 */
abstract class yiggExternalVideoFactory
{
  /**
   * A list of names from video support classes
   *
   * @var array List of names from video support classes
   */
  private static $factoryClasses = array();

  /**
   * Whether the video support files are loaded or not
   *
   * @var bool Whether the video support files are loaded or not
   */
  private static $supportFilesLoaded = false;

  /**
   * Loads the video support files from the support directory
   *
   * This needs to be done cause the auto loader of symfony only loads the files
   * when you try to use a class which is currently not defined and we need them
   * to be loaded earlier cause the support files register themselves at the factory
   * to inform it about their existence.
   */
  protected static function loadSupportFiles()
  {
    $supportFiles = sfFinder::type('file')
      ->name('*.php')
      ->in(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'support');

    foreach ($supportFiles as $file)
    {
      require_once($file);
    }

    self::$supportFilesLoaded = true;
  }

  /**
   * Registers a video support class at the factory
   *
   * @param yiggExternalVideoInterface $class_name The support class you want to register
   */
  public static function registerFactoryClass($class_name)
  {
    if (false == in_array('yiggExternalVideoInterface', class_implements($class_name)))
    {
      throw new sfException(sprintf('The support class "%s" must implement "yiggExternalVideoInterface".', $class_name));
    }

    self::$factoryClasses[] = $class_name;
  }

  /**
   * Returns a list of registered video support classes
   *
   * @return array List of registered video support classes
   */
  public static function getFactoryClasses()
  {
    if (false == self::$supportFilesLoaded)
    {
      self::loadSupportFiles();
    }

    return self::$factoryClasses;
  }

  /**
   * Checks whether a support class is registered or not
   *
   * @param string $class_name The name of the support class to search for
   * @return bool Whether the class is registered or not
   */
  public static function isFactoryClassRegistered($class_name)
  {
    return in_array($class_name, self::getFactoryClasses());
  }

  /**
   * Factory method which creates a video support object based on the URL
   *
   * @param string $url Video URL
   * @param string $class_name A class name that will be used to create the instance if supplied
   * @return mixed Instance of a support object implementing yiggExternalVideoInterface or NULL if the URL is not supported
   */
  public static function createFromUrl($url, $class_name = null)
  {
    if (null != $class_name)
    {
      if (false == self::isFactoryClassRegistered($class_name))
      {
        return null;
      }
      else
      {
        $instance = new $class_name;

        return $instance->initWithUrl($url);
      }
    }

    foreach (self::getFactoryClasses() as $className)
    {
      $instance = new $className;

      if (null != $instance->initWithUrl($url))
      {
        return $instance;
      }
    }
    return null;
  }

  /**
   * Creates a defined video support object and calls init using the ID
   *
   * @param string $id Video ID
   * @param string $class_name Name of a registered support class
   * @return mixed Instance of a support object implementing yiggExternalVideoInterface or NULL if the class is not registered or init failed
   */
  public static function createFromId($id, $class_name)
  {
    if (false == self::isFactoryClassRegistered($class_name))
    {
      return null;
    }

    $instance = new $class_name;

    return $instance->initWithId($id);
  }
}