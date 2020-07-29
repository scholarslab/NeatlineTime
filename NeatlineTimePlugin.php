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

require_once NEATLINE_TIME_PLUGIN_DIR . '/NeatlineTimePlugin.php';
require_once NEATLINE_TIME_HELPERS_DIR . '/NeatlineTimeFunctions.php';

/**
 * NeatlineTime plugin class
 */
class NeatlineTimePlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'install',
        'uninstall',
        'upgrade',
        'initialize',
        'define_acl',
        'define_routes',
        'admin_append_to_plugin_uninstall_message',
        'item_browse_sql',
        'config',
        'config_form',
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
     * Timeline install hook
     */
    public function hookInstall()
    {
        $sqlNeatlineTimeline = "CREATE TABLE IF NOT EXISTS `{$this->_db->prefix}neatline_time_timelines` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` TINYTEXT COLLATE utf8_unicode_ci DEFAULT NULL,
            `description` TEXT COLLATE utf8_unicode_ci DEFAULT NULL,
            `query` TEXT COLLATE utf8_unicode_ci DEFAULT NULL,
            `creator_id` INT UNSIGNED NOT NULL,
            `public` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
            `featured` TINYINT(1) NOT NULL DEFAULT '0',
            `center_date` date NOT NULL default '2018-01-01',
            `added` timestamp NOT NULL default '2000-01-01 00:00:00',
            `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            ) ENGINE=innodb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

        $this->_db->query($sqlNeatlineTimeline);

        $this->setDefaultOptions();

    }

    /**
     * Timeline uninstall hook
     */
    public function hookUninstall()
    {

        $sql = "DROP TABLE IF EXISTS
            `{$this->_db->prefix}neatline_time_timelines`";

        $this->_db->query($sql);

        delete_option('neatlinetime');

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
                $this->setDefaultOptions();
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

        if (version_compare($oldVersion, '2.1', '<')) {
          $rows = $this->_db->query(
            "show columns from {$this->_db->prefix}neatline_time_timelines where field='center_date';"
          );

          if ($rows->rowCount() === 0) {
            $sqlNeatlineTimeline = "ALTER TABLE  `{$this->_db->prefix}neatline_time_timelines`
            ADD COLUMN `center_date` date NOT NULL default '0000-00-00'";

            $this->_db->query($sqlNeatlineTimeline);
          }
        }

        if (version_compare($oldVersion, '2.1.1', '<')) {
            $sql = "ALTER TABLE `{$this->_db->prefix}neatline_time_timelines`
            MODIFY COLUMN `center_date` date NOT NULL default '2018-01-01',
            MODIFY COLUMN `added` timestamp NOT NULL default CURRENT_TIMESTAMP";
            $this->_db->query($sql);
        }

        if (version_compare($oldVersion, '2.1.2', '<')) {
            $sql = "ALTER TABLE `{$this->_db->prefix}neatline_time_timelines`
            MODIFY COLUMN `added` timestamp NOT NULL default '2000-01-01 00:00:00'";
            $this->_db->query($sql);
        }

    }

    /**
     * Timeline initialize hook
     */
    public function hookInitialize()
    {
        add_translation_source(dirname(__FILE__) . '/languages');
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
     * Timeline admin_append_to_plugin_uninstall_message hook
     */
    public function hookAdminAppendToPluginUninstallMessage()
    {
        $string = __('<strong>Warning</strong>: Uninstalling the Neatline Time plugin
          will remove all custom Timeline records.');

        echo '<p>'.$string.'</p>';

    }

    /**
     * Filter the items_browse_sql to return only items that have a non-empty
     * value for the DC:Date field, when using the neatlinetime-json context.
     * Uses the ItemSearch model (models/ItemSearch.php) to add the check for
     * a non-empty DC:Date.
     *
     * @param Omeka_Db_Select $select
     */
    public function hookItemBrowseSql()
    {

        $context = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch')->getCurrentContext();
        if ($context == 'neatlinetime-json') {
            $search = new ItemSearch($select);
            $newParams[0]['element_id'] = neatlinetime_get_option('item_date');
            $newParams[0]['type'] = 'is not empty';
            $search->advanced($newParams);
        }

    }

    /**
     * Plugin configuration.
     */
    public function hookConfig()
    {
      $options = $_POST;
      unset($options['install_plugin']);
      $options = serialize($options);
      set_option('neatlinetime', $options);
    }

    /**
     * Plugin configuration form.
     */
    public function hookConfigForm()
    {
        include 'config_form.php';
    }

    /**
     * Add timeline assets for exhibit pages using the timeline layout.
     */
    public function hookExhibitBuilderPageHead($args)
    {
        if (array_key_exists('neatline-time', $args['layouts'])) {
            queue_timeline_assets();
        }
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

    protected function setDefaultOptions()
    {
        $options = array();
        $fields = array('Title', 'Description', 'Date');

        foreach ($fields as $field) {
            $key = 'item_'.strtolower($field);
            $element = $this->_db->getTable('Element')->findByElementSetNameAndElementName("Dublin Core", "$field");
            $options[$key] = $element->id;
        }

        $options = serialize($options);
        set_option('neatlinetime', $options);
    }
}
