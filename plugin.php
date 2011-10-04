<?php
/**
 * @author Scholars' Lab
 * @copyright 2010-2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Neatline Time
 * @link http://omeka.org/codex/Plugins/NeatlineTime
 * @since 1.0
 */

if (!defined('TIMELINE_PLUGIN_DIR')) {
    define('TIMELINE_PLUGIN_DIR', dirname(__FILE__));
}

<<<<<<< HEAD
if (!defined('TIMELINE_HELPERS_DIR')) {
<<<<<<< HEAD
    define('TIMELINE_HELPERS_DIR', TIMELINE_PLUGIN_DIR . '/helpers');
=======
if (!defined('NEATLINE_TIME_HELPERS_DIR')) {
    define('NEATLINE_TIME_HELPERS_DIR', NEATLINE_TIME_PLUGIN_DIR . '/helpers');
>>>>>>> rename
=======
    define('TIMELINE_HELPERS_DIR', TIMELINE_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'helpers');
>>>>>>> parent of eef36ff... got rid of DIR_SEPARATORS
}

if (!defined('TIMELINE_FORMS_DIR')) {
    define('TIMELINE_FORMS_DIR', TIMELINE_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'forms');
}

<<<<<<< HEAD
<<<<<<< HEAD
require_once TIMELINE_PLUGIN_DIR . '/TimelinePlugin.php';
require_once TIMELINE_HELPERS_DIR . '/TimelineFunctions.php';
=======
require_once NEATLINE_TIME_PLUGIN_DIR . '/NeatlineTimePlugin.php';
require_once NEATLINE_TIME_HELPERS_DIR . '/NeatlineTimeFunctions.php';
>>>>>>> rename
=======
require_once TIMELINE_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'TimelinePlugin.php';
require_once TIMELINE_HELPERS_DIR . DIRECTORY_SEPARATOR . 'TimelineFunctions.php';
>>>>>>> parent of eef36ff... got rid of DIR_SEPARATORS

new NeatlineTimePlugin;
