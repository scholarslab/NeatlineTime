<?php
/**
 * The edit view for the Timelines administrative panel.
 *
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @subpackage Views
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */

$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape('Timelines | Edit a Timeline'));
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
<?php echo $form; ?>
</div>
<?php foot(); ?>