<?php

namespace Prime\Bundle\TranslationsBundle\MenuPlugin;

use Netgen\Bundle\AdminUIBundle\MenuPlugin\MenuPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class TranslationsMenuPlugin implements MenuPluginInterface
{
    /**
     * @var array
     */
    protected $enabledBundles;

    public function __construct(array $enabledBundles)
    {
        $this->enabledBundles = $enabledBundles;
    }

    public function getIdentifier()
    {
        return 'prime_translations';
    }

    public function getTemplates()
    {
        return array(
            'aside' => '@PrimeTranslations/menu/plugins/aside.html.twig',
            'left' => '@PrimeTranslations/menu/plugins/left.html.twig',
        );
    }

    public function isActive()
    {
        if (isset($this->enabledBundles['LexikTranslationBundle'])) {
            return true;
        }

        return false;
    }

    public function matches(Request $request)
    {
        return mb_stripos(
                $request->attributes->get('_route'),
                'lexik_translation'
            ) === 0;
    }
}
