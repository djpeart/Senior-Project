<?php ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }

    $assets = array();

    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 
    $sql = "SELECT assets.AssetID, Name, Price, Active, FullName FROM assets LEFT JOIN (assignments LEFT JOIN clients ON clients.ClientID = assignments.ClientID) ON assets.AssetID = assignments.AssetID";
    if($stmt = mysqli_prepare($acclink, $sql)){
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) > 0){
                mysqli_stmt_bind_result($stmt, $AssetID, $Name, $Price, $Active, $FullName);
                
                while (mysqli_stmt_fetch($stmt)){
                   $assets[$AssetID] = array (
                        "AssetID" => $AssetID, 
                        "Name" => $Name, 
                        "Price" => $Price, 
                        "Active" => ($Active == 1) ? "Yes" : "No", 
                        "FullName" => $FullName                      
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
			<title>Assets</title>
			<style type="text/css">body{ font: 14px sans-serif;}</style>
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		</head>
	<body>
        <?php requirePermissionLevel(1); ?>
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
                            <li class="active"><a href="/asset">Assets</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="/account/reset-password.php"><span class="glyphicon glyphicon-user"></span> Change Password</a></li>
                            <li><a href="/account/logout.php"><span class="glyphicon glyphicon-log-in"></span> Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            
            <div class="text-center">
                <h1>Asset View</h1>
            </div>

            <br><br>
            <div class="row">

                <div class="col-md-2 text-center">
                    <a class="btn btn-primary btn-block" data-toggle="modal" data-target="#addAsset">Add Asset</a>
                </div>
                <div class="col-md-10">
                    <input class="form-control" id="myInput" type="text" placeholder="Search..">
                </div>
            </div><br />

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Rented</th>
                            <th scope="col">Renter Name</th>
                            
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php              
                            foreach ($assets as $asset) {

                                print "\r\n                         <tr onclick=\"window.location='index.php?aid=" . $asset["AssetID"] . "';\" class=\"";
                                
                                if ($asset["Rented"] =="Yes" && false) 
                                    print "info";

                                print  "\">\r\n";
                                    print "                             <th scope=\"row\">" . $asset["AssetID"] . "</th>\r\n";
                                    print "                             <td>" . $asset["Name"] . "</a></td>\r\n";
                                    print "                             <td>$" . $asset["Price"] . "</td>\r\n";
                                    print "                             <td>" . $asset["Active"] . "</td>\r\n";
                                    print "                             <td>" . $asset["FullName"] . "</td>\r\n";
                                print "                         </tr>\r\n";

                                
                            }
                
                        ?>
                    </tbody>
                </table>
            </div>
            
        </div>

        <div class="modal fade" id="editAsset" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <form class="form-horizontal" action="/asset/action/updateAssetRecord.php" method="GET">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h2 class="modal-title"><strong>Asset Details</strong></h2>
                        </div>

                        <div class="modal-body">

                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="aid" value="<?php echo isset($_GET["aid"]) ? $assets[$_GET["aid"]]["AssetID"] : ""; ?>">
                                
                            <div class="form-group">
                                <label class="control-label col-sm-2">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="Name" class="form-control" value="<?php echo isset($_GET["aid"]) ? $assets[$_GET["aid"]]["Name"] : ""; ?>">
                                </div> 
                            </div> 

                            <div class="form-group">
                                <label class="control-label col-sm-2">Price Per Month</label>
                                <div class="col-sm-10">
                                    <input type="number" step="any" min=0 name="Price" class="form-control" value="<?php echo isset($_GET["aid"]) ? $assets[$_GET["aid"]]["Price"] : ""; ?>">
                                </div> 
                            </div>

                        </div>

                        <div class="modal-footer">
                            <div class="form-group">
                                <label class="control-label col-sm-2"></label>
                                <div class="col-sm-10">
                                    <input type="submit" class="btn btn-primary" value="Save Changes">
                                    <a class="btn btn-danger" data-toggle="modal" data-target="#deleteAsset">Delete Asset</a>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteAsset" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title"><strong>Are you sure?</strong></h2>
                    </div>
                    <div class="modal-body">
                        <h4>
                            This will permanently remove <strong><?php echo $assets[$_GET["aid"]]["Name"]; ?></strong> from the system.
                            <br><br>
                            <strong>This cannot be undone.</strong>
                        </h4>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-danger" href="/asset/action/updateAssetRecord.php?action=delete&aid=<?php echo $assets[$_GET["aid"]]["AssetID"]; ?>">Delete</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addAsset" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<form class="form-horizontal" action="/asset/action/updateAssetRecord.php" method="get">

						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h2 class="modal-title"><strong>Add Asset</strong></h2>
						</div>

                        <div class="modal-body">

                            <input required type="hidden" name="action" value="add">

                            <div class="form-group">
                                <label class="control-label col-sm-2">Name</label>
                                 <div class="col-sm-10">
                                    <input type="text" name="Name" class="form-control">
                                </div> 
                            </div> 

                            <div class="form-group">
                                <label class="control-label col-sm-2">Price Per Month</label>
                                <div class="col-sm-10">
                                    <input type="number" step="any" min=0 name="Price" class="form-control">
                                </div> 
                            </div>
                        
                        </div>

                        <div class="modal-footer">

                            <div class="form-group">
                                <label class="control-label col-sm-2"></label>
                                <div class="col-sm-10">
                                    <input type="submit" class="btn btn-primary" value="Add Asset">
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

        <?php 
			if (isset($_GET["aid"])) {
				print "<script type=\"text/javascript\"> $('#editAsset').modal('show');</script>";
			}
		?>
	</body>
</html>