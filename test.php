<?php

	echo "<pre>";
	print_r($_FILES);
	print_r($GLOBALS);
	echo "</pre>";

?>


<form action="test.php" method="post">

	<input type="file" name="file" />
	<input type="submit" />

</form>