<?php
	session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>General</title>
	</head>

	<body>
			
		<?php
			require('db.php');

			$breakTime = $_SESSION["break"];
			$closest = $_SESSION["closest"];
			$eventName=$_SESSION["eventName"];
			$times= $_SESSION["times"];
			$timeslot=$_SESSION["timeslot"];
			$how_many_break = $_SESSION["how_many_break"];

			$breakTime = $closest;

			$_SESSION["break"] = $breakTime;

			$query = "INSERT INTO event (event_Name, event_breaktime, event_timeslots, event_times, how_many_break) VALUES ('$eventName','$breakTime', '$timeslot', '$times', '$how_many_break')";
			$result = mysqli_query($con,$query);
					
        		if($result){

				header("location:input.php");
				
        		}else{
							
				echo "ERROR: Could not able to execute $query. " . mysqli_error($con);
			}
    		?>		
				
	</body>
</html>