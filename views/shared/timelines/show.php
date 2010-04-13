<?php head(); ?>

<div id="timelinediv"></div>

<?php

$tags =  item("Item Type Metadata","Tag",array("delimiter" => ','));
$query = array('tags' => $tags);
$things = get_items($query);
createTimeline("timelinediv",$things);

foot();
?>
