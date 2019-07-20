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

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use KejawenLab\Semart\Skeleton\Application as Constants;
use KejawenLab\Semart\Skeleton\Security\Authorization\Permission;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/address")
 *
 * @Permission(menu="UPDATEADDRESS")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UpdateAddressController
{
    /**
     * @Route("/", methods={"GET"}, name="address_updater_index", options={"expose"=true})
     *
     * @Permission(actions=Permission::VIEW)
     */
    public function index()
    {

    }

    /**
     * @Route("/update", methods={"POST"}, name="address_updater", options={"expose"=true})
     *
     * @Permission(actions=Permission::EDIT)
     */
    public function update(KernelInterface $kernel)
    {
        ini_set('max_execution_time', '-1');

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => sprintf('%s:address:update', Constants::APP_UNIQUE_NAME),
        ]);

        $output = new BufferedOutput(OutputInterface::VERBOSITY_NORMAL, true);
        $application->run($input, $output);

        $converter = new AnsiToHtmlConverter();

        return new JsonResponse([
            'status' => 'OK',
            'output' => $converter->convert($output->fetch()),
        ]);
    }
}
