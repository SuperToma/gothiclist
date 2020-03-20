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
            ->composerInstallFlags('--no-dev --prefer-dist --no-interaction --quiet')
            ->sharedFilesAndDirs([
                '.env',
                'var/log/',
                'public/.well-known/',
                'public/img/releases/',
                'mp3/',
            ])
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
