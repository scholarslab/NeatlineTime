<?php
/**
 * The browse view for the Timelines administrative panel.
 */

$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape(__('Neatline Time | Browse Timelines')));
echo head($head);
?>
<?php echo flash(); ?>
<?php if ($total_results) : ?>
<div class="pagination"><?php echo pagination_links(); ?></div>
<div class="table-actions">
<?php if (is_allowed('NeatlineTime_Timelines', 'add')): ?>
    <a href="<?php echo html_escape(url('neatline-time/timelines/add')); ?>" class="small green button">
        <?php echo __('Add a Timeline'); ?>
    </a>
<?php endif; ?>
</div>
<table>
    <thead id="timelines-table-head">
        <tr>
        <th><?php echo __('Title'); ?></th>
        <th><?php echo __('Description'); ?></th>
        </tr>
    </thead>
    <tbody id="types-table-body">
        <?php foreach (loop('Neatline_Time_Timelines') as $timeline): ?>
        <tr>
            <td class="timeline-title title">
                <?php echo link_to_timeline(); ?>
                <ul class="action-links group">
                        <?php if (is_allowed($timeline, 'edit')): ?>
                        <li><?php echo link_to_timeline(__('Edit Metadata'), array(), 'edit'); ?></li>
                        <?php endif; ?>
                        <?php if (is_allowed($timeline, 'query')): ?>
                        <li><?php echo link_to_timeline(__('Edit Item Query'), array(), 'query'); ?></li>
                        <?php endif; ?>

                        <?php if (is_allowed($timeline, 'delete')): ?>
                        <li><?php echo link_to_timeline(__('Delete'), array('class' => 'delete-confirm'), 'delete-confirm'); ?></li>
                        <?php endif; ?>
                </ul>
            </td>
            <td><?php echo snippet_by_word_count(metadata($timeline, 'description'), '10'); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="table-actions">
<?php if (is_allowed('NeatlineTime_Timelines', 'add')): ?>
    <a href="<?php echo html_escape(url('neatline-time/timelines/add')); ?>" class="small green button">
        <?php echo __('Add a Timeline'); ?>
    </a>
<?php endif; ?>
</div>

<?php else : ?>
    <p><?php echo __('There are no timelines.'); ?> <?php if (is_allowed('NeatlineTime_Timelines', 'add')): ?><a href="<?php echo html_escape(url('neatline-time/timelines/add')); ?>"><?php echo __('Add a Timeline'); ?>.</a><?php endif; ?></p>
<?php endif; ?>
<?php echo foot(); ?>
