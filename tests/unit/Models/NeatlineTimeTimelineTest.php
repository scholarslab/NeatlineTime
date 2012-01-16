<?php
/**
 * Test the NeatlineTimeTimeline model.
 */
class NeatlineTimeTimelineTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->dbAdapter = new Zend_Test_DbAdapter;
        $this->db = new Omeka_Db($this->dbAdapter);
        $this->timeline = new NeatlineTimeTimeline($this->db);
        $this->user = new User($this->db);
    }
    
    public function testGetSetProperties()
    {
        $this->timeline->title = 'Timeline Title';
        $this->timeline->description = 'Timeline Description';
        $this->timeline->public = '1';
        $this->timeline->featured = '1';
        $this->timeline->creator_id = $this->user->id;
        $this->timeline->query = serialize(array('range' => '1'));

        $this->assertEquals('Timeline Title', $this->timeline->title);
        $this->assertEquals('Timeline Description', $this->timeline->description);
        $this->assertEquals('1', $this->timeline->public);
        $this->assertEquals('1', $this->timeline->featured);
        $this->assertEquals($this->user->id, $this->timeline->creator_id);
        $this->assertEquals(array('range' => '1'), unserialize($this->timeline->query));

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
        $this->collection->id = '1';
        $this->timeline->title = 'Timeline Title';
        $this->timeline->save();

        $this->assertNotNull($this->timeline->modified);
        $this->assertThat(new Zend_Date($this->timeline->modified), $this->isInstanceOf('Zend_Date'),
            "'modified' column should contain a valid date (signified by validity as constructor for Zend_Date)");
    }

    public function testAddedBy()
    {
        
        $this->timeline->title = 'Timeline Title';
        $this->timeline->creator_id = $this->user->id;

        $this->assertTrue($this->timeline->addedBy($this->user));
        $this->assertEquals($this->user->id, $this->timeline->creator_id);
    }
}
