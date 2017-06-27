<?php

/**
 * Test the NeatlineTime_Timeline model.
 */
class NeatlineTime_TimelineTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->dbAdapter = new Zend_Test_DbAdapter;
        $this->db = new Omeka_Db($this->dbAdapter);
        $this->timeline = new NeatlineTime_Timeline($this->db);
        $this->user = new User($this->db);
        $bootstrap = new Omeka_Test_Bootstrap;
        $bootstrap->getContainer()->db = $this->db;
        Zend_Registry::set('bootstrap', $bootstrap);

        //The process don't use the default install process, so force options.
        set_option('neatline_time_defaults', json_encode(NeatlineTime_Test_AppTestCase::$options));
    }

    public function tearDown()
    {
        Zend_Registry::_unsetInstance();
    }

    public function testGetSetProperties()
    {
        $this->timeline->title = 'Timeline Title';
        $this->timeline->description = 'Timeline Description';
        $this->timeline->public = '1';
        $this->timeline->featured = '1';
        $this->timeline->owner_id = $this->user->id;
        $this->timeline->setQuery(array('range' => '1'));
        $parameters = array(
            'item_title' => 50,
            'item_description' => 42,
            'item_date' => 43,
            'item_date_end' => '',
            'render_year' => 'january_1',
            'center_date' => '',
            'viewer' => '{}',
        );
        $this->timeline->setParameters($parameters);

        $this->assertEquals('Timeline Title', $this->timeline->title);
        $this->assertEquals('Timeline Description', $this->timeline->description);
        $this->assertEquals('1', $this->timeline->public);
        $this->assertEquals('1', $this->timeline->featured);
        $this->assertEquals($this->user->id, $this->timeline->owner_id);
        $this->assertEquals(array('range' => '1'), json_decode($this->timeline->query, true));
        $this->assertEquals($parameters, json_decode($this->timeline->parameters, true));
    }

    public function testInsertSetsAddedDate()
    {
        $this->dbAdapter->appendLastInsertIdToStack('1');
        $this->timeline->title = 'Timeline Title';
        $this->timeline->save();

        $this->assertNotNull($this->timeline->added);
        $this->assertThat(new Zend_Date($this->timeline->added), $this->isInstanceOf('Zend_Date'),
            "'added' column should contain a valid date (signified by validity as constructor for Zend_Date)");
    }

    public function testInsertSetsModifiedDate()
    {
        $this->dbAdapter->appendLastInsertIdToStack('1');
        $this->timeline->title = 'Timeline Title';
        $this->timeline->save();

        $this->assertNotNull($this->timeline->modified);
        $this->assertThat(new Zend_Date($this->timeline->modified), $this->isInstanceOf('Zend_Date'),
            "'modified' column should contain a valid date (signified by validity as constructor for Zend_Date)");
    }

    public function testUpdateSetsModifiedDate()
    {
        $this->dbAdapter->appendLastInsertIdToStack('1');
        $this->timeline->title = 'Timeline Title';
        $this->timeline->save();

        $this->assertNotNull($this->timeline->modified);
        $this->assertThat(new Zend_Date($this->timeline->modified), $this->isInstanceOf('Zend_Date'),
            "'modified' column should contain a valid date (signified by validity as constructor for Zend_Date)");
    }

    public function testQueryJsonization()
    {
        $this->dbAdapter->appendLastInsertIdToStack('1');
        $this->timeline->title = 'Timeline Title';
        $this->timeline->query = array('range' => '1');
        $this->timeline->save();

        $this->assertTrue(is_array(json_decode($this->timeline->query, true)));
        $this->assertEquals(array('range' => '1'), json_decode($this->timeline->query, true));
        $this->assertEquals(array('range' => '1'), $this->timeline->getQuery());
    }

    public function testDontRejsonizeQuery()
    {
        $this->dbAdapter->appendLastInsertIdToStack('1');
        $this->timeline->title = 'Timeline Title';
        $this->timeline->query = array('range' => '1');
        $this->timeline->save();

        $this->assertTrue(is_array(json_decode($this->timeline->query, true)));
        $this->assertEquals(array('range' => '1'), json_decode($this->timeline->query, true));

        $this->timeline->public = 1;
        $this->timeline->save();

        $this->assertTrue(is_array(json_decode($this->timeline->query, true)));
        $this->assertEquals(array('range' => '1'), json_decode($this->timeline->query, true));
        $this->assertEquals(array('range' => '1'), $this->timeline->getQuery());
    }
}
