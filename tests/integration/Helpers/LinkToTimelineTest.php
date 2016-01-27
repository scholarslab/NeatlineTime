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
     * @uses link_to_timeline()
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
        $url = record_url($timeline);
        $title = $timeline->title;
        $this->assertEquals(
            $linkDefault, "<a href=\"${url}\">${title}</a>"
        );

        $linkText = 'New Text';
        $linkWithNewText = link_to_timeline($linkText);
        $matcher['content'] = $linkText;
        $this->assertEquals(
            $linkWithNewText,
            "<a href=\"${url}\">${linkText}</a>"
        );

        $linkToEditWithProps = link_to_timeline(null, array('class' => 'edit'), 'edit');
        $matcher['content'] = $timeline->title;
        $matcher['attributes']['class'] = 'edit';
        $matcher['attributes']['href'] = $url = record_url($timeline, 'edit');
        $this->assertEquals(
            $linkToEditWithProps,
            "<a href=\"${url}\" class=\"edit\">${title}</a>"
        );

    }
}
