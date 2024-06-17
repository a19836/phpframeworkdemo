<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
?><html>
<head>
	<title> 500 Server Error</title>
</head>
<body style="text-align:center; background-color:#F7F7F7; ">
<h2>Sorry... Server Error!</h2>
<h2>Server Error - The server detected a syntax error in the client's request...</h2>
<?php
$ip = getenv ("REMOTE_ADDR"); $requri = getenv ("REQUEST_URI"); $servname = getenv ("SERVER_NAME"); $combine = "IP: <b>" . $ip . "</b> tried to load <b>http://" . $servname . $requri . "</b>"; $httpref = getenv ("HTTP_REFERER"); $httpagent = getenv ("HTTP_USER_AGENT"); $today = date("D M j Y g:i:s a T"); $message = "($today) \n
<br><br>
$combine, with the following navigator:<br> \n
User Agent = $httpagent \n<br> \n
Requested File = $requested_file \n
<h2> $note </h2>\n"; echo $message; $EVC->setTemplate("empty"); ?>
</body>
</html>
