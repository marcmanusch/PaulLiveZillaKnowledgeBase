<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 04.02.18
 * Time: 17:04
 */

namespace PaulLiveZillaKnowledgeBase;

use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PaulLiveZillaKnowledgeBase extends Plugin
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('paul_live_zilla_knowledge_base.plugin_dir', $this->getPath());
        parent::build($container);
    }
}