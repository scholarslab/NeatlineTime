<?php
/**
 * Tests for the set_timelines_for_loop helper.
 */
class SetTimelinesForLoopTest extends NeatlineTime_Test_AppTestCase {

    /**
     * Tests setting timelines for loop on the items/browse page, since there
     * are no timeline loops on that view by default.
     */
    public function testSetTimelinesForLoop()
    {

        $timeline = $this->_createTimeline();
        $this->dispatch('items/browse');
        set_timelines_for_loop(array($timeline));
        $this->assertTrue(in_array($timeline, get_timelines_for_loop()));

    }

}