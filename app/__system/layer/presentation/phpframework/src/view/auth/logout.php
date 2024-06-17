<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$head = '<style>
body:not(.in_popup),
  body:not(.in_popup) #main_column {
	background:#DFE1ED;
}
h1 {width:100%; text-align:center; font-size:14px; font-weight:bold; margin-top:100px;}
h2 {width:100%; text-align:center; margin-top:10px;}
</style>'; $main_content = '<h1>You are logged OUT!</h1>
<h2>To login again click <a href="' . $project_url_prefix . 'auth/login">here</a>...</h2>'; ?>
