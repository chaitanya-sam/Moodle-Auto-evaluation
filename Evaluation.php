<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Evaluation
 *
 * @author root
 */
 
//include_once 'test.php';
include_once 'wrapper.php';
class Evaluation {
    //put your code here
    //public static question_attempt $qa1;
  public function evaluate(array $response,$rightanswer,$rightoutput)
    {
	
	//read corect output
 global $DB;
 $fraction = '';
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


/* $parser = new Parser_Diag();
$result = $parser->parse0($contents_teacher,$contents_student);
$fraction = $result->result;
   if (strcmp($contents_teacher,$contents_student)==0) {
           return 1;
        } else {
           return 0;
        }
*/

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
$contents_teacher_file = '';
// Read contents
if ($file_teacher) {
    $contents_teacher_file = $file_teacher->get_content();
} else {
    // file doesn't exist - do something
}

//read student code
global $DB;
$result = $DB->get_records('files',array('itemid'=>$response['answer']));
$result = array_shift($result);

$fs = get_file_storage();
// Prepare file record object
$fileinfo = array(
    'component' => $result->component,     // usually = table name
    'filearea' => $result->filearea,     // usually = table name
    'itemid' =>  $response['answer'],               // usually = ID of row in table
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

$s1 = new Assign_marks($contents_student_code,$fileinfo['filename'],$contents_teacher_testcase,$contents_teacher_output,1);
$score = $s1->Automated_evaluation();

//($contents_teacher_output,$contents_teacher_testcase,$contents_student_code,$fileinfo['filename']);

//echo $contents_student_code;
//$temp=0;
//$obj = new test();
//$temp=$obj->test_func();

//return .3;
return $score;//$temp->fraction;
    }
   
}
?>
