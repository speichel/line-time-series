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

	
	
$target_dir = "../../../examples/line-time-series/uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);




$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);


// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = filesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is OK" . $check["mime"] . ". ";
        $uploadOk = 1;
    } else {
        echo "File is NOT OK. ";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {

    echo "File already exists. ";
    $uploadOk = 0;
	
}

// Check file size
//if ($_FILES["fileToUpload"]["size"] > 500000) {
    //echo "Sorry, your file is too large.";
    //$uploadOk = 0;
//}
// Allow certain file formats
//if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
//&& $imageFileType != "gif" ) {
    //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    //$uploadOk = 0;
//}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Your file was not uploaded. ";
		
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
	
	
	
	//START - The follow code is all for naming a file the current date and time
	$hour =(date('H') - 05.0);
	if ($hour < 10) 
		$hour = "0" . $hour;

	$file_prepped_for_changing = date('Y-m-d_') . $hour . date('-i-s')  .  ".txt";
	//END - Code naming a file the current date and time
	
	$automatically_populated_textfile = file_get_contents($target_file);  //this will be the raw file in the future, hopefully automatically saved in some location
	//define('DIRECTORY_SEPARATOR','//');
	//$path='uploads'.DIRECTORY_SEPARATOR;


	$file_prepped_for_changing =  '../../../examples/line-time-series/uploads/' . $file_prepped_for_changing; //$path . $file_prepped_for_changing;
	file_put_contents($file_prepped_for_changing, $automatically_populated_textfile); //putting the contents in the file saved as today's date and current time stamp
	
	
	
	
	
	
	
	$lines = file($file_prepped_for_changing);
	
	
	/*if (preg_match("/#/", "PHP is # the web scripting language of choice.")) {
	echo "A match was found.";
	} else {
	echo "A match was not found.";
	}*/
		
		foreach ($lines as $line){
				
			$contents = file_get_contents($file_prepped_for_changing);
			
			if (preg_match("/#/",$line)) {
				$contents = str_replace($line, '', $contents);
				file_put_contents($file_prepped_for_changing, $contents);
				break;
				} else {
						$contents = str_replace($line, '', $contents);
						file_put_contents($file_prepped_for_changing, $contents);
						}
			
			}	
			
		$lines = file($file_prepped_for_changing);
		echo '<br><br>' . $file_prepped_for_changing . '<br>';
		
		$isle=0;
		foreach ($lines as $line){
	
		
			$line = preg_replace("/(?:\s\s+|\n|\t)/", ' ', $line);
			$line = preg_replace("/^ +/", '', $line);
			$unformated_date = preg_match_all("/\d{2}\/\d{2}\/\d{4}/",$line,$matches_out,PREG_PATTERN_ORDER);
			//$unformated_date = print_r($matches_out[0]);
			
			//echo $matches_out[0][0];// . $matches_out[0][1] ;
			//echo $matches_out[1][0] . $matches_out[1][1] ;
			//echo '<br>';
			
			$unformated_date = strtotime($matches_out[0][0]);
			//echo $unformated_date;
			//echo '<br></br>';
			$formated_date = date('Y-m-d',$unformated_date);
			$line = preg_replace("/\d{2}\/\d{2}\/\d{4}/",$formated_date, $line);
			//echo $line;
			//echo '<br></br>';
			//file_put_contents($file_prepped_for_changing, $output);
			$result .= $line . "\n";
			}
		file_put_contents($file_prepped_for_changing, $result);
	
	
	

		//Here is where we need to see if the file is the same name and size as a previous file loaded.  Also, need have this occur only with when the Upload File button is clicked.  
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



?>
