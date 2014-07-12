<?php
//inside moodle
class Comparator 
{
	public $current_output;
	public $expected_output;
	public function __construct($f1,$f2)
	{
		$this->current_output = $f1;
		$this->expected_output = $f2;
	}
	
//compare makes use of python script \n not working properly	
	public function compare()
	{
     //chdir('/tmp');
    // echo exec('pwd');
	//echo "inside compare";
	/******************************
      exec('gcc return_exp.c');
      exec("./a.out",$output,$return_value);
    /******************************/
    /*****************************/
     //exec("python test_script.py $this->expected_output $this->current_output >> log");
    /******************************/
	//  echo "return  = $return_value" ."<br />";
	}
	
	
	
	/******************************working properly*********************/
	public function compare_v2($student_log)
	{
		//chdir('/tmp');
		$file1 = file($this->current_output);
		$file2 = file($this->expected_output);
		//echo $file2[0];
		//echo "size of the first arrray is"; 
		$len1 = sizeof($file1);
		$len2 = sizeof($file2);
		if (trim($file1[$len2-1]) == '') 
		          {
					  $len2 = $len2-1;
				  }
		if (trim($file1[$len1-1]) == '') 
		          {
					  $len1 = $len1-1;
				  }
		$fp = fopen("$student_log", 'a');
		fwrite($fp,"No of lines in the expected output : $len2\n");
		fwrite($fp,"No of lines in the current output  : $len1\n");
		$n = min($len1,$len2);
		//echo "min = $n"."<br />";
		//echo gettype($file1[0]);
		$score = 0;	
		for ($i=0; $i < $n; $i++)
		{
			//echo "i = $i"."<br />";
			$expected = trim($file2[$i]);
			$current = trim($file1[$i]);
			fwrite($fp,"\nexpected                           : $expected\n");
			fwrite($fp,"current                            : $current\n");
			if (strcmp($expected,$current) == 0)
		     {
			    $score = $score+1;
			    //echo $score."<br />";
			    fwrite($fp,"result                             : Sucess\n");
	         }
	        else fwrite($fp,"result                            : Failure\n\n");
			
		}
		//echo "score = $score";
		//echo($score/$len2);
		fclose($fp);
		return ($score/$len2);
		
	}
	public function min($m,$n)
	{
		if ($m == $n)
	        return $m;
	    else if($m < $n)
	        return $m;
	    else return $n;
	}
	/************************************************************************************************/
}
/****************************driver*****************************************************
$comp = new Comparator("output","expected-runrate-basic.out");
$score  = $comp->compare_v2();	
/**************************************************************************************/
//echo "the score is $score"."<br />";
//echo "the comparator is working"."<br />";
?>
