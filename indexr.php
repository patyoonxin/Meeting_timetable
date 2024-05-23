<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Random</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
    			// Get all checkboxes
    			var checkboxes = document.querySelectorAll('input[name="customerCheckbox"]');

    			// Add event listener to each checkbox
    			checkboxes.forEach(function(checkbox) {
        			checkbox.addEventListener('change', function() {
            				// Get the value of the clicked checkbox
            				var customerId = this.value;

            				// Find all table cells containing the customer's name
            				var cells = document.querySelectorAll('td');
		
            				cells.forEach(function(cell) {
                				if (cell.innerHTML.includes(customerId)) {
  							cell.style.visibility = checkbox.checked ? 'hidden' : 'visible';
                				}
            				});
        			});
   			});
		});
	</script>
<style>
	.column {
  		float: left;
  		width: 50%;
		padding-left:15px;
	}

	.row:after {
		content: "";
  		display: table;
  		clear: both;
	}
	table, th, td {
  	
	}
	th,td{
		
		min-width:125px;
		text-align:center;
	}
	@media print {
		@page {
      			size: landscape;
      			margin: 25px;
   		}
  		.column,button {
   			display: none !important;
  		}

  		table {
    			display: block !important;
    			width:100% !important;
    			table-layout: auto !important;
    				
  		}
		th,td{	
			min-width:25px;
			font-size: 8px;
			word-wrap: break-word;
		}
			
	}
 
</style>
</head>
<body>

	<?php
		require('db.php');								// database & session
		$eventName=$_SESSION["eventName"];
		$times=explode('|', $_SESSION["times"]);
		$timetable=explode('|', $_SESSION["timeslot"]);
		$count_timeslot = count($timetable);
		array_unshift($timetable,' ');
		
		
		$breaktime = $_SESSION["break"];						// to determine break time
		$how_many_break = $_SESSION["how_many_break"];

		if(array_search($breaktime,$times) !== false){
			$key_break = array_search($breaktime,$times);
		}else{
			$key_break = -1;
		}

		$color = array("#641E16","#4A235A","#1A5276","#117864","#438D80","#9A7D0A","#935116","#979A9A","#5F6A6A","#212F3C",
				"#943126","#76448A","#2874A6 ","#014E21","#1D8348","#9C640C","#873600","#797D7F","#515A5A","#1C2833",
				"#000000","#191970","#4E5B31","#555D50","#86608E","#E238EC","#FBB917","#FC6C85","#FF1493","#EB5406",
				"#728FCE","#43C6DB","#5F9EA0","#4CC552","#A2AD9C","#A2AD9C","#BAB86C","#F9966B","#FF8674","#7F525D"); 
	?>

	<div class="container">
    		<h1 style="margin-top:25px;margin-bottom:30px;"><b><?php echo $eventName ?></b></h1>
		<div class="row">
    		<div class="column"><h3>COMPANY</h3>
	
	<?php	
	
		$sql = "SELECT * FROM company";							// print companies
		$result = mysqli_query($con, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)){
				echo $row["compName"]." : ".$row["compProduct"]."<br>";
				$arr[] = $row["compName"];					// store comp name into array (used to print comp name in table later)
			}
		}else {
			echo "No company available.";
		}
		echo "<br>";
	?>
		</div>
		<div class="column"><h3>CUSTOMER</h3>

	<?php
		$customer_colors = array();
		$sql = "SELECT * FROM customer";						// print customers 
		$result = mysqli_query($con, $sql);

		$a=0;
		
		while ($row = mysqli_fetch_assoc($result)) {
    
    			$cust_start = isset($times[$row['time_start']]) ? $times[$row['time_start']] : "Anytime";
    			$cust_end = isset($times[$row['time_end']]) ? $times[$row['time_end']] : "Anytime";

			echo "<input type='checkbox' class='form-check-input' name='customerCheckbox' value='" . $row["custName"] . "'>";

			echo " ";

    			// Print customer details with logic to print "Anytime" only once
    			if ( $cust_start === "Anytime" && $cust_end === "Anytime") {
        			echo $row["custName"] . " : " . $row["custProduct"] . " ( <mark>Anytime</mark> )<br>";

    			} else {
        			echo $row["custName"] . " : " . $row["custProduct"] . " ( <mark>" . $cust_start . " - " . $cust_end . "</mark> )<br>";
    			}

			$customer_colors[$row["custName"]] = $color[$a];

			$a++;
		}
		$customer_colors[""] = "#FFFFFF";

		$sql = "SELECT * FROM customer ORDER BY RAND()";				// print customers (randomly)
		$result1 = mysqli_query($con, $sql);
		if (mysqli_num_rows($result) > 0) {

			while($row = mysqli_fetch_assoc($result1)){

				$custID = $row['custID'];
				$custName = $row['custName'];
				$custProduct = $row['custProduct'];
				$time_start = $row['time_start'];
				$time_end = $row['time_end'];
												// store customers' details into array
				$custName_random[] = array('custID'=>$custID, 'custName'=>$custName, 'custProduct'=>$custProduct, 'time_start'=>$time_start, 'time_end'=>$time_end);		
									
			}
		}


	?>
		</div>
		</div>


		<br>
	
		<?php

			$sql2 = "SELECT * FROM company";
			$result2 = mysqli_query($con, $sql2);
			while($row = mysqli_fetch_array($result2)){
				$company_Array[] = array('compID'=>$row['compID'], 'compName'=>$row['compName'], 'compProduct'=>$row['compProduct']);
			}

			foreach ( $company_Array as $var => $company) { 					// for loop comp
				
				$cust_Array[$var] = array();
				
				if($key_break!==-1){								// to store word 'BREAK'
					for($a=0;$a<=$key_break;$a++){
						if($a==$key_break){

							for($z=0;$z<$how_many_break;$z++){
								$cust_Array[$var][$a+$z] = "[BREAK]";
							}

						}else{
							$cust_Array[$var][$a] = NULL;
						}
					}
				}
				$nameCompany = $company["compName"];
				
    				$compProduct = $company["compProduct"];
				
				$my_array1 = explode(",", $compProduct);					// break string into array

				foreach($custName_random as $vall){
					if($vall['time_start']<>-1 && $vall['time_end']<>-1){
					
						$custProduct = $vall['custProduct'];
						$custName = $vall['custName'];
						$custID = $vall['custID'];
						$custA = $vall['time_start'];
						$custB = $vall['time_end'];

						$my_array2 = explode(",", $custProduct);

						foreach($my_array1 as $value => $my){					
							$name=$my;

							if(in_array($name, $my_array2)){					// compare if comp product == cust product
								
									$nextcolumn = $custA;
									$stop_after_put_in = 0;	

									for($v=0;$v<$nextcolumn;$v++){
										if(!empty($cust_Array[$var][$v])){
													
										}else{
											$cust_Array[$var][$v] = NULL;		
										}	
									}

									while ($nextcolumn<$custB){										// start compare and arrange position
										$stop=0;
										
  										for($x=0;$x<$var;$x++){								// for loop column
											if(empty($cust_Array[$x][$nextcolumn])){				// to see if the lasr row same column cell empty or not, if empty than continue to (stop!=1)
												continue;
											}else{
												if($cust_Array[$x][$nextcolumn]==$custName){			
													$stop=1;						// if cust name already exist then break (move to next column)
													if(!empty($cust_Array[$var][$nextcolumn])){
													
													}else{
														$cust_Array[$var][$nextcolumn] = NULL;		
													}							// set null if there is empty space inside array
													break;
												}
											}
										}
										
										if($stop!=1){								// if the column x contain same cust name, execute below code
											if(!empty($cust_Array[$var][$nextcolumn])){			// if cell is occupied then skip (move to next column)
												$nextcolumn++;
												continue;
											}else{
												$cust_Array[$var][$nextcolumn] = $custName;		// if cell not occupied then put in
												$stop_after_put_in = 1;						
												break;
											}
										}
  										$nextcolumn++;
									}
									if($stop_after_put_in = 1){
										break;
									}	
							}
						}
					}else{}
				}


				foreach($custName_random as $vall){
					if($vall['time_start']==-1 && $vall['time_end']<>-1){

						$custProduct = $vall['custProduct'];
						$custName = $vall['custName'];
						$custID = $vall['custID'];
						
						$custB = $vall['time_end'];



						$my_array2 = explode(",", $custProduct);

						foreach($my_array1 as $value => $my){					
							$name=$my;

							if(in_array($name, $my_array2)){					// compare if comp product == cust product
								
									$nextcolumn = 0;
									$stop_after_put_in = 0;	


									while ($nextcolumn<$custB){										// start compare and arrange position
										$stop=0;
										
  										for($x=0;$x<$var;$x++){								// for loop column
											if(empty($cust_Array[$x][$nextcolumn])){				// to see if the lasr row same column cell empty or not, if empty than continue to (stop!=1)
												continue;
											}else{
												if($cust_Array[$x][$nextcolumn]==$custName){			
													$stop=1;						// if cust name already exist then break (move to next column)
													if(!empty($cust_Array[$var][$nextcolumn])){
													
													}else{
														$cust_Array[$var][$nextcolumn] = NULL;		
													}							// set null if there is empty space inside array
													break;
												}
											}
										}
										
										if($stop!=1){								// if the column x contain same cust name, execute below code
											if(!empty($cust_Array[$var][$nextcolumn])){			// if cell is occupied then skip (move to next column)
												$nextcolumn++;
												continue;
											}else{
												$cust_Array[$var][$nextcolumn] = $custName;		// if cell not occupied then put in
												$stop_after_put_in = 1;						
												break;
											}
										}
  										$nextcolumn++;
									}
									if($stop_after_put_in = 1){
										break;
									}	
							}
						}
					}else{}
				}


				foreach($custName_random as $vall){
					if($vall['time_start']<>-1 && $vall['time_end']==-1){

						$custProduct = $vall['custProduct'];
						$custName = $vall['custName'];
						$custID = $vall['custID'];
						$custA = $vall['time_start'];


						$my_array2 = explode(",", $custProduct);

						foreach($my_array1 as $value => $my){					
							$name=$my;

							if(in_array($name, $my_array2)){					// compare if comp product == cust product
								
									$nextcolumn = $custA;
									$stop_after_put_in = 0;	

									for($v=0;$v<$nextcolumn;$v++){
										if(!empty($cust_Array[$var][$v])){
													
										}else{
											$cust_Array[$var][$v] = NULL;		
										}	
									}

									while ($nextcolumn<$count_timeslot){										// start compare and arrange position
										$stop=0;
										
  										for($x=0;$x<$var;$x++){								// for loop column
											if(empty($cust_Array[$x][$nextcolumn])){				// to see if the lasr row same column cell empty or not, if empty than continue to (stop!=1)
												continue;
											}else{
												if($cust_Array[$x][$nextcolumn]==$custName){			
													$stop=1;						// if cust name already exist then break (move to next column)
													if(!empty($cust_Array[$var][$nextcolumn])){
													
													}else{
														$cust_Array[$var][$nextcolumn] = NULL;		
													}							// set null if there is empty space inside array
													break;
												}
											}
										}
										
										if($stop!=1){								// if the column x contain same cust name, execute below code
											if(!empty($cust_Array[$var][$nextcolumn])){			// if cell is occupied then skip (move to next column)
												$nextcolumn++;
												continue;
											}else{
												$cust_Array[$var][$nextcolumn] = $custName;		// if cell not occupied then put in
												$stop_after_put_in = 1;						
												break;
											}
										}
  										$nextcolumn++;
									}
									if($stop_after_put_in = 1){
										break;
									}	
							}
						}
					}else{}
				}




				foreach($custName_random as $vall){
				if($vall['time_start']==-1 && $vall['time_end']==-1){
					$custProduct = $vall['custProduct'];
					$custName = $vall['custName'];
					$custID = $vall['custID'];
					
				
					$my_array2 = explode(",", $custProduct);				// break cust product(string) into array

					foreach($my_array1 as $value => $my){					
						$name=$my;

						if(in_array($name, $my_array2)){				// compare if comp product == cust product
														
								$nextcolumn = 0;
								//$n=0;
								$stop_after_put_in = 0;

								while ($nextcolumn<$count_timeslot){											// start compare and arrange position
									$stop=0;
									
  									for($x=0;$x<$var;$x++){								// for loop column
									   
										if(empty($cust_Array[$x][$nextcolumn])){				
											continue;
										}else{
											if($cust_Array[$x][$nextcolumn]==$custName){			
												$stop=1;						// if cust name already exist then break (move to next column)
												if(!empty($cust_Array[$var][$nextcolumn])){
												
												}else{
													$cust_Array[$var][$nextcolumn] = NULL;		
												}							// set null if there is empty space inside array
												break;
											}
										}
									}
									
									if($stop!=1){								// if the column x contain same cust name, execute below code
										if(!empty($cust_Array[$var][$nextcolumn])){			// if cell is occupied then skip (move to next column)
											$nextcolumn++;
											continue;
										}else{
											$cust_Array[$var][$nextcolumn] = $custName;		// if cell not occupied then put in
											//$n=-1;							// indicate to stop do while looping
											$stop_after_put_in = 1;
											break;
										}
									}
  									$nextcolumn++;
								}
								if($stop_after_put_in = 1){
									break;
								}				

						}
					
					} //end foreach my_array1
					
				}else{}
				}
			
			}
			
			
			//$swab = 0;
			//foreach ($cust_Array as $key => $val) {	
				//$count = 0;			
   				//foreach ($val as $keyItem => $value) {
        			//	$count++;
    				//}		
				//if($count>$swab){
				//	$swab = $count;
				//}		
			//}	
		?>

	<div style="overflow-x:auto;">
	<table class="table table-striped">

		<?php
			
			echo "<tr>";
			foreach($timetable as $key2){
				 
				echo "<th>".$key2." </th>";
	
			}
			echo "</tr>";
			foreach ($cust_Array as $key => $val) {
				 if ($key == 0) {
        				echo "<tr><td><b>", $arr[$key], "</b></td>";
        				foreach ($val as $keyItem => $value) {
						if ($keyItem > $count_timeslot-1){
							break;
						}
    				
            					else if ($value == '[BREAK]') {
                					echo "<td rowspan='0' style='background-color: #F7F3DA;'> $value </td>";
            					} else {
                					
                					echo "<td style='background-color: $customer_colors[$value];color:white;'> $value </td>";
            					}
        				}
        				echo "</tr>";
   				 } else {
        				echo "<tr><td><b>", $arr[$key], "</b></td>";
        				foreach ($val as $keyItem => $value) {
						if ($keyItem > $count_timeslot-1){
							break;
						}
    				
            					else if ($value == '[BREAK]') {

            					} else {
                					
                					echo "<td style='background-color: $customer_colors[$value];color:white;'> $value </td>";
            					}
        				}
        				echo "</tr>";
    				}
			}
			
		
		?>
	</table>
	</div>

	<br><button class="btn btn-primary" style="margin-top:20px;margin-bottom:15px;" onclick="location.href='index3.php'">Alphabetize</button>

	<button class="btn btn-primary" style="margin-top:20px;margin-bottom:15px;" onclick="location.href='indexr.php'">Randomize</button>

	<button class="btn btn-primary" style="margin-top:20px;margin-bottom:15px;" onclick="window.print()">Print</button>

    	<button class="btn btn-danger" style="margin-top:20px;float: right;margin-bottom:15px;" onclick="location.href='close.php'">Close</button>

	<?php
	//echo '<pre>' . print_r($cust_Array,1) . '</pre>'; 				//testing

	?>

	</div>
</body>
</html>
