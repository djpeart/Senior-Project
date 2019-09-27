<?php ini_set('display_errors',1); error_reporting(E_ALL);

	class ObjectTest {
		public $a;
		public $b;
	
		function __construct($x, $y) {
			$this->a = $x;
			$this->b = $y;
		}

		//public function try() {
		//	print $this->a;
		//	print $this->b;
		//}
	}
?>

<html>
	<pre>
		<?php
			//$test = new ObjectTest(3,5)
			//var_dump(get_object_vars($test));
			print("Hello World");
		?>
		
	</pre>
</html>

