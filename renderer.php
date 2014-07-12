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
 * True-false question renderer class.
 *
 * @package    qtype
 * @subpackage truefalse
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

include_once 'wrapper.php';
include_once 'test.php';

/**
 * Generates the output for true-false questions.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_program_renderer extends qtype_renderer {
      public function formulation_and_controls(question_attempt $qa,
            question_display_options $options) {
            Global $DB;
       $question = $qa->get_question();
       $step = $qa->get_last_step_with_qt_var('answer');
       $files = '';
       if (empty($options->readonly)) {
                $files = $this->files_input($qa, 1, $options);

            } else {
                $files = $this->files_read_only($qa, $options);                
            }
        

        $result = '';
        $result .= html_writer::tag('div', $question->format_questiontext($qa),
                array('class' => 'qtext'));

        $result .= html_writer::start_tag('div', array('class' => 'ablock'));
        $result .= html_writer::tag('div', $files, array('class' => 'answer'));
        $result .= html_writer::end_tag('div');

        return $result;
    }
    public function files_input(question_attempt $qa, $numallowed,
            question_display_options $options) {
        global $CFG;
        require_once($CFG->dirroot . '/lib/form/filemanager.php');

        $pickeroptions = new stdClass();
        $pickeroptions->mainfile = null;
        $pickeroptions->maxfiles = $numallowed;
        $pickeroptions->itemid = $qa->prepare_response_files_draft_itemid(
                'answer', $options->context->id);
        $pickeroptions->context = $options->context;
        $pickeroptions->return_types = FILE_INTERNAL;

        $pickeroptions->itemid = $qa->prepare_response_files_draft_itemid(
                'answer', $options->context->id);

        $fm = new form_filemanager($pickeroptions);
        $filesrenderer = $this->page->get_renderer('core', 'files');
        return $filesrenderer->render($fm). html_writer::empty_tag(
                'input', array('type' => 'hidden', 'name' => $qa->get_qt_field_name('answer'),
                'value' => $pickeroptions->itemid));
    }
     public function files_read_only(question_attempt $qa, question_display_options $options) {
        $files = $qa->get_last_qt_files('answer', $options->context->id);
        $output = array();

        foreach ($files as $file) {
            $output[] = html_writer::tag('p', html_writer::link($qa->get_response_file_url($file),
                    $this->output->pix_icon(file_file_icon($file), get_mimetype_description($file),
                    'moodle', array('class' => 'icon')) . ' ' . s($file->get_filename())));
        }
        return implode($output);
    }

    public function specific_feedback(question_attempt $qa) {

    }

     
    public function correct_response(question_attempt $qa) {
		$qa1=$qa;
     $fs = get_file_storage();
     $question= $qa->get_question();
     $rightanswer = $question->rightanswer;
     $rightoutput = $question->rightoutput;
     $submittedanswer = $qa->get_last_qt_var('answer');

//read correct test
global $DB;
 $fraction = '';
$result_main = $DB->get_records('files',array('itemid'=>$rightanswer));
$result_main = array_shift($result_main);

      $fs = get_file_storage();
// Prepare file record object
$fileinfo = array(
    'component' => $result_main->component,     // usually = table name
    'filearea' => $result_main->filearea,     // usually = table name
    'itemid' => $rightanswer,               // usually = ID of row in table
    'contextid' => $result_main->contextid, // ID of context
    'filepath' => $result_main->filepath,           // any path beginning and ending in /
    'filename' => $result_main->filename); // any filename

// Get file
$file_teacher_testcase = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
                      $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);
$contents_teacher_testcase = '';
// Read contents
if ($file_teacher_testcase) {
    $contents_teacher_testcase = $file_teacher_testcase->get_content();
} else {
    // file doesn't exist - do something
}


//load correct output
$result_main = $DB->get_records('files',array('itemid'=>$rightoutput));
$result_main = array_shift($result_main);

      $fs = get_file_storage();
// Prepare file record object
$fileinfo = array(
    'component' => $result_main->component,     // usually = table name
    'filearea' => $result_main->filearea,     // usually = table name
    'itemid' => $rightoutput,               // usually = ID of row in table
    'contextid' => $result_main->contextid, // ID of context
    'filepath' => $result_main->filepath,           // any path beginning and ending in /
    'filename' => $result_main->filename); // any filename

// Get file
$file_teacher_output = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
                      $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);
$contents_teacher_output = '';
// Read contents
if ($file_teacher_output) {
    $contents_teacher_output = $file_teacher_output->get_content();
} else {
    // file doesn't exist - do something
}


global $DB;
$result = $DB->get_records('files',array('itemid'=>$submittedanswer));
$result = array_shift($result);

$fs = get_file_storage();
// Prepare file record object
$fileinfo = array(
    'component' => $result->component,     // usually = table name
    'filearea' => $result->filearea,     // usually = table name
    'itemid' =>  $submittedanswer,               // usually = ID of row in table
    'contextid' => $result->contextid, // ID of context
    'filepath' => $result->filepath,           // any path beginning and ending in /
    'filename' => $result->filename); // any filename

// Get file
$file_student = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
                      $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);
$contents_student_code = '';
// Read contents
if ($file_student) {
    $contents_student_code = $file_student->get_content();
} else {
    // file doesn't exist - do something
}
//$temp = new solution();
//$parse = new simpleparser();
//$temp = $parse->parse1($contents_teacher,$contents_student,50,50);
//$str = $temp->teachersoln.$temp->studsoln;
//$temp1 = new solution();
//$str = $temp1->getgrade($file_teacher_testcase, $file_teacher_output, $file_student);
$score = 0;
$s1 = new Assign_marks($contents_student_code,$fileinfo['filename'],$contents_teacher_testcase,$contents_teacher_output,1);
$score = $s1->Automated_evaluation();
	//$temp = new test();
	//$temp->create($file_student,$file_teacher_output);
//echo "the score: $score";
return $score;//$str;
//echo $contents_student_code;
//return $score;
}
}
