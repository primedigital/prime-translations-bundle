<?php

namespace Prime\Bundle\TranslationsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class PrimeTranslationsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('menu_plugin.yml');

        $activatedBundles = $container->getParameter('kernel.bundles');

        if ($this->hasLexikTranslations($activatedBundles)) {
            $loader->load('pagelayout.yml');
        }
    }

    /**
     * Returns if Lexik Translations bundle is active or not.
     *
     * @param array $activatedBundles
     *
     * @return bool
     */
    protected function hasLexikTranslations(array $activatedBundles)
    {
        return false;

        if (!array_key_exists('LexikTranslationBundle', $activatedBundles)) {
            return false;
        }

        return true;
    }
}
