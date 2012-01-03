<?php
/**
 * Testing helper class.
 */
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
    public function _createTimeline($data = array(), $user = null)
    {
        if (empty($data)) {
            $data['title'] = 'Timeline Title';
            $data['description'] = 'Timeline description.';
            $data['public'] = '1';
            $data['featured'] = '1';
            $data['query'] = array('range' => '1');
        }

        $data['creator_id'] = $user ? $user->id : $this->user->id;

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
