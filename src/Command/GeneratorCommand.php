<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Command;

use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Generator\GeneratorFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GeneratorCommand extends Command
{
    private $generatorFactory;

    public function __construct(GeneratorFactory $generatorFactory)
    {
        $this->generatorFactory = $generatorFactory;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(sprintf('%s:crud:generate', Application::APP_UNIQUE_NAME))
            ->setAliases([sprintf('%s:generate', Application::APP_UNIQUE_NAME), sprintf('%s:gen', Application::APP_UNIQUE_NAME)])
            ->setDescription('Generate Simpel CRUD')
            ->setHelp('Generate Simpel CRUD')
            ->addArgument('entity', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $reflection = new \ReflectionClass($input->getArgument('entity'));

        $output->writeln('<info>Running Semart Schema Updater</info>');
        $migration = $this->getApplication()->find('doctrine:schema:update');
        $migration->run(new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force' => true,
            '--no-interaction' => true,
        ]), $output);

        $output->writeln('<info>Running Semart CRUD Generator</info>');
        $this->generatorFactory->generate($reflection);
        $output->writeln(sprintf('<comment>Simple CRUD for %s class is generated</comment>', $reflection->getName()));
    }
}
