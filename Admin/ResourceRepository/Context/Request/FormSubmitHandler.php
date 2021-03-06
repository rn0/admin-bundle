<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractHandler;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\ResourceEvents;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\Request;

class FormSubmitHandler extends AbstractHandler
{
    /**
     * @param AdminEvent $event
     * @param Request $request
     * @throws \FSi\Bundle\AdminBundle\Exception\RequestHandlerException
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        $this->validateEvent($event);
        $this->eventDispatcher->dispatch(ResourceEvents::RESOURCE_CONTEXT_POST_CREATE, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        if ($request->getMethod() == 'POST') {
            $this->eventDispatcher->dispatch(ResourceEvents::RESOURCE_FORM_REQUEST_PRE_SUBMIT, $event);

            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $event->getForm()->submit($request);
            $this->eventDispatcher->dispatch(ResourceEvents::RESOURCE_FORM_REQUEST_POST_SUBMIT, $event);

            if ($event->hasResponse()) {
                return $event->getResponse();
            }
        }
    }

    /**
     * @param AdminEvent $event
     * @throws RequestHandlerException
     */
    protected function validateEvent(AdminEvent $event)
    {
        if (!$event instanceof FormEvent) {
            throw new RequestHandlerException(sprintf("%s require FormEvent", get_class($this)));
        }
    }
}