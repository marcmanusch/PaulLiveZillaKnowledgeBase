<?php

namespace PaulLiveZillaKnowledgeBase\Subscriber;

use Enlight\Event\SubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Frontend implements SubscriberInterface
{

    /** @var  ContainerInterface */
    private $container;

    /**
     * Frontend contructor.
     * @param ContainerInterface $container
     **/
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Custom' => 'onFrontendPostDispatch',
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     */
    public function onFrontendPostDispatch(\Enlight_Event_EventArgs $args)
    {
        /** @var $controller \Enlight_Controller_Action */
        $controller = $args->getSubject();
        $view = $controller->View();
        $view->addTemplateDir($this->container->getParameter('paul_live_zilla_knowledge_base.plugin_dir') . '/Resources/Views');
        $config = $this->container->get('shopware.plugin.config_reader')->getByPluginName('PaulLiveZillaKnowledgeBase');

        // Get plugin settings
        $paulServer = $config['server'];
        $paulUser = $config['user'];
        $paulPass = $config['pass'];
        $paulPageID = $config['paulPageID'];
        $paulActive = $config['active'];
        $paulKnowledge = [];

        // Get current page ID
        $sCustomPage = $view->getAssign('sCustomPage');
        $id = $sCustomPage['id'];

        // Check if current View is ID of FAQ page AND plugin is active
        if ($paulPageID == $id & $paulActive == 1) {
            $paulKnowledge[] = static::getKnowledge($paulServer, $paulUser, $paulPass);
            $paulKnowledgeTree[] = static::buildTree($paulKnowledge, 1);

            // Assign FAQ to view
            $view->assign('paulKnowledge', $paulKnowledgeTree);
        }

    }

    public static function getKnowledge($paulServer, $paulUser, $paulPass)
    {
        $apiURL = $paulServer;

        // authentication parameters
        $postd["p_user"] = $paulUser;
        $postd["p_pass"] = md5($paulPass);

        // function parameter
        $postd["p_knowledgebase_entries_list"] = 1;
        $postd["p_json_pretty"] = 0;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiURL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postd));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);

        if ($server_output === false)
            exit(curl_error($ch));

        curl_close($ch);

        $response = json_decode($server_output);

        $paulKnowledge = [];

        foreach ($response->KnowledgeBaseEntries as $obj) {

            $paulKnowledgeEntrie = [
                'Title' => $obj->KnowledgeBaseEntry->Title,
                'Value' => $obj->KnowledgeBaseEntry->Value,
                'Type' => $obj->KnowledgeBaseEntry->Type,
                'ParentId' => $obj->KnowledgeBaseEntry->ParentId,
                'Id' => $obj->KnowledgeBaseEntry->Id,
                'IsPublic' => $obj->KnowledgeBaseEntry->IsPublic
            ];

            $paulKnowledge[] = $paulKnowledgeEntrie;

        }
    }

    public static function buildTree(array $paulKnowledge, $parentId = 1)
    {
        $branch = array();

        foreach ($paulKnowledge as $element) {
            if ((string)$element['ParentId'] === (string)$parentId) {
                $children = static::buildTree($paulKnowledge, $element['Id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}