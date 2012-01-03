<?php
/**
 * Tests for neatlinetime_display_search_query function
 */
class NeatlineTimeDisplaySearchQueryTest extends NeatlineTime_Test_AppTestCase
{
    protected $_isAdminTest = false;

    /**
     * Tests whether neatlinetime_display_search_query() returns the correct value.
     */
    public function testValue() 
    {

        $timeline = $this->_createTimeline();

        $this->dispatch('neatline-time/timelines/show/1');

        $searchQuery = neatlinetime_display_search_query(timeline('query'));

        // Check that the output contains our expected HTML wrapper.
        $this->assertContains('<div class="filters">', $searchQuery);

        // Check that the output contains text of the query for our test timeline.
        $this->assertContains('Range: 1', $searchQuery);
    }
}