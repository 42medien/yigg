<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('mugim', 'mug', false);
sfContext::createInstance($configuration)->dispatch();