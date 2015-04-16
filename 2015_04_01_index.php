<?php
require_once('calendar/classes/tc_calendar.php');

header ( "Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); 
header ("Pragma: no-cache");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>

		<script language="javascript" src="calendar/calendar.js"></script>
		<link href="calendar/calendar.css" rel="stylesheet" type="text/css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>test</title>
		<link rel="stylesheet" href="style.css">

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script src="../../js/highcharts.js"></script>
		<script src="../../js/modules/exporting.js"></script>
		<style type="text/css"> ${demo.css} </style>
	</head>
	
	<style>
	#main-wrap{ 
	width: 1400px; 
	height: 770px;
	margin: auto; 
	background-color: #FF0000; 
	padding: 2px; 
	} 
	#header{ 
	height: 120px; 
	margin-bottom: 2px; 
	background-color: #0000FF; 
	padding: 5px; 
	} 
	#block_left{
	height: 95%;
	width: 30%;   
	float: left;
	margin:auto auto auto auto;
	background-color: #CC00FF; 
	padding: .05%; 
	}
	#block_right{
	height: 95%;
	width: 30%;   
	float: right;
	margin:auto auto auto auto;
	background-color: #CC00FF; 
	padding: .05%; 
	}
	#block_center{
	height: 95%;
	width: 30%;   
	position: relative;
	left: 0;
	right: 0;
	margin:auto auto auto auto;
	background-color: #CC00FF; 
	padding: .05%; 
	}


	#content{ 
	padding: 5px; 
	background-color: #00CC00; 
	} 
	#navigation{ 
	height: 200px;
	background-color: #CC9900; 
	} 
	#footer{ 
	height: 200px;
	padding: 5px; 
	background-color: #33CCCC; 
	}
	.input{
	//font-size: 18px;
	font-family:'ProximaNova-Bold';
	text-transform: uppercase;
	text-align: left;
	}
	</style>

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

	//START - The follow code is all for naming a file the current date and time
	$hour =(date('H') - 05.0);
	if ($hour < 10) 
		$hour = "0" . $hour;

	$file_prepped_for_changing = date('Y-m-d_') . $hour . date('-i-s')  .  ".txt";
	//END - The follow code is all for naming a file the current date and time
	
	$automatically_populated_textfile = file_get_contents("C:\Program Files (x86)\Ampps\www\Highchart\examples\line-time-series\BigBlueRAW.txt");  //this will be the raw file in the future, hopefully automatically saved in some location
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
	
	
	

		
		$querytextfiletosql2 = 'LOAD DATA LOCAL INFILE "' . $file_prepped_for_changing .
								'" REPLACE INTO TABLE table_name 
								FIELDS TERMINATED BY " " 
								LINES TERMINATED BY "\\n"
								(X,Date_Table,Time_Table,VA_MAG,VB_MAG,VC_MAG,MV10,SC01,SC02,P,Q,PF,MV32,MV27) ';
		
		$result2 = mysql_query($querytextfiletosql2) or trigger_error('Query MySQL Error: ' . mysql_error());
		

		//Querying to graph in javascript
		
		//$searchable_by_post = "SELECT VA_MAG FROM table_name WHERE 'Data_Table' = '" . $DayStart  ."'";
		
	
		
		if ($_POST['DayStart']==0) {	$DayStart  = "2015-03-23"; } 
			else {	$DayStart = $_POST['DayStart'];	 } 
		if ($_POST['DayEnd']==0) {	$DayEnd  = "2015-03-25"; } 
			else {	$DayEnd = $_POST['DayEnd'];	 } 
			
		$limitgraph2 = mysql_query("SELECT COUNT(VA_MAG) FROM `table_name` WHERE `Date_Table`>='" . $DayStart . "' AND `Date_Table`<='" . $DayEnd . "'");
		$resultcount2 = mysql_fetch_row($limitgraph2);
		$kt2=$resultcount2[0]-1;    
		$i=0;
															
		do  {    	
			$i=$i+1;
			} while ($i<($kt2+1));
		echo "Count Variables:";
		echo "<br>";	
		echo $i;
		echo "<br>";
		echo $i/2000;
		echo "<br>";
		echo $mod_skip = ceil($i/2000);
		echo "<br>";
		

		//echo $query_first_plot_VA_MAG="SELECT `VA_MAG` FROM `table_name` WHERE `Date_Table`>='" . $DayStart . "' AND `Date_Table`<='" . $DayEnd . "'";
		echo $query_first_plot_VA_MAG="SET @row := 0 (SELECT * FROM (SELECT @row := @row + 1 AS rownum, VA_MAG FROM table_name WHERE Date_Table>='" . $DayStart . "' AND `Date_Table`<='" . $DayEnd . "') as DT WHERE MOD(rownum," . $mod_skip . ")=0)";
		
		mysql_query("SET @row := 0;");
		$query_first_plot_VA_MAG = mysql_query("(SELECT * FROM (SELECT @row := @row + 1 AS rownum, VA_MAG FROM table_name WHERE Date_Table>='" . $DayStart . "' AND `Date_Table`<='" . $DayEnd . "') as DT WHERE MOD(rownum," . $mod_skip . ")=0)");
		while ($row = mysql_fetch_array($query_first_plot_VA_MAG)) 
			$datagraph_VA_MAG[] = $row["VA_MAG"];	
			
		mysql_query("SET @row := 0;");
		$query_first_plot_P = mysql_query("SELECT * FROM (SELECT @row := @row + 1 AS rownum, P FROM table_name WHERE Date_Table>='" . $DayStart . "' AND `Date_Table`<='" . $DayEnd . "') as DT WHERE MOD(rownum," . $mod_skip . ")=0");
		while ($row = mysql_fetch_array($query_first_plot_P)) 
			$datagraph_P[] = $row["P"];
		
		mysql_query("SET @row := 0;");
		$query_first_plot_Date_Table_1 = mysql_query("SELECT * FROM (SELECT @row := @row + 1 AS rownum, Date_Table FROM table_name WHERE Date_Table>='" . $DayStart . "' AND `Date_Table`<='" . $DayEnd . "') as DT WHERE MOD(rownum," . $mod_skip . ")=0");
		while ($row = mysql_fetch_array($query_first_plot_Date_Table_1)) 
			$datagraph_Date_Table_1[] = $row["Date_Table"];
			
		mysql_query("SET @row := 0;");	
		$query_first_plot_Time_Table_1 = mysql_query(" SELECT * FROM (SELECT @row := @row + 1 AS rownum, Time_Table FROM table_name WHERE Date_Table>='" . $DayStart . "' AND `Date_Table`<='" . $DayEnd . "') as DT WHERE MOD(rownum," . $mod_skip . ")=0");
		while ($row = mysql_fetch_array($query_first_plot_Time_Table_1)) 
			$datagraph_Time_Table_1[] = $row["Time_Table"];
		
		
		
		$resultcount_1 = mysql_num_rows($query_first_plot_P);
		echo "<br>";
		echo "<br>";
		echo $resultcount_1;
		$kt2=$resultcount_1[0]-1;    
		$i=0;
															
		do  {    	
			$i=$i+1;
			} while ($i<($kt2+1));
		echo "<br>";
		echo "Count Variables:";
		echo "<br>";	
		echo $i;
		echo "<br>";
		echo $i/2000;
		echo "<br>";
		echo $mod_skip = ceil($i/2000);
		echo "<br>";

		
			
			
			
		$query_first_plot_MV10 = mysql_query("SELECT MV10 FROM table_name");
		while ($row = mysql_fetch_array($query_first_plot_MV10)) 
			$datagraph_MV10[] = $row["MV10"];
			
		$query_first_plot_Q = mysql_query("SELECT Q FROM table_name");
		while ($row = mysql_fetch_array($query_first_plot_Q)) 
			$datagraph_Q[] = $row["Q"];
			
		$query_first_plot_MV32 = mysql_query("SELECT MV32 FROM table_name");
		while ($row = mysql_fetch_array($query_first_plot_MV32)) 
			$datagraph_MV32[] = $row["MV32"];

			
			
		$query_first_plot_PF = mysql_query("SELECT PF FROM table_name");
		while ($row = mysql_fetch_array($query_first_plot_PF)) 
			$datagraph_PF[] = $row["PF"];
		
		$query_first_plot_MV27 = mysql_query("SELECT MV27 FROM table_name");
		while ($row = mysql_fetch_array($query_first_plot_MV27)) 
			$datagraph_MV27[] = $row["MV27"];

			
			
		$query_first_plot_Date_Table = mysql_query("SELECT Date_Table FROM table_name");
		while ($row = mysql_fetch_array($query_first_plot_Date_Table)) 
			$datagraph_Date_Table[] = $row["Date_Table"];
			
		$query_first_plot_Time_Table = mysql_query("SELECT Time_Table FROM table_name");
		while ($row = mysql_fetch_array($query_first_plot_Time_Table)) 
			$datagraph_Time_Table[] = $row["Time_Table"];	
	
		//This can be used for all
		$limitgraph = mysql_query("SELECT COUNT(VA_MAG) FROM table_name");
		$resultcount = mysql_fetch_row($limitgraph);
	
	?>
	
	<script type="text/javascript">
	$(function () {


		$('#container').highcharts({
			chart: {
				zoomType: 'x',
				width: 1400,
				height: 200
			},
			title: {
				text: 'Voltage & Power'
			},
			subtitle: {
				text: document.ontouchstart === undefined ?
						'Click and drag in the plot area to zoom in' :
						'Pinch the chart to zoom in'
			},
			xAxis:  {
						type: 'datetime',
						labels: {enabled:false},
						
						<?php
						$kt2=$resultcount_1-1;    $x=0; $i=0;
															echo "categories: {";
															do    {    
																	echo '"'; echo $x;    echo '": ';    $answer=($kt2-$i);
																	echo '"'; echo $datagraph_Date_Table_1[$x] . " " . $datagraph_Time_Table_1[$x]; echo '"';

																	if ($answer==0)    {echo ' ';}
																	else             {echo ',';}

																	$x=$x+1; $i=$i+1;
																} while ($i<($kt2+1));
															echo "}";
						?>
						
						
						

					
					},
			yAxis: {
				title: {
					text: 'Voltage'
				}
			},
			legend: {
				layout: 'vertical',
				align: 'left',
				x: 120,
				verticalAlign: 'top',
				y: 80,
				floating: true,
				backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
			},
			
			
			
			
			plotOptions: {
				area: {
					fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [
							[0, Highcharts.getOptions().colors[0]],
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
						]
					},
					marker: {
						radius: 2
					},
					lineWidth: 1,
					states: {
						hover: {
							lineWidth: 1
						}
					},
					threshold: null
					
				}
			},
			
			series: [{

				type: 'area',
				name: 'Voltage (kV)',
				color: '#468499',
				fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [
							[0, '#468499'],
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
						]
					},

				data:  [ <?php $reversedata_VA_MAG = array_reverse($datagraph_VA_MAG); echo join($reversedata_VA_MAG, ',') ?> ]
			},
			{

				type: 'area',
				name: 'Power (MW)',
				color: '#468499',
				fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [
							[0, '#468499'],
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
						]
					},

				data:  [ <?php $reversedata_P = array_reverse($datagraph_P); echo join($reversedata_P, ',') ?> ]
			}
			
						]
		});
		
		
		
		
	});
	</script>
	
	<script type="text/javascript">
	$(function () {


		$('#container2').highcharts({
			chart: {
				zoomType: 'x',
				width: 1400,
				height: 200
			},
			title: {
				text: 'Var Set Point & Point of Interconnection Vars & Capacitor Bank'
			},
			subtitle: {
				text: document.ontouchstart === undefined ?
						'Click and drag in the plot area to zoom in' :
						'Pinch the chart to zoom in'
			},
			xAxis:  {
						type: 'datetime',
						labels: {enabled:false},
						
						<?php
						$kt2=$resultcount[0]-1;    $x=0; $i=0;
															echo "categories: {";
															do    {    
																	echo '"'; echo $x;    echo '": ';    $answer=($kt2-$i);
																	echo '"'; echo $datagraph_Date_Table[$x] . " " . $datagraph_Time_Table[$x]; echo '"';

																	if ($answer==0)    {echo ' ';}
																	else             {echo ',';}

																	$x=$x+1; $i=$i+1;
																} while ($i<($kt2+1));
															echo "}";
						?>
						
						
						

					
					},
			yAxis: {
				title: {
					text: 'VARS'
				}
			},
			legend: {
				layout: 'vertical',
				align: 'left',
				x: 120,
				verticalAlign: 'top',
				y: 80,
				floating: true,
				backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
			},
			
			
			
			
			plotOptions: {
				area: {
					fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [
							[0, Highcharts.getOptions().colors[0]],
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
						]
					},
					marker: {
						radius: 2
					},
					lineWidth: 1,
					states: {
						hover: {
							lineWidth: 1
						}
					},
					threshold: null
					
				}
			},
			
			series: [{

				type: 'area',
				name: 'Var Set Point',
				color: '#468499',
				fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [
							[0, '#468499'],
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
						]
					},

				data:  [ <?php $reversedata_MV10 = array_reverse($datagraph_MV10); echo join($reversedata_MV10, ',') ?> ]
			},
			{

				type: 'area',
				name: 'Point of Interconnection Vars',
				color: '#468499',
				fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [
							[0, '#468499'],
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
						]
					},

				data:  [ <?php $reversedata_Q= array_reverse($datagraph_Q); echo join($reversedata_Q, ',') ?> ]
			},
			{

				type: 'area',
				name: 'Capacitor Bank',
				color: '#468499',
				fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [
							[0, '#468499'],
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
						]
					},

				data:  [ <?php $reversedata_MV32 = array_reverse($datagraph_MV32); echo join($reversedata_MV32, ',') ?> ]
			}
			
						]
		});
		
		
		
		
	});
	</script>

	<script type="text/javascript">
	$(function () {


		$('#container3').highcharts({
			chart: {
				zoomType: 'x',
				width: 1400,
				height: 200
			},
			title: {
				text: 'Voltage & Power'
			},
			subtitle: {
				text: document.ontouchstart === undefined ?
						'Click and drag in the plot area to zoom in' :
						'Pinch the chart to zoom in'
			},
			xAxis:  {
						type: 'datetime',
						labels: {enabled:false},
						
						<?php
						$kt2=$resultcount[0]-1;    $x=0; $i=0;
															echo "categories: {";
															do    {    
																	echo '"'; echo $x;    echo '": ';    $answer=($kt2-$i);
																	echo '"'; echo $datagraph_Date_Table[$x] . " " . $datagraph_Time_Table[$x]; echo '"';

																	if ($answer==0)    {echo ' ';}
																	else             {echo ',';}

																	$x=$x+1; $i=$i+1;
																} while ($i<($kt2+1));
															echo "}";
						?>
						
						
						

					
					},
			yAxis: {
				title: {
					text: 'PF'
				}
			},
			legend: {
				layout: 'vertical',
				align: 'left',
				x: 120,
				verticalAlign: 'top',
				y: 80,
				floating: true,
				backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
			},
			
			
			
			
			plotOptions: {
				area: {
					fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [
							[0, Highcharts.getOptions().colors[0]],
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
						]
					},
					marker: {
						radius: 2
					},
					lineWidth: 1,
					states: {
						hover: {
							lineWidth: 1
						}
					},
					threshold: null
					
				}
			},
			
			series: [{

				type: 'area',
				name: 'Power Factor (PF)',
				color: '#468499',
				fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [
							[0, '#468499'],
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
						]
					},

				data:  [ <?php $reversedata_PF = array_reverse($datagraph_PF); echo join($reversedata_PF, ',') ?> ]
			},
			{

				type: 'area',
				name: 'Meeting Requirement',
				color: '#468499',
				fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [
							[0, '#468499'],
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
						]
					},

				data:  [ <?php $reversedata_MV27 = array_reverse($datagraph_MV27); echo join($reversedata_MV27, ',') ?> ]
			}
			
						]
		});
		
		
		
		
	});
	</script>
	
	

	<body>
	
		<div id="main-wrap"> 
			<div id="header">
				<div id="block_left">
					
				<p class="input" style="text-align: center;">DATA TIME RANGE</p>

				<form id="form1" name="form1" method="post" action="test_calendar_value.php">
				<table border="0" cellspacing="0" cellpadding="2">
				<tr>
				<td>Start Date :</td>
				<td><?php

				   $date3_default = "2015-04-13";

				  $myCalendar = new tc_calendar("date3", true, false);
					  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
					  $myCalendar->setDate(date('d', strtotime($date3_default))
							, date('m', strtotime($date3_default))
							, date('Y', strtotime($date3_default)));
					  $myCalendar->setPath("calendar/");
					  $myCalendar->setYearInterval(1970, 2020);
					  $myCalendar->setAlignment('left', 'bottom');
					  $myCalendar->setDatePair('date3', 'date4', $date4_default);
					  $myCalendar->writeScript();	  



				?></td></tr><tr><td>End Date :</td>

				<td><?php

					  $date4_default = "2015-04-19";


					  $myCalendar = new tc_calendar("date4", true, false);
					  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
					  $myCalendar->setDate(date('d', strtotime($date4_default))
						   , date('m', strtotime($date4_default))
						   , date('Y', strtotime($date4_default)));
					  $myCalendar->setPath("calendar/");
					  $myCalendar->setYearInterval(1970, 2020);
					  $myCalendar->setAlignment('left', 'bottom');
					  $myCalendar->setDatePair('date3', 'date4', $date3_default);
					  $myCalendar->writeScript();	  
					  
					  $theDate = isset($_REQUEST["date1"]) ? $_REQUEST["date1"] : "";
					    
					  echo $theDate;
					  echo "VAlue";
				
				?></td><td>  <input type="button" name="button2" id="button2" value="Check the value" onclick="javascript:alert('Date select from '+this.form.date3.value+' to '+this.form.date4.value);"> </td>
				</tr>

				<tr> 
				</tr>
				</table>
				<p>

				</p>
			</form>
			
			

				</div>
				
				<div id="block_right">   
					<p class="input" style="text-align: center;">Date Range Temporary:</p>
					
					<form action="2015_04_01_index.php" method="post">			
						<div class="input">DayStart:</div><input type="text" name="DayStart" placeholder=" <?php echo $DayStart;?> ">
						
						<div class="input">DayEnd:</div><input type="text" name="DayEnd" placeholder=" <?php echo $DayEnd;?> ">
						
					
							
							
						<input type="submit">
					</form>
					
				</div>
				
				<div id="block_center">
					<p class="input" style="text-align: center;">Data File Upload</p>
					
					<form action="2015_04_01_index.php" method="post">			
						<p class="input">File Load:</p><input type="text" name="File">
						<input type="submit">
					</form>
					
				</div>
			</div> 
			
			
			<div id="navigation">
				<div id="container" style="min-width: 210px; height: 200px; margin: 0 auto"></div>
			</div> 
			
			
			<div id="footer">
				<div id="container2" style="min-width: 210px; height: 200px; margin: 0 auto"></div>
			</div>
			
			<div id="navigation">
				<div id="container3" style="min-width: 210px; height: 200px; margin: 0 auto"></div>
			</div> 
			
		</div>
	
	</body>
</html>
