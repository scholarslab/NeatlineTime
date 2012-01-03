<?php
/**
 * The public browse view for Timelines.
 */
 
$head = array('bodyclass' => 'timelines primary',
              'title' => html_escape('Neatline Time | Timelines'));
head($head);
?>

<div id="primary" class="timelines">
    <h1>Browse Timelines</h1>
    <?php if (has_timelines_for_loop()) : while ( loop_timelines() ) :?>
    <div class="timeline">
        <h2><?php echo link_to_timeline(); ?></h2>
        <?php echo timeline('Description'); ?>
    </div>
    <?php endwhile; else: ?>
        <p>You have no timelines.</p>
    <?php endif; ?>
</div>
<?php foot(); ?>
