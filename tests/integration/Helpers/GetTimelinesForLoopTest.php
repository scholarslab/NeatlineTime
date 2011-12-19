<?php
/**
 * Tests for get_timelines_for_loop helper.
 */
class GetTimelinesForLoopTest extends NeatlineTime_Test_AppTestCase {

    public function testGetTimelinesForLoop()
    {

        for ($i = 1; $i < 11; $i++) {
            $this->_createTimeline();
        }

        $this->dispatch('neatline-time/timelines');

        $timelines = get_timelines_for_loop();
        $this->assertEquals(10, count($timelines));

    }
}