<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class extends DefaultDeployer
{
    public function configure()
    {
        return $this->getConfigBuilder()
            ->server('gothiclist@gothiclist.com')
            ->deployDir('/home/gothiclist')
            ->repositoryUrl('git@github.com:SuperToma/gothiclist.git')
            ->repositoryBranch('master')
            ->sharedFilesAndDirs(['.env'])
        ;
    }

    public function beforeStartingDeploy()
    {
        // $this->runLocal('./vendor/bin/simple-phpunit');
    }

    public function beforeFinishingDeploy()
    {
        // $this->runRemote('{{ console_bin }} app:my-task-name');
        $this->runLocal('say "The deployment has finished."');
    }
};
