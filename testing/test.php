<?php
	print "Hello World!";
	for($x=0;$x<10;$x++) {
		print "<p>The value is $x</p>";
	}

	$players = 
		array(
			"DUNCAN, king of Scotland" => "Larry",
			"MALCOM, son of the king" => "Curly",
			"MACBETH" => "Moe",
			"MACDUFF" => "Rafael");
	print "<pre>";

	print str_pad("Dramatis Personae", 50, " ", STR_PAD_BOTH). "\n";

	foreach($players as $role => $actor)
		print str_pad($role, 30, ".")
			. str_pad($actor, 20,".",STR_PAD_LEFT)
			. "\n";

	print "</pre>";
?>

