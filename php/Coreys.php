<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	</head>
	<body>

<?php	
set_time_limit(60); //60 seconds = 1 minute
	
	DEFINE('DBUSER','root');
	DEFINE('DBPW','mysql');
	DEFINE('DBHOST','localhost');
	DEFINE('DBNAME','database_name');
	
	if ($dbc = mysql_connect (DBHOST, DBUSER, DBPW))
	{
		if (!mysql_select_db (DBNAME))
		{
			trigger_error("Coundn't select database MySQL Error:" . mysql_error());
			exit();
		} 
	} else {
	
	trigger_error("Coundn't select database MySQL Error:" . mysql_error());
	exit();
	}




//********************Corey's Section ************************************************************************************	

	//START - The follow code is all for naming a file the current date and time
	$hour =(date('H') - 6.0);
	if ($hour < 10) 
		$hour = "0" . $hour;
	$file_prepped_for_changing = date('Y-m-d_') . $hour . date('-i-s')  .  ".txt";
	//END - Code naming a file the current date and time
	
	$target_file= "../../examples/line-time-series - For Corey/uploads/To Add To Database/Mess_for_Corey.txt";
	$automatically_populated_textfile = file_get_contents($target_file);  //this will be the raw file in the future, hopefully automatically saved in some location
	$file_prepped_for_changing =  '../../examples/line-time-series - For Corey/uploads/' . $file_prepped_for_changing; //$path . $file_prepped_for_changing;
	file_put_contents($file_prepped_for_changing, $automatically_populated_textfile); //putting the contents in the file saved as today's date and current time stamp
	$lines = file($file_prepped_for_changing);
	
	
		$iterations = 0;  //This is only for troubleshooting and seeing outputs of items on your browser
		foreach ($lines as $line){
			$iterations = $iterations + 1; //This is only for troubleshooting and seeing outputs of items on your browser
			
			//echo $iterations . ': ' . $line . '<br>'; //This is only for troubleshooting and seeing outputs of items on your browser
			
			//$line = preg_replace("/(?:\s\s+|\n|\t)/", ' ', $line); //replace spaces inbetween numbers
			
			//echo $iterations . ': ' . $line . '<br>'; //This is only for troubleshooting and seeing outputs of items on your browser
			
			//$line = preg_replace("/^ +/", '', $line); //replace the first space... i think
			
			//echo $iterations . ': ' . $line . '<br>'; //This is only for troubleshooting and seeing outputs of items on your browser
			
			//$unformated_date = preg_match_all("/\d{1,2}\/\d{2}\/\d{4}/",$line,$matches_out,PREG_PATTERN_ORDER); //Find dates	
			
			//echo $iterations . ': ' . $unformated_date  . '<br>'; //This is only for troubleshooting and seeing outputs of items on your browser
			
			//$unformated_date = strtotime($matches_out[0][0]);  //Take the first string and convert it to an actual date
			
			//echo $iterations . ': ' . $unformated_date  . '<br>'; //This is only for troubleshooting and seeing outputs of items on your browser
			
			//$formated_date = date('Y-m-d',$unformated_date);  //Format Date to the Y-m-d format
			
			//echo $iterations . ': ' . $formated_date   . '<br>'; //This is only for troubleshooting and seeing outputs of items on your browser
			
			//$line = preg_replace("/\d{1,2}\/\d{2}\/\d{4}/",$formated_date, $line); //Replace the old date with the new, properly formated date

			//echo $iterations . ': ' . $line . '<br>'; //This is only for troubleshooting and seeing outputs of items on your browser
			
			$line = preg_replace("/\"/", '', $line);	//This line should replace the quotation marks with space
			
			$line = preg_replace("/ERCOT/", '', $line);	//This will take out the word ERCOT
			
			$line = preg_replace("/\,/", ' ', $line);	//This line will remove all commas
			
			$line = preg_replace("/(f f f f f f f f)/", '', $line);	//This will remove "f f f f f f f f"
			
			$line = preg_replace("/(Process f f)/", ' ', $line);	//This should replace the string of characters "Process f f" with a space
			
			$line = preg_replace("/\s\d{4}\-\d{1,2}\-\d{1,2}\s/", ' ', $line);	//This line will replace exactly one of the dates
			
			$line = preg_replace("/(SEL-3530-0030A709C5FA)/", ' ', $line);	//Replace the string of text with a space

			$line = preg_replace("/\s+(f)\s+(t)\s+(f)\s+(f)\s+/", ' ', $line);	//Replace the string of text with a space
			
			$line = preg_replace("/\s\w{8}\s/", ' ', $line);	//Delete "Protocol"
			
			$line = preg_replace("/(IEC_UNSPECIFIED)/", '', $line);	//Delete "IEC_UNSPECIFIED"
			
			$line = preg_replace("/(IEC_GOOD)/", '', $line);
			
			$line = preg_replace("/\s+\w\s+\w\s+\w\s+\w\s+/", ' ', $line);
			
			$line = preg_replace("/\s+(DNP)\s+/", ' ', $line);
			
			$line = preg_replace("/\d{2}\s+\d\s+\d{2}\:\d{2}\:\d{2}\.\d{5,6}/", '', $line);
			
			$line = preg_replace("/^/", '<br>', $line);	//Skip a line at the beginning
			
			//echo $iterations . ': ' . $line . '<br>'; //This is only for troubleshooting and seeing outputs of items on your browser
			
			echo "<br>";
			
			$line = preg_match_all("/[A-Za-z]+\w*\.\w+/", $line, $Patchy, PREG_PATTERN_ORDER);
		
			$Patchy_variable = $Patchy[0][0];
			echo $Patchy_variable; 
			
			$line = preg_match_all("/\s\-?\d+\.\d+/", $line, $Aiden, PREG_PATTERN_ORDER);
			
			$Aiden_variable = $Aiden[0][0];
			echo $Aiden; 
			
			$line = preg_match_all("/\d{1,2}\:\d{2}\:\d{2}\.\d+/", $line, $Sagey, PREG_PATTERN_ORDER);
			
			$Sagey_variable = $Sagey[0][0];
			echo $Sagey; 
			
			$line = preg_match_all("/[A-Z]{2}\s[A-Za-z]+|[A-Za-z]+\s[A-Za-z]+\s[A-Za-z]+/", $line, $Maddy, PREG_PATTERN_ORDER);
			
			$Maddy_variable = $Maddy[0][0];
			echo $Maddy;
			
			$query_text = 'INSERT INTO "Goal_for_Corey"("Tag_Name Message", "Message", "t_Value", "Comment") VALUES ($Patchy_variable, $Aiden_variable, $Sagey_variable, $Maddy_variable)';
			$query_ran = mysql_query($query_text) or trigger_error('Query MySQL Error: ' . mysql_error());
			
			
			//$sql = "INSERT INTO Goal_for_Corey (Tag_Name Message, Message, t_Value, Comment)
			//VALUES($out1, $out2, $out3, $out4)";
			
			//$out = array($out1, $out2, $out3, $out4);	//Create multi-dimensional array to store all of the matches
			//Look at Derek Banas' Web Design and Programming Pt 4 PHP Arrays for more examples of this
			
			//print_r ($out1);
			
			$result .= $line . "\n";  //add a return to each line			
			
			}
		file_put_contents($file_prepped_for_changing, $result);  //Tada, file should be formed
		
	


