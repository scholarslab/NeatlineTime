<?php
/**
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */

/**
 * Timeline plugin class
 *
 * @package Timeline
 */
class TimelinePlugin
{
    private static $_hooks = array(
        'install',
        'uninstall',
        'define_acl',
        'define_routes',
        'admin_append_to_plugin_uninstall_message',
        'item_browse_sql'
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
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->_db->prefix}timelines` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` TINYTEXT COLLATE utf8_unicode_ci DEFAULT NULL,
            `description` TEXT COLLATE utf8_unicode_ci DEFAULT NULL,
            `creator_id` INT UNSIGNED NOT NULL,
            `public` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
            `featured` TINYINT(1) NOT NULL DEFAULT '0',
            `added` timestamp NOT NULL default '0000-00-00 00:00:00',
            `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM;";
        $this->_db->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->_db->prefix}timeline_entries` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `timeline_id` INT UNSIGNED NOT NULL,
            `data` TEXT COLLATE utf8_unicode_ci NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM;";
        $this->_db->query($sql);
    }

    /**
     * Timeline uninstall hook
     */
    public function uninstall()
    {
        $sql = "DROP TABLE IF EXISTS
            `{$this->_db->prefix}timelines`,
            `{$this->_db->prefix}timeline_entries`;";
        $this->_db->query($sql);
    }

    /**
     * Timeline define_acl hook
     */
    public function defineAcl($acl)
    {
        $acl->loadResourceList(
            array('Timeline_Timelines' => array('browse', 'add', 'edit', 'editSelf', 'editAll', 'delete', 'deleteSelf', 'deleteAll'))
        );

        // All everyone access to browse and show.
        $acl->allow(null, 'Timeline_Timelines', array('show', 'browse'));
        
        // Allow contributors everything but editAll and deleteAll.
        $acl->allow('contributor', 'Timeline_Timelines');
        $acl->deny('contributor', 'Timeline_Timelines', array('editAll', 'deleteAll'));
    }

    /**
     * Timeline define_routes hook
     */
    public function defineRoutes($router)
    {
        $router->addRoute(
            'timelineActionRoute',
            new Zend_Controller_Router_Route(
                'timelines/:action/:id',
                array(
                    'module'        => 'timeline',
                    'controller'    => 'timelines'
                    ),
                array( 'id'         => '\d+')
                )
            );

        $router->addRoute(
            'timelineDefaultRoute',
            new Zend_Controller_Router_Route(
                'timelines/:action',
                array(
                    'module'        => 'timeline',
                    'controller'    => 'timelines'
                    )
                )
            );

        $router->addRoute(
            'timelinePaginationRoute',
            new Zend_Controller_Router_Route(
                'timelines/:page',
                array(
                    'module'        => 'timeline',
                    'controller'    => 'timelines',
                    'action'        => 'browse',
                    'page'          => '1'
                    ),
                array( 'page'       => '\d+')
                )
            );
    }

    /**
     * Timeline admin_append_to_plugin_uninstall_message hook
     */
    public function adminAppendToPluginUninstallMessage()
    {
        echo '<p><strong>Warning</strong>: Uninstalling the Timeline plugin
            will remove all custom Timeline records, and will remove the
            ability to browse items on a timeline.';
    }

    /**
     * Deal with Timeline-specific search terms.
     *
     * @param Omeka_Db_Select $select
     * @param array $params
     */
    public function itemBrowseSql($select, $params)
    {
        if (($request = Zend_Controller_Front::getInstance()->getRequest())) {
            
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
    public function adminNavigationMain($nav)
    {
        $nav['Timelines'] = uri('timelines');
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
        $nav['Browse Timelines'] = uri('timelines');
        return $nav;
    }

    /**
     * Adds the timeglider-json context to response contexts.
     */
    public function defineResponseContexts($context)
    {
        $context['timeglider-json'] = array('suffix'  => 'timeglider-json', 
                                'headers' => array('Content-Type' => 'text/javascript'));
        
        return $context;
    }

    /**
     * Adds timeglider-json context to the 'browse' and 'show' actions for the
     * Items and Timeline_Timelines controllers.
     */
    public function defineActionContexts($context, $controller)
    {
        if ($controller instanceof Timeline_TimelinesController || $controller instanceof ItemsController) {
            $context['browse'][] = 'timeglider-json';
            $context['show'][] = 'timeglider-json';
        }

        return $context;
    }
}
