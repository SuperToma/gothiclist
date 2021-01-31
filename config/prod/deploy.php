<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class extends DefaultDeployer
{
    public function configure()
    {
        return $this->getConfigBuilder()
            ->server('toma@gothiclist.com')
            ->deployDir('/var/www/gothiclist')
            ->repositoryUrl('git@github.com:SuperToma/gothiclist.git')
            ->repositoryBranch('master')
            ->composerInstallFlags('--no-dev --prefer-dist --no-interaction')
            ->useSshAgentForwarding(true)
            ->sharedFilesAndDirs([
                '.env',
                'var/log/',
                'public/.well-known/',
                'public/img/releases/',
                'assets/mp3/',
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
