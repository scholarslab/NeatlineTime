<?php
/**
 * The shared timeglider-json browse view for Timelines
 *
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @subpackage Views
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */
 
$timelineArray = array();
foreach ($timelines as $timeline) {
    $timelineArray[] = $timeline->toArray();
}
$json = Zend_Json::encode($timelineArray);
    echo Zend_Json::prettyPrint($json);