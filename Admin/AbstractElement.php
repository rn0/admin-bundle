<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin;

use FSi\Bundle\AdminBundle\Exception\MissingOptionException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractElement implements Element
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct($options = [])
    {
        $optionsResolver = new OptionsResolver();
        $this->setDefaultOptions($optionsResolver);
        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteParameters()
    {
        return [
            'element' => $this->getId(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($name)
    {
        if (!$this->hasOption($name)) {
            throw new MissingOptionException(sprintf('Option with name: "%s" does\'t exists.', $name));
        }

        return $this->options[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($name)
    {
        return isset($this->options[$name]);
    }
}
