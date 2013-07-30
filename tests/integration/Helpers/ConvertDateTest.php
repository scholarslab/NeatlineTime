<?php
/**
 * Tests the get_current_timeline() helper.
 */
class ConvertDateTest extends NeatlineTime_Test_AppTestCase
{   
    protected $_isAdminTest = true;

    // https://github.com/scholarslab/NeatlineTime/issues/38
    public function testIso8601Dates() {
        $this->assertContains(
            '2012-07-12', neatlinetime_convert_date('2012-07-12')
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
        $this->assertContains(
            '0135-01-01', neatlinetime_convert_date('135-01-01')
        );
        $this->assertContains(
            '0035-01-01', neatlinetime_convert_date('0035-01-01')
        );
        $this->assertContains(
            '0003-01-01', neatlinetime_convert_date('0003-01-01')
        );
    }

    public function testBCE() {
        $this->assertContains(
            '-0135-01-01', neatlinetime_convert_date('-0135-01-01')
        );
        $this->assertContains(
            '-0035-01-01', neatlinetime_convert_date('-0035-01-01')
        );
        $this->assertContains(
            '-0003-01-01', neatlinetime_convert_date('-0003-01-01')
        );
        $this->assertContains(
            '-2013-01-01', neatlinetime_convert_date('-2013-01-01')
        );
    }
}
