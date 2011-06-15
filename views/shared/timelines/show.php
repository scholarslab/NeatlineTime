<?php
/*
Add the Simile Timeline javascript to the queue using Zend's headScript,
since Omeka's queue_js() function doesn't support URLs.

Must call both javascripts before calling head() function, so the scripts
get added to the queue before the page loads.
*/
$this->headScript()->appendFile('http://static.simile.mit.edu/timeline/api-2.3.1/timeline-api.js?bundle=false');
queue_js('createTimeline');
?>
<?php head(); ?>
<h1><?php echo item('Dublin Core', 'Title'); ?></h1>
<?php echo display_timeline_for_item(); ?>

<?php foot(); ?>
