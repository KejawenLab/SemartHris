<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests;

use KejawenLab\Semart\Skeleton\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ApplicationTest extends WebTestCase
{
    public function testConst()
    {
        $this->assertEquals('app.request', Application::REQUEST_EVENT);
        $this->assertEquals('app.pre_validation', Application::PRE_VALIDATION_EVENT);
        $this->assertEquals('app.pagination', Application::PAGINATION_EVENT);
        $this->assertEquals('app.pre_commit', Application::PRE_COMMIT_EVENT);
    }
}
