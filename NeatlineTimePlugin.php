<?php

if (!defined('NEATLINE_TIME_PLUGIN_DIR')) {
    define('NEATLINE_TIME_PLUGIN_DIR', dirname(__FILE__));
}

if (!defined('NEATLINE_TIME_HELPERS_DIR')) {
    define('NEATLINE_TIME_HELPERS_DIR', NEATLINE_TIME_PLUGIN_DIR . '/helpers');
}

if (!defined('NEATLINE_TIME_FORMS_DIR')) {
    define('NEATLINE_TIME_FORMS_DIR', NEATLINE_TIME_PLUGIN_DIR . '/forms');
}

require_once NEATLINE_TIME_HELPERS_DIR . '/NeatlineTimeFunctions.php';

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
        'items_browse_sql',
        'public_head',
        'admin_head',
        'exhibit_builder_page_head'
    );

    protected $_filters = array(
        'admin_navigation_main',
        'public_navigation_main',
        'response_contexts',
        'action_contexts',
        'exhibit_layouts'
    );

    /**
     * @var array Options and their default values.
     */
    protected $_options = array(
        'neatlinetime' => null,
        // Can be 'simile' or 'knightlab'.
        'neatline_time_library' => 'simile',
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
            `center_date` date NULL,
            `added` timestamp NOT NULL default '2000-01-01 00:00:00',
            `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            ) ENGINE=innodb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

        $this->_db->query($sqlNeatlineTimeline);

        $this->_setDefaultOptions();
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

        // Earlier than version 1.1.
        if (version_compare($oldVersion, '1.1', '<')) {
            if (!get_option('neatlinetime')) {
                $this->_setDefaultOptions();
            }
        }

        if (version_compare($oldVersion, '2.0.2', '<') && version_compare($oldVersion, '2.0', '>') ) {
            if ($timelines = get_records('NeatlineTimeTimeline')) {
                foreach ($timelines as $timeline) {
                    $query = unserialize($timeline->query);
                    while (!is_array($query)) {
                        $query = unserialize($query);
                    }
                    $timeline->query = serialize($query);
                    $timeline->save();
                }
            }
        }

        if (version_compare($oldversion, '2.1', '<')) {
          $rows = $this->_db->query(
            "show columns from {$this->_db->prefix}neatline_time_timelines where field='center_date';"
          );

          if ($rows->rowCount() === 0) {
            $sqlNeatlineTimeline = "ALTER TABLE  `{$this->_db->prefix}neatline_time_timelines`
            ADD COLUMN `center_date` date NOT NULL default '0000-00-00'";

            $this->_db->query($sqlNeatlineTimeline);
          }
        }

        if (version_compare($oldversion, '2.1.1', '<')) {
            $sql = "
                ALTER TABLE  `{$this->_db->prefix}neatline_time_timelines`
                MODIFY COLUMN `center_date` date NULL,
                MODIFY COLUMN `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
            ";

            $this->_db->query($sql);
        }

        if (version_compare($oldversion, '2.1.2', '<')) {
            $sql = "
                ALTER TABLE  `{$this->_db->prefix}neatline_time_timelines`
                MODIFY COLUMN `added` timestamp NOT NULL default '2000-01-01 00:00:00'
            ";

            $this->_db->query($sql);
        }

        if (version_compare($oldversion, '2.1.3', '<')) {
            set_option('neatline_time_library', $this->_options['neatline_time_library']);
        }

        if (version_compare($oldversion, '2.1.4', '<')) {
            $sql = "
            ALTER TABLE  `{$this->_db->prefix}neatline_time_timelines`
            CHANGE COLUMN `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            CHANGE COLUMN `creator_id` `owner_id` INT(10) UNSIGNED NOT NULL
            ";

            $this->_db->query($sql);
        }
    }

    /**
     * Timeline uninstall hook
     */
    public function hookUninstall()
    {

        $sql = "DROP TABLE IF EXISTS
        `{$this->_db->prefix}neatline_time_timelines`";

        $this->_db->query($sql);

        $this->_uninstallOptions();
    }

    /**
     * Display the uninstall message.
     */
    public function hookUninstallMessage()
    {
        $string = __('<strong>Warning</strong>: Uninstalling the Neatline Time plugin
          will remove all custom Timeline records.');

        echo '<p>'.$string.'</p>';

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
                    'module'        => 'neatline-time',
                    'controller'    => 'timelines'
                    ),
                array('id'          => '\d+')
                )
            );

        $router->addRoute(
            'timelineDefaultRoute',
            new Zend_Controller_Router_Route(
                'neatline-time/timelines/:action',
                array(
                    'module'        => 'neatline-time',
                    'controller'    => 'timelines'
                    )
                )
            );

        $router->addRoute(
            'timelineRedirectRoute',
            new Zend_Controller_Router_Route(
                'neatline-time',
                array(
                    'module'        => 'neatline-time',
                    'controller'    => 'timelines',
                    'action'        => 'browse'
                    )
                )
            );

        $router->addRoute(
            'timelinePaginationRoute',
            new Zend_Controller_Router_Route(
                'neatline-time/timelines/:page',
                array(
                    'module'        => 'neatline-time',
                    'controller'    => 'timelines',
                    'action'        => 'browse',
                    'page'          => '1'
                    ),
                array('page'        => '\d+')
                )
            );

    }

    /**
     * Hook used to alter the query for items.
     *
     * @param array $args
     */
    public function hookItemsBrowseSql($args)
    {
        // Filter the items_browse_sql to return only items that have a non-empty
        // value for the DC:Date field, when using the neatlinetime-json context.
        // Uses the ItemSearch model (models/ItemSearch.php) to add the check for
        // a non-empty DC:Date.

        $db = $this->_db;
        $select = $args['select'];
        $params = $args['params'];

        $context = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch')->getCurrentContext();
        if ($context == 'neatlinetime-json') {
            $search = new ItemSearch($select);
            $newParams[0]['element_id'] = neatlinetime_get_option('item_date');
            $newParams[0]['type'] = 'is not empty';
            $search->advanced($newParams);
        }

    }

    /**
     * Shows plugin configuration page.
     *
     * @return void
     */
    public function hookConfigForm($args)
    {
        $view = $args['view'];
        echo $view->partial(
            'plugins/neatline-time-config-form.php',
            array());
    }

    /**
     * Processes the configuration form.
     *
     * @return void
     */
    public function hookConfig($args)
    {
        $post = $args['post'];

        // Set the specified values, else the standard values of Omeka.
        $options = array();
        $options['item_title'] = isset($post['item_title']) ? (integer) $post['item_title'] : 50;
        $options['item_description'] = isset($post['item_description']) ? (integer) $post['item_description'] : 41;
        $options['item_date'] = isset($post['item_date']) ? (integer) $post['item_date'] : 40;
        $post['neatlinetime'] = serialize($options);

        foreach ($this->_options as $optionKey => $optionValue) {
            if (isset($post[$optionKey])) {
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

    protected function _setDefaultOptions()
    {
        $options = array();
        $fields = array('Title', 'Description', 'Date');

        foreach ($fields as $field) {
            $key = 'item_'.strtolower($field);
            $element = $this->_db->getTable('Element')->findByElementSetNameAndElementName("Dublin Core", "$field");
            $options[$key] = $element->id;
        }

        $options = serialize($options);
        $this->_options['neatlinetime'] = $options;
        set_option('neatlinetime', $options);
    }
}
