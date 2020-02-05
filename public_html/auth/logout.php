
<?php
if(isset($_POST["logout"])) {
	$_SESSION["user_id"] = "";
	session_destroy();
}
?>
