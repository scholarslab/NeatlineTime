<?php
/**
 * Tests for has_timelines_for_loop helper.
 */
class HasTimelinesForLoopTest extends NeatlineTime_Test_AppTestCase {

    /**
     * Shouldn't have timelines for loop, since we haven't created any.
     */
    public function testHasNoTimelinesForLoop()
    {

        $this->dispatch('neatline-time/timelines');
        $this->assertFalse(has_timelines_for_loop());

    }

    /**
     * Creates a timeline, then checks to see if helper returns true.
     */
    public function testHasTimelinesForLoop()
    {

        $this->_createTimeline();
        $this->dispatch('neatline-time/timelines');
        $this->assertTrue(has_timelines_for_loop());

    }

    /**
     * Tests if helper returns false, if loop for timelines is reset.
     */
    public function testHasNoTimelinesForLoopAfterReset()
    {

        $this->_createTimeline();
        $this->dispatch('neatline-time/timelines');
        $this->assertTrue(has_timelines_for_loop());

        $timelines = array();
        set_timelines_for_loop($timelines);
        $this->assertFalse(has_timelines_for_loop());

    }
}