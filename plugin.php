<?php
/**
 * @author Scholars' Lab
 * @copyright 2010-2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Neatline Time
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */

if (!defined('NEATLINE_TIME_PLUGIN_DIR')) {
    define('NEATLINE_TIME_PLUGIN_DIR', dirname(__FILE__));
}

if (!defined('NEATLINE_TIME_HELPERS_DIR')) {
    define('NEALTINE_TIME_HELPERS_DIR', NEATLINE_TIME_PLUGIN_DIR . '/helpers');
}

if (!defined('NEATLINE_TIME_FORMS_DIR')) {
    define('NEATLINE_TIME_FORMS_DIR', NEATLINE_TIME_PLUGIN_DIR . '/forms');
}

require_once NEATLINE_TIME_PLUGIN_DIR . '/TimelinePlugin.php';
require_once NEATLINE_TIME_PLUGIN_DIR . '/TimelineFunctions.php';

new NeatlineTimePlugin;
