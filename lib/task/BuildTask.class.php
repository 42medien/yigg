<?php

class BuildTask extends sfBaseTask {
  /**
   *
   */
  protected function configure() {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      new sfCommandOption('no-confirmation', null, sfCommandOption::PARAMETER_NONE, 'Do not prompt for confirmation'),
      new sfCommandOption('all', null, sfCommandOption::PARAMETER_NONE, 'Build all controller')
    ));

    $this->namespace        = 'yigg';
    $this->name             = 'build';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [BuildPlatform|INFO] task does things.
Call it with:

  [php symfony BuildPlatform|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array()) {
    sfContext::createInstance($this->configuration);

    // create "cache" folder
    $this->getFilesystem()->mkdirs("cache");
    $this->getFilesystem()->execute("rm -rf cache/*");

    // clean "log" folder
    if ($options['env'] != "dev") {
      $this->getFilesystem()->mkdirs("log");
      $this->getFilesystem()->execute("rm -rf log/*");
    }

    // build all?
    if ($options['all']) {
      $args = array("--all");
    } else {
      $args = array();
    }

    // create controllers
    $this->runTask('yigg:generate-controller', $args, array('application' => $options['application'], 'env' => $options['env']));
    //$this->executeDbTasks($arguments, $options);
  }

  /**
   *
   */
  protected function executeDbTasks($arguments = array(), $options = array()) {
    $this->logSection('yigg', 'run db tasks');

    // new options array
    $opts = array();
    $env = $opts['env'] = $options['env'];

    // new arguments array
    $args = array();
    if ($options['no-confirmation']) {
      $args[] = '--no-confirmation';
    }

    // generate arguments array
    if ($env == 'dev' || $env == 'staging') {
      // add arguments for the "dev" or "staging" environment
      $args[] = '--all';
      // check if "confirmation" is enabled
      $args[] = '--and-load';
    } elseif($env == 'prod') {
      // add arguments for the "prod" environment
      $args[] = '--all-classes';
      // $args[] = '--and-migrate';
    } else {
      throw new sfException(sprintf('Module "%s" does not exist.', $env));
    }

    // run doctrine build task
    $this->runTask('doctrine:build', $args, array('env' => $opts['env']));
    // clean up models folder
    $this->runTask('doctrine:clean', array("--no-confirmation"));

    $this->runTask('cc');
  }
}