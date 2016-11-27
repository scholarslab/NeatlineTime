<?php

if (!defined('NEATLINE_TIME_HELPERS_DIR')) {
    define('NEATLINE_TIME_HELPERS_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR
        . 'libraries' . DIRECTORY_SEPARATOR
        . 'NeatlineTime');
}
require_once NEATLINE_TIME_HELPERS_DIR . DIRECTORY_SEPARATOR . 'Functions.php';

/**
 * NeatlineTime plugin class
 */
class NeatlineTimePlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'initialize',
        'install',
        'upgrade',
        'uninstall',
        'uninstall_message',
        'config',
        'config_form',
        'define_acl',
        'define_routes',
        'public_head',
        'admin_head',
        'exhibit_builder_page_head',
    );

    protected $_filters = array(
        'admin_navigation_main',
        'public_navigation_main',
        'response_contexts',
        'action_contexts',
        'exhibit_layouts',
        'items_browse_params',
    );

    /**
     * @var array Options and their default values.
     */
    protected $_options = array(
        // Can be 'simile' or 'knightlab'.
        'neatline_time_library' => 'simile',
        'neatline_time_defaults' => array(
            // Numbers are the id of elements of a standard install of Omeka.
            'item_title' => 50,
            'item_description' => 41,
            'item_date' => 40,
            'item_date_end' => '',
            'render_year' => 'skip',
            'center_date' => '',
        ),
    );

    /**
     * Timeline initialize hook
     */
    public function hookInitialize()
    {
        add_translation_source(dirname(__FILE__) . '/languages');
    }

    /**
     * Timeline install hook
     */
    public function hookInstall()
    {
        $sqlNeatlineTimeline = "CREATE TABLE IF NOT EXISTS `{$this->_db->prefix}neatline_time_timelines` (
            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` TINYTEXT COLLATE utf8_unicode_ci DEFAULT NULL,
            `description` TEXT COLLATE utf8_unicode_ci DEFAULT NULL,
            `query` TEXT COLLATE utf8_unicode_ci DEFAULT NULL,
            `owner_id` INT(10) UNSIGNED NOT NULL,
            `public` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
            `featured` TINYINT(1) NOT NULL DEFAULT '0',
            `parameters` TEXT COLLATE utf8_unicode_ci NOT NULL,
            `added` timestamp NOT NULL default '2000-01-01 00:00:00',
            `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `public` (`public`),
            KEY `featured` (`featured`),
            KEY `owner_id` (`owner_id`)
        ) ENGINE=innodb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

        $this->_db->query($sqlNeatlineTimeline);

        $this->_options['neatline_time_defaults'] = json_encode($this->_options['neatline_time_defaults']);
        $this->_installOptions();
    }

    /**
     * Timeline upgrade hook.
     *
     * Add newer upgrade checks after existing ones.
     */
    public function hookUpgrade($args)
    {
        $oldVersion = $args['old_version'];
        $newVersion = $args['new_version'];
        $db = $this->_db;

        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade.php';
    }

    /**
     * Timeline uninstall hook
     */
    public function hookUninstall()
    {
        $sql = "DROP TABLE IF EXISTS
        `{$this->_db->prefix}neatline_time_timelines`";

        $this->_db->query($sql);

        // Remove old options.
        delete_option('neatlinetime');
        delete_option('neatline_time_render_year');

        $this->_uninstallOptions();
    }

    /**
     * Display the uninstall message.
     */
    public function hookUninstallMessage()
    {
        $string = __('<strong>Warning</strong>: Uninstalling the Neatline Time plugin
          will remove all custom Timeline records.');
        echo '<p>' . $string . '</p>';
    }

    /**
     * Timeline define_acl hook
     */
    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];

        $acl->addResource('NeatlineTime_Timelines');

        // All everyone access to browse, show, and items.
        $acl->allow(null, 'NeatlineTime_Timelines', array('show', 'browse', 'items'));

        $acl->allow('researcher', 'NeatlineTime_Timelines', 'showNotPublic');
        $acl->allow('contributor', 'NeatlineTime_Timelines', array('add', 'editSelf', 'querySelf', 'itemsSelf', 'deleteSelf', 'showNotPublic'));
        $acl->allow(array('super', 'admin', 'contributor', 'researcher'), 'NeatlineTime_Timelines', array('edit', 'query', 'items', 'delete'), new Omeka_Acl_Assert_Ownership);
    }

    /**
     * Timeline define_routes hook
     */
    public function hookDefineRoutes($args)
    {
        $router = $args['router'];

        $router->addRoute(
            'timelineActionRoute',
            new Zend_Controller_Router_Route(
                'neatline-time/timelines/:action/:id',
                array(
                    'module' => 'neatline-time',
                    'controller' => 'timelines'
                ),
                array('id' => '\d+')
            )
        );

        $router->addRoute(
            'timelineDefaultRoute',
            new Zend_Controller_Router_Route(
                'neatline-time/timelines/:action',
                array(
                    'module' => 'neatline-time',
                    'controller' => 'timelines'
                )
            )
        );

        $router->addRoute(
            'timelineRedirectRoute',
            new Zend_Controller_Router_Route(
                'neatline-time',
                array(
                    'module' => 'neatline-time',
                    'controller' => 'timelines',
                    'action' => 'browse'
                )
            )
        );

        $router->addRoute(
            'timelinePaginationRoute',
            new Zend_Controller_Router_Route(
                'neatline-time/timelines/:page',
                array(
                    'module' => 'neatline-time',
                    'controller' => 'timelines',
                    'action' => 'browse',
                    'page' => '1'
                ),
                array('page' => '\d+')
            )
        );
    }

    /**
     * Shows plugin configuration page.
     *
     * @return void
     */
    public function hookConfigForm($args)
    {
        $defaults = get_option('neatline_time_defaults');
        $defaults = json_decode($defaults, true) ?: $this->_options['neatline_time_defaults'];

        $view = $args['view'];
        echo $view->partial(
            'plugins/neatline-time-config-form.php',
            array(
                'defaults' => $defaults,
            ));
    }

    /**
     * Processes the configuration form.
     *
     * @return void
     */
    public function hookConfig($args)
    {
        $post = $args['post'];
        foreach ($this->_options as $optionKey => $optionValue) {
            if (isset($post[$optionKey])) {
                if (is_array($optionValue)) {
                    $post[$optionKey] = json_encode($post[$optionKey]);
                }
                set_option($optionKey, $post[$optionKey]);
            }
        }
    }

    public function hookAdminHead($args)
    {
        $requestParams = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        $module = isset($requestParams['module']) ? $requestParams['module'] : 'default';
        $controller = isset($requestParams['controller']) ? $requestParams['controller'] : 'index';
        $action = isset($requestParams['action']) ? $requestParams['action'] : 'index';
        if ($module != 'neatline-time' || $controller != 'timelines' || $action != 'show') {
            return;
        }
        $this->_head($args);
    }

    public function hookPublicHead($args)
    {
        $requestParams = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        $module = isset($requestParams['module']) ? $requestParams['module'] : 'default';
        $controller = isset($requestParams['controller']) ? $requestParams['controller'] : 'index';
        $action = isset($requestParams['action']) ? $requestParams['action'] : 'index';
        if ($module != 'neatline-time' || $controller != 'timelines' || $action != 'show') {
            return;
        }
        $this->_head($args);
    }

    /**
     * Add timeline assets for exhibit pages using the timeline layout.
     */
    public function hookExhibitBuilderPageHead($args)
    {
        if (array_key_exists('neatline-time', $args['layouts'])) {
            $this->_head($args);
        }
    }

    /**
     * Load all assets.
     *
     * Replace queue_timeline_assets()
     *
     * @return void
     */
    private function _head($args)
    {
        $library = get_option('neatline_time_library');
        if ($library == 'knightlab') {
            queue_css_url('//cdn.knightlab.com/libs/timeline3/latest/css/timeline.css');
            queue_js_url('//cdn.knightlab.com/libs/timeline3/latest/js/timeline.js');
            return;
        }

        // Default neatline library.
        queue_css_file('neatlinetime-timeline');

        queue_js_file('neatline-time-scripts');

        // Check useInternalJavascripts in config.ini.
        $config = Zend_Registry::get('bootstrap')->getResource('Config');
        $useInternalJs = isset($config->theme->useInternalJavascripts)
            ? (bool) $config->theme->useInternalJavascripts
            : false;
        $useInternalJs = isset($config->theme->useInternalAssets)
            ? (bool) $config->theme->useInternalAssets
            : $useInternalJs;

        if ($useInternalJs) {
            $timelineVariables = 'Timeline_ajax_url="' . src('simile-ajax-api.js', 'javascripts/simile/ajax-api') . '";
                Timeline_urlPrefix="' . dirname(src('timeline-api.js', 'javascripts/simile/timeline-api')) . '/";
                Timeline_parameters="bundle=true";';
            queue_js_string($timelineVariables);
            queue_js_file('timeline-api', 'javascripts/simile/timeline-api');
        } else {
            queue_js_url('//api.simile-widgets.org/timeline/2.3.1/timeline-api.js?bundle=true');
        }
        queue_js_string('SimileAjax.History.enabled = false; // window.jQuery = SimileAjax.jQuery;');
    }

    /**
     * Timeline admin_navigation_main filter.
     *
     * Adds a button to the admin's main navigation.
     *
     * @param array $nav
     * @return array
     */
    public function filterAdminNavigationMain($nav)
    {
        $nav[] = array(
            'label' => __('Neatline Time'),
            'uri' => url('neatline-time'),
            'resource' => 'NeatlineTime_Timelines',
            'privilege' => 'browse'
        );
        return $nav;
    }

    /**
     * Timeline public_navigation_main filter.
     *
     * Adds a button to the public theme's main navigation.
     *
     * @param array $nav
     * @return array
     */
    public function filterPublicNavigationMain($nav)
    {
        $nav[] = array(
            'label' => __('Neatline Time'),
            'uri' => url('neatline-time')
        );
        return $nav;
    }

    /**
     * Adds the neatlinetime-json context to response contexts.
     */
    public function filterResponseContexts($contexts)
    {
        $contexts['neatlinetime-json'] = array(
            'suffix'  => 'neatlinetime-json',
            'headers' => array('Content-Type' => 'text/javascript')
        );
        return $contexts;
    }

    /**
     * Adds neatlinetime-json context to the 'items' actions for the
     * NeatlineTime_TimelinesController.
     */
    public function filterActionContexts($contexts, $args)
    {
        if ($args['controller'] instanceof NeatlineTime_TimelinesController) {
            $contexts['items'][''] = 'neatlinetime-json';
        }
        return $contexts;
    }

    /**
     * Register an exhibit layout for displaying a timeline.
     *
     * @param array $layouts Exhibit layout specs.
     * @return array
     */
    public function filterExhibitLayouts($layouts)
    {
        $layouts['neatline-time'] = array(
            'name' => __('Neatline Time'),
            'description' => __('Embed a NeatlineTime timeline.')
        );
        return $layouts;
    }

    /**
     * Filter items browse params.
     *
     * @param array $params
     * @return array
     */
    public function filterItemsBrowseParams($params)
    {
        // Filter the items to return only items that have a non-empty value for
        // the DC:Date or the specified field when using the neatlinetime-json
        // context.
        $context = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch')->getCurrentContext();
        if ($context != 'neatlinetime-json') {
            return $params;
        }
        $id = (integer) Zend_Controller_Front::getInstance()->getRequest()->getParam('id');
        if (empty($id)) {
            return $params;
        }
        $timeline = $this->_db->getTable('NeatlineTime_Timeline')->find($id);
        if (empty($timeline)) {
            return $params;
        }
        $params['advanced'][] = array(
            'joiner' => 'and',
            'element_id' => $timeline->getProperty('item_date'),
            'type' =>'is not empty',
        );
        return $params;
    }
}
