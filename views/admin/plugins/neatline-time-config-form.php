<?php
$elements = get_table_options('Element', null, array(
    'record_types' => array('Item', 'All'),
    'sort' => 'alphaBySet',
));
// Remove the "Select Below" label.
unset($elements['']);
?>
<fieldset id="fieldset-neatline-time-library"><legend><?php echo __('Javascript Library'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('neatline_time_library',
                __('Timeline library')); ?>
        </div>
        <div class="inputs five columns omega">
            <?php
            $options = array('simile' => 'Simile', 'knightlab' => 'Knightlab');
            echo $this->formSelect('neatline_time_library',
                get_option('neatline_time_library') ?: 'simile',
                array(),
                $options);
            ?>
            <p class="explanation">
                <?php echo __('Two libraries are available: the standard open source Simile Timeline, or the Knightlab Timeline.'); ?>
            </p>
        </div>
    </div>
</fieldset>
<fieldset id="fieldset-neatline-time-elements"><legend><?php echo __('Elements'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('item_title', __('Item Title')); ?>
        </div>
        <div class='inputs five columns omega'>
            <?php
                echo $this->formSelect('item_title',
                    neatlinetime_get_option('item_title') ?: 50,
                    array(),
                    $elements);
            ?>
            <p class="explanation">
                <?php echo __('The title field to use when displaying an item on a timeline. Default is DC:Title'); ?>
            </p>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('item_description', __('Item Description')); ?>
        </div>
        <div class='inputs five columns omega'>
            <?php
                echo $this->formSelect('item_description',
                    neatlinetime_get_option('item_description') ?: 41,
                    array(),
                    $elements);
            ?>
            <p class="explanation">
                <?php echo __('The description field to use when displaying an item on a timeline. Default is DC:Description'); ?>
            </p>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('item_date', __('Item Date')); ?>
        </div>
        <div class='inputs five columns omega'>
            <?php
                echo $this->formSelect('item_date',
                    neatlinetime_get_option('item_date') ?: 40,
                    array(),
                    $elements);
            ?>
            <p class="explanation">
                <?php echo __('The date field to use to retrieve and display items on a timeline. Default is DC:Date.'); ?>
            </p>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('neatline_time_render_year', __('Render Year')); ?>
        </div>
        <div class='inputs five columns omega'>
            <?php
            $values = array(
                'skip' => __('Skip the record'),
                'january_1' => __('Pick first January'),
                'july_1' => __('Pick first July'),
                'full_year' => __('Mark entire year'),
            );
            echo $this->formRadio('neatline_time_render_year', get_option('neatline_time_render_year') ?: 'skip', NULL, $values); ?>
            <p class="explanation">
                <?php echo __('When a date is a single year, like "1066", the value should be interpreted to be displayed on the timeline.'); ?>
            </p>
        </div>
    </div>
</fieldset>
