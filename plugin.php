<?php
/**
 * Timeline plugin is a helper function for creating SIMILE Timelines from an
 * array of items in Omeka. The plugin allows one to specify metadata elements
 * from which the time data should be derived, as well as the element for
 * defining the caption.
 *
 * This plugin requires that {@link http://www.jquery.org jQuery} be
 * loaded in your theme (preferably in the head element).
 *
 * @author    Scholars' Lab
 * @copyright 2010 The Board and Visitors of the University of Virginia
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   $Id$
 * @package   Timeline
 * @link      http://omeka.org/codex/Plugins/Timeline
 **/

define('TIMELINE_PLUGIN_DIR', dirname(__FILE__));
define('TIMELINE_HELPERS_DIR', TIMELINE_PLUGIN_DIR
                              . DIRECTORY_SEPARATOR
                              . 'helpers');

require_once TIMELINE_PLUGIN_DIR . DIRECTORY_SEPARATOR
        . 'TimelinePlugin.php';
require_once TIMELINE_HELPERS_DIR . DIRECTORY_SEPARATOR
        . 'ThemeHelpers.php';

new TimelinePlugin;
