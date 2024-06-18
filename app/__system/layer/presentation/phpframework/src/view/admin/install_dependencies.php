<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$head = '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icon CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/install_dependencies.css" type="text/css" charset="utf-8" />'; $main_content = '<div class="install_dependencies">
	<form method="post">
		<div class="title">Install Dependencies</div>
		<div class="info">
			This framework utilizes external libraries with GPL and LGPL licenses. To access its full functionality, you should install them. Please make your choice by clicking one of the buttons below.<br/>
			We strongly recommend installing the dependencies.
		</div>
		
		<input type="submit" name="install" value="Install dependencies" />
		<input type="submit" name="continue" value="Continue without dependencies" />
	</form>
</div>'; ?>
