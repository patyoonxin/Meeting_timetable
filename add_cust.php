<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Timetable</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

	<script>
		// a function to hide customers name if its corresponding checkbox is ticked
		document.addEventListener("DOMContentLoaded", function() {

    			// Get all checkboxes
    			var checkboxes = document.querySelectorAll('input[name="customerCheckbox[]"]');

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

		// enable or disable 'Add customer' and 'Submit' buttons 
		$(function() {

			// buttons enabled if at least 1 checkbox checked
    			$(".checklist").click(function(){
        			$('.addCust').prop('disabled',$('input.checklist:checked').length == 0);
				$('.submitCust').prop('disabled',$('input.checklist:checked').length == 0);
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
		.nopadding {
   			padding: 1px !important;
   			margin: 0 !important;
		}
		
		// CSS for printing
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
		require('db.php');

		$eventName=$_SESSION["eventName"];
		$times=explode('|', $_SESSION["times"]);
		$timetable=explode('|', $_SESSION["timeslot"]);
		$count_timeslot = count($timetable);
		array_unshift($timetable,' ');
		$cust_Array = $_SESSION['cust_Array'];
		
		$array_cust_replaced[] = array();
    		//$array_cust_replaced = $_SESSION['array_cust_replaced'];
		
    		$addedCustomerData = $_SESSION['addedCustomerData'];
 			

		$color = array("#641E16","#4A235A","#1A5276","#117864","#438D80","#9A7D0A","#935116","#979A9A","#5F6A6A","#212F3C",
				"#943126","#76448A","#2874A6 ","#014E21","#1D8348","#9C640C","#873600","#797D7F","#515A5A","#1C2833",
				"#000000","#191970","#4E5B31","#555D50","#86608E","#E238EC","#FBB917","#FC6C85","#FF1493","#EB5406",
				"#728FCE","#43C6DB","#5F9EA0","#4CC552","#A2AD9C","#A2AD9C","#BAB86C","#F9966B","#FF8674","#7F525D"); 

		function generateTable($timetable,$cust_Array,$count_timeslot,$array_cust_replaced,$arr,$customer_colors) {
    $printTable = '<div style="overflow-x:auto;">
        <table class="table table-striped">
            <tr>';

    foreach ($timetable as $key2) {
        $printTable .= "<th>$key2</th>";
    }

    $printTable .= '</tr>';

    foreach ($cust_Array as $key => $val) {
        if ($key == 0) {
            $printTable .= "<tr><td><b>$arr[$key]</b></td>";

            foreach ($val as $keyItem => $value) {
                if ($keyItem > $count_timeslot - 1) {
                    break;
                } else if ($value == '[BREAK]') {
                    $printTable .= "<td rowspan='0' style='background-color: #F7F3DA;'>$value</td>";
                } else if (in_array($value, $array_cust_replaced)) {
                    $printTable .= "<td style='background-color: $customer_colors[$value];color:white;visibility:hidden;'>$value</td>";
                } else {
                    $printTable .= "<td style='background-color: $customer_colors[$value];color:white;'>$value</td>";
                }
            }
            $printTable .= '</tr>';
        } else {
            $printTable .= "<tr><td><b>$arr[$key]</b></td>";

            foreach ($val as $keyItem => $value) {
                if ($keyItem > $count_timeslot - 1) {
                    break;
                } else if ($value == '[BREAK]') {
                    // Do nothing
                } else if (in_array($value, $array_cust_replaced)) {
                    $printTable .= "<td style='background-color: $customer_colors[$value];color:white;visibility:hidden;'>$value</td>";
                } else {
                    $printTable .= "<td style='background-color: $customer_colors[$value];color:white;'>$value</td>";
                }
            }
            $printTable .= '</tr>';
        }
    }


    $printTable .= '</table>
        </div>
        <br>
        <button class="btn btn-danger" style="margin-top:20px;float: right;margin-bottom:15px;" onclick="location.href=\'index3.php\'">Back</button>
        <button class="btn btn-primary" style="margin-top:20px;margin-bottom:15px;" onclick="window.print()">Print</button>';

    return $printTable;
}


function generateCustArray($CustomerData,$company_Array,$cust_Array,$array_cust_replaced,$count_timeslot){

		$cust_both_anytime = array();
		$cust_start_anytime = array();
		$cust_end_anytime = array();
		$cust_not_anytime = array();
	foreach ($CustomerData as $key) {
        
        		if ($key["custA"] == -1 && $key["custB"] == -1) {
           			$cust_both_anytime[] = array(
                			'custName' => $key["custName"],
                			'custProduct' => $key["custProduct"],
                			'custA' => $key["custA"],
                			'custB' => $key["custB"]
            			);
        		}
        
        
        		if ($key["custA"] == -1 && $key["custB"] != -1) {
         			$cust_start_anytime[] = array(
                			'custName' => $key["custName"],
                			'custProduct' => $key["custProduct"],
                			'custA' => $key["custA"],
                			'custB' => $key["custB"]
            			);
        		}
        
        
        		if ($key["custA"] != -1 && $key["custB"] == -1) {
            			$cust_end_anytime[] = array(
                			'custName' => $key["custName"],
                			'custProduct' => $key["custProduct"],
                			'custA' => $key["custA"],
                			'custB' => $key["custB"]
            			);
        		}
        
     
        		if ($key["custA"] != -1 && $key["custB"] != -1) {
            
            			$cust_not_anytime[] = array(
                			'custName' => $key["custName"],
                			'custProduct' => $key["custProduct"],
                			'custA' => $key["custA"],
                			'custB' => $key["custB"]
            			);
        		}
   		 }
		
		foreach ( $company_Array as $var => $company) { 

			$compProduct = $company["compProduct"];				
			$my_array1 = explode(",", $compProduct);

			foreach($cust_not_anytime as $key3 => $value3){

				$custName = $value3["custName"];
				$custProduct = $value3["custProduct"];
				$my_array2 = explode(",", $custProduct);
				$custA = $value3["custA"];
				$custB = $value3["custB"];

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
										
										if($stop!=1){									// if the column x contain same cust name, execute below code
											if(empty($cust_Array[$var][$nextcolumn])){				// if cell is occupied then skip (move to next column)
												$cust_Array[$var][$nextcolumn] = $custName;			// if cell not occupied then put in
												$stop_after_put_in = 1;						
												break;
											}else if (in_array($cust_Array[$var][$nextcolumn], $array_cust_replaced)){
												$cust_Array[$var][$nextcolumn] = $custName;			// if cell not occupied then put in
												$stop_after_put_in = 1;						
												break;
											}else{
												$nextcolumn++;
												continue;
											}
										}
  										$nextcolumn++;
									}
									if($stop_after_put_in = 1){
										break;
									}	
							}
						}
			}

			foreach($cust_start_anytime as $key2 => $value2){

				$custName = $value2["custName"];
				$custProduct = $value2["custProduct"];
				$my_array2 = explode(",", $custProduct);
				$custB = $value2["custB"];

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
											if(empty($cust_Array[$var][$nextcolumn])){	// if cell is occupied then skip (move to next column)
												$cust_Array[$var][$nextcolumn] = $custName;		// if cell not occupied then put in
												$stop_after_put_in = 1;						
												break;
											}else if (in_array($cust_Array[$var][$nextcolumn], $array_cust_replaced)){
												$cust_Array[$var][$nextcolumn] = $custName;		// if cell not occupied then put in
												$stop_after_put_in = 1;						
												break;
											}else{
												$nextcolumn++;
												continue;
											}
										}
  										$nextcolumn++;
									}
									if($stop_after_put_in = 1){
										break;
									}	
							}
						}
			}

			foreach($cust_end_anytime as $key1 => $value1){
				$custName = $value1["custName"];
				$custProduct = $value1["custProduct"];
				$my_array2 = explode(",", $custProduct);
				$custA = $value1["custA"];

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
								
								if(empty($cust_Array[$var][$nextcolumn])){	// if cell is occupied then skip (move to next column)
									$cust_Array[$var][$nextcolumn] = $custName;		// if cell not occupied then put in
									$stop_after_put_in = 1;						
									break;
								}else if (in_array($cust_Array[$var][$nextcolumn], $array_cust_replaced)){
									$cust_Array[$var][$nextcolumn] = $custName;		// if cell not occupied then put in
									$stop_after_put_in = 1;						
									break;
								}else{
									$nextcolumn++;
									continue;
								}
							}
  							$nextcolumn++;
						}
						if($stop_after_put_in = 1){
							break;
						}	
					}
				}

			}

			foreach($cust_both_anytime as $key0 => $value0){

				$custName = $value0["custName"];
				$custProduct = $value0["custProduct"];
				$my_array2 = explode(",", $custProduct);

				foreach($my_array1 as $value => $my){					
					$name=$my;

					if(in_array($name, $my_array2)){				// compare if comp product == cust product
														
						$nextcolumn = 0;
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
									
							if($stop!=1){								// if the column not contain same cust name, execute below code
								if(empty($cust_Array[$var][$nextcolumn])){	// if cell is occupied then skip (move to next column)
									$cust_Array[$var][$nextcolumn] = $custName;		// if cell not occupied then put in
									$stop_after_put_in = 1;						
									break;
								}else if (in_array($cust_Array[$var][$nextcolumn], $array_cust_replaced)){
									$cust_Array[$var][$nextcolumn] = $custName;		// if cell not occupied then put in
									$stop_after_put_in = 1;						
									break;
								}else{
									$nextcolumn++;
									continue;
								}
							}
  							$nextcolumn++;
						}
						if($stop_after_put_in = 1){
							break;
						}				

					}else{
							
					}
					
				} //end foreach my_array1
			}

		}
	
	
    	$_SESSION['cust_Array'] = $cust_Array;

	return $cust_Array;

}


		if (isset($_POST['submit'])) {

		if (isset($_POST['custName'])) {

		$custName1 = $_POST['custName'];
    		$custProducts = array_map('strtoupper', $_POST['custProduct']);
    		$custA = $_POST['a'];
    		$custB = $_POST['b'];

		foreach($_POST['customerCheckbox'] as $check) {
			$array_cust_replaced[] = $check;
		
		}
		foreach ($custName1 as $key_cust => $name_cust) {
			$CustomerData[] = array(
        			'custName' => $name_cust,
        			'custProduct' => $custProducts[$key_cust],
        			'custA' => $custA[$key_cust],
        			'custB' => $custB[$key_cust]
  			);
    		
		}
		
		if(empty($addedCustomerData)){
			$addedCustomerData = $CustomerData;
			
		}else{
			$addedCustomerData = array_merge($addedCustomerData, $CustomerData);
		}
		$_SESSION['addedCustomerData'] = $addedCustomerData;
	
	?>
		<div class="container">
    		<h1 style="margin-top:25px;margin-bottom:30px;"><b><?php echo $eventName ?></b></h1>
		<div class="row">
    		<div class="column"><h3>COMPANY</h3>
	
	<?php	$sql = "SELECT * FROM company";							// print companies
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
		<form action="add_cust.php" id="process_addCust" method="post">

	<?php	
		$customer_colors = array();
		$sql = "SELECT * FROM customer";						// print customers 
		$result = mysqli_query($con, $sql);
		
		$a=0;
		while ($row = mysqli_fetch_assoc($result)) {

    			$custA1 = isset($times[$row['time_start']]) ? $times[$row['time_start']] : "Anytime";
    			$custB1 = isset($times[$row['time_end']]) ? $times[$row['time_end']] : "Anytime";

			if(in_array($row["custName"], $array_cust_replaced)){
				echo "<input type='checkbox' class='checklist' name='customerCheckbox[]' id='addCust' value='" . $row["custName"] . "' checked>";
			}else{
				echo "<input type='checkbox' class='checklist' name='customerCheckbox[]' id='addCust' value='" . $row["custName"] . "'>";
			}

    			// Print customer details with logic to print "Anytime" only once
    			if ( $custA1 === "Anytime" && $custB1 === "Anytime") {
        			echo " ".$row["custName"] . " : " . $row["custProduct"] . " ( <mark>Anytime</mark> )<br>";

    			} else {
        			echo " " .$row["custName"] . " : " . $row["custProduct"] . " ( <mark>" . $custA1 . " - " . $custB1 . "</mark> )<br>";
    			}

			$customer_colors[$row["custName"]] = $color[$a];

			$a++;
			
		}

		
		foreach ($addedCustomerData as $row1) {

			$custA2 = isset($times[$row1["custA"]]) ? $times[$row1["custA"]] : "Anytime";
    			$custB2 = isset($times[$row1["custB"]]) ? $times[$row1["custB"]] : "Anytime";

			if(in_array($row1["custName"], $array_cust_replaced)){
				echo "<input type='checkbox' class='checklist' name='customerCheckbox[]' id='addCust' value='" . $row1["custName"] . "' checked>";
			}else{
				echo "<input type='checkbox' class='checklist' name='customerCheckbox[]' id='addCust' value='" . $row1["custName"] . "'>";
			}

			if ( $custA2 === "Anytime" && $custB2 === "Anytime") {
        			echo " ".$row1["custName"] . " : " . $row1["custProduct"] . " ( <mark>Anytime</mark> )<br>";

    			} else {
        			echo " " .$row1["custName"] . " : " . $row1["custProduct"] . " ( <mark>" . $custA2 . " - " . $custB2 . "</mark> )<br>";
    			}

			$customer_colors[$row1["custName"]] = $color[$a];
			$a++;
		}

		$customer_colors[""] = "#FFFFFF";
	?>
		<div id="dynamic_rows"></div>
		<button type="button" class="btn btn-primary addCust" disabled="disabled" onclick="addRow()">Add Customer</button>
		<input type="submit" name="submit" class="btn btn-primary submitCust" disabled="disabled">	

		</form>
		</div>
		</div>

		<br>
	
		<?php

		$sql2 = "SELECT * FROM company";
		$result2 = mysqli_query($con, $sql2);
		while($row = mysqli_fetch_array($result2)){
			$company_Array[] = array('compID'=>$row['compID'], 'compName'=>$row['compName'], 'compProduct'=>$row['compProduct']);
			$arr[] = $row["compName"];
		}

		
    	
	$cust_Array = generateCustArray($CustomerData,$company_Array,$cust_Array,$array_cust_replaced,$count_timeslot);

	echo generateTable($timetable,$cust_Array,$count_timeslot,$array_cust_replaced,$arr,$customer_colors,);

	?>

	</div>
<?php 	
	}else{header("location:index3.php");}

	}else{








	}
