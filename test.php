<?php

defined('MOODLE_INTERNAL') || die();

class test {
	
	public function create($contents_student_code,$temp) {
		
		/*$parts = explode('/', 'temp');
        $file = array_pop($parts);
        $dir = '';
        foreach($parts as $part)
            if(!is_dir($dir .= "/$part")) mkdir($dir);
        //file_put_contents("$dir/$file", $contents);
		echo $contents_teacher_output;
		file_put_contents ("$dir/$filename" , $contents_student_code);
		$filename .= "output.txt";
		//echo $filename;
		file_put_contents ("$dir/$filename", $contents_teacher_output);
		$filename .= "testcase.txt";
		file_put_contents ("$dir/$filename" , $contents_teacher_testcase);
		
		//echo "ashu";
		*/
		
		//echo $temp;
		$temp1 = $temp->get_content();
		echo $temp1;
	}
}
