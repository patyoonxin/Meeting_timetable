<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customer</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script></head>
<body>
	<?php
				require('db.php');
				$times=explode('|', $_SESSION["times"]);
								
			?>
    <div class="container">
        <h1 style="margin-top: 25px; margin-bottom: 22px;"><b>Customer Details</b></h1>
       
            <br><form action="customer_input2.php" method="POST" name="customer">
	<label>Please fill in:</label><br><br>
            <div class="form-row" style="text-align: center;">
		 <div class="col-md-12">  
                <div class="form-group col-md-1">
                    <label>ID</label>
                </div>
                <div class="form-group col-md-2">
                    <label>Name of customer</label>
                </div>
                <div class="form-group col-md-5">
                    <label>Products of company (Please use ',' to separate each product) </label>
		    <br><small>*NO SPACES before and after ','</small>
                </div>
                <div class="form-group col-md-2">
                    <label>Country </label>
                </div>
		<div class="form-group col-md-1">
                    <label>Time Restriction</label>
                </div>
		<div class="form-group col-md-1">
                    <label> </label>
                </div>
		</div>
		
            </div>
          
                  
                        <div class="form-row">
			 <div class="col-md-12">  
                            <div class="form-group col-md-1">
                                <input type="number" class="form-control" name="custID[]" placeholder="ID" required>
                            </div>
                            <div class="form-group col-md-2">
                                <input type="text" class="form-control" name="custName[]" placeholder="Name" required>
                            </div>
                            <div class="form-group col-md-5">
                                <input type="text" class="form-control" name="custProduct[]" placeholder="Products (Please use ',' to separate each product)" required>
                            </div>
                            <div class="form-group col-md-2">
                                <input type="text" class="form-control" name="country[]" placeholder="Country" required>
                            </div>
	
                            <div class="form-check col-md-1">
                                <input type="checkbox" class="form-check-input" id="timeRestriction_0" onclick="showPrompt(this)">
                            </div>
			    <div class="form-group col-md-1">
                           	<label> </label>
                	    </div>
                   
			</div>
			</div>

                        <div class="time-restriction" id="timeRestrictionMsg_0" style="display: none;  padding-left: 40px;">
			
                            <small><i>*if you have time restriction please fill in</i></small><br>
			
                            		<label for="timeRestrictionA_0">I prefer ___ or afterwards.</label>
                           		<select name="a[]" class="form-control" id="timeRestrictionA_0" style="display: inline-block; width: auto;">
                                		<option value="-1" selected>(Anytime)</option>
                                		<?php foreach ($times as $y => $value) { ?>
                                    			<option value="<?php echo $y; ?>"><?php echo $value; ?></option>
                                		<?php } ?>
                            		</select><br>
			
                            		<label for="timeRestrictionB_0">I prefer ___ before.&emsp;&emsp;&emsp;&nbsp;</label>
                            		<select name="b[]" class="form-control" id="timeRestrictionB_0" style="display: inline-block; width: auto;">
                                		<option value="-1" selected>(Anytime)</option>
                                		<?php foreach ($times as $z => $value2) { ?>
                                    			<option value="<?php echo $z; ?>"><?php echo $value2; ?></option>
                                		<?php } ?>
                            		</select><br><br><br>
			
                        </div>
                    
        
		 <div id="dynamic_rows"></div>

		<button type="button" class="btn btn-primary" onclick="addRow()" style="margin-top:20px;margin-bottom:20px;">Add new row</button>
                <input type="submit" name="submit" class="btn btn-primary" style="float:right;margin-top:20px;margin-bottom:20px;margin-right:25px;">
		<button class="btn btn-default mb-2" onclick="location.href='index3.php'" style="margin-top:20px;float:right;margin-right:10px;margin-bottom:20px;">Skip</button>
		

            </form>
		<script>
    var rowCounter = 1; // Initialize a counter to keep track of row IDs

    function addRow() {
        rowCounter++; // Increment the counter for each new row

        // Create a new row template
        var row = `
            <div class="form-row" id="dynamic_row_${rowCounter}">
		<div class="col-md-12 mb-3">   
                <div class="form-group col-md-1">
                    <input type="number" class="form-control" name="custID[]" placeholder="ID" required>
                </div>
                <div class="form-group col-md-2">
                    <input type="text" class="form-control" name="custName[]" placeholder="Name" required>
                </div>
                <div class="form-group col-md-5">
                    <input type="text" class="form-control" name="custProduct[]" placeholder="Products (Please use ',' to separate each product)" required>
                </div>
                <div class="form-group col-md-2">
                    <input type="text" class="form-control" name="country[]" placeholder="Country" required>
                </div>
                <div class="form-check col-md-1">
                    <input type="checkbox" class="form-check-input" id="timeRestriction_${rowCounter}" onclick="showPrompt(this)">
                </div>
		<div class="form-group col-md-1">
                    <button type="button" class="btn btn-danger" onclick="deleteRow(this)"> X </button>
                </div>
		</div>
            </div>
            <div class="time-restriction" id="timeRestrictionMsg_${rowCounter}" style="display: none; padding-left: 40px;">
                <small><i>*if you have time restriction please fill in</i></small><br>
                <label for="timeRestrictionA_${rowCounter}">I prefer ___ or afterwards.</label>
                <select name="a[]" class="form-control" id="timeRestrictionA_${rowCounter}" style="display: inline-block; width: auto;">
                    <option value="-1" selected>(Anytime)</option>
                    <?php foreach ($times as $y => $value) { ?>
                        <option value="<?php echo $y; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                </select><br>
                <label for="timeRestrictionB_${rowCounter}">I prefer ___ before.&emsp;&emsp;&emsp;&nbsp;</label>
                <select name="b[]" class="form-control" id="timeRestrictionB_${rowCounter}" style="display: inline-block; width: auto;">
                    <option value="-1" selected>(Anytime)</option>
                    <?php foreach ($times as $z => $value2) { ?>
                        <option value="<?php echo $z; ?>"><?php echo $value2; ?></option>
                    <?php } ?>
                </select><br><br><br>
            </div>
        `;

        // Append the new row to the container
        $('#dynamic_rows').append(row);
    }
</script>
     <script>
        function showPrompt(checkbox) {
            var index = checkbox.id.split("_")[1];
            var text = document.getElementById("timeRestrictionMsg_" + index);
            text.style.display = checkbox.checked ? "block" : "none";
        }
	function deleteRow(button){	
		var row = $(button).closest('.form-row');
		row.remove();
	}
    </script>

</body>
</html>