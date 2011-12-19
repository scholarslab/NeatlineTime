<?php
/**
 * Tests for the timeline_delete_button helper.
 */
class TimelineDeleteButtonTest extends NeatlineTime_Test_AppTestCase {

    public function testTimelineDeleteButton()
    {
        $this->_createTimeline();
        $this->dispatch('neatline-time/timelines/show/1');

        $helperOutput = timeline_delete_button();

        // Confirm the action goes to delete-confirm for the timeline.
        $formAction = 'action="/neatline-time/timelines/delete-confirm/1"';
        $this->assertContains($formAction, $helperOutput);

    }
}