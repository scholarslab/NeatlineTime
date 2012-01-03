<?php
/**
 * Test Runner.
 */
require_once 'NeatlineTime_Test_AppTestCase.php';

class NeatlineTime_AllTests extends PHPUnit_Framework_TestSuite
{

    /**
     * Aggregate the tests.
     *
     * @return NeatlineTime_AllTests $suite The test suite.
     */
    public static function suite()
    {

        $suite = new NeatlineTime_AllTests('Neatline Time Tests');

        $collector = new PHPUnit_Runner_IncludePathTestCollector(
            array(
                dirname(__FILE__) . '/integration',
                dirname(__FILE__) . '/unit'
            )
        );

        $suite->addTestFiles($collector->collectTests());

        return $suite;

    }

}
