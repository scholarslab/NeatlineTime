<?php
/**
 * @author Scholars' Lab
 * @copyright 2010-2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */

if (!defined('TIMELINE_PLUGIN_DIR')) {
    define('TIMELINE_PLUGIN_DIR', dirname(__FILE__));
}

if (!defined('TIMELINE_HELPERS_DIR')) {
    define('TIMELINE_HELPERS_DIR', TIMELINE_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'helpers');
}

if (!defined('TIMELINE_FORMS_DIR')) {
    define('TIMELINE_FORMS_DIR', TIMELINE_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'forms');
}

require_once TIMELINE_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'TimelinePlugin.php';
require_once TIMELINE_HELPERS_DIR . DIRECTORY_SEPARATOR . 'TimelineFunctions.php';

new TimelinePlugin;
