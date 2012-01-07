<?php

/**
 * NeatlineTime plugin class
 */
class NeatlineTimePlugin
{
    private static $_hooks = array(
        'install',
        'uninstall',
        'define_acl',
        'define_routes',
        'admin_append_to_plugin_uninstall_message',
        'item_browse_sql',
        'admin_theme_header'
    );

    private static $_filters = array(
        'admin_navigation_main',
        'public_navigation_main',
        'define_response_contexts',
        'define_action_contexts'
    );

    private $_db;

    /**
     * Initializes instance properties and hooks the plugin into Omeka.
     */
    public function __construct()
    {

        $this->_db = get_db();
        $this->addHooksAndFilters();

    }

    /**
     * Centralized location where plugin hooks and filters are added
     */
    public function addHooksAndFilters()
    {

        foreach (self::$_hooks as $hookName) {
            $functionName = Inflector::variablize($hookName);
            add_plugin_hook($hookName, array($this, $functionName));
        }

        foreach (self::$_filters as $filterName) {
            $functionName = Inflector::variablize($filterName);
            add_filter($filterName, array($this, $functionName));
        }

    }

    /**
     * Timeline install hook
     */
    public function install()
    {

        $sqlNeatlineTimeline = "CREATE TABLE IF NOT EXISTS `{$this->_db->prefix}neatline_time_timelines` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` TINYTEXT COLLATE utf8_unicode_ci DEFAULT NULL,
            `description` TEXT COLLATE utf8_unicode_ci DEFAULT NULL,
            `query` TEXT COLLATE utf8_unicode_ci DEFAULT NULL,
            `creator_id` INT UNSIGNED NOT NULL,
            `public` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
            `featured` TINYINT(1) NOT NULL DEFAULT '0',
            `added` timestamp NOT NULL default '0000-00-00 00:00:00',
            `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM";

        $this->_db->query($sqlNeatlineTimeline);

    }

    /**
     * Timeline uninstall hook
     */
    public function uninstall()
    {

        $sql = "DROP TABLE IF EXISTS
            `{$this->_db->prefix}neatline_time_timelines`";

        $this->_db->query($sql);

    }

    /**
     * Timeline define_acl hook
     */
    public function defineAcl($acl)
    {

        $acl->loadResourceList(
            array('NeatlineTime_Timelines' => array('browse', 'add', 'edit', 'editSelf', 'editAll', 'query', 'querySelf', 'queryAll', 'delete', 'deleteSelf', 'deleteAll', 'showNotPublic'))
        );

        // All everyone access to browse and show.
        $acl->allow(null, 'NeatlineTime_Timelines', array('show', 'browse'));

        $acl->allow('researcher', 'NeatlineTime_Timelines', 'showNotPublic');
        $acl->allow('contributor', 'NeatlineTime_Timelines', array('add', 'editSelf', 'querySelf', 'deleteSelf', 'showNotPublic'));
        $acl->allow(array('super', 'admin', 'contributor', 'researcher'), 'NeatlineTime_Timelines', array('edit', 'query', 'delete'), new NeatlineTime_OwnershipAclAssertion());

    }

    /**
     * Timeline define_routes hook
     */
    public function defineRoutes($router)
    {

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
                    'controller'    => 'timelines'
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
    public function adminAppendToPluginUninstallMessage()
    {

        echo '<p><strong>Warning</strong>: Uninstalling the Neatline Time plugin
            will remove all custom Timeline records.';

    }

    /**
     * Filter the items_browse_sql to return only items that have a non-empty
     * value for the DC:Date field, when using the neatlinetime-json context.
     * Uses the ItemSearch model (models/ItemSearch.php) to add the check for
     * a non-empty DC:Date.
     *
     * @param Omeka_Db_Select $select
     */
    public function itemBrowseSql($select)
    {

        $context = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch')->getCurrentContext();
        if ($context == 'neatlinetime-json') {
            $dcDate = $this->_db->getTable('Element')->findByElementSetNameAndElementName('Dublin Core', 'Date');
            $search = new ItemSearch($select);
            $newParams[0]['element_id'] = $dcDate->id;
            $newParams[0]['type'] = 'is not empty';
            $search->advanced($newParams);
        }

    }

    /**
     * Include the the neatline CSS changes in the admin header.
     *
     * @return void
     */
    public function adminThemeHeader()
    {

        $request = Zend_Controller_Front::getInstance()->getRequest();

        // Queue CSS.
        if ($request->getModuleName() == 'neatline-time') {
            queue_css('neatline-time-admin');
        }

        // queue js
        queue_js('moment.min');

    }

    /**
     * Timeline admin_navigation_main filter.
     *
     * Adds a button to the admin's main navigation.
     *
     * @param array $nav
     * @return array
     */
    public function adminNavigationMain($nav)
    {

        $nav['Neatline Time'] = uri('neatline-time');
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
    public function publicNavigationMain($nav)
    {

        $nav['Browse Timelines'] = uri('neatline-time/timelines');
        return $nav;

    }

    /**
     * Adds the neatlinetime-json context to response contexts.
     */
    public function defineResponseContexts($context)
    {

        $context['neatlinetime-json'] = array(
            'suffix'  => 'neatlinetime-json',
            'headers' => array('Content-Type' => 'text/javascript')
        );

        return $context;

    }

    /**
     * Adds neatlinetime-json context to the 'browse' and 'show' actions for
     * the Items controller.
     */
    public function defineActionContexts($context, $controller)
    {

        if ($controller instanceof ItemsController) {
            $context['browse'][] = 'neatlinetime-json';
            $context['show'][] = 'neatlinetime-json';
        }

        return $context;

    }

}
