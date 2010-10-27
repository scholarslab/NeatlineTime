<?php
/**
 * Timeline Testrunner 
 *
 * @version $Id$
 * @copyright Rector and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @package Omeka
 * @subpackage Timeline
 */

 // check if the plugin_dir has been defined; if not, set it
defined('TIMELINE_DIR') || define(
    'TIMELINE_DIR', dirname(dirname(__FILE__))
);

require_once('Timeline_ViewTestCase.php');

class Timeline_AllTests extends PHPUnit_Framework_TestSuite
{

    /**
     * Set up the test suite instance
     * 
     * @return Plugin_AllTests Tests for the test runner
     */
    public static function suite()
    {
        $suite = new Timeline_AllTests('Timeline Plugin Tests');
        $testCollector = new PHPUnit_Runner_IncludePathTestCollector(
                array(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cases')
        );
		
        $suite->addTestFiles($testCollector->collectTests());

        return $suite;
    }
}

