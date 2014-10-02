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

        $matcher = array(
            'tag' => 'a',
            'content' => $timeline->title,
            'attributes' => array(
                'href' => record_url($timeline)
            )
        );

        $linkDefault = link_to_timeline();
        $this->assertTag($matcher, $linkDefault);

        $linkText = 'New Text';
        $linkWithNewText = link_to_timeline($linkText);
        $matcher['content'] = $linkText;
        $this->assertTag($matcher, $linkWithNewText);

        $linkToEditWithProps = link_to_timeline(null, array('class' => 'edit'), 'edit');
        $matcher['content'] = $timeline->title;
        $matcher['attributes']['class'] = 'edit';
        $matcher['attributes']['href'] = record_url($timeline, 'edit');
        $this->assertTag($matcher, $linkToEditWithProps);

    }
}
