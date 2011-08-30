<?php
/**
 * The browse view for the Timelines administrative panel.
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
              'title' => html_escape('Timelines | Browse'));
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<p id="add-timeline" class="add-button"><a class="add" href="<?php echo html_escape(uri('neatline-time/timelines/add')); ?>">Add a Timeline</a></p>
<div id="primary">
<?php if ($neatlinetimetimelines) : ?>
<div class="pagination"><?php echo pagination_links(); ?></div>
<table>
    <thead id="timelines-table-head">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <?php if (has_permission('NeatlineTime_Timelines', 'edit')): ?>
            <th>Edit</th>
            <?php endif; ?>
            <?php if (has_permission('NeatlineTime_Timelines', 'delete')): ?>
            <th>Delete</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody id="types-table-body">
<?php foreach ($neatlinetimetimelines as $timeline) : ?>
        <tr>
            <td><?php echo $timeline->id; ?></td>
            <td><?php echo $timeline->title; ?></td>
            <td><?php echo snippet_by_word_count($timeline->description, '50'); ?></td>
            <?php if (has_permission($timeline, 'edit')): ?>
            <td><?php echo link_to_edit($timeline); ?></td>
            <?php endif; ?>
            <?php if (has_permission($timeline, 'delete')): ?>
            <td><?php echo delete_button($timeline); ?></td>
            <?php endif; ?>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>
<?php else : ?>
    <p>There are no timelines. <?php if (has_permission('NeatlineTime_Timelines', 'add')): ?><a href="<?php echo html_escape(uri('neatline-time/timelines/add')); ?>">Add a new Timeline.</a><?php endif; ?></p>
<?php endif; ?>
</div>
<?php foot(); ?>
