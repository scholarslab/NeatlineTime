<?php
/**
 * Tests for timeline function
 */
class TimelineTest extends NeatlineTime_Test_AppTestCase
{
    protected $_isAdminTest = false;

    /**
     * Tests whether timeline() returns the correct value.
     *
     * @uses ::timeline
     **/
    public function testTimelineValue() 
    {

        $timeline = $this->_createTimeline();

        $this->dispatch('neatline-time/timelines/show/1');

        // Timeline Title
        $this->assertEquals('Timeline Title', timeline('title'));

        // Timeline Description
        $this->assertEquals('Timeline description.', timeline('description'));

    }
}
