<?php
/**
 * The public show view for Timelines.
 */

$timelineTitle = metadata($neatline_time_timeline, 'title');
$head = array(
    'bodyclass' => 'timelines primary',
    'title' => $timelineTitle,
);
echo head($head);
?>
<h1><?php echo $timelineTitle; ?></h1>
    <?php // Construct the timeline.
    $library = get_option('neatline_time_library') ?: 'simile';
    $libraryPartial = $library == 'simile' ? '_timeline' : '_timeline_' . $library;
    echo $this->partial('timelines/' . $libraryPartial . '.php',
        array(
            'timeline' => $neatline_time_timeline,
    )); ?>
    <?php echo metadata($neatline_time_timeline, 'description'); ?>
<?php echo foot();
