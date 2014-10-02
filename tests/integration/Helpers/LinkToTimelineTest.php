<?php
/**
 * Tests for link_to_timeline function
 */
class LinkToTimelineTest extends NeatlineTime_Test_AppTestCase
{
    protected $_isAdminTest = false;

    /**
     * Tests whether link_to_timeline() returns the correct link for a timeline.
     *
     * @uses ::link_to_timeline
     */
    public function testLinkToTimeline()
    {

        $timeline = $this->_createTimeline();

        $this->dispatch('neatline-time/timelines/show/1');

        $content = $timeline->title;
        $url     = record_url($timeline);

        $linkDefault = link_to_timeline();
        $this->assertEquals("<a href=\"$url\">$content</a>", $linkDefault);

        $linkText = 'New Text';
        $linkWithNewText = link_to_timeline($linkText);
        $matcher['content'] = $linkText;
        $this->assertEquals("<a href=\"$url\">$linkText</a>", $linkWithNewText);

        $url = record_url($timeline, 'edit');

        $linkToEditWithProps = link_to_timeline(
            null,
            array('class' => 'edit'),
            'edit'
        );
        $this->assertEquals(
            "<a href=\"$url\" class=\"edit\">$content</a>",
            $linkToEditWithProps
        );

    }
}