?>
	
	<script>
    		var rowCounter = 1; // Initialize a counter to keep track of row IDs

		// Update remaining rows whenever a checkbox is checked or unchecked
		function updateRemainingRows() {
    			var checkedCheckboxes = document.querySelectorAll('input[name="customerCheckbox[]"]:checked');
    			var remainingRows = checkedCheckboxes.length - $('.form-row').length;

   			 return remainingRows;
		}

		// Update remaining rows initially and whenever a checkbox is clicked
		function updateRowsAvailability() {
    			var remainingRows = updateRemainingRows();

    			// Enable/disable the "Add Customer" button based on remaining rows
    			$('.addCust').prop('disabled', remainingRows < 0);

    			// Enable/disable the form submission button based on remaining rows
    			$('.submitCust').prop('disabled', remainingRows < 0);
		}

		// Update remaining rows whenever a checkbox is clicked
		$(function() {
    			$(".checklist").click(function(){
        			updateRowsAvailability();
    			});
		});

		function addRow() {
    			var remainingRows = updateRemainingRows();

    			// Check if there are remaining rows allowed to be added
    			if (remainingRows > 0) {
        			// Create a new row template
        			var row = `
            				<div class="form-row" id="dynamic_row_${rowCounter}">
                				<div class="row" style="margin-left:1px;">
                    					<div class="form-group col-md-2  nopadding">
                        					<input type="text" class="form-control" name="custName[]" placeholder="Name" required>
                    					</div>
                    					<div class="form-group col-md-3 nopadding">
                        					<input type="text" class="form-control" name="custProduct[]" placeholder="Products" required>
                    					</div>
                    					<div class="form-group col-md-2  nopadding">
                        					<select name="a[]" class="form-control" id="timeRestrictionA_${rowCounter}" style="display: inline-block; width: auto;">
                            						<option value="-1" selected>(Anytime)</option>
                            						<?php foreach ($times as $y => $value) { ?>
                                						<option value="<?php echo $y; ?>"><?php echo $value; ?></option>
                            						<?php } ?>
                        					</select>
                    					</div>
                    					<div class="form-group col-md-3">
                        					<select name="b[]" class="form-control" id="timeRestrictionB_${rowCounter}" style="display: inline-block; width: auto;">
                            						<option value="-1" selected>(Anytime)</option>
                            						<?php foreach ($times as $z => $value2) { ?>
                                						<option value="<?php echo $z; ?>"><?php echo $value2; ?></option>
                            						<?php } ?>
                        					</select>
                    					</div>
                    					<div class="form-group col-md-1 nopadding">
                        					<button type="button" class="btn btn-danger" onclick="deleteRow(this)"> X </button>
                    					</div>
                				</div>
            				</div>
        			`;

        			// Append the new row to the container
        			$('#dynamic_rows').append(row);
        			rowCounter++; // Increment the counter for each new row

        			// Update remaining rows after adding a row
        			updateRowsAvailability();
    			} else {
        			alert("You cannot add more rows. Please uncheck some checkboxes.");
    			}
		}


		function deleteRow(button){	
			var row = $(button).closest('.form-row');
			row.remove();
			updateRowsAvailability();
		}
	</script>

	
</body>
</html>
