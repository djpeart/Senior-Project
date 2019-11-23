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
			<title>Assets</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
            <style type="text/css">body{ font: 14px sans-serif; text-align: center; }</style>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		</head>
	<body>
        <?php requirePermissionLevel(1); ?>
        <div class="container">
            <br><nav class="navbar navbar-inverse">
				<div class="container-fluid">
					<div class="navbar-header">
						<div class="navbar-brand" href="">Dan's Senior Project</div>
					</div>
					<ul class="nav navbar-nav">
						<li><a href="/welcome.php">Welcome</a></li>
						<li><a href="/client">Clients</a></li>
						<li class="active"><a href="/asset/view.php">Assets</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="/account/reset-password.php"><span class="glyphicon glyphicon-user"></span> Change Password</a></li>
						<li><a href="/account/logout.php"><span class="glyphicon glyphicon-log-in"></span> Sign Out</a></li>
					</ul>
				</div>
			</nav>

            
            <h1>Assets View</h1>
            <input class="form-control" id="myInput" type="text" placeholder="Search..">
            <table class="table table-striped table-responsive ">
                <thead>
                    <tr>
                        <th class="text-center">AssetID</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Renter Name</th>
                        <th class="text-center">Start Date</th>
                        <th class="text-center">End Date</th>
                        <th class="text-center">Rented</th>
                    </tr>
                </thead>
                <tbody id="myTable">
                    <?php              

                        require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 
                        $sql = "SELECT assets.AssetID, Name, Price, FullName, StartDate, EndDate, Active FROM assets LEFT JOIN (assignments LEFT JOIN clients ON clients.ClientID = assignments.ClientID) ON assets.AssetID = assignments.AssetID";
                        if($stmt = mysqli_prepare($acclink, $sql)){
                            if(mysqli_stmt_execute($stmt)){
                                mysqli_stmt_store_result($stmt);
                                if(mysqli_stmt_num_rows($stmt) > 0){
                                    mysqli_stmt_bind_result($stmt, $AssetID, $Name, $Price, $FullName, $StartDate, $EndDate, $Active);
                                    
                                    while (mysqli_stmt_fetch($stmt)){
                                        print "                         <tr class=\"";
                                        //print ($BillDueBy <= date('Y-m-d')) ? "danger" : "";
                                        print  "\">\r\n";
                                            print "                             <td>" . $AssetID . "</td>\r\n";
                                            print "                             <td><a class=\"display: block\" href=\"edit.php?id=" . $AssetID . "\">" . $Name . "</a></td>\r\n";
                                            print "                             <td>" . $Price . "</td>\r\n";
                                            print "                             <td>" . $FullName . "</td>\r\n";
                                            print "                             <td>" . $StartDate . "</td>\r\n";
                                            print "                             <td>" . $EndDate . "</td>\r\n";
                                            print "                             <td>" . $Active . "</td>\r\n";
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

            <a class="btn btn-primary btn-block" href="add.php">Add an asset</a>
            <a class="btn btn-primary btn-block" href="remove.php">Remove an asset</a>
                
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