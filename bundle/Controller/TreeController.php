<?php

namespace Prime\Bundle\TranslationsBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\Core\MVC\Symfony\Locale\LocaleConverterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Lexik\Bundle\TranslationBundle\Storage\StorageInterface;
use Lexik\Bundle\TranslationBundle\Util\Overview\StatsAggregator;

class TreeController extends Controller
{
    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    protected $translator;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @var \Lexik\Bundle\TranslationBundle\Storage\StorageInterface
     */
    protected $storage;

    /**
     * @var \Lexik\Bundle\TranslationBundle\Util\Overview\StatsAggregator
     */
    protected $stats;

    /**
     * TreeController constructor.
     *
     * @param \Lexik\Bundle\TranslationBundle\Storage\StorageInterface $storage
     * @param \Lexik\Bundle\TranslationBundle\Util\Overview\StatsAggregator $stats
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(
        StorageInterface $storage,
        StatsAggregator $stats,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $this->translator = $translator;
        $this->router = $router;
        $this->storage = $storage;
        $this->stats = $stats;
    }

    /**
     * Get contents with collections
     *
     * @param bool $isRoot
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getChildrenAction($isRoot = false)
    {
//        $this->denyAccessUnlessGranted('ez:infocollector:read');

        $result = array();

        if ((bool) $isRoot) {
            $result[] = $this->getRootTreeData();
        } else {

            $domains = $this->storage->getTransUnitDomains();

            foreach ($domains as $domain) {
                $result[] = $this->getData($domain, $isRoot);
            }
        }

        return (new JsonResponse())->setData($result);
    }

    /**
     * Generates data for root of the tree.
     *
     * @return array
     */
    protected function getRootTreeData()
    {
        $domains = $this->storage->getTransUnitDomains();
        $count = count($domains);

        return array(
            'id' => '0',
            'parent' => '#',
//            'text' => $this->translator->trans('prime_translations.title', ['%count%' => $count], 'prime_translations'),
            'text' => 'Translations',
            'children' => true,
            'state' => array(
                'opened' => true,
            ),
            'a_attr' => array(
                'href' => $this->router->generate('lexik_translation_overview'),
                'rel' => '0',
            ),
        );
    }

    /**
     * Creates tree structure for Content
     *
     * @param \Netgen\Bundle\InformationCollectionBundle\API\Value\InformationCollection\Content $content
     * @param bool $isRoot
     *
     * @return array
     */
    protected function getData($domain, $isRoot = false)
    {
        $languages = $this->getConfigResolver()->getParameter('languages');

        /** @var LocaleConverterInterface $converter */
        $converter = $this->get('ezpublish.locale.converter');


        $locale = $converter->convertToPOSIX($languages[0]);
        $isoLocale = explode("_", $locale)[0];


        $stats = $this->get('lexik_translation.overview.stats_aggregator')->getStats();

        $domainText = $domain;
        if (!empty($stats[$domain])) {
            if (!empty($stats[$domain][$locale])) {
                $domainText = $domain . ' (' . $stats[$domain][$locale]['keys'] . ')';
            }

            if (!empty($stats[$domain][$isoLocale])) {
                $domainText = $domain . ' (' . $stats[$domain][$isoLocale]['keys'] . ')';
            }
        }

        return array(
            'id' => $domain,
            'parent' => $isRoot ? '#' : '0',
            'text' => $domainText,
            'children' => false,
            'a_attr' => array(
//                'href' => $this->router->generate('lexik_translation_grid') . "#?filter[_domain]=$domain",
                'href' => "",
                'rel' => $domain,
            ),
            'state' => array(
                'opened' => $isRoot,
            ),
        );
    }
}
