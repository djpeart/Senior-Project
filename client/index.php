<?php ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }

    $clients = array();
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 
    $sql = "SELECT ClientID, FullName, PhoneNumber, Street, City, State, ZIP, MonthlyPrice, MonthlyPrice-Balance, DueDate FROM clients";
    if($stmt = mysqli_prepare($acclink, $sql)){
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) > 0){
                mysqli_stmt_bind_result($stmt, $ClientID, $FullName, $PhoneNumber, $Street, $City, $State, $ZIP, $MonthlyPrice, $Balance, $DueDate);

                while(mysqli_stmt_fetch($stmt)){
                    $clients[count($clients)+1] = array (
                        "ClientID" => $ClientID,
                        "FullName" => $FullName, 
                        "PhoneNumber" => $PhoneNumber, 
                        "Street" => $Street, 
                        "City" => $City, 
                        "State" => $State, 
                        "ZIP" => $ZIP, 
                        "MonthlyPrice" => $MonthlyPrice, 
                        "Balance" => $Balance, 
                        "DueDate" => $DueDate
                    );
                }
            }
        }
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($acclink); 
?>

<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
			<title>View Client</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
            <style type="text/css">body{ font: 14px sans-serif;}</style>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		</head>
	<body>
        <?php requirePermissionLevel(1); ?>

        <div class="container"><br>

            <nav class="navbar navbar-inverse">
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
                            <li class="active"><a href="/client">Clients</a></li>
                            <li><a href="/asset">Assets</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="/account/reset-password.php"><span class="glyphicon glyphicon-user"></span> Change Password</a></li>
                            <li><a href="/account/logout.php"><span class="glyphicon glyphicon-log-in"></span> Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            
           <div class="text-center">
                <h1>Accounts Receivable</h1>
            </div>
            
            <br><br>
            <div class="row">

                <div class="col-md-2 text-center">
                    <a class="btn btn-primary btn-block" data-toggle="modal" data-target="#addClient">Add Client</a>
                </div>
                <div class="col-md-10">
                    <input class="form-control" id="myInput" type="text" placeholder="Search..">
                </div>
            </div><br />
            
            <div class="table-responsive">
                <table class="table table-hover ">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Address</th>
                            <th scope="col">Monthly Due</th>
                            <th scope="col">Balance</th>
                            <th scope="col">Due Date</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php 
                            foreach ($clients as $client) {

                                print "\r\n                         <tr onclick=\"window.location='view.php?id=" . $client["ClientID"] . "';\" class=\"";

                                $diff = date_diff(date_create($client["DueDate"]),date_create(date("Y-m-d")))->format("%a");
                                if ($client["DueDate"] > date("Y-m-d")) 
                                    $diff = -$diff;
                                
                                if ($client["Balance"] > 0) {
                                    if ($diff >= 0)
                                        print "danger";
                                    else if ($diff >= -10)
                                        print "warning";
                                } else if (($client["Balance"] <= 0) && ($client["MonthlyPrice"] > 0)) {
                                    print "success";
                                }
                                

                                print  "\">\r\n";
                                    print "                             <th scope=\"row\">" . $client["ClientID"] . "</th>\r\n";
                                    print "                             <td>" . $client["FullName"] . "</a></td>\r\n";
                                    print "                             <td>" . $client["PhoneNumber"] . "</td>\r\n";
                                    print "                             <td>" . $client["Street"] . " " . $client["City"] . " " . $client["State"] . " " . $client["ZIP"] . "</td>\r\n";
                                    print "                             <td>$" . $client["MonthlyPrice"] . "</td>\r\n";
                                    print "                             <td>$" . $client["Balance"] . "</td>\r\n";
                                    print "                             <td>" . $client["DueDate"] . "</td>\r\n";
                                print "                         </tr>\r\n";
                            }
                            
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="addClient" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<form class="form-horizontal" action="/client/action/updateClientRecord.php" method="get">

						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h2 class="modal-title"><strong>Add Client</strong></h2>
						</div>

						<div class="modal-body">

                            <input required type="hidden" name="action" value="add">
							
							<div class="form-group">
								<label class="control-label col-sm-2">Full Name</label>
								<div class="col-sm-10">
									<input required type="text" name="FullName" class="form-control">
								</div>
							</div> 

							<div class="form-group">
								<label class="control-label col-sm-2">Phone Number</label>
								<div class="col-sm-10">
									<input required type="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" name="PhoneNumber" placeholder="012-345-6789" class="form-control">
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-sm-2">Street</label>
								<div class="col-sm-10">
									<input required type="text" name="Street" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-2">City</label>
								<div class="col-sm-10">
									<input required type="text" name="City" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-2">State</label>
								<div class="col-sm-10">
									<input required type="text" name="State" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-2">ZIP</label>
								<div class="col-sm-10">
									<input required type="text" name="ZIP" class="form-control">
								</div>
							</div>

						</div>

						<div class="modal-footer">

							<div class="form-group">
								<label class="control-label col-sm-2"></label>
								<div class="col-sm-10">
									<input type="submit" class="btn btn-primary" value="Add Client">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>

						</div>

					</form>
				</div>
			</div>
		</div>

        <script>
            $(document).ready(function(){
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
	</body>
</html>