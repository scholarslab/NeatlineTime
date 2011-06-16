<?php
/**
 * @author      Scholars' Lab
 * @copyright   2010-2011 The Board and Visitors of the University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version     $Id$
 * @package     Timeline
 * @link        http://omeka.org/codex/Plugins/Timeline
 */

/**
 * Timeline test integration helper.
 *
 * @copyright   2010-2011 The Board and Visitors of the University of Virginia
 * @package     Timeline
 */
class Timeline_IntegrationHelper
{
    const PLUGIN_NAME = 'Timeline';
    
    public function setUpPlugin()
    {        
        $pluginHelper = new Omeka_Test_Helper_Plugin;
        $this->addPluginHooksAndFilters($pluginHelper->pluginBroker, self::PLUGIN_NAME);
        $pluginHelper->setUp(self::PLUGIN_NAME);
    }
        
    public function addPluginHooksAndFilters($pluginBroker, $pluginName)
    {   
        // Set the current plugin so the add_plugin_hook function works
        $pluginBroker->setCurrentPluginDirName($pluginName);

        // Add plugin hooks
        add_plugin_hook('install', 'TimelinePlugin::install');
        add_plugin_hook('uninstall', 'TimelinePlugin::uninstall');
        add_plugin_hook('define_acl', 'TimelinePlugin::defineAcl');
        add_plugin_hook('define_routes', 'TimelinePlugin::defineRoutes');
        add_plugin_hook('admin_append_to_plugin_uninstall_message', 'TimelinePlugin::adminAppendToPluginUninstallMessage');

        // Add plugin filters
        add_filter('admin_navigation_main', 'TimelinePlugin::adminNavigationMain');
        add_filter('define_response_contexts', 'TimelinePlugin::defineResponseContexts');     
        add_filter('define_action_contexts', 'TimelinePlugin::defineActionContexts');     
    }
}
