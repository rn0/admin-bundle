<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Message;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FlashMessages
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * FlashMessages constructor.
     * @param SessionInterface $session
     * @param $prefix
     */
    public function __construct(SessionInterface $session, $prefix)
    {
        $this->prefix = $prefix;
        $this->session = $session;
    }

    public function success($message, $domain = 'FSiAdminBundle')
    {
        $this->add('success', $message, $domain);
    }

    public function error($message, $domain = 'FSiAdminBundle')
    {
        $this->add('error', $message, $domain);
    }

    public function warning($message, $domain = 'FSiAdminBundle')
    {
        $this->add('warning', $message, $domain);
    }

    public function info($message, $domain = 'FSiAdminBundle')
    {
        $this->add('info', $message, $domain);
    }

    public function all()
    {
        return $this->getFlashBag()->get($this->prefix);
    }

    private function add($type, $message, $domain)
    {
        if ($this->getFlashBag()->has($this->prefix)) {
            $messages = $this->getFlashBag()->get($this->prefix);
        } else {
            $messages = [];
        }

        $messages[$type][] = ['text' => $message, 'domain' => $domain];

        $this->flashBag->set($this->prefix, $messages);
    }

    /**
     * @return FlashBagInterface
     */
    private function getFlashBag()
    {
        if ($this->flashBag instanceof FlashBagInterface) {
            return $this->flashBag;
        }

        $this->flashBag = method_exists($this->session, 'getFlashBag')
            ? $this->session->getFlashBag()
            : new FlashBag();

        return $this->flashBag;
    }
}