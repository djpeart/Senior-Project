<?php ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }
    

?>

<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
			<title>View Client</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
            <style type="text/css">body{text-align: center; }</style>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		</head>
	<body>
        <?php requirePermissionLevel(1); ?>
        <div class="container"><br>
            
            <nav class="navbar navbar-inverse">
				<div class="container-fluid">
					<div class="navbar-header">
						<div class="navbar-brand" href="">Dan's Senior Project</div>
					</div>
					<ul class="nav navbar-nav">
						<li><a href="/welcome.php">Welcome</a></li>
						<li class="active"><a href="/client/view.php">Clients</a></li>
						<li><a href="/asset/view.php">Assets</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="account/reset-password.php"><span class="glyphicon glyphicon-user"></span> Change Password</a></li>
						<li><a href="account/logout.php"><span class="glyphicon glyphicon-log-in"></span> Sign Out</a></li>
					</ul>
				</div>
			</nav>

            <h1>Accounts Receivable</h1>
            Add Amount due
            Subtract the amount instead of add
            <input class="form-control" id="myInput" type="text" placeholder="Search..">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="text-align: center">ClientID</th>
                                <th style="text-align: center">Name</th>
                                <th style="text-align: center">Phone Number</th>
                                <th style="text-align: center">Street</th>
                                <th style="text-align: center">City</th>
                                <th style="text-align: center">State</th>
                                <th style="text-align: center">ZIP</th>
                                <th style="text-align: center">Balance</th>
                                <th style="text-align: center">Due Date</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php 
                                
                                // Include config file
                                require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 
                                $sql = "SELECT ClientID, FullName, PhoneNumber, Street, City, State, ZIP, clients.Balance, total FROM clients LEFT JOIN billing on ClientID = client";
                                if($stmt = mysqli_prepare($acclink, $sql)){
                                    if(mysqli_stmt_execute($stmt)){
                                        mysqli_stmt_store_result($stmt);
                                        if(mysqli_stmt_num_rows($stmt) > 0){
                                            mysqli_stmt_bind_result($stmt, $ClientID, $FullName, $PhoneNumber, $Street, $City, $State, $ZIP, $Balance, $total);

                                            while (mysqli_stmt_fetch($stmt)){
                                                print "                         <tr class=\"";
                                                print ($total > 0) ? "danger" : "";
                                                print  "\">\r\n";
                                                    print "                             <td>" . $ClientID . "</td>\r\n";
                                                    print "                             <td><a class=\"display: block\" href=\"edit.php?id=" . $ClientID . "\">" . $FullName . "</a></td>\r\n";
                                                    print "                             <td>" . $PhoneNumber . "</td>\r\n";
                                                    print "                             <td>" . $Street . "</td>\r\n";
                                                    print "                             <td>" . $City . "</td>\r\n";
                                                    print "                             <td>" . $State . "</td>\r\n";
                                                    print "                             <td>" . $ZIP . "</td>\r\n";
                                                    print "                             <td>" . $Balance . "</td>\r\n";
                                                    print "                             <td>" . $total . "</td>\r\n";
                                                print "                         </tr>\r\n";
                                            }
                                        }
                                    }
                                }
                                
                                mysqli_stmt_close($stmt);
                                mysqli_close($acclink); 
                            ?>
                        </tbody>
                    </table>
                </div>
             <a class="btn btn-primary btn-block" href="add.php">Add a client</a>
            <a class="btn btn-primary btn-block" href="remove.php">Remove a client</a>
            <a class="btn btn-primary btn-block" href="payment.php">Make Payment</a>
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