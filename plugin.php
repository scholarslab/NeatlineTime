<?php
/**
 * PHP 5
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @author Scholars' Lab
 * @copyright 2010-2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package NeatlineTime
 * @link http://omeka.org/codex/Plugins/NeatlineTime
 * @since 1.0
 */

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
require_once NEATLINE_TIME_PLUGIN_DIR . '/OwnershipAclAssertion.php';
require_once NEATLINE_TIME_HELPERS_DIR . '/NeatlineTimeFunctions.php';

new NeatlineTimePlugin;
