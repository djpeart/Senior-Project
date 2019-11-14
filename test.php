<?php  ini_set('display_errors',1); error_reporting(E_ALL);


	if($_SERVER["REQUEST_METHOD"] == "POST"){

        print var_dump($_POST);
        
	}

?>

<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
			<title>Edit Client</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
			<style type="text/css">body{ font: 14px sans-serif; }</style>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		</head>
	<body>

	
        <div class="container">
			<div class="row">
				<div class="col-sm-6">

					<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="form-group">
						    <label class="control-label col-sm-2"></label>
							    <div class="col-sm-10">
							    	<input name="action" type="submit" class="btn btn-primary" value="Update">
						    	</div>
						</div>
					</form>

                    <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="form-group">
						    <label class="control-label col-sm-2"></label>
							    <div class="col-sm-10">
							    	<input name="action" type="submit" class="btn btn-danger" value="Delete">
						    	</div>
						</div>
					</form>


				</div>
			</div>
        </div> 
    </body>
</html>






