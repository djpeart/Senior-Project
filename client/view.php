<?php ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }

    $clients = array();
    
    // Include config file
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
                <h1>Accounts Receivable</h1>
            </div>
            
            <br><br>
            <div class="row">

                <div class="col-md-2">
                    <a class="btn btn-primary btn-block" href="add.php">Add a client</a>
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

                                    print "\r\n                         <tr onclick=\"window.location='\\edit.php?id=" . $client["ClientID"] . "';\" class=\"";

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