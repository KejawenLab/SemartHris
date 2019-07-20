<?php
/**
 * This file is part of the Semart HRIS Application.
 *
 * (c) Muhamad Surya Iksanudin <surya.kejawen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Command;

use KejawenLab\Semart\Skeleton\Application;
use KejawenLab\Semart\Skeleton\Component\Address\AddressUpdaterService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UpdateAddressCommand extends Command
{
    private $addressUpdater;

    public function __construct(AddressUpdaterService $addressUpdater)
    {
        $this->addressUpdater = $addressUpdater;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(sprintf('%s:address:update', Application::APP_UNIQUE_NAME))
            ->setDescription('Update Province, District and Sub District from Upstream')
            ->setHelp('Update Province, District and Sub District from Upstream')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '<info>Update database propinsi, kabupaten dan kecamatan.</info>',
            '==================================================',
        ]);
        $start = microtime(true);
        $this->addressUpdater->update($output);
        $end = microtime(true);
        $second = $end - $start;
        $minute = round($second / 60);
        $hour = 0;
        if ($minute > 0) {
            $second = (int) $second - ($minute * 60);
            $hour = round($minute / 60);
            if ($hour > 0) {
                $minute = (int) $minute - ($hour * 60);
            }
        }

        $output->writeln(sprintf('<comment>Pemrosesan telah selesai dalam "<info>%s</info>" jam "<info>%s</info>" menit "<info>%s</info>" detik</comment>', $hour, $minute, number_format($second, 2)));
    }
}
