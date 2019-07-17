<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Command;

use KejawenLab\Semart\Skeleton\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class InstallationCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName(sprintf('%s:install', Application::APP_UNIQUE_NAME))
            ->setAliases([sprintf('%s:create', Application::APP_UNIQUE_NAME)])
            ->setDescription('Install Semart Application Skeleton')
            ->setHelp('Install Semart Application Skeleton')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Creating new Semart Application database</info>');
        $createDatabase = $this->getApplication()->find('doctrine:database:create');
        $createDatabase->run(new ArrayInput([
            'command' => 'doctrine:database:create',
            '--if-not-exists' => true,
        ]), $output);

        $noInteraction = ['--no-interaction' => true];

        $output->writeln('<info>Running Semart Schema Updater</info>');
        $migration = $this->getApplication()->find('doctrine:schema:update');
        $migration->run(new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force' => true,
        ] + $noInteraction), $output);

        $output->writeln('<info>Loading Semart Application initial data</info>');
        $fixtures = $this->getApplication()->find('doctrine:fixtures:load');
        $fixtures->run(new ArrayInput([
                'command' => 'doctrine:fixtures:load',
        ] + $noInteraction), $output);

        $output->writeln('<info>Semart Application Installation is finished</info>');
        $output->writeln('<comment>Run <info>php bin/console server:run</info> to start your server</comment>');
        $output->writeln('<comment>Login with username: <info>admin</info> and password: <info>semartadmin</info></comment>');
    }
}
