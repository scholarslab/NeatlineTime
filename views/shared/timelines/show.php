<?php head(); ?>
<body>
<div id="timelinediv"></div>

<?php

$tags = array();
foreach ($item->getTags() as $tag) {
	array_push($tags, $tag->name);
}
$query = array('tags' => implode(',',$tags));
$things = get_items($query);
createTimeline("timelinediv",$things);

foot();
?>

</body>
