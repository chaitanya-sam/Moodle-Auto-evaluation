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
 * True-false question definition class.
 *
 * @package    qtype
 * @subpackage truefalse
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

include_once "Evaluation.php";
include_once "wrapper.php";
/**
 * Represents a true-false question.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_program_question extends question_graded_automatically {
    public $rightanswer;
    public $rightoutput;
    //public $lang;
    public function get_expected_data() {
        return array('answer' => PARAM_INTEGER);
    }

    public function get_correct_response() {
        return array('answer' => (int)$this->rightanswer, 'answer' => (int)$this->rightoutput);
    }

    public function summarise_response(array $response) {
        
    }

    public function classify_response(array $response) {
       
    }

    public function is_complete_response(array $response) {
        return array_key_exists('answer', $response);
    }

    public function get_validation_error(array $response) {
        if ($this->is_gradable_response($response)) {
            return '';
        }
        return get_string('pleaseselectananswer', 'qtype_program');
    }

    public function is_same_response(array $prevresponse, array $newresponse) {
        return question_utils::arrays_same_at_key_missing_is_blank(
                $prevresponse, $newresponse, 'answer');
    }

    public function grade_response(array $response) {
       /* if ($this->rightanswer == $response['answer']) {
            $fraction = 1;
        } else {
            $fraction = 0;
        }*/
         $eval = new Evaluation();
         //$s1 = new Assign_marks("mt2013050-runrate-basic.c","runrate-basic.in","expected-runrate-basic.out",1);
        
         
           $fraction = $eval->evaluate($response,$this->rightanswer,$this->rightoutput);

        return array($fraction, question_state::graded_state_for_fraction($fraction));
    }

    public function check_file_access($qa, $options, $component, $filearea, $args, $forcedownload) {}
       
}
