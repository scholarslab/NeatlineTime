<div class="field">
    <label for="item_date"><?php echo __('Item Date'); ?></label>
    <div class="inputs">
    <?php echo neatlinetime_option_select('item_date'); ?>

    <p class="explanation">
    <?php
        echo __('The date field to use to retrieve and display items on a timeline. Default is DC:Date.'); ?>
    </p>
    </div>
</div>

<div class="field">
    <label for="item_title"><?php echo __('Item Title'); ?></label>
    <div class="inputs">


    <?php echo neatlinetime_option_select('item_title'); ?>
    <p class="explanation">
    <?php
        echo __('The title field to use when displaying an item on a timeline. Default is DC:Title'); ?>
    </p>
    </div>
</div>

<div class="field">
    <label for="item_description"><?php echo __('Item Description'); ?></label>
    <div class="inputs">

    <?php echo neatlinetime_option_select('item_description'); ?>
    <p class="explanation">
    <?php
        echo __('The description field to use when displaying an item on a timeline. Default is DC:Description'); ?>
    </p>
    </div>
</div>
