<?php
/**
 * Tests for timeline function
 */
class TimelineTest extends Omeka_Test_AppTestCase
{
    protected $_isAdminTest = false;
    
    public function setUp()
    {
        parent::setUp();
        $this->helper = new NeatlineTime_Test_AppTestCase;
        $this->helper->setUpPlugin();

        $timeline = $this->helper->_createTimeline();
    }

    /**
     * Tests whether timeline() returns the correct value.
     *
     * @uses timeline()
     **/
    public function testTimelineValue() 
    {

        $this->dispatch('neatline-time/timelines/show/1');

        // Exhibit Title
        $this->assertEquals('Timeline Title', timeline('title'));

        // Exhibit Description
        $this->assertEquals('Timeline description.', timeline('description'));

    }
}