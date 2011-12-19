<?php
/**
 * Tests the total_timelines helper.
 */
class TotalTimelinesTest extends NeatlineTime_Test_AppTestCase {

    public function testTotalTimelines()
    {

        $this->dispatch('neatline-time/timelines');
        $this->assertEquals(0, total_timelines());
        
        $timelineOne = $this->_createTimeline();
        $this->assertEquals(1, total_timelines());
        
        $timelineTwo = $this->_createTimeline();
        $this->assertEquals(2, total_timelines());

        $timelineTwo->delete();
        $this->assertEquals(1, total_timelines());

    }
}