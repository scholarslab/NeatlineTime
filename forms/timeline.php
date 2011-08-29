<?php
/**
 * @author Scholars' Lab
 * @copyright 2011 The Board and Visitors of the University of Virginia
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @package Timeline
 * @link http://omeka.org/codex/Plugins/Timeline
 * @since 1.0
 */

/**
 * Form for Timeline records.
 *
 * @since 1.0
 * @author Scholars' Lab
 * @package Timeline
 * @subpackage Forms
 */
class Timeline_Form_Timeline extends Omeka_Form
{
    public function init()
    {
        parent::init();

        $this->setMethod('post');
        $this->setAttrib('id', 'timeline-form');

        // Title
        $this->addElement('text', 'title', array(
            'label'       => 'Title',
            'description' => 'A title for your timeline.'
        ));

        // Description
        $this->addElement('textarea', 'description', array(
            'label'       => 'Description',
            'description' => 'A description for your timeline.',
            'attribs'     => array('class' => 'html-editor', 'rows' => '15')
        ));

        // Public/Not Public
        $this->addElement('select', 'public', array(
            'label'        => 'Status',
            'description'  => 'Whether the timeline is public or not.',
            'multiOptions' => array('0' => 'Not Public', '1' => 'Public')
        ));

        // Submit
        $this->addElement('submit', 'submit', array(
            'label' => 'Save Timeline'
        ));

        // Group the title, description, and public fields
        $this->addDisplayGroup(
            array('title', 
                  'description',
                  'public',
                 ),
            'timeline_info'
        );

        // Add the submit to a separate display group.
        $this->addDisplayGroup(array('submit'), 'submit');
    }

    /**
     * Overrides the default decorators in Omeka Form to remove escaping from element descriptions.
     **/
    public function getDefaultElementDecorators()
    {
        return array(
            'ViewHelper', 
            'Errors', 
            array(array('InputsTag' => 'HtmlTag'), array('tag' => 'div', 'class' => 'inputs')), 
            array('Description', array('tag' => 'p', 'class' => 'hint', 'escape' => false)), 
            'Label', 
            array(array('FieldTag' => 'HtmlTag'), array('tag' => 'div', 'class' => 'field'))
        );
    }
}
