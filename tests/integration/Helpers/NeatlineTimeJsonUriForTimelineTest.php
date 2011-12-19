<?php
/**
 * Tests the neatlinetime_json_uri_for_timeline helper.
 */
class NeatlineTimeJsonUriForTimelineTest extends NeatlineTime_Test_AppTestCase {

    protected $_isAdminTest = false;

    public function testNeatlineTimeJsonUriForTimelineOutput()
    {

        $this->_createTimeline();

        $this->dispatch('neatline-time/timelines/show/1');

        $html = '/items/browse?range=1&output=neatlinetime-json';

        $this->assertSame($html, neatlinetime_json_uri_for_timeline());

    }
}