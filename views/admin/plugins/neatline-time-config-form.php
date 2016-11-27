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
<fieldset id="fieldset-neatline-time-default"><legend><?php echo __('Default Parameters'); ?></legend>
    <p class="explanation">
        <?php echo __('These parameters are used as defaults for all timelines.'); ?>
        <?php echo __('They can be overridden in the form of each timeline.'); ?>
    </p>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('neatline_time_defaults[item_title]', __('Item Title')); ?>
        </div>
        <div class='inputs five columns omega'>
            <?php
                echo $this->formSelect('neatline_time_defaults[item_title]',
                    $defaults['item_title'],
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
            <?php echo $this->formLabel('neatline_time_defaults[item_description]', __('Item Description')); ?>
        </div>
        <div class='inputs five columns omega'>
            <?php
                echo $this->formSelect('neatline_time_defaults[item_description]',
                    $defaults['item_description'],
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
            <?php echo $this->formLabel('neatline_time_defaults[item_date]', __('Item Date')); ?>
        </div>
        <div class='inputs five columns omega'>
            <?php
                echo $this->formSelect('neatline_time_defaults[item_date]',
                    $defaults['item_date'],
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
            <?php echo $this->formLabel('neatline_time_defaults[render_year]', __('Render Year')); ?>
        </div>
        <div class='inputs five columns omega'>
            <?php
            $values = array(
                'skip' => __('Skip the record'),
                'january_1' => __('Pick first January'),
                'july_1' => __('Pick first July'),
                'full_year' => __('Mark entire year'),
            );
            echo $this->formRadio('neatline_time_defaults[render_year]',
                $defaults['render_year'],
                null,
                $values);
            ?>
            <p class="explanation">
                <?php echo __('When a date is a single year, like "1066", the value should be interpreted to be displayed on the timeline.'); ?>
            </p>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('neatline_time_defaults[center_date]', __('Center Date')); ?>
        </div>
        <div class='inputs five columns omega'>
            <?php
            echo $this->formText('neatline_time_defaults[center_date]',
                $defaults['center_date'],
                null);
            ?>
            <p class="explanation">
                <?php echo __('Set the default center date for the timeline.'); ?>
                <?php echo __('The format should be "YYYY-MM-DD".'); ?>
            </p>
        </div>
    </div>
</fieldset>
