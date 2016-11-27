<?php
/**
 * Testing helper class.
 */

class NeatlineTime_Test_AppTestCase extends Omeka_Test_AppTestCase
{
    private $_dbHelper;

    public static $options = array(
        'item_title' => 50,
        'item_description' => 41,
        'item_date' => 40,
        'item_date_end' => '',
        'render_year' => 'skip',
        'center_date' => '',
    );

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
        $plugin_helper = new Omeka_Test_Helper_Plugin;
        $plugin_helper->setUp('NeatlineTime');

        Omeka_Test_Resource_Db::$runInstaller = true;
    }

    /**
     * Create a timeline for testing.
     *
     * @return NeatlineTime_Timeline
     */
    public function _createTimeline($data = array(), $user = null)
    {
        if (empty($data)) {
            $data['title'] = 'Timeline Title';
            $data['description'] = 'Timeline description.';
            $data['public'] = '1';
            $data['featured'] = '1';
            $data['query'] = array('range' => '1');
            $data['parameters'] = array(
                'render_year' => 'skip',
            );
        }

        $data['owner_id'] = $user ? $user->id : $this->user->id;

        $timeline = new NeatlineTime_Timeline;

        foreach ($data as $k => $v) {
            $timeline->$k = $v;
        }

        $timeline->save();
        return $timeline;
    }
}
