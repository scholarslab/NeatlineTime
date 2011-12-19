<?php
/**
 * Tests for the set_current_timeline helper.
 */
class SetCurrentTimelineTest extends NeatlineTime_Test_AppTestCase {

    protected $_isAdminTest = true;

    public function testSetCurrentTimelineTest()
    {
        $timeline = $this->_createTimeline();
        $this->dispatch('neatline-time/timelines/show/1');

        $newTimeline = $this->_createTimeline(array('title' => 'New Timeline'));
        set_current_timeline($newTimeline);
        $this->assertEquals($newTimeline, get_current_timeline());
    }
}