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

    // https://github.com/scholarslab/NeatlineTime/issues/39
    public function testYearOnlyDate() {
        $this->assertFalse(
            neatlinetime_convert_date('2012')
        );
    }

    // https://github.com/scholarslab/NeatlineTime/issues/45
    public function testShortYear() {
        $this->assertEquals(
            '0135-01-01T00:00:00+00:00', neatlinetime_convert_date('135-01-01')
        );
        $this->assertEquals(
            '0035-01-01T00:00:00+00:00', neatlinetime_convert_date('0035-01-01')
        );
        $this->assertEquals(
            '0003-01-01T00:00:00+00:00', neatlinetime_convert_date('0003-01-01')
        );
    }

    public function testBCE() {
        $this->assertEquals(
            '-0135-01-01T00:00:00-01:00', neatlinetime_convert_date('-0135-01-01')
        );
        $this->assertEquals(
            '-0135-01-01T00:00:00-01:00', neatlinetime_convert_date('-0135-01-01')
        );
        $this->assertEquals(
            '-0035-01-01T00:00:00+00:00', neatlinetime_convert_date('-0035-01-01')
        );
        $this->assertEquals(
            '-0003-01-01T00:00:00+00:00', neatlinetime_convert_date('-0003-01-01')
        );
        $this->assertEquals(
            '-2013-01-01T00:00:00+00:00', neatlinetime_convert_date('-2013-01-01')
        );
    }
}
