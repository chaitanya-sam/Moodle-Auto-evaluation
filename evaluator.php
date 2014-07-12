<?php
//inside moodle
//exec('rm output');
abstract class Tester
{
   public $filename;
   public function __construct($fname)
	{
		$this->filename = $fname;
	}
  abstract public function compile();
  abstract public function execute($testcase);
}

class CTester extends Tester
{
   public function __construct($fname)
   {
	   parent::__construct($fname);
	   //echo "C Tester initialised" ."<br />";
   }
   public function compile()
   {
	   //chdir('/tmp');
	   $fname = $this->filename;
    exec("gcc $fname");
    //echo exec('pwd');
    
	   //echo "compiled";
	  
	   return true;
   }
   public function execute($testcase)
   {
	   //chdir('/tmp');
	   //exec("cat seperator.txt >> log");
	   $fname = $this->filename;
	   $f  = explode(".",$fname);
	   //echo "name = $f[0]";
	   $f[1]= "out";
	   $output = implode(".",$f);
	   //echo "out put is $output"."<br />";

	   exec("./a.out $testcase $output");
	   //echo "C executor called"."<br />";
   } 	
}


class JavaTester extends Tester
{
   public function __construct($fname)
   {
	   parent::__construct($fname);
	   //echo "Java Tester initialised" ."<br />";
   }
   public function compile()
   {
	   //chdir('/tmp');
	   $fname = $this->filename;
    exec("javac $fname");
    
	   //echo "compiled";
	  
	   return true;
   }
   public function execute($testcase)
   {
	    chdir('/tmp');
	   //exec("cat seperator.txt >> log");
	   $fname = $this->filename;
	   $f  = explode(".",$fname);
	   $f[1]= "out";
	   $output = implode(".",$f);
	   //$output = fname +".out";
	   //echo "out put is $output";
	   exec("javac $fn[0] $testcase $output");
	   //echo "Java executor called"."<br />";
   } 	
}


/* **********************************************CDRIVER*******************************
$c = new CTester("mt2013050-runrate-basic.c");
$c->compile();
$c->execute("runrate-basic.in");
/*************************************************************************************/

/***********************************************passing command line arguments***********************/



/****************************************************************************************************/

/* **********************************************CDRIVER*******************************
$j = new JavaTester("Hello.java");
$j-> execute("hells.c");


/************************************************CDRIVER*******************************/

//echo "the evaluator is working"."<br />";
?>
