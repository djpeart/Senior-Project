<?php  ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }

	//include $_SERVER['DOCUMENT_ROOT'] . '/log/logActions.php';

    // Include config file
    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 

	
    // Define variables and initialize with empty values
	$FullName = $PhoneNumber = $Street = $City = $State = $ZIP = $Balance = $ClientID = "";
	$FullName_err = $PhoneNumber_err = $Street_err = $City_err = $State_err = $ZIP_err = $Balance_err  = "";


	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "GET"){
		
		$sql = "SELECT ClientID, FullName, PhoneNumber, Street, City, State, ZIP, MonthlyPrice, Balance, DueDate FROM clients WHERE ClientID = ?";
		
		if($stmt = mysqli_prepare($acclink, $sql)){ 
			mysqli_stmt_bind_param($stmt, "i", $param_ClientID);

			$param_ClientID = $_GET["id"];           

			if(mysqli_stmt_execute($stmt)){
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_num_rows($stmt) == 1){
					mysqli_stmt_bind_result($stmt, $ClientID, $FullName, $PhoneNumber, $Street, $City, $State, $ZIP, $MonthlyPrice, $Balance, $DueDate);
					if (!mysqli_stmt_fetch($stmt)){	
						echo "Oops! Something went wrong. Please try again later.";			
					} else {
						$DaysTillDue = date_diff(date_create($DueDate),date_create(date("Y-m-d")))->format("%a");
						if ($DueDate > date("Y-m-d")) 
							$DaysTillDue = -$DaysTillDue;
					}
				}
			} else{
				echo "Oops! Something went wrong. Please try again later.";
			}

			mysqli_stmt_close($stmt);
		}


		$assignments = array();
		$sql = "SELECT AssignmentID, assets.AssetID, Name, Price, StartDate, EndDate, Active FROM assignments INNER JOIN assets ON assignments.AssetID=assets.AssetID WHERE ClientID=?";
		
		if($stmt = mysqli_prepare($acclink, $sql)){ 
			mysqli_stmt_bind_param($stmt, "i", $param_ClientID);
			
			$param_ClientID = $_GET["id"];           

			if(mysqli_stmt_execute($stmt)){
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_num_rows($stmt) > 0){
					mysqli_stmt_bind_result($stmt, $AssignmentID, $AssetID, $Name, $Price, $StartDate, $EndDate, $Active);
					while(mysqli_stmt_fetch($stmt)){
						$assignments[$AssignmentID] = array (
							"AssignmentID" => $AssignmentID,
							"AssetID" => $AssetID,
							"Name" => $Name,
							"Price" => $Price,
							"StartDate" => $StartDate,
							"EndDate" => $EndDate,
							"Active" => $Active
						);	
					}
				}
			} else{
				echo "Oops! Couldn't pull data. Please try again later.";
			}

			mysqli_stmt_close($stmt);
		}


		$payments = array();
		$sql = "SELECT PaymentID, Amount, Date FROM payments WHERE ClientID=?";
		
		if($stmt = mysqli_prepare($acclink, $sql)){ 
			mysqli_stmt_bind_param($stmt, "i", $param_ClientID);
			
			$param_ClientID = $_GET["id"];           

			if(mysqli_stmt_execute($stmt)){
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_num_rows($stmt) > 0){
					mysqli_stmt_bind_result($stmt, $PaymentID, $Amount, $Date);
					while(mysqli_stmt_fetch($stmt)){
						$payments[$PaymentID] = array (
							"PaymentID" => $PaymentID,
							"Amount" => $Amount,
							"Date" => $Date
						);	
					}
				}
			} else{
				echo "Oops! Couldn't pull data. Please try again later.";
			}

			mysqli_stmt_close($stmt);
		}

		$assets = array();
		$sql = "SELECT assets.AssetID, Name FROM assets LEFT JOIN assignments ON assets.AssetID = assignments.AssetID WHERE AssignmentID IS NULL";
		
		if($stmt = mysqli_prepare($acclink, $sql)){			
			if(mysqli_stmt_execute($stmt)){
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_num_rows($stmt) > 0){
					mysqli_stmt_bind_result($stmt, $AssetID, $Name);
					while (mysqli_stmt_fetch($stmt)){
						$assets[count($assets)+1] = array (
							"AssetID" => $AssetID,
							"Name" => $Name
						);
					}
				}
			}
			mysqli_stmt_close($stmt);
		}
		


		//mysqli_close($acclink);
	}

	if($_SERVER["REQUEST_METHOD"] == "POST"){

		// Check if nFullName is empty
		if(empty(trim($_POST["FullName"]))){
			$FullName_err = "Please the full name.";
		} else{
			$FullName = trim($_POST["FullName"]);
		}

		// Check if nPhoneNumber is empty
		if(empty(trim($_POST["PhoneNumber"]))){
			$PhoneNumber_err = "Please enter the phone number.";
		} else{
			$PhoneNumber = trim($_POST["PhoneNumber"]);
		}
		
		// Check if nStreet is empty
		if(empty(trim($_POST["Street"]))){
			$Street_err = "Please enter the street address.";
		} else{
			$Street = trim($_POST["Street"]);
		}
		
		// Check if nCity is empty
		if(empty(trim($_POST["City"]))){
			$City_err = "Please enter the city.";
		} else{
			$City = trim($_POST["City"]);
		}
		
		// Check if nState is empty
		if(empty(trim($_POST["State"]))){
			$State_err = "Please enter the state.";
		} else{
			$State = trim($_POST["State"]);
		}
		
		// Check if nZIP is empty
		if(empty(trim($_POST["ZIP"]))){
			$ZIP_err = "Please enter the ZIP code.";
		} else{
			$ZIP = trim($_POST["ZIP"]);
		}
				
		if(empty($_POST["ClientID"])){
			echo "Didn't receive ClientID, please go back and try again";
		} else {
			$ClientID = $_POST["ClientID"];
		}

		// Validate entries are in
		if(empty($FullName_err) && empty($PhoneNumber_err) && empty($Street_err) && empty($City_err) && empty($State_err) && empty($ZIP_err)){
			// Prepare a select statement
			$sql = "UPDATE clients SET FullName = ?, PhoneNumber = ?, Street = ?, City = ?, State = ?, ZIP = ? WHERE ClientID = ?";
			
			if($stmt = mysqli_prepare($acclink, $sql)){ //This is the line that gives me the error
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "sssssii", $param_FullName, $param_PhoneNumber, $param_Street, $param_City, $param_State, $param_ZIP, $param_ClientID);
			

				// Set parameters
				$param_FullName = $FullName;
				$param_PhoneNumber = $PhoneNumber;
				$param_Street = $Street;
				$param_City = $City;
				$param_State = $State;
				$param_ZIP = $ZIP;
				$param_ClientID = $ClientID;   

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					echo "Successfully saved the record.";
					header("location: /client/edit.php?id=" . $ClientID);
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
			}
			
			// Close statement
			mysqli_stmt_close($stmt);
			//mysqli_close($acclink);
		} 
	}

