<?php
$timelineArray = array();
foreach ($timelines as $timeline) {
    $timelineArray[] = $timeline->toArray();
}
$json = Zend_Json::encode($timelineArray);
    echo Zend_Json::prettyPrint($json);