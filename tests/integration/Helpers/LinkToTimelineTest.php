<?php
/**
 * Tests for link_to_timeline function
 */
class LinkToTimelineTest extends Omeka_Test_AppTestCase
{
    protected $_isAdminTest = false;
    
    public function setUp()
    {
        parent::setUp();
        $this->helper = new NeatlineTime_Test_AppTestCase;
        $this->helper->setUpPlugin();

        $timeline = $this->helper->_createTimeline();
    }
    
    /**
     * Tests whether link_to_timeline() returns the correct link for a timeline.
     *
     * @uses link_to_timeline()
     */
    public function testLinkToTimeline()
    {

        $this->dispatch('neatline-time/timelines/show/1');

        $linkDefault = link_to_timeline();
        $this->assertSame($linkDefault, '<a href="/neatline-time/timelines/show/1">Timeline Title</a>');

        $linkWithNewText = link_to_timeline('New Text');
        $this->assertSame($linkWithNewText, '<a href="/neatline-time/timelines/show/1">New Text</a>');

        $linkToEditWithProps = link_to_timeline(null, array('class' => 'edit'), 'edit');
        $this->assertSame($linkToEditWithProps, '<a class="edit" href="/neatline-time/timelines/edit/1">Timeline Title</a>');

    }
}