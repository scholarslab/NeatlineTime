<?php
$this->headScript()->appendFile('http://static.simile.mit.edu/timeline/api-2.3.1/timeline-api.js?bundle=false');
queue_js('createTimeline');
?>
<?php head(); ?>
<h1><?php echo item('Dublin Core', 'Title'); ?></h1>
<?php echo display_timeline_for_item(); ?>

<?php foot(); ?>
