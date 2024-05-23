<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Company</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
<?php
	require('db.php');
?>
	<div class="container">
    		<h1 style="margin-top:25px;margin-bottom:40px;"><b>Company Details</b></h1>

        		<form action="input2.php" method="POST" name="company">
            			<label>Please fill in:</label><br><br>
            			<div class="form-row" style="text-align: center;">
				<div class="col-md-12">
                			<div class="form-group col-md-1">
                    				<label>ID</label>
                			</div>
                			<div class="form-group col-md-2">
                    				<label>Name of company</label>
                			</div>
                			<div class="form-group col-md-6">
                    				<label>Products of company(Please use ',' to separate each product) </label>
						<br><small>*NO SPACES before and after ','</small>
                			</div>
                			<div class="form-group col-md-2">
                    				<label>Country</label>
                			</div>
					<div class="form-group col-md-1">
                    				<label> </label>
                			</div>
            			</div>
	 			</div>

				<div class="col-md-12">
                			<div class="form-row">
                    				<div class="form-group col-md-1">
                        				<input type="number" class="form-control" name="compID[]" placeholder="ID" required/>
                    				</div>
                    				<div class="form-group col-md-2">
                        				<input type="text" class="form-control" name="compName[]" placeholder="Name" required/>
                    				</div>
                    				<div class="form-group col-md-6">
                        				<input type="text" class="form-control" name="compProduct[]" placeholder="Products (Please use ',' to separate each product)" required/>
                    				</div>
                    				<div class="form-group col-md-2">
                        				<input type="text" class="form-control" name="country[]" placeholder="Country" required/>
                    				</div>
						<div class="form-group col-md-1">
                    					<label> </label>
                				</div>
						
                			</div>
				</div>
	
           			 <div id="dynamic_rows"></div>

				<button type="button" class="btn btn-default" onclick="addRow()" style="margin-left:15px;margin-top:20px;margin-bottom:15px;">Add new row</button>
				<input type="submit" name="submit" class="btn btn-primary" style="float: right;margin-top:20px;margin-bottom:15px;margin-right:25px;">
            			

            			
				<button class="btn btn-default mb-2" style="margin-top:20px;float: right;margin-right:10px;margin-bottom:15px;" onclick="location.href='customer_input.php'">Skip</button>
        		</form>
 

    <script>
	function addRow() {
           	 // Create a new row template
           	 var row = `
			<div class="col-md-12">
                	<div class="form-row">
                    		<div class="form-group col-md-1">
                        		<input type="number" class="form-control" name="compID[]" placeholder="ID" required/>
                    		</div>
                    		<div class="form-group col-md-2">
                        		<input type="text" class="form-control" name="compName[]" placeholder="Name" required/>
                    		</div>
                   		<div class="form-group col-md-6">
                        		<input type="text" class="form-control" name="compProduct[]" placeholder="Products (Please use ',' to separate each product)" required/>
                    		</div>
                    		<div class="form-group col-md-2">
                        		<input type="text" class="form-control" name="country[]" placeholder="Country" required/>
                    		</div>
		    		<div class="form-group col-md-1">
                    			<button type="button" class="btn btn-danger" onclick="deleteRow(this)"> X </button>
                    		</div>
                	</div>
			</div>
            	`;

            	// Append the new row to the container
            	$('#dynamic_rows').append(row);
        }

	function deleteRow(button){	
		var row = $(button).closest('.form-row');
		row.remove();
	}

    </script>
	
</body>
</html>
