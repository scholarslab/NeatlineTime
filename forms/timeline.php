<?php
/**
 * PHP 5
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 */

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
        $this->addDisplayGroup(array('submit'), 'timeline_submit');
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
