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

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use KejawenLab\Semart\Skeleton\Component\Contract\Company\CompanyInterface;
use KejawenLab\Semart\Skeleton\Request\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanySubscriber implements EventSubscriberInterface
{
    public function filterRequest(RequestEvent $event)
    {
        $company = $event->getObject();
        if (!$company instanceof CompanyInterface) {
            return;
        }

        $request = $event->getRequest();
        $company->setBirthDay(\DateTime::createFromFormat('d-m-Y', $request->request->get('birthDay')));
        $request->request->remove('birthDay');
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => [['filterRequest']],
        ];
    }
}
