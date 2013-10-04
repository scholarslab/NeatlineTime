<?php
$formStem = $block->getFormStem();
$options = $block->getOptions();
?>
<div class="timeline-id">
    <?php
    echo $this->formLabel($formStem . '[options][timeline-id]',
        __('Select a timeline'));
    echo $this->formSelect($formStem . '[options][timeline-id]',
        @$options['timeline-id'], array(),
        get_table_options('NeatlineTimeTimeline'));
    ?>
</div>
