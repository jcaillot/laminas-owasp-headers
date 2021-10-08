<?php

namespace Chaman\Tests;

use ReflectionClass;

use Interop\Container\ContainerInterface;

use PHPUnit\Framework\TestCase;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Http\Headers;
use Laminas\Http\Response;
use Laminas\Mvc\ApplicationInterface;
use Laminas\Mvc\MvcEvent;

use Chaman\Listener\OwaspHeadersListener;

// php vendor/bin/phpunit test/Listener/OwaspHeadersListenerTest.php
class OwaspHeadersListenerTest extends TestCase
{
    public function testAttach()
    {
        $listener = new OwaspHeadersListener();
        $eventManager = $this->createMock(EventManagerInterface::class);
        $eventManager->method('attach')->willReturn(function () {
        });
        $listener->attach($eventManager);
        $reflector = new ReflectionClass(OwaspHeadersListener::class);
        $prop = $reflector->getProperty('listeners');
        $prop->setAccessible(true);
        $listenerList = $prop->getValue($listener);
        $this->assertNotEmpty($listenerList);
        $this->assertCount(1, $listenerList);
    }

    public function testAddOwaspHeaders()
    {
        $listener = new OwaspHeadersListener();
        $event = $this->createEvent();
        $listener->addOwaspHeaders($event);
        /** @var Response $response */
        $response = $event->getApplication()->getResponse();
        /** @var Headers $headers */
        $headers = $response->getHeaders();
        $this->assertCount(2, $headers);
    }

    private function createEvent()
    {
        $sm = $this->createMock(ContainerInterface::class);
        $sm->method('get')
            ->with('Config')
            ->willReturn([
                             'owasp-headers' => [
                                 'X-foo-header' => 'bar',
                                 'X-bar-header' => 'foo',
                             ],
                         ]);

        $app = $this->createMock(ApplicationInterface::class);
        $app->method('getServiceManager')
            ->willReturn($sm);

        $response = new Response();
        $app->method('getResponse')
            ->willReturn($response);

        $event = $this->createMock(MvcEvent::class);
        $event->method('getApplication')
            ->willReturn($app);

        return $event;
    }
}
