<?php

namespace PaulLiveZillaKnowledgeBase\Subscriber;

use Enlight\Event\SubscriberInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AddJs
 * @package PaulLiveZillaKnowledgeBase\Subscriber
 */
class AddJs implements SubscriberInterface
{

    /** @var ContainerInterface */
    private $container;

    /**
     * AddJs constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Theme_Compiler_Collect_Plugin_Javascript' => 'addJavascriptFiles'
        ];
    }

    /**
     * Provides an ArrayCollection for js compressing
     * @param Enlight_Event_EventArgs $args
     *
     * @return ArrayCollection
     */
    public function addJavascriptFiles(\Enlight_Event_EventArgs $args)
    {
        $pluginDir = $this->container->getParameter('paul_live_zilla_knowledge_base.plugin_dir');

        $js = $pluginDir . '/Resources/Views/frontend/_public/src/js/paulCollapse.js';

        return new ArrayCollection(array($js));

    }
}