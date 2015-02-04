<?php

namespace spec\FSi\Bundle\AdminBundle\Menu\Builder;

use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Bundle\AdminBundle\Menu\Builder\Exception\InvalidYamlStructure;
use FSi\Bundle\AdminBundle\Menu\Item\ElementItem;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class YamlBuilderSpec extends ObjectBehavior
{
    function let(Manager $manager)
    {
        $prophet = new Prophet();
        $manager->getElement(Argument::type('string'))->will(function($args) use ($prophet) {
            $element = $prophet->prophesize('FSi\Bundle\AdminBundle\Admin\Element');
            $element->getId()->willReturn($args[0]);
            return $element;
        });

        $manager->hasElement(Argument::type('string'))->willReturn(true);
        $this->beConstructedWith($manager, __DIR__ . '/admin_menu.yml');
    }

    function it_throws_exception_when_yaml_definition_of_menu_is_invalid(Manager $manager)
    {
        $menuYaml = __DIR__ . '/invalid_admin_menu.yml';
        $this->beConstructedWith($manager, $menuYaml);

        $this->shouldThrow(new InvalidYamlStructure(
            sprintf('File "%s" should contain top level "menu:" key', $menuYaml)
        ))->during('buildMenu');
    }

    function it_build_menu()
    {
        $this->buildMenu()->shouldReturnAnInstanceOf('FSi\Bundle\AdminBundle\Menu\Item\Item');
        $this->buildMenu()->shouldHaveItem('news', 'news');
        $this->buildMenu()->shouldHaveItem('article', 'article');
        $this->buildMenu()->shouldHaveItem('structure', false);
        $this->buildMenu()->shouldHaveItemThatHaveChild('structure', 'home_page', 'home_page');
        $this->buildMenu()->shouldHaveItemThatHaveChild ('structure', 'contact', 'contact');
    }

    public function getMatchers()
    {
        return array(
            'haveItem' => function(Item $menu, $itemName, $elementId = false) {
                    $items = $menu->getChildren();
                    foreach ($items as $item) {
                        if ($item->getName() === $itemName) {
                            if (!$elementId) {
                                return true;
                            }

                            /** @var ElementItem $item */
                            return $item->getElement()->getId() === $elementId;
                        }
                    }
                    return false;
                },
            'haveItemThatHaveChild' => function(Item $menu, $itemName, $childName, $elementId = false) {
                    foreach ($menu->getChildren() as $item) {
                        if ($item->getName() === $itemName && $item->hasChildren()) {
                            foreach ($item->getChildren() as $child) {
                                if ($child->getName() === $childName) {
                                    if (!$elementId) {
                                        return true;
                                    }

                                    /** @var ElementItem $child */
                                    return $child->getElement()->getId() === $elementId;
                                }
                            }
                        }
                    }
                    return false;
                }
        );
    }
}
