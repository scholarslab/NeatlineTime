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
            'label'       => __('Title'),
            'description' => __('A title for your timeline.')
        ));

        // Description
        $this->addElement('textarea', 'description', array(
            'label'       => __('Description'),
            'description' => __('A description for your timeline.'),
            'attribs'     => array('class' => 'html-editor', 'rows' => '15')
        ));

        // Public/Not Public
        $this->addElement('select', 'public', array(
            'label'        => __('Status'),
            'description'  => __('Whether the timeline is public or not.'),
            'multiOptions' => array('0' => 'Not Public', '1' => 'Public')
        ));

        // Featured/Not Featured
        $this->addElement('select', 'featured', array(
            'label'        => __('Featured'),
            'description'  => __('Whether the timeline is featured or not.'),
            'multiOptions' => array('0' => 'Not Featured', '1' => 'Featured')
        ));


        // Set the center date for the timeline
        $this->addElement('text', 'center_date', array(
            'label'       => __('Center Date'),
            'description' => __('Set the center date of your timeline. Please use format YYYY-MM-DD.'),
            'validator' => array('date')
        ));

        // Submit
        $this->addElement('submit', 'submit', array(
            'label' => __('Save Timeline')
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
                'center_date',
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
