<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu\Builder;

use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Menu\Builder\Exception\InvalidYamlStructure;
use FSi\Bundle\AdminBundle\Menu\Item\ElementItem;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use Symfony\Component\Yaml\Yaml;

class YamlBuilder implements Builder
{
    /**
     * @var string
     */
    private $configFilePath;

    /**
     * @var Yaml
     */
    private $yaml;

    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ManagerInterface $manager
     * @param string $configFilePath
     */
    public function __construct(ManagerInterface $manager, $configFilePath)
    {
        $this->configFilePath = $configFilePath;
        $this->yaml = new Yaml();
        $this->manager = $manager;
    }

    /**
     * @return Item
     * @throws InvalidYamlStructure
     */
    public function buildMenu()
    {
        $config = $this->yaml->parse($this->configFilePath, true, true);

        if (!isset($config['menu'])) {
            throw new InvalidYamlStructure(
                sprintf('File "%s" should contain top level "menu:" key', $this->configFilePath)
            );
        }

        $menu = new Item();
        $menu->setOptions(array(
            'attr' => array(
                'id' => 'top-menu',
                'class' => 'nav navbar-nav',
            )
        ));

        $this->populateMenu($menu, $config['menu']);

        return $menu;
    }

    private function populateMenu(Item $menu, array $configs)
    {
        foreach ($configs as $name => $itemConfig) {
            $item = $this->itemFactory($name, $itemConfig);

            if ($this->hasEntry($itemConfig, 'label')) {
                $item->setLabel($itemConfig['label']);
            }

            if ($this->hasEntry($itemConfig, 'children')) {
                $item->setOptions(array('attr' => array('class' => 'admin-element',)));
                $this->populateMenu($item, $itemConfig['children']);
            }

            $menu->addChild($item);
        }
    }

    private function itemFactory($name, $itemConfig)
    {
        if ($this->manager->hasElement($name)) {
            return new ElementItem($name, $this->manager->getElement($name));
        }

        if ($this->hasEntry($itemConfig, 'element_id') && $this->manager->hasElement($itemConfig['element_id'])) {
            return new ElementItem($name, $this->manager->getElement($itemConfig['element_id']));
        }

        return new Item($name);
    }

    private function hasEntry($itemConfig, $keyName)
    {
        return is_array($itemConfig) && array_key_exists($keyName, $itemConfig);
    }
}
