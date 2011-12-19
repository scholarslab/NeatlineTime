<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4; */

/**
 * Testing helper class.
 *
 * PHP version 5
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @package     omeka
 * @subpackage  neatline
 * @author      Scholars' Lab <>
 * @author      Bethany Nowviskie <bethany@virginia.edu>
 * @author      Adam Soroka <ajs6f@virginia.edu>
 * @author      David McClure <david.mcclure@virginia.edu>
 * @copyright   2011 The Board and Visitors of the University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache 2 License
 */
?>

<?php

require_once '../NeatlineTimePlugin.php';

class NeatlineTime_Test_AppTestCase extends Omeka_Test_AppTestCase
{

    private $_dbHelper;

    /**
     * Spin up the plugins and prepare the database.
     *
     * @return void.
     */
    public function setUp()
    {

        parent::setUp();

        $this->user = $this->db->getTable('User')->find(1);
        $this->_authenticateUser($this->user);

        // Set up Neatline Time.
        $plugin_broker = get_plugin_broker();
        $this->_addPluginHooksAndFilters($plugin_broker, 'NeatlineTime');
        $plugin_helper = new Omeka_Test_Helper_Plugin;
        $plugin_helper->setUp('NeatlineTime');

        $this->_dbHelper = Omeka_Test_Helper_Db::factory($this->core);

    }

    /**
     * Install the plugin.
     *
     * @return void.
     */
    public function _addPluginHooksAndFilters($plugin_broker, $plugin_name)
    {

        $plugin_broker->setCurrentPluginDirName($plugin_name);
        new NeatlineTimePlugin;

    }

    /**
     * Create a timeline for testing.
     *
     * @return NeatlineTimeTimeline
     */
    public function _createTimeline($data = array())
    {
        if (empty($data)) {
            $data['title'] = 'Timeline Title';
            $data['description'] = 'Timeline description.';
            $data['public'] = '1';
            $data['featured'] = '1';
            $data['creator'] = $this->user->id;
            $data['query'] = array('range' => '1');
        }

        $timeline = new NeatlineTimeTimeline;

        foreach ($data as $k => $v) {
            if ($k == 'query') {
                $v = serialize($v);
            }
            $timeline->$k = $v;
        }

        $timeline->save();
        return $timeline;
    }

}