//This next section gets into enter your data from the text file you just reformatted above, to an actual database.  You will probably want to read more about SQL database on w3c schools before tinkering
	
		$querytextfiletosql2 = 'LOAD DATA LOCAL INFILE "' . $file_prepped_for_changing .
								'" REPLACE INTO TABLE table_name 
								FIELDS TERMINATED BY " " 
								LINES TERMINATED BY "\\n"
								(X,Date_Table,Time_Table,VA_MAG,VB_MAG,VC_MAG,MV10,SC01,SC02,P,Q,PF,MV32,MV27) ';
		
		$result2 = mysql_query($querytextfiletosql2) or trigger_error('Query MySQL Error: ' . mysql_error());
		
		
$queryreduceduplicates = 'CREATE TABLE table_reorganized
SELECT X,Date_Table,Time_Table,VA_MAG,VB_MAG,VC_MAG,MV10,SC01,SC02,P,Q,PF,MV32,MV27
FROM table_name
ORDER BY Date_Table, Time_Table ASC;';
$result3 = mysql_query($queryreduceduplicates) or trigger_error('Query MySQL Error: ' . mysql_error());


$queryreduceduplicates = 'DELETE FROM table_name WHERE 1;';
$result3 = mysql_query($queryreduceduplicates) or trigger_error('Query MySQL Error: ' . mysql_error());

$queryreduceduplicates = 'INSERT INTO table_name SELECT DISTINCT X,Date_Table,Time_Table,VA_MAG,VB_MAG,VC_MAG,MV10,SC01,SC02,P,Q,PF,MV32,MV27 FROM table_reorganized; ';
$result3 = mysql_query($queryreduceduplicates) or trigger_error('Query MySQL Error: ' . mysql_error());

$queryreduceduplicates = 'DROP TABLE table_reorganized;';
$result3 = mysql_query($queryreduceduplicates) or trigger_error('Query MySQL Error: ' . mysql_error());


//Identifying Out of Bounds Parameters 
$queryoutofbounds = 'DROP TABLE outofbounds';
$result4 = mysql_query($queryoutofbounds) or trigger_error('Query MySQL Error: ' . mysql_error());

$queryoutofbounds = 'CREATE TABLE outofbounds 
SELECT X,Date_Table,Time_Table,VA_MAG,VB_MAG,VC_MAG,MV10,SC01,SC02,P,Q,PF,MV32,MV27 
FROM table_name 
WHERE (PF> .95 AND P>4000 AND (abs(MV10-Q) > 2000) AND ((Q>3000) OR (Q<-3000)) AND ((VA_MAG+VB_MAG+VC_MAG)/3)<93822) 
OR    (PF> .95 AND P>4000 AND (abs(MV10-Q) > 2000) AND ((Q>3000) OR (Q<-3000)) AND ((VA_MAG+VB_MAG+VC_MAG)/3)>95548)';
$result4 = mysql_query($queryoutofbounds) or trigger_error('Query MySQL Error: ' . mysql_error());
		
		
		
		
		
		
//**********************End of Corey's Section... for now**********************************************************************************
?>  </body>
</html>
