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
<fieldset id="fieldset-neatline-time-nav"><legend><?php echo __('Navigation'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('neatline_time_link_to_nav',
                __('Add secondary link')); ?>
        </div>
        <div class="inputs five columns omega">
            <?php
            $options = array(
                '' => __('None'),
                'browse' => __('Browse timelines'),
                'main' => __('Display main timeline'),
            );
            echo $this->formSelect('neatline_time_link_to_nav',
                get_option('neatline_time_link_to_nav') ?: 'browse',
                array(),
                $options);
            ?>
            <p class="explanation">
                <?php echo __('The secondary link is displayed in the menu used in items/browse.'); ?>
                <?php echo __('The option "Main" allows to display a main timeline, like the Geolocation map.'); ?>
            </p>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('neatline_time_link_to_nav_main',
                __('Main timeline')); ?>
        </div>
        <div class="inputs five columns omega">
            <?php
            $options = get_table_options('NeatlineTime_Timeline');
            echo $this->formSelect('neatline_time_link_to_nav_main',
                get_option('neatline_time_link_to_nav_main') ?: '',
                array(),
                $options);
            ?>
            <p class="explanation">
                <?php echo __('This parameter is used only when the previous one is "Display main timeline".'); ?>
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
                <?php echo __('Items with empty value for this field will be skipped.'); ?>
            </p>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('neatline_time_defaults[item_date_end]', __('Item End Date')); ?>
        </div>
        <div class='inputs five columns omega'>
            <?php
                $elements = array('' =>__('None')) + $elements;
                echo $this->formSelect('neatline_time_defaults[item_date_end]',
                    $defaults['item_date_end'],
                    array(),
                    $elements);
            ?>
            <p class="explanation">
                <?php echo __('If set, this field will be used to set the end of a period.'); ?>
                <?php echo __('If should be different from the main date.'); ?>
                <?php echo __('In that case, the previous field will be the start date.'); ?>
                <?php echo __('In all cases, it is possible to set a range in one field with a "/", like "1939-09-01/1945-05-08".'); ?>
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
                <?php echo __('An empty value means "now", "0000-00-00" the earliest date, and "9999-99-99" the latest date.'); ?>
            </p>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('neatline_time_defaults[viewer]', __('Viewer')); ?>
        </div>
        <div class='inputs five columns omega'>
            <?php
            echo $this->formTextarea('neatline_time_defaults[viewer]',
                $defaults['viewer'],
                array(
                    'rows' => 10,
                    'cols' => 60,
            ));
            ?>
            <p class="explanation">
                <?php echo __('Set the default params of the viewer as json, or let empty for the included default.'); ?>
                <?php echo __('Currently, only "bandInfos" and "centerDate" are managed.'); ?>
            </p>
        </div>
    </div>
</fieldset>
