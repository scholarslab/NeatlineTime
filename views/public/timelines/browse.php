<?php
/**
 * The public browse view for Timelines.
 *
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @subpackage Views
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */
 
head(); ?>

<div id="primary" class="timelines">
    <h1>Browse Timelines</h1>
    <?php if (has_timelines_for_loop()) : while ( loop_timelines() ) :?>
    <div class="timeline">
        <h2><?php echo link_to_timeline(); ?></h2>
        <?php echo timeline('Description'); ?>
    </div>
    <?php endwhile; else: ?>
        <p>You have no timelines</p>
    <?php endif; ?>
</div>
<?php foot(); ?>