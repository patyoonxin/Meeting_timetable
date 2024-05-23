<?php
	session_start();
?>

<!DOCTYPE html>
	<html>
		<head>
			<title>General</title>
			<meta name="viewport" content="width=device-width, initial-scale=1">
  			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		</head>

		<body>
			<?php require('db.php') ?>

			<div class="container">
				<h1 style="margin-top:25px;margin-bottom:35px;"><b>About Slot</b></h1>
				<form action="" method="POST">
					<div class="form-group">
						<label>Event Name:</label><br>
						<input type="text" class="form-control" name="event_name" required><br>
					</div>
					<div class="form-group">
						<label>How many minutes in 1 slot?</label><br>
						<input type="number" class="form-control" name="mins_slot" required><br>
					</div>
					<div class="form-group">
						<label>Please enter your preferred break time (e.g. 10:00 or - ) :</label><br>
						<input type="text" class="form-control" name="break_time" required>
						<small>*The break will last one hour.<small><br><br>
					</div>
					<input type="submit" class="btn btn-primary"/>
				</form>
				<br><button class="btn btn-primary" style="margin-top:20px;margin-bottom:15px;" onclick="location.href='event.php'">History</button>
			
			<?php 
				
    				if ($_SERVER["REQUEST_METHOD"] == "POST") {
    					
					function generateTimeslots($startTime, $stopTime, $slotDuration) {
    						$timeslots = [];

    						// Ensure valid time formats (assuming HH:MM format)
    						$startTime = strtotime($startTime);
    						$stopTime = strtotime($stopTime);
    						

    						$currentTime = $startTime;
    						$x = 0; 

    						while ($currentTime <= $stopTime) {
        						$nextTime = $currentTime + ($slotDuration * 60);
							if ($nextTime <= $stopTime) {

            								$timeslots[] = date('H:i', $currentTime) . " - " . date('H:i', $nextTime);
									$times[] = date('H:i', $currentTime);
							}

        						$currentTime = $nextTime;
        						$x++;
    						}

    					return array( $timeslots,$times);
					}

					$error = 0;

					$startTime = "09:00";
					$stopTime = "17:00";
					$eventName = $_POST["event_name"];
					$slotDuration = $_POST["mins_slot"];
					$breakTime = $_POST["break_time"];
					$how_many_break = ceil(60 / $slotDuration);
					

					list($timeslots,$times) = generateTimeslots($startTime, $stopTime, $slotDuration, $breakTime);
					//echo '<pre>'; print_r($timeslots); echo '</pre>';


					if(array_search($breakTime,$times)==false && $breakTime!=='-'){
					
						$breakStart = strtotime($breakTime);   
						$smallest = [];

						foreach ($times as $i) {
    							// Convert $i (a time string) to timestamp
    							$iTimestamp = strtotime($i);
    							// Calculate the absolute difference in seconds
    							$smallest[$i] = abs($iTimestamp - $breakStart);
						}

						asort($smallest);
						$closestTimeSlot = key($smallest); // This gives the closest time slot to the break time
						$breakTime = $closestTimeSlot;
			
					}

				
					
					$string_array_timeslots = implode('|', $timeslots);
					$_SESSION["timeslot"] = $string_array_timeslots;

					$string_array_times = implode('|', $times);
					$_SESSION["times"] = $string_array_times;

					
                			$_SESSION["break"] = $breakTime;
					$_SESSION["how_many_break"] = $how_many_break;
					$_SESSION["eventName"] = $eventName;

					

						
					$query = "INSERT INTO event (event_Name, event_breaktime, event_timeslots, event_times, how_many_break) VALUES ('$eventName','$breakTime', '$string_array_timeslots', '$string_array_times', '$how_many_break')";
					$result = mysqli_query($con,$query);
					
        				if($result){
							
						header("location:input.php");
				
        				}else{
							
						echo "ERROR: Could not able to execute $query. " . mysqli_error($con);
					}					
					
				}
			?>

			</div>	
		</body>

	</html>