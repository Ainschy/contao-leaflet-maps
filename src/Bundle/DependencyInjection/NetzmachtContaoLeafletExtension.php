<?php

/**
 * Leaflet maps for Contao CMS.
 *
 * @package    contao-leaflet-maps
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/netzmacht/contao-leaflet-maps/blob/master/LICENSE
 * @filesource
 */

declare(strict_types=1);

namespace Netzmacht\Contao\Leaflet\Bundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class NetzmachtContaoLeafletExtension.
 *
 * @package Netzmacht\Contao\Leaflet\DependencyInjection
 */
class NetzmachtContaoLeafletExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(dirname(__DIR__) . '/Resources/config')
        );

        // Common config, services and listeners
        $loader->load('config.yml');
        $loader->load('services.yml');
        $loader->load('listeners.yml');

        // Amenities and providers config
        $loader->load('amenities.yml');
        $loader->load('providers.yml');

        // Other services
        $loader->load('mappers.yml');
        $loader->load('encoders.yml');
        $loader->load('layers.yml');

        if (!$container->hasDefinition('netzmacht.contao_leaflet.cache')) {
            if ($container->getParameter('kernel.debug')) {
                $container->setAlias('netzmacht.contao_leaflet.cache', 'netzmacht.contao_leaflet.cache.debug');
            } else {
                $container->setAlias('netzmacht.contao_leaflet.cache', 'netzmacht.contao_leaflet.cache.default');
            }
        }
    }
}
