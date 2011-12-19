<?php
/**
 * Tests for the neatlinetime_display_timeline helper.
 */
class NeatlineTimeDisplayTimelineTest extends NeatlineTime_Test_AppTestCase {

    protected $_isAdminTest = false;

    public function testNeatlineTimeDisplayTimeline()
    {

        $this->_createTimeline();

        $this->dispatch('neatline-time/timelines/show/1');

        $helperOutput = neatlinetime_display_timeline();

        // Test for NeatlineTime.loadTimeline javascript.
        $loadTimelineString = 'NeatlineTime.loadTimeline("neatlinetime-timeline-title-1", "/items/browse?range=1&output=neatlinetime-json")';
        $this->assertContains($loadTimelineString, $helperOutput);

        // Test for empty div element, with correct ID and class attributes.
        $emptyDivString = '<div id="neatlinetime-timeline-title-1" class="neatlinetime-timeline"></div>';
        $this->assertContains($emptyDivString, $helperOutput);

    }
}