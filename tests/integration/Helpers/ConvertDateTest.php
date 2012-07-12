<?php
/**
 * Tests the get_current_timeline() helper.
 */
class ConvertDateTest extends NeatlineTime_Test_AppTestCase
{   
    protected $_isAdminTest = true;

    // https://github.com/scholarslab/NeatlineTime/issues/38
    public function testIso8601Dates() {
        $this->assertEquals(
            '2012-07-12T00:00:00+00:00', neatlinetime_convert_date('2012-07-12')
        );
    }
}
