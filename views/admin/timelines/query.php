<?php
/**
 * The edit query view for a specific Timeline.
 *
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @subpackage Views
 * @link http://omeka.org/codex/Plugins/NeatlineTime
 * @since 1.0
 */

$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape('Neatline Time | Edit Timeline Query'));
head($head);
?>
<script type="text/javascript" charset="utf-8">
    jQuery(window).load(function(){
       Omeka.Search.activateSearchButtons; 
    });
</script>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
    <?php echo items_search_form(array(), current_uri()); ?>

</div>
<?php foot(); ?>