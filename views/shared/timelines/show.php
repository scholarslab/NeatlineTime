<?php head(); ?>

<div id="timelinediv" style="height:200px"></div>

<?php

$tags =  item("Item Type Metadata","Tag",array("delimiter" => ','));
$query = array('tags' => $tags);
$things = get_items($query);
createTimeline("timelinediv",$things);

foot();
?>
