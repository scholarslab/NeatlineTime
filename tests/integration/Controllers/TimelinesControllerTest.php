<?php
/**
 * Timelines controller integration tests.
 */
class TimelinesControllerTest extends NeatlineTime_Test_AppTestCase
{

    public function setUp()
    {

        parent::setUp();
        $timeline = $this->_createTimeline();

    }

    /**
     * Data provider for testRouting.
     */
    public static function routes()
    {
        return array(
            array('/neatline-time/timelines/browse', 'browse'),
            array('/neatline-time/timelines/add', 'add'),
            array('/neatline-time/timelines/show/1', 'show'),
            array('/neatline-time/timelines/edit/1', 'edit'),
            array('/neatline-time/timelines/query/1', 'query')
        );
    }

    /**
     * @dataProvider routes
     */
    public function testRouting($url, $action)
    {
        $this->dispatch($url);
        $this->assertController('timelines');
        $this->assertAction($action);
    }

}
