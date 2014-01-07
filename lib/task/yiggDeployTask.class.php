<?php
class yiggDeployTask extends sfProjectDeployTask
{
    protected function configure()
    {
        parent::configure();
        $this->name = 'push';
        $this->aliases = array('push');        
        $this->briefDescription = 'Deploys a project to live';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $options["rsync-options"] = '-az --force --delete --progress';
        $options["trace"] = true;
        parent::execute($arguments, $options);
    }
}