<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GenericFormElementSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     */
    function let($factory)
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\MyForm');
        $this->beConstructedWith(array());
        $this->setFormFactory($factory);
    }

    function it_is_form_element()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\FormElement');
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Element');
    }

    function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_form');
    }

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     */
    function it_throw_exception_when_init_form_does_not_return_instance_of_form($factory)
    {
        $factory->create(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initForm should return instanceof Symfony\\Component\\Form\\FormInterface"))
            ->during('createForm');
    }

    function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn(array(
            'template_form' => null,
        ));
    }
}
