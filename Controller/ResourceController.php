<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class ResourceController extends ControllerAbstract
{
    /**
     * @ParamConverter("element", class="\FSi\Bundle\AdminBundle\Admin\ResourceRepository\Element")
     */
    public function resourceAction(ResourceRepository\Element $element, Request $request)
    {
        return $this->handleRequest($element, $request, 'fsi_admin_resource');
    }
}
