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

    /**
     * By default, a single number is not accepted.
     *
     * https://github.com/scholarslab/NeatlineTime/issues/39
     */
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

    /**
     * A single number may be allowed with the specified parameter.
     */
    public function testYearOnlyDateWithParamJanuary1()
    {
        set_option('neatline_time_render_year', 'january_1');
        $this->assertContains(
            '1066-01-01', neatlinetime_convert_date('1066')
            );
        set_option('neatline_time_render_year', 'skip');
        $this->assertFalse(
            neatlinetime_convert_date('1066')
            );
    }

    /**
     * A single number may be allowed with the specified parameter.
     */
    public function testYearOnlyDateWithParamJuly1()
    {
        set_option('neatline_time_render_year', 'july_1');
        $this->assertContains(
            '1066-07-01', neatlinetime_convert_date('1066')
            );
    }

    /**
     * A single number may be allowed with the specified parameter.
     */
    public function testYearOnlyDateWithParamFullYear()
    {
        set_option('neatline_time_render_year', 'full_year');
        $this->assertFalse(neatlinetime_convert_date('1066'));
        $result = neatlinetime_convert_single_date('1066');
        $this->assertContains('1066-01-01', $result[0]);
        $this->assertContains('1066-12-31', $result[1]);
    }

    public function testRangeDatesJanuary1()
    {
        set_option('neatline_time_render_year', 'january_1');
        $result = neatlinetime_convert_range_date(array('1066', '1067'));
        $this->assertContains('1066-01-01', $result[0]);
        $this->assertContains('1067-01-01', $result[1]);
        $result = neatlinetime_convert_range_date(array('1066-10-09', '1067'));
        $this->assertContains('1066-10-09', $result[0]);
        $this->assertContains('1067-01-01', $result[1]);
        $result = neatlinetime_convert_range_date(array('1066', '1066'));
        $this->assertContains('1066-01-01', $result[0]);
        $this->assertContains('1066-12-31', $result[1]);
    }

    public function testRangeDatesJuly1()
    {
        set_option('neatline_time_render_year', 'july_1');
        $result = neatlinetime_convert_range_date(array('1066', '1067'));
        $this->assertContains('1066-07-01', $result[0]);
        $this->assertContains('1067-06-30', $result[1]);
        $result = neatlinetime_convert_range_date(array('1066', '1067-05-04'));
        $this->assertContains('1066-07-01', $result[0]);
        $this->assertContains('1067-05-04', $result[1]);
        $result = neatlinetime_convert_range_date(array('1066', '1066'));
        $this->assertContains('1066-01-01', $result[0]);
        $this->assertContains('1066-12-31', $result[1]);
    }

    public function testRangeDatesFullYear()
    {
        set_option('neatline_time_render_year', 'full_year');
        $result = neatlinetime_convert_range_date(array('1066', '1067'));
        $this->assertContains('1066-01-01', $result[0]);
        $this->assertContains('1067-12-31', $result[1]);
        $result = neatlinetime_convert_range_date(array('1067', '1066'));
        $this->assertContains('1066-01-01', $result[0]);
        $this->assertContains('1067-12-31', $result[1]);
        $result = neatlinetime_convert_range_date(array('1066', '1066'));
        $this->assertContains('1066-01-01', $result[0]);
        $this->assertContains('1066-12-31', $result[1]);
        $result = neatlinetime_convert_range_date(array('1067-04-21', '1067'));
        $this->assertContains('1067-04-21', $result[0]);
        $this->assertContains('1067-12-31', $result[1]);
    }
}
