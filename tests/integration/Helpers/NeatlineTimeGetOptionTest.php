<?php
/**
 * Tests for neatlinetime_get_option function
 *
 * @deprecated since 2.1.9
 */
class NeatlineTimeGetOptionTest extends NeatlineTime_Test_AppTestCase
{
    protected $_options = array(
      'item_title',
      'item_date',
      'item_description'
    );

    public function setUp()
    {
        parent::setUp();

        $this->db = get_db();
        $this->_elementTable = $this->db->getTable('Element');

        //The process don't use the default install process, so force options.
        set_option('neatline_time_defaults', json_encode(NeatlineTime_Test_AppTestCase::$options));
    }

    /**
     * Tests whether neatlinetime_get_option() returns the correct value.
     */
    public function testDefault()
    {
        foreach ($this->_options as $option) {
            $field = ucwords(str_replace('item_' , '', $option));
            $element = $this->_elementTable->findByElementSetNameAndElementName("Dublin Core", "$field");
            $value = neatlinetime_get_option($option);
            $this->assertEquals($value, $element->id);
        }
    }

    public function testUpdatedOptions()
    {
        set_option('neatline_time_defaults', json_encode(array(
            'item_title' => 1,
            'item_date' => 2,
            'item_description' => 3,
        )));

        $this->assertEquals(neatlinetime_get_option('item_title'), 1);
        $this->assertEquals(neatlinetime_get_option('item_date'), 2);
        $this->assertEquals(neatlinetime_get_option('item_description'), 3);
    }
}
