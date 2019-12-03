<?php ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }

    $users = array();

    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/login.php'; 
    $sql = "SELECT id, username, created_at, permlevel FROM users";
    if($stmt = mysqli_prepare($loginlink, $sql)){
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) > 0){
                mysqli_stmt_bind_result($stmt, $id, $username, $created_at, $permlevel);
                
                while (mysqli_stmt_fetch($stmt)){
                   $users[$id] = array (
                        "id" => $id, 
                        "username" => $username, 
                        "created_at" => $created_at, 
                        "permlevel" => $permlevel                
                   );
                }
            }
        }
    }

    mysqli_stmt_close($stmt);
    
?>

<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
			<title>User Management</title>
			<style type="text/css">body{ font: 14px sans-serif;}</style>
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		</head>
	<body>
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
                            <li><a href="/">Welcome</a></li>
                            <li><a href="/client">Clients</a></li>
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
                <h1>Users</h1>
            </div>

            <?php if ($_SESSION["permlevel"] < 3) {
                alert("alert-warning","Error!","You do not have permission to view this content");
                 exit;
            } ?>
            
            <br>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Username</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Permission Level</th>                            
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php              
                            foreach ($users as $user) {

                                print "\r\n                         <tr onclick=\"window.location='users.php?id=" . $user["id"] . "';\">\r\n";
                                    print "                             <th scope=\"row\">" . $user["id"] . "</th>\r\n";
                                    print "                             <td>" . $user["username"] . "</a></td>\r\n";
                                    print "                             <td>" . $user["created_at"] . "</td>\r\n";
                                    print "                             <td>" . $user["permlevel"] . "</td>\r\n";
                                print "                         </tr>\r\n";
                            }
                
                        ?>
                    </tbody>
                </table>
            </div>
            
        </div>

        <div class="modal fade" id="editPermLevel" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <form class="form-horizontal" action="/account/edit.php" method="GET">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h2 class="modal-title"><strong><?php echo htmlspecialchars($users[$_GET["id"]]["username"])?></strong></h2>
                        </div>

                        <div class="modal-body">

                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
                                
                            <div class="form-group">
                                <label class="control-label col-sm-2">Permission Level</label>
                                <div class="col-sm-10">
                                    <input type="number" min=0 name="permlevel" class="form-control" value="<?php echo isset($_GET["id"]) ? $users[$_GET["id"]]["permlevel"] : ""; ?>">
                                </div> 
                            </div>

                        </div>

                        <div class="modal-footer">
                            <div class="form-group">
                                <label class="control-label col-sm-2"></label>
                                <div class="col-sm-10">
                                    <input type="submit" class="btn btn-primary" value="Update">
                                    <a class="btn btn-danger" data-toggle="modal" data-target="#deleteUser">Delete User</a>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteUser" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title"><strong>Are you sure?</strong></h2>
                    </div>
                    <div class="modal-body">
                        <h4>
                            This will permanently remove <strong><?php echo $users[$_GET["id"]]["username"]; ?></strong> from the system.
                            <br><br>
                            <strong>This cannot be undone.</strong>
                        </h4>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-danger" href="/account/edit.php?action=delete&id=<?php echo $_GET["id"]; ?>">Delete</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <?php 
			if (isset($_GET["id"])) {
				print "<script type=\"text/javascript\"> $('#editPermLevel').modal('show');</script>";
			}
		?>
	</body>
</html>