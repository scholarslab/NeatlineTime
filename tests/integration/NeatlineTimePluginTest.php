<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4; */

/**
 * This tests for functionality in the Plugin object itself.
 */
class NeatlineTimePluginTest extends NeatlineTime_Test_AppTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->db = get_db();
        $this->_elementTable = $this->db->getTable('Element');

        //The process don't use the default install process, so force options.
        set_option('neatline_time_defaults', json_encode(self::$options));
    }

    /**
     * Tests for default Neatline options on installation.
     */
    public function testSetDefaultOptions()
    {
        $optionNames = array('item_title', 'item_description', 'item_date');

        $options = json_decode(get_option('neatline_time_defaults'), true);

        foreach ($optionNames as $optionName) {
            $field = ucwords(str_replace('item_', '', $optionName));
            $element = $this->_elementTable->findByElementSetNameAndElementName("Dublin Core", "$field");
            $this->assertEquals($options[$optionName], $element->id);
        }
    }
}
