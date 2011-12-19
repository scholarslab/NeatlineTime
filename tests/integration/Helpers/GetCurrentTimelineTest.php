<?php
/**
 * Tests the get_current_timeline() helper.
 */
class GetCurrentTimelineTest extends NeatlineTime_Test_AppTestCase
{   
    protected $_isAdminTest = true;

    public function testGetCurrentTimeline()
    {
        $this->_createTimeline();
        $this->dispatch('neatline-time/timelines/show/1');
        $timeline = get_current_timeline();
        $this->assertEquals('1', $timeline->id);
    }
}