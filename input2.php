<?php
	session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Company</title>
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
				
				$dlt = "DELETE FROM company";
				$rst = mysqli_query($con,$dlt);

        			foreach ($_POST['compName'] as $key => $value) {
    					$compID = mysqli_real_escape_string($con, $_POST['compID'][$key]);
    					$compName = mysqli_real_escape_string($con, $_POST['compName'][$key]);
    					$compProduct = mysqli_real_escape_string($con, strtoupper($_POST['compProduct'][$key]));

    					$query = "INSERT INTO company (compID, compName, compProduct) VALUES ('$compID', '$compName', '$compProduct')";
    					$result = mysqli_query($con, $query);

    					if ($result) {
        					echo "<h4>$compName details saved successfully.</h4>";
    					} else {
        					$error = 1;
        					echo "<h4>Failed to save $compName details. Please go back and save again.</h4>";
    					}
				}

   			}

			if($error!=1){
		?>
				<button class="btn btn-default mb-2" style="margin-top:20px;margin-bottom:15px;" onclick="location.href='customer_input.php'">Next</button>
 		<?php
			}
    		?>	
		</div>	
				
	</body>
</html>