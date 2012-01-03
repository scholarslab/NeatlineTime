<?php
/**
 * Timelines controller integration tests.
 */
class NeatlineTime_TimelinesControllerTest extends NeatlineTime_Test_AppTestCase
{

    public function setUp()
    {

        parent::setUp();
        $timeline = $this->_createTimeline();

    }

    /**
     * Data provider for TimelinesController routes.
     */
    public static function routes()
    {
        return array(
            array('/neatline-time/timelines/browse', 'timelines', 'browse'),
            array('/neatline-time/timelines/add', 'timelines', 'add'),
            array('/neatline-time/timelines/edit/1', 'timelines', 'edit'),
            array('/neatline-time/timelines/query/1', 'timelines', 'query')
        );
    }

    /**
     * @dataProvider routes
     */
    public function testRouting($url, $controller, $action, $callback = null)
    {
        $this->dispatch($url, $callback);
        $this->assertController($controller);
        $this->assertAction($action);
    }

}
