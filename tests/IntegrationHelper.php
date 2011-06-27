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
        $pluginHelper->pluginBroker->setCurrentPluginDirName(self::PLUGIN_NAME);

        if(class_exists('TimelinePlugin')) {
            new TimelinePlugin;
        }

        $pluginHelper->setUp(self::PLUGIN_NAME);
    }
}
