<?php
/**
 * @author Scholars' Lab
 * @copyright 2010-2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */

require_once 'IntegrationHelper.php';

/**
 * Test suite for the Timeline plugin
 *
 * @since 1.0
 * @author Scholars' Lab
 * @package Timeline
 * @subpackage Tests
 */
class Timeline_AllTests extends PHPUnit_Framework_TestSuite
{

    /**
     * Set up the test suite instance
     *
     * @return Plugin_AllTests Tests for the test runner
     */
    public static function suite()
    {
        $suite = new Timeline_AllTests('Timeline Tests');
        $testCollector = new PHPUnit_Runner_IncludePathTestCollector(
                array(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cases')
        );

        $suite->addTestFiles($testCollector->collectTests());

        return $suite;
    }
}

