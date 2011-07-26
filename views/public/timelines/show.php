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

queue_js('timeglider-0.0.9.min');
queue_css('timeglider');
$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape('Timelines | Show'));
head($head);
?>
<h1><?php echo timeline('title'); ?></h1>

<div id="primary">
    <?php echo timeline('description'); ?>
    <script>
    jQuery(document).ready(function($){
        $('#timeglider')
            .css({'height': '400px'})
            .timeline({
                "data_source":<?php echo js_escape(abs_timeline_uri().'?output=timeglider-json'); ?>,
                "min_zoom":5,
                "max_zoom":60,
                "show_footer": false
            });
    });
    </script>
    <div id="timeglider"></div>
</div>
<?php foot(); ?>