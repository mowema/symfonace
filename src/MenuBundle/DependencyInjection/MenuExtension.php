<?php

/**
 * Copyright 2014 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2014 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/ConfigKnpMenuBundle/blob/master/LICENSE
 * @link https://github.com/jbouzekri/ConfigKnpMenuBundle
 */

/**
 * @namespace
 */
namespace MenuBundle\DependencyInjection;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MenuExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuredMenus = array();
        if (is_file($file = $container->getParameter('kernel.root_dir') . '/config/navigation.yml')) {
            $configuredMenus = $this->parseFile($file);
            $container->addResource(new FileResource($file));
        }

        foreach ($container->getParameter('kernel.bundles') as $bundle) {
            $reflection = new \ReflectionClass($bundle);
           
            if (is_file($file = dirname($reflection->getFilename()) . '/Resources/config/navigation.yml')) {
                $configuredMenus = array_replace_recursive($configuredMenus, $this->parseFile($file));
                $container->addResource(new FileResource($file));
            }
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // validate menu configurations
        foreach ($configuredMenus as $rootName => $menuConfiguration) {
            $configuration = new NavigationConfiguration();
            $configuration->setMenuRootName($rootName);
            $menuConfiguration[$rootName] = $this->processConfiguration(
                $configuration,
                array($rootName => $menuConfiguration)
            );
        }

        // Set configuration to be used in a custom service
        $container->setParameter('config.menu.configuration', $configuredMenus);

        // Last argument of this service is always the menu configuration
        $container
            ->getDefinition('config.menu.provider')
            ->addArgument($configuredMenus);

    }

    /**
     * Parse a navigation.yml file
     *
     * @param string $file
     *
     * @return array
     */
    public function parseFile($file)
    {
        $bundleConfig = Yaml::parse(realpath($file));

        if (!is_array($bundleConfig)) {
            return array();
        }

        return $bundleConfig;
    }
}
