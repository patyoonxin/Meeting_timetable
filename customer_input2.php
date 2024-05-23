<?php
	session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Customer</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>

	<body>
		<div class="container">
		<h1 style="margin-top:25px;margin-bottom:40px;"><b>Update Status</b></h1>
			
		<?php
			require('db.php');

			//echo '<pre style="text-align: left;">' . print_r($_POST, true) . '</pre>';
			
			$error=0;
			if (!empty($_POST['submit'])) {

				$dlt = "DELETE FROM customer";
				$rst = mysqli_query($con,$dlt);

        			foreach($_POST['custName'] as $key => $value) {

					$custID = mysqli_real_escape_string($con, $_POST['custID'][$key]);
    					$custName = mysqli_real_escape_string($con, $_POST['custName'][$key]);
    					$custProduct = mysqli_real_escape_string($con, strtoupper($_POST['custProduct'][$key]));
					$cust_start = mysqli_real_escape_string($con, $_POST['a'][$key]);
    					$cust_end = mysqli_real_escape_string($con, $_POST['b'][$key]);

            				$query = "INSERT INTO customer (custID, custName, custProduct, time_start, time_end) VALUES ('$custID','$custName','$custProduct','$cust_start','$cust_end')";
					$result = mysqli_query($con,$query);
					
        				if($result){

						echo "<h4>".$_POST['custName'][$key]." details saved successfully.</h4>";
				
        				}else{
						$error = 1;
						echo "<h4>Fail to save ".$_POST['custName'][$key]." details. Please go back and save again.</h4>";
					}
       				}
   			}

			if($error!=1){
		?>
				<button class="btn btn-default mb-2" style="margin-top:20px;margin-bottom:15px;" onclick="location.href='index3.php'">Next</button>
 		<?php
			}
    		?>		
		</div>	
	</body>
</html>