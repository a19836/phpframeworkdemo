<?php
if (isset($error_message) && $error_message) {
	echo "<div id=\"error_message\">$error_message</div>
	<script>setTimeout(\"document.getElementById('error_message').style.display='none'\", 5000);</script>";
}

if (isset($status_message) && $status_message) {
	echo "<div id=\"status_message\">$status_message</div>
	<script>setTimeout(\"document.getElementById('status_message').style.display='none'\", 5000);</script>";
}
?>
