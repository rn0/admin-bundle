<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class GenericFormElement extends AbstractElement implements FormElement
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'fsi_admin_form';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'template_form' => null,
        ));

        $resolver->setAllowedTypes(array(
            'template_form' => array('null', 'string'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setFormFactory(FormFactoryInterface $factory)
    {
        $this->formFactory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function createForm($data = null)
    {
        $form = $this->initForm($this->formFactory, $data);

        if (!is_object($form) || !$form instanceof FormInterface) {
            throw new RuntimeException('initForm should return instanceof Symfony\\Component\\Form\\FormInterface');
        }

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRoute()
    {
        return 'fsi_admin_form';
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRouteParameters()
    {
        return array(
            'element' => $this->getId()
        );
    }

    /**
     * Initialize create Form. This form will be used in createAction in FormController.
     *
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     * @param mixed $data
     * @return \Symfony\Component\Form\FormInterface
     */
    abstract protected function initForm(FormFactoryInterface $factory, $data = null);
}
