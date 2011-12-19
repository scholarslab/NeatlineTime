<?php
/**
 * Tests for the has_timelines() helper.
 */
class HasTimelinesTest extends NeatlineTime_Test_AppTestCase
{   
    protected $_isAdminTest = true;

    /**
     * Shouldn't have any timelines yet, since we haven't added any.
     */
    public function testHasNoTimelines()
    {
        $this->dispatch('neatline-time/timelines');
        $this->assertFalse(has_timelines());
    }
    
    public function testHasTimelines()
    {
        $this->_createTimeline();
        $this->dispatch('neatline-time/timelines');
        $this->assertTrue(has_timelines());
    }
}