<?php

namespace AcMarche\LunchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class AcMarcheLunchExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('lunch.yml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['LiipImagineBundle'])) {
            $this->loadDefaultValuesLiipImagine($container);
        }

        if (isset($bundles['VichUploaderBundle'])) {
            $this->loadDefaultValuesVich($container);
        }
    }

    protected function loadDefaultValuesLiipImagine(ContainerBuilder $container)
    {
        $configs = $this->loadYml('imagine.yml');
        foreach ($container->getExtensions() as $name => $extension) {
            if ($name == 'liip_imagine') {
                $container->prependExtensionConfig($name, $configs);
                break;
            }
        }
    }

    protected function loadDefaultValuesVich(ContainerBuilder $container)
    {
        $configs = $this->loadYml('vich.yml');
        foreach ($container->getExtensions() as $name => $extension) {
            if ($name == 'vich_uploader') {
                $container->prependExtensionConfig($name, $configs);
                break;
            }
        }
    }

    protected function loadYml($name)
    {
        try {
            $configs = Yaml::parse(file_get_contents(__DIR__ . '/../Resources/config/' . $name));
            return $configs;
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
        return [];
    }

    protected function plustar()
    {

        /*
       // get all bundles
       $bundles = $container->getParameter('kernel.bundles');

       if (!isset($bundles['AcMarcheTravauxBundle'])) {
           // disable AcmeGoodbyeBundle in bundles
           $config = array('use_acme_goodbye' => false);
           foreach ($container->getExtensions() as $name => $extension) {
               switch ($name) {
                   case 'acme_something':
                   case 'acme_other':
                       // set use_acme_goodbye to false in the config of
                       // acme_something and acme_other
                       //
                       // note that if the user manually configured
                       // use_acme_goodbye to true in app/config/config.yml
                       // then the setting would in the end be true and not false
                       $container->prependExtensionConfig($name, $config);
                       break;
               }
           }
       }

       // process the configuration of AcmeHelloExtension
       $configs = $container->getExtensionConfig($this->getAlias());
       // use the Configuration class to generate a config array with
       // the settings "acme_hello"
       $config = $this->processConfiguration(new Configuration(), $configs);

       // check if entity_manager_name is set in the "acme_hello" configuration
       if (isset($config['entity_manager_name'])) {
           // prepend the acme_something settings with the entity_manager_name
           $config = array('entity_manager_name' => $config['entity_manager_name']);
           $container->prependExtensionConfig('acme_something', $config);
       }*/

    }
}
