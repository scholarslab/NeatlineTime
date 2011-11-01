<?php
/**
 * The public show view for Timelines.
 *
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @subpackage Views
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */

queue_timeline_assets();
$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape('Timelines | Show'));
head($head);
?>
<h1><?php echo timeline('title'); ?></h1>

<div id="primary">
    <?php echo timeline('description'); ?>
</div>
<?php foot(); ?>
