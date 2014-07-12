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
 * Question type class for the true-false question type.
 *
 * @package    qtype
 * @subpackage truefalse
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * The true-false question type class.
 *
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_program extends question_type {
    public function save_question_options($question) {
        global $DB;
        $result = new stdClass();
        $context = $question->context;
          // Fetch old answer ids so that we can reuse them
        $oldanswers = $DB->get_records('question_program',
                array('question' => $question->id), 'id ASC');
        // Save the answer - update an existing answer if possible.
        $answer = array_shift($oldanswers);
        if (!$answer) {
            $answer = new stdClass();
            $answer->question = $question->id;
            $answer->answer = 0;
            $answer->itemid = $question->progoutput;
			$answer->lang = $question->lang;
           // $answer->classmark = $question->classmark;
            //$answer->relationmark = $question->relationmark;
            $answer->id = $DB->insert_record('question_program', $answer);
        }

		$answer->itemid = $question->progoutput;
        $answer->lang = $question->lang;
        $answer->otherlang = $question->otherlang;
        $answer->compilecommand = $question->compilecommand;
        $answer->executioncommand = $question->executioncommand;
        $DB->update_record('question_program', $answer);
         
         
         $oldanswers = $DB->get_records('question_answers',
                array('question' => $question->id), 'id ASC');

        $answer = array_shift($oldanswers);
        if (!$answer) {
            $answer = new stdClass();
            $answer->question = $question->id;
            $answer->answer = '';
            $answer->feedback = '';
            $answer->id = $DB->insert_record('question_answers', $answer);
        }
        //update answer table
        $answer->answer   = $question->userfile;
        $answer->fraction = $question->defaultmark;
        $answer->feedbackformat = $question->generalfeedback['format'];
        $DB->update_record('question_answers', $answer);
        $trueid = $answer->id;
               
        
        // Delete any left over old answer records.
        $fs = get_file_storage();
        foreach ($oldanswers as $oldanswer) {
            $fs->delete_area_files($context->id, 'question', 'answerfeedback', $oldanswer->id);
            $DB->delete_records('question_answers', array('id' => $oldanswer->id));
        }

        // Save question options in question_truefalse table
        if ($options = $DB->get_record('question_program', array('question' => $question->id))) {
            // No need to do anything, since the answer IDs won't have changed
            // But we'll do it anyway, just for robustness
            $options->answer  = $trueid;
            $DB->update_record('question_program', $options);
        } else {
            $options = new stdClass();
            $options->question    = $question->id;
            $options->answer  = $trueid;
            $DB->insert_record('question_program', $options);
        }

        $this->save_hints($question);

        return true;
    }

    /**
     * Loads the question type specific options for the question.
     */
    public function get_question_options($question) {
        global $DB, $OUTPUT;
        // Get additional information from database
        // and attach it to the question object
        if (!$question->options = $DB->get_record('question_program',
                array('question' => $question->id))) {
            echo $OUTPUT->notification('Error: Missing question options!');
            return false;
        }
        // Load the answers
        if (!$question->options->answers = $DB->get_records('question_answers',
                array('question' =>  $question->id), 'id ASC')) {
            echo $OUTPUT->notification('Error: Missing question answers for program question ' .
                    $question->id . '!');
            return false;
        }
        
          
        return true;
    }

    protected function initialise_question_instance(question_definition $question, $questiondata) {
        parent::initialise_question_instance($question, $questiondata);
                global $DB;
        $answers = $questiondata->options->answers;
        $question->generalfeedback =  $answers[$questiondata->options->answer]->feedback;
                $question->generalfeedbackformat =
                $answers[$questiondata->options->answer]->feedbackformat;
        $question->answerid =  $questiondata->options->answer;
        //$question->rightanswer = 10;
         $getanswers = $DB->get_records('question_answers',array('question' => $question->id), 'id ASC');
         $answerval = array_shift($getanswers);
         if($answerval)
         $question->rightanswer = $answerval->answer;
         
         
         $getanswers = $DB->get_records('question_program',array('question' => $question->id), 'id ASC');
         $answerval = array_shift($getanswers);
         if($answerval)
         $question->rightoutput = $answerval->itemid;

         /*$getanswers = $DB->get_records('question_program',array('question' => $question->id), 'id ASC');
         $answerval = array_shift($getanswers);
         if($answerval)
         {
         $question->classmark = $answerval->classmark;
         $question->relationmark = $answerval->relationmark;
         }*/
         
    }

    public function delete_question($questionid, $contextid) {
        global $DB;
        $DB->delete_records('question_program', array('question' => $questionid));

        parent::delete_question($questionid, $contextid);
    }

    public function move_files($questionid, $oldcontextid, $newcontextid) {
        parent::move_files($questionid, $oldcontextid, $newcontextid);
        $this->move_files_in_answers($questionid, $oldcontextid, $newcontextid);
    }

    protected function delete_files($questionid, $contextid) {
        parent::delete_files($questionid, $contextid);
        $this->delete_files_in_answers($questionid, $contextid);
    }

    public function get_random_guess_score($questiondata) {
        return 0.5;
    }

    public function get_possible_responses($questiondata) {
       
    }
}
