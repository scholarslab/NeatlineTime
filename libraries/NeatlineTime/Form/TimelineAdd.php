<?php
/**
 * Form for Timeline records.
 */
class NeatlineTime_Form_TimelineAdd extends Omeka_Form
{
    public function init()
    {
        parent::init();

        $this->setMethod('post');
        $this->setAttrib('id', 'timeline-form');

        // Title
        $this->addElement('text', 'title', array(
            'label' => __('Title'),
            'description' => __('A title for this timeline.'),
        ));

        // Description
        $this->addElement('textarea', 'description', array(
            'label' => __('Description'),
            'description' => __('A description for this timeline.'),
            'attribs' => array('class' => 'html-editor', 'rows' => '15'),
        ));

        // Public/Not Public
        $this->addElement('checkbox', 'public', array(
            'label' => __('Status'),
            'description' => __('Whether the timeline is public or not.'),
            'value' => false,
        ));

        // Featured/Not Featured
        $this->addElement('checkbox', 'featured', array(
            'label' => __('Featured'),
            'description' => __('Whether the timeline is featured or not.'),
            'value' => false,
        ));

        $values = get_table_options('Element', null, array(
            'record_types' => array('Item', 'All'),
            'sort' => 'alphaBySet',
        ));
        unset($values['']);
        foreach (array(
                'item_title' => array(__('Item Title')),
                'item_description' => array(__('Item Description')),
                'item_date' => array(__('Item Date')),
            ) as $parameterName => $parameterOptions) {
            $this->addElement('select', $parameterName, array(
                'label' => $parameterOptions[0],
                'multiOptions' => $values,
                'value' => false,
            ));
        }

        $values = array('' =>__('None')) + $values;
        $this->addElement('select', 'item_date_end', array(
            'label' => __('Item End Date'),
            'description' => __('If set, the process will use the other date as a start date.'),
            'multiOptions' => $values,
            'value' => false,
        ));

        // Set fhe mode to render a year.
        $values = array(
            'skip' => __('Skip the record'),
            'january_1' => __('Pick first January'),
            'july_1' => __('Pick first July'),
            'full_year' => __('Mark entire year'),
        );
        $this->addElement('radio', 'render_year', array(
            'label' => __('Render Year'),
            'description' => __('When a date is a single year, like "1066", the value should be interpreted to be displayed on the timeline.'),
            'multiOptions' => $values,
            'value' => false,
        ));

        // Set the center date for the timeline.
        $this->addElement('text', 'center_date', array(
            'label' => __('Center Date'),
            'description' => __('Set the center date of the timeline.')
                . ' ' . __('The format should be "YYYY-MM-DD".')
                . ' ' . __('An empty value means "now", "0000-00-00" the earliest date, and "9999-99-99" the latest date.'),
            'validator' => array('date'),
        ));

        // Set the params of the viewer.
        $this->addElement('textarea', 'viewer', array(
            'label' => __('Viewer'),
            'description' => __('Set the params of the viewer as json, or let empty for the included default.')
                . ' ' . __('Currently, only "bandInfos" and "centerDate" are managed.'),
            'attribs' => array('rows' => '10'),
        ));

        // Submit
        $this->addElement('submit', 'submit', array(
            'label' => __('Save Timeline'),
        ));

        // Group the title, description, and public/featured fields.
        $this->addDisplayGroup(
            array(
                'title',
                'description',
                'public',
                'featured',
            ),
            'timeline_info',
            array(
                'legend' => __('About the timeline'),
                'description' => __('Set the main metadata of the timeline.'),
        ));
        $this->addDisplayGroup(
            array(
                'item_title',
                'item_description',
                'item_date',
                'item_date_end',
                'render_year',
                'center_date',
                'viewer',
            ),
            'timeline_parameters',
            array(
                'legend' => __('Specific parameters'),
                'description' => __('Set the specific parameters of the timeline.')
                    . ' ' . __('If not set, the defaults set in the config page will apply.'),
        ));

        // Add the submit to a separate display group.
        $this->addDisplayGroup(array('submit'), 'timeline_submit');

        $this->addElement('sessionCsrfToken', 'csrf_token');
    }
}
