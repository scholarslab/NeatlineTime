<?php
$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape('Timelines | Browse'));
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<p id="add-timeline" class="add-button"><a class="add" href="<?php echo html_escape(uri('timelines/add')); ?>">Add a Timeline</a></p>
<div id="primary">
<?php if ($timelines) : ?>
<div class="pagination"><?php echo pagination_links(); ?></div>
<table>
    <thead id="timelines-table-head">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <?php if (has_permission('Timeline_Timelines', 'edit')): ?>
            <th>Edit</th>
            <?php endif; ?>
            <?php if (has_permission('Timeline_Timelines', 'delete')): ?>
            <th>Delete</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody id="types-table-body">
<?php foreach ($timelines as $timeline) : ?>
        <tr>
            <td><?php echo $timeline->id; ?></td>
            <td><?php echo $timeline->title; ?></td>
            <td><?php echo $timeline->description; ?></td>
            <?php if (has_permission($timeline, 'edit')): ?>
            <td><?php echo link_to($timeline, 'edit', 'Edit', array('class'=>'edit')); ?></td>
            <?php endif; ?>     
            <?php if (has_permission($timeline, 'delete')): ?>
            <td><?php echo delete_button($timeline); ?></td>
            <?php endif; ?>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>
<?php else : ?>
    <p>There are no timelines!</p>
<?php endif; ?>
</div>
<?php foot(); ?>