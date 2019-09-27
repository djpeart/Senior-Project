<html>
	<pre>
		<?php ini_set('display_errors',1); error_reporting(E_ALL);
			$conn = mysqli_connect("localhost", "php", "whatsapassword", "accounting");
			if ($conn->connect_error) {
				die("Connection Failed: " . $conn->connect_error);
			}

			$result = $conn->query("select * from clients");

			if ($result->num_rows > 0) {
				echo "<br>"
					. str_pad("ClientID",10)
					. str_pad("Name", 32)
					. str_pad("Phone Number", 16)
					. str_pad("Street", 32)
					. str_pad("City", 16)
					. str_pad("State", 6)
					. str_pad("ZIP", 6)
					. str_pad("Balance", 7)
					. "<br>";
				while ($row = $result->fetch_assoc()) {
					echo "<br>" 
					. str_pad($row["ClientID"],10)
					. str_pad($row["FullName"], 32)
					. str_pad($row["PhoneNumber"], 16)
					. str_pad($row["Street"], 32)
					. str_pad($row["City"], 16)
					. str_pad($row["State"], 6)
					. str_pad($row["ZIP"], 6)
					. str_pad("$" . $row["Balance"], 7);
				}
			} else {
				echo "0 results";
			}
			echo "<br>";
			$conn->close();
		?>
	</pre>
<html>

