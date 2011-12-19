<?php
/**
 * Tests whether the output_format_list() helper includes the
 * neatlinetime-json output.
 */
class NeatlineTimeJsonOutputTest extends NeatlineTime_Test_AppTestCase
{
    protected $_isAdminTest = false;

    public function testNeatlineTimeJsonOutputInFormatList() 
    {

        $this->dispatch('items/browse');

        $html = items_output_uri('neatlinetime-json');

        $this->assertContains($html, output_format_list());

    }

}