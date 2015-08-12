<!DOCTYPE HTML>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Highcharts Example</title>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script src="../../js/highcharts.js"></script>
		<script src="../../js/modules/exporting.js"></script>
		<style type="text/css"> ${demo.css} </style>
	</head>

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
		
		$automatically_populated_textfile = file_get_contents("C:\Program Files (x86)\Ampps\www\Highchart\examples\line-time-series\BigBlueDataUnshaped.txt");  //this will be the raw file in the future, hopefully automatically saved in some location
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
		
		
		
	
			
			/*$querytextfiletosql2 = 'LOAD DATA LOCAL INFILE "' . $file_prepped_for_changing .
									'" REPLACE INTO TABLE table_name 
									FIELDS TERMINATED BY " " 
									LINES TERMINATED BY "\\n"
									(X,Date_Table,Time_Table,VA_MAG,VB_MAG,VC_MAG,MV10,SC01,SC02,P,Q,PF,MV32,MV27)
									';
			
			$result2 = mysql_query($querytextfiletosql2) or trigger_error('Query MySQL Error: ' . mysql_error());*/
			

			//Querying to graph in javascript
			$query_first_plot_VA_MAG = mysql_query("SELECT VA_MAG FROM table_name");
			while ($row = mysql_fetch_array($query_first_plot_VA_MAG)) 
				$datagraph_VA_MAG[] = $row["VA_MAG"];	
				
			$query_first_plot_VB_MAG = mysql_query("SELECT VB_MAG FROM table_name");
			while ($row = mysql_fetch_array($query_first_plot_VB_MAG)) 
				$datagraph_VB_MAG[] = $row["VB_MAG"];
	
			$query_first_plot_Date_Table = mysql_query("SELECT Date_Table FROM table_name");
			while ($row = mysql_fetch_array($query_first_plot_Date_Table)) 
				$datagraph_Date_Table[] = $row["Date_Table"];
				
			$query_first_plot_Time_Table = mysql_query("SELECT Time_Table FROM table_name");
			while ($row = mysql_fetch_array($query_first_plot_Time_Table)) 
				$datagraph_Time_Table[] = $row["Time_Table"];	
		
		
			$limitgraph = mysql_query("SELECT COUNT(VA_MAG) FROM table_name");
			$resultcount = mysql_fetch_row($limitgraph);
			$kt2=$resultcount[0]-1;   
			$i=0;									
				do    {    
					$i=$i+1;
					} while ($i<($kt2+1));
				echo $i;
			
			
			
		
		?>
		
		<form action="index_4_experimental.php" method="post">
		Start Date: <input type="date" name="name"><br>
		End Date: <input type="date" name="email"><br>
		<input type="submit">
		</form>
		
		<br></br>
		
		<form action="index_4_experimental.php" method="post">
		Chart Length:
		<select name="Chart Length"> 
		<option value="ScreenWidth">ScreenWidth  <!-- 1400px on my computer -->
		<option value="2xScreenWidth">2xScreenWidth
		<option value="4xScreenWidth">4xScreenWidth
		</select>
		</form>

		<br></br>
		
		<form action="index_4_experimental.php" method="post">
		File to Add to SQL: <input type="file" name="name"><br>
		<input type="submit">
		</form>
		
		<br></br>
		
	
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

					data:  [ <?php $reversedata_VB_MAG = array_reverse($datagraph_VB_MAG); echo join($reversedata_VB_MAG, ',') ?> ]
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

					data:  [ <?php $reversedata_VA_MAG = array_reverse($datagraph_VA_MAG); echo join($reversedata_VA_MAG, ',') ?> ]
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

					data:  [ <?php $reversedata_VB_MAG = array_reverse($datagraph_VB_MAG); echo join($reversedata_VB_MAG, ',') ?> ]
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

					data:  [ <?php $reversedata_VB_MAG = array_reverse($datagraph_VB_MAG); echo join($reversedata_VB_MAG, ',') ?> ]
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

					data:  [ <?php $reversedata_VA_MAG = array_reverse($datagraph_VA_MAG); echo join($reversedata_VA_MAG, ',') ?> ]
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

					data:  [ <?php $reversedata_VB_MAG = array_reverse($datagraph_VB_MAG); echo join($reversedata_VB_MAG, ',') ?> ]
				}
				
							]
			});
			
			
			
			
		});
		</script>

	
	
	
		<body>
			
			<div style="background-color:#EEEEEE; position: absolute; width: 1000px:">
				<div id="container" style="min-width: 210px; height: 200px; margin: 0 auto"></div>

				<div id="container2" style="min-width: 210px; height: 200px; margin: 0 auto"></div>
				<div id="container3" style="min-width: 210px; height: 200px; margin: 0 auto"></div>
			</div>

		</body>

</html>
