<?php

declare(strict_types=1);

namespace Chaman\Listener;

use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\Mvc\MvcEvent;

class OwaspHeadersListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, [$this, 'addOwaspHeaders'], 2);
    }

    /**Dum
     * @param EventInterface $e
     */
    public function addOwaspHeaders(EventInterface $e)
    {
        /** @var MvcEvent $app */
        $app = $e->getApplication();
        $configArr = $app->getServiceManager()->get('Config');

        if (array_key_exists('owasp-headers', $configArr)) {
            $responseHeaders = $app->getResponse()->getHeaders();
            foreach ($configArr['owasp-headers'] as $key => $value) {
                $responseHeaders->addHeaderLine($key, $value);
            }
        }
    }
}
