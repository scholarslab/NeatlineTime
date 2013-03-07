<?php
/**
 * Form for Timeline records.
 */
class NeatlineTime_Form_Timeline extends Omeka_Form
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

        // Submit
        $this->addElement('submit', 'submit', array(
            'label' => __('Save Timeline')
        ));

        // Group the title, description, and public fields
        $this->addDisplayGroup(
            array('title', 
                  'description',
                  'public',
                  'featured'
                 ),
            'timeline_info'
        );

        // Add the submit to a separate display group.
        $this->addDisplayGroup(array('submit'), 'timeline_submit');
    }

}
