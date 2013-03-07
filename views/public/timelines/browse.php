<?php
/**
 * The public browse view for Timelines.
 */
 
$head = array('bodyclass' => 'timelines primary',
              'title' => html_escape(__('Browse Timelines')));
echo head($head);
?>

<div class="timelines">
<h1><?php echo __('Browse Timelines'); ?></h1>
    <?php if (has_timelines_for_loop()) : while ( loop_timelines() ) :?>
    <div class="timeline">
        <h2><?php echo link_to_timeline(); ?></h2>
        <?php echo timeline('Description'); ?>
    </div>
    <?php endwhile; ?>
    <div class="pagination">
      <?php echo pagination_links(); ?>
    </div>
    <?php else: ?>
    <p><?php echo __('You have no timelines.'); ?></p>
    <?php endif; ?>
</div>
<?php echo foot(); ?>
