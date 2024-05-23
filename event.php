<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Event</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script></head>
<body>
<?php
		require('db.php');
										
?>
	
	<div class="container">
		<h1 style="margin-top: 25px; margin-bottom: 22px;"><b>Event History</b></h1>
		<form action="" method="post">
<?php
		$sql = "SELECT * FROM event";							
		$result = mysqli_query($con, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)){
				$event_name=$row["event_name"];
				$id=$row["idevent"];
				echo "<button name='event' class='btn btn-primary' style='width: 200px;margin-left:5px;margin-top:20px;margin-bottom:15px;' value='".$id."'>".$event_name."</button>";
				
			}
		}else {
			echo "No event available.";
		}

?>
		</form>
	</div>

<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$id = $_POST["event"];

		$sql2 = "SELECT * FROM event WHERE idevent='$id'";							
		$result2 = mysqli_query($con, $sql2);
		if (mysqli_num_rows($result2) > 0) {
			while($row = mysqli_fetch_assoc($result2)){
				$event_name=$row["event_name"];
				$event_breaktime=$row["event_breaktime"];	
				$event_timeslots=$row["event_timeslots"];	
				$event_times=$row["event_times"];	
				$how_many_break=$row["how_many_break"];	
			}
		}

		$_SESSION["timeslot"] = $event_timeslots;
		$_SESSION["times"] = $event_times;					
                $_SESSION["break"] = $event_breaktime;
		$_SESSION["how_many_break"] = $how_many_break;
		$_SESSION["eventName"] = $event_name;
		
		header("location:index3.php");
	}
?>
	
</body>
</html>