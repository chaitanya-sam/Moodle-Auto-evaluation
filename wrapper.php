<?php
include_once 'evaluator.php';
include_once 'comparator.php';
//echo "bunoes dis";
//echo exec('pwd');
//code inside moodle
class Assign_marks
{
	public $student_rollno;
	public $student_code;
	public $test_case;
	public $current_output;    //file to be created
	public $expected_output;   //already existing
	public $language;          //language in which the code is written
	public $score;
	public $student_log;
	
/*
 * Wrapper is the code that is called from the gui, it includes evaluator and comparator. So, It does the compiling , 
 * the executing, generation of outputs using testcases provided and evaluating these testcases against the expected
 * output.
 * 
*/
  
  public function __construct($student_code,$roll_no_filename,$test_case,$expected_output,$language)
  {
	//echo "constructor called**************************************************************";
	chdir('/tmp');
	//echo exec('pwd');
	exec('rm a.out');
	exec('rm expected.out');
	exec('rm testcase.in');
	
	exec("rm $roll_no_filename");
	file_put_contents("$roll_no_filename", $student_code);  //storing the file contents to the local file system
	
	$temp  = explode("-",$roll_no_filename);
	$this->student_rollno = $temp[0];
	//echo "current rollno : $temp[0]"."<br/>";
	$this->student_code = $roll_no_filename;
	
	exec('rm testcase.in');
	//echo $test_case;
	file_put_contents('testcase.in', $test_case);
	$this->test_case = "testcase.in";
	//echo "test case is $test_case"."<br/>";
	
	
	$temp  = explode(".",$roll_no_filename);
	//echo "currentfilename = $temp[0]"."<br/>";
	$temp[1] = "out";
	$this->current_output = implode(".",$temp);
	//echo $this->current_output."<br/>";
	//file_put_contents("$this->current_output", $student_code);
	
	$this->expected_output = "expected.out";
	exec('rm expected.out');
	file_put_contents('expected.out', $expected_output);
	$this->language = $language;
	$this->score = 0;
	
	$temp  = explode(".",$roll_no_filename);
	$temp[1] = "log";
	$this->student_log = implode(".",$temp);
	exec("rm $this->student_log");  //clean the previously existing log of the student if any
	  
  }
 public function cleaner()
  {
	chdir('/temp');
	exec("rm $this->student_code");
	exec("rm $this->test_case");
	exec("rm $this->current_output");
	exec("rm $this->expected_output");
  }
  
  //this function gives the contents of the student's execution log
  //can be used to upload this into database
  // Be sure to execute this after the execution of AutomatedEvaluation
 public function get_student_log_content()
 {
	 $student_log_content = file_get_contents("$this->student_log", true);
	 return $student_log_content;
 }
  public function Automated_evaluation()
  {
	  chdir('/tmp');
   switch($this->language)
	{
	  case 1: {
		        
		        exec("rm $this->current_output"); //removes any previously existing outputfile of the same name
		        
		        //compiling and execution
		        $c = new CTester("$this->student_code");
		        $c->compile();
                $c->execute("$this->test_case");
                
                
		        //log writing
		        exec("cat seperator.txt >> log");
		        $fp = fopen("$this->student_log", 'a');
		        fwrite($fp,"Roll Number = $this->student_rollno\n");
		        fwrite($fp,"Matching Output :\n");
		        fclose($fp);
		        
		        //comparator
		        $comp = new Comparator("$this->current_output","$this->expected_output");
		        //echo "@ $this->current_output"."<br/>";
		        //$comp->compare();                      //logwriting and matching at the same time
		        $this->score = $comp->compare_v2($this->student_log);   //to assign marks
		        exec("cat $this->student_log >>log");
		        //echo "the score is $this->score"."<br/>";
		        break;
		        //to be removed
		       // $this->score = 4;
		        //break;
		      }
	  case 2: //java
	          {
				//compiling and execution
				$j = new JavaTester("$this->student_code");
				$j->compile();
				$j>execute("$this->test_case");
				
				
				//log writing
		        exec("cat seperator.txt >> log");
		        $fp = fopen('log', 'a');
		        fwrite($fp,"Roll Number = $this->student_rollno\n");
		        fwrite($fp,"Matching Output :\n");
		        fclose($fp);
				
				
				//comparator
		        $comp = new Comparator("$this->current_output","$this->expected_output");
		        //echo "@ $this->current_output";
		        $comp->compare();                      //logwriting and matching at the same time
		        $this->score = $comp->compare_v2();   //to assign marks
		        //echo "the score is $this->score";
				break;
			  }       
	  default: echo "code is not present in the evaluator yet";	
	}
	chdir('/var/www/moodle/mod/quiz');
	//echo $this->score;
	return $this->score;  
  }
}

/*********************************Wrapper Driver*************************************************
$code_ptr = file_get_contents('mt2013050-runrate-basic.c', true);
$testcase_ptr = file_get_contents('runrate-basic.in',true);
$expected_ptr = file_get_contents('expected-runrate-basic.out',true);
$s1 = new Assign_marks($code_ptr,"mt2013050-runrate-basic.c",$testcase_ptr,$expected_ptr,1);
$score = $s1->Automated_evaluation();
echo "the wrapper is working $score"."<br />";
/* *********************************************************************************************/
?>
