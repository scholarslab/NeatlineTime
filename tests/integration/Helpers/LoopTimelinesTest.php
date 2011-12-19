<?php
/**
 * Tests for the loop_timelines helper.
 */
class LoopTimelinesTest extends NeatlineTime_Test_AppTestCase {

    public function testNoLoopTimelines()
    {

        $this->dispatch('neatline-time/timelines');
        $this->assertEmpty(loop_timelines());

    }

    public function testLoopTimelines()
    {

        for ($i=1; $i<=5; $i++) {
            $this->_createTimeline();
        }

        $this->dispatch('neatline-time/timelines');

        $this->assertNotEmpty(loop_timelines());

    }
}