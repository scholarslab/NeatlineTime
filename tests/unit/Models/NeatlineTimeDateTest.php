<?php

class NeatlineTimeDateTest extends NeatlineTime_Test_AppTestCase {

    public function testDisplayBCEDate()
    {
        $this->_testDateDisplay("AAA", "-0001-10-10", "Oct 10, 2 BCE");
        $this->_testDateDisplay("BBB", "-0200-02-02", "Feb 2, 201 BCE");
        $this->_testDateDisplay("CCC", "-2013-03-03", "Mar 3, 2014 BCE");
        $this->_testDateDisplay("DDD", "-0000-03-03", "Mar 3, 1 BCE");
    }

    public function testDisplayCEDate()
    {
        $this->_testDateDisplay("EEE", "0000-04-04", "Apr 4, 1 BCE");
        $this->_testDateDisplay("FFF", "0020-05-05", "May 5, 20");
        $this->_testDateDisplay("GGG", "0300-06-06", "Jun 6, 300");
        $this->_testDateDisplay("HHH", "2014-07-07", "Jul 7, 2014");
    }

    protected function _testDateDisplay($title, $date, $display)
    {
        $item = insert_item(
            array('public' => '1'),
            array(
                'Dublin Core' => array(
                    'Title' => array( array('text' => 'testDisplayBCEDate', 'html' => '0') ),
                    'Date'  => array( array('text' => $date,                'html' => '0') )
                )
            )
        );

        $actual = metadata($item, array('Dublin Core', 'Date'));
        $item->delete();

        $this->assertEquals($display, $actual);
    }

}
