<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Defines the editing form for the true-false question type.
 *
 * @package    qtype
 * @subpackage truefalse
 * @copyright  2007 Jamie Pratt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


require_once($CFG->dirroot.'/question/type/edit_question_form.php');


/**
 * True-false question editing form definition.
 *
 * @copyright  2007 Jamie Pratt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_program_edit_form extends question_edit_form {
    /**
     * Add question-type specific form fields.
     *
     * @param object $mform the form being built.
     */
    protected function definition_inner($mform) {
       $menu = array('C', 'C++', 'Java', 'Python','Other');
	   $mform->addElement('select','lang','Language', $menu);
	   $mform->addElement('text','otherlang','Other Language');
       $mform->addElement('text','compilecommand','Compile Command');
       $mform->addElement('text','executioncommand','Execution Command');
       	
       $mform->addElement('filepicker','userfile','Upload Test Case');
       $mform->addElement('filepicker','progoutput','Desired Output');
       
       
       //$mform->addRule('progoutput', null, 'required', null, 'client');
       //$mform->addRule('userfile', null, 'required', null, 'client');
       $mform->addRule('lang', null, 'required', null, 'client');
       
     }

    public function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);
        
        return $question;
    }

    public function qtype() {
        return 'program';
    }
}