?>

<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
			<title>View Client</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
			<style type="text/css">body{ font: 14px sans-serif; }</style>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		</head>
	<body>
		<?php requirePermissionLevel(2); ?>

		<div class="container">

			<br><nav class="navbar navbar-inverse">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>                        
						</button>
						<div class="navbar-brand" href="">Dan's Senior Project</div>
					</div>
					<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav">
							<li><a href="/welcome.php">Welcome</a></li>
							<li class="active"><a href="/client/view.php">Clients</a></li>
							<li><a href="/asset/view.php">Assets</a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li><a href="/account/reset-password.php"><span class="glyphicon glyphicon-user"></span> Change Password</a></li>
							<li><a href="/account/logout.php"><span class="glyphicon glyphicon-log-in"></span> Sign Out</a></li>
						</ul>
					</div>
				</div>
			</nav>

			<div class="text-center">
				<h1><strong><?php echo $FullName; ?></strong></h1>
			</div>

			<div class="row">
				<div class="col-md-6">

					<h3 class="text-center"><strong>Details</strong></h3>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th scope="col"></th>
									<th scope="col"></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th scope="row">Name</th>
									<td><?php echo $FullName; ?></td>
								</tr>
								<tr>
									<th scope="row">Phone Number</th>
									<td><?php echo $PhoneNumber; ?></td>
								</tr>
								<tr>
									<th scope="row">Address</th>
									<td><?php echo $PhoneNumber; ?></td>
								</tr>
								<tr>
									<th scope="row">Street</th>
									<td><?php echo $Street; ?></td>
								</tr>
								<tr>
									<th scope="row">City</th>
									<td><?php echo $City; ?></td>
								</tr>
								<tr>
									<th scope="row">State</th>
									<td><?php echo $State; ?></td>
								</tr>
								<tr>
									<th scope="row">ZIP</th>
									<td><?php echo $ZIP; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<a class="btn btn-primary" data-toggle="modal" data-target="#editClient">Edit Client Details</a>
					<br />
					
					<h3 class="text-center"><strong>Assets</strong></h3>
					<div class="table-responsive">
						<table class="table table-hover ">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">Name</th>
									<th scope="col">Price</th>
									<th scope="col">StartDate</th>
									<th scope="col">EndDate</th>
									<th scope="col">Active</th>
								</tr>
							</thead>
							<tbody id="myTable">
								<?php 
									foreach ($assignments as $assignment) {
										print "\r\n                         <tr onclick=\"window.location='\\edit.php?id=" . $ClientID . "&aid=" . $assignment["AssignmentID"] . "';\">\r\n";
											print "                             <th scope=\"row\">" . $assignment["AssetID"] . "</th>\r\n";
											print "                             <td>" . $assignment["Name"] . "</td>\r\n";
											print "                             <td>" . $assignment["Price"] . "</td>\r\n";
											print "                             <td>" . $assignment["StartDate"] . "</td>\r\n";
											print "                             <td>" . $assignment["EndDate"] . "</td>\r\n";
											print "                             <td>" . ($assignment["Active"] ? "Yes" : "No") . "</td>\r\n";
										print "                         </tr>\r\n";
									}
									
								?>
							</tbody>
						</table>
					</div>
					<?php if(count($assignments) == 0) print "There's nothing here :(<br>" ?>
					<br /><a class="btn btn-primary" data-toggle="modal" data-target="#addAssignment">Assign Asset</a>

					<br />
				</div>
				<div class="col-md-6">

					<h3 class="text-center"><strong>Account Status</strong></h3>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th scope="col"></th>
									<th scope="col"></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th scope="row">Due Date</th>
									<td><?php echo $DueDate; echo ($DaysTillDue > 0) ? " (" . abs($DaysTillDue) . " days ago)" : " (" . abs($DaysTillDue) . " days from now)"; ?></td>
								</tr>
								<tr>
									<th scope="row">Monthly Due</th>
									<td>$<?php echo $MonthlyPrice; ?></td>
								</tr>
								<tr>
									<th scope="row">Amount Paid</th>
									<td>$<?php echo $Balance; ?></td>
								</tr>
								<tr>
									<th scope="row">Balance</th>
									<td>$<?php echo $MonthlyPrice - $Balance; echo (($MonthlyPrice - $Balance) < 0) ? " (Overpay)" : ""; ?></td>
								</tr>
							</tbody>
						</table>
					</div>

					<br />
					<a class="btn btn-primary" data-toggle="modal" data-target="#editDueDate">Change Due Date</a>
					<a class="btn btn-info" href="update.php?id=<?php echo $ClientID; ?>">Update Balances</a>

					<br />
					


					<h3 class="text-center"><strong>Payments</strong></h3>
					<div class="table-responsive">
						<table class="table table-hover ">
							<thead>
								<tr>
									<th scope="col">PaymentID</th>
									<th scope="col">Amount</th>
									<th scope="col">Date Received</th>
								</tr>
							</thead>
							<tbody id="myTable">
								<?php 
									foreach ($payments as $payment) {
										print "\r\n                         <tr onclick=\"window.location='\\edit.php?id=" . $ClientID . "&pid=" . $payment["PaymentID"] . "';\">\r\n";
											print "                             <th scope=\"row\">" . $payment["PaymentID"] . "</th>\r\n";
											print "                             <td>" . $payment["Amount"] . "</td>\r\n";
											print "                             <td>" . $payment["Date"] . "</td>\r\n";
										print "                         </tr>\r\n";
									}
									
								?>
							</tbody>
						</table>
					</div>
					<?php if(count($payments) == 0) print "There's nothing here :(<br>" ?>
					<br /><a class="btn btn-primary" data-toggle="modal" data-target="#addPayment">Add Payment</a>
				</div>

				
			</div>

			<br />
			<div class="text-center">
				<a class="btn btn-default" href="view.php">Back</a><br /><br />
				<?php //<a class="btn btn-danger" data-toggle="modal" data-target="#deleteClient">Delete Client</a><br /><br /> ?>
			</div>
        </div> 

		<div class="modal fade" id="editClient" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<form class="form-horizontal" action="/client/edit.php?id=<?php echo $ClientID; ?>" method="post">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h2 class="modal-title"><strong>Client Details</strong></h2>
						</div>
						<div class="modal-body">
							
							<div class="form-group <?php echo (!empty($FullName_err)) ? 'has-error' : ''; ?>">
								<label class="control-label col-sm-2">Full Name</label>
								<div class="col-sm-10">
									<input type="text" name="FullName" class="form-control" value="<?php echo $FullName; ?>">
									<span class="help-block"><?php echo $FullName_err; ?></span>
								</div>
							</div> 

							<div class="form-group <?php echo (!empty($PhoneNumber_err)) ? 'has-error' : ''; ?>">
								<label class="control-label col-sm-2">Phone Number</label>
								<div class="col-sm-10">
									<input type="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" name="PhoneNumber" class="form-control" value="<?php echo $PhoneNumber; ?>">
									<span class="help-block"><?php echo $PhoneNumber_err; ?></span>
								</div>
							</div>
							
							<div class="form-group <?php echo (!empty($Street_err)) ? 'has-error' : ''; ?>">
								<label class="control-label col-sm-2">Street</label>
								<div class="col-sm-10">
									<input type="text" name="Street" class="form-control" value="<?php echo $Street; ?>">
									<span class="help-block"><?php echo $Street_err; ?></span>
								</div>
							</div>

							<div class="form-group <?php echo (!empty($City_err)) ? 'has-error' : ''; ?>">
								<label class="control-label col-sm-2">City</label>
								<div class="col-sm-10">
									<input type="text" name="City" class="form-control" value="<?php echo $City; ?>">
									<span class="help-block"><?php echo $City_err; ?></span>
								</div>
							</div>

							<div class="form-group <?php echo (!empty($State_err)) ? 'has-error' : ''; ?>">
								<label class="control-label col-sm-2">State</label>
								<div class="col-sm-10">
									<input type="text" name="State" class="form-control" value="<?php echo $State; ?>">
									<span class="help-block"><?php echo $State_err; ?></span>
								</div>
							</div>

							<div class="form-group <?php echo (!empty($ZIP_err)) ? 'has-error' : ''; ?>">
								<label class="control-label col-sm-2">ZIP</label>
								<div class="col-sm-10">
									<input type="text" name="ZIP" class="form-control" value="<?php echo $ZIP; ?>">
									<span class="help-block"><?php echo $ZIP_err; ?></span>
								</div>
							</div>

							<input type="hidden" name="ClientID" value="<?php echo $ClientID; ?>">

						</div>
						<div class="modal-footer">
							<div class="form-group">
								<label class="control-label col-sm-2"></label>
								<div class="col-sm-10">
									<input type="submit" class="btn btn-primary" value="Save Changes">
									<a class="btn btn-danger" data-toggle="modal" data-target="#deleteClient">Delete Client</a>
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
							
							
						</div>
						
					</form>
				</div>
			</div>
		</div>

		<div class="modal fade" id="deleteClient" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 class="modal-title"><strong>Are you sure?</strong></h2>
					</div>
					<div class="modal-body">
						<h4>
							This will peremenenantly remove <strong><?php echo $FullName; ?></strong> from the system.
							<br><br>
							<strong>This cannot be undone.</strong>
						</h4>
					</div>
					<div class="modal-footer">
						<a class="btn btn-danger" href="remove.php?id=<?php echo $ClientID; ?>">Delete</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		

		<div class="modal fade" id="addAssignment" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<form class="form-horizontal" action="/client/assign.php" method="get">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h2 class="modal-title"><strong>Assign Asset</strong></h2>
						</div>
						<div class="modal-body">

							<input required type="hidden" name="action" value="add">
							<input required type="hidden" name="cid" value="<?php echo $ClientID; ?>">
							

							<div class="form-group">
								<label class="control-label col-sm-2">Asset</label>
								<div class="col-sm-10">
									<select required name="Asset" class="form-control">
										<?php

											if(count($assets) > 0){

												echo "								<option selected value=-1>--Select--</option>\r\n";

												foreach ($assets as $asset) {
													echo "								<option value=" . $asset["AssetID"] . ">" . $asset["Name"] . "</option>\r\n";
												}

											}

										?>
									</select>
									<span class="help-block">
										<?php 
											if(count($assets) == 0)
												print "No unassigned assets!";
										?>
									</span>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-2">Start Date</label>
								<div class="col-sm-10">
									<input required type="date" name="StartDate" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-2">End Date</label>
								<div class="col-sm-10">
									<input type="date" name="EndDate" class="form-control">
								</div>
							</div>

						</div>
						<div class="modal-footer">
							<div class="form-group">
								<label class="control-label col-sm-2"></label>
								<div class="col-sm-10">
									<input type="submit" class="btn btn-primary" value="Add">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
							
							
						</div>
						
					</form>
				</div>
			</div>
		</div>
		
		<div class="modal fade" id="addPayment" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<form class="form-horizontal" action="/client/payment.php" method="get">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h2 class="modal-title"><strong>Add Payment</strong></h2>
						</div>
						<div class="modal-body">
						
							<input required type="hidden" name="action" value="add">
							<input required type="hidden" name="cid" value="<?php echo $ClientID; ?>">

							<div class="form-group">
								<label class="control-label col-sm-2">Payment Amount</label>
								<div class="col-sm-10">
									<input required type="number" step="any" min=0 name="Amount" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-2">Date Received</label>
								<div class="col-sm-10">
									<input required type="date" name="Date" class="form-control">
								</div>
							</div>

						</div>
						<div class="modal-footer">

							<div class="form-group">
								<label class="control-label col-sm-2"></label>
								<div class="col-sm-10">
									<input type="submit" class="btn btn-primary" value="Add">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="modal fade" id="editAssignment" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<form class="form-horizontal" action="/client/assign.php" method="get">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h2 class="modal-title"><strong>Edit Assignment</strong></h2>
						</div>
						<div class="modal-body">
							
							<input required type="hidden" name="action" value="update">
							<input required type="hidden" name="aid" value="<?php echo $_GET["aid"]; ?>">
							<input required type="hidden" name="cid" value="<?php echo $ClientID; ?>">
							
							<div class="form-group">
								<label class="control-label col-sm-2">Asset</label>
								<div class="col-sm-10">
									<div class="form-control">
										<?php echo $assignments[$_GET["aid"]]["Name"]; ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-2">Start Date</label>
								<div class="col-sm-10">
									<input required type="date" name="StartDate" class="form-control" value="<?php echo $assignments[$_GET["aid"]]["StartDate"]; ?>">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-2">End Date</label>
								<div class="col-sm-10">
									<input type="date" name="EndDate" class="form-control" value="<?php echo $assignments[$_GET["aid"]]["EndDate"]; ?>">
								</div>
							</div>

						</div>
						<div class="modal-footer">
							<div class="form-group">
								<label class="control-label col-sm-2"></label>
								<div class="col-sm-10">
									<input type="submit" class="btn btn-primary" value="Update">
									<a class="btn btn-danger" data-toggle="modal" data-target="#deleteAssignment">Delete Assignment</a>
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
							
							
						</div>
						
					</form>
				</div>
			</div>
		</div>

		<div class="modal fade" id="deleteAssignment" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 class="modal-title"><strong>Are you sure?</strong></h2>
					</div>
					<div class="modal-body">
						<h4>
							This will unassign <strong><?php echo $assignments[$_GET["aid"]]["Name"]; ?></strong> from <strong><?php echo $FullName; ?></strong>
							<br><br>
							Monthly Due will be re-calculated.
						</h4>
					</div>
					<div class="modal-footer">
						<a class="btn btn-danger" href="assign.php?action=delete&aid=<?php echo $_GET["aid"]; ?>&cid=<?php echo $ClientID; ?>">Unassign</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="deletePayment" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 class="modal-title"><strong>Remove Payment</strong></h2>
					</div>
					<div class="modal-body"> 
						This will remove record of the payment made on <strong><?php echo $payments[$_GET["pid"]]["Date"]; ?></strong> in the amount of <strong>$<?php echo $payments[$_GET["pid"]]["Amount"]; ?></strong>.
						<br><br>
						This will not update <strong><?php echo $FullName; ?></strong>'s balance.
						<br><br><br>
						<strong>This cannot be undone.</strong>
						
					</div>
					<div class="modal-footer">
						<a class="btn btn-danger" href="payment.php?action=delete&pid=<?php echo $_GET["pid"]; ?>&cid=<?php echo $ClientID; ?>">Delete</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>


    </body>
	<?php 
		if (isset($_GET["aid"])) {
			print "<script type=\"text/javascript\"> $('#editAssignment').modal('show');</script>";
		}

		if (isset($_GET["pid"])) {
			print "<script type=\"text/javascript\"> $('#deletePayment').modal('show');</script>";
		}
	?>

</html>






