<?php
/**
 * The public show view for Timelines.
 */

queue_timeline_assets();
$head = array('bodyclass' => 'timelines primary',
              'title' => timeline('title')
              );
head($head);
?>
<h1><?php echo timeline('title'); ?></h1>

<div id="primary">

    <!-- Construct the timeline. -->
    <?php echo $this->partial('timelines/_timeline.php'); ?>

    <?php echo timeline('description'); ?>

</div>
<?php foot(); ?>
