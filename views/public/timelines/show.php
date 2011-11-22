<?php
/**
 * The show view for the Timelines administrative panel.
 *
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @subpackage Views
 * @link http://omeka.org/codex/Plugins/NeatlineTime
 * @since 1.0
 */

queue_timeline_assets();
$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape('Neatline Time | Show'));
head($head);
?>
<h1><?php echo timeline('title'); ?></h1>

<div id="primary">

    <?php echo timeline('description'); ?>
    <script>
        jQuery(document).ready(function() {
           loadTimeline('my-timeline', '<?php echo neatlinetime_json_uri_for_timeline(); ?>'); 
        });
    </script>
    <div id="my-timeline" style="height:150px;"></div>

</div>
<?php foot(); ?>
