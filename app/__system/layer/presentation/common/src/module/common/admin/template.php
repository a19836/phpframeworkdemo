<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<link rel="stylesheet" href="<?php echo $project_common_url_prefix; ?>css/global.css" type="text/css" charset="utf-8" />
	
	<script type="text/javascript" src="<?php echo $project_common_url_prefix; ?>vendor/json/js/json2.js"></script>
	<script type="text/javascript" src="<?php echo $project_common_url_prefix; ?>vendor/jquery/js/jquery-1.8.1.min.js"></script>
	<script type="text/javascript" src="<?php echo $project_common_url_prefix; ?>vendor/jquery/js/jquery.center.js"></script>
	
	<!-- Add Jquery UI JS and CSS files -->
	<link rel="stylesheet" href="<?php echo $project_common_url_prefix; ?>vendor/jqueryui/css/jquery-ui-1.11.4.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="<?php echo $project_common_url_prefix; ?>vendor/jqueryui/js/jquery-ui-1.11.4.min.js"></script>
	
	<script type="text/javascript" src="<?php echo $project_common_url_prefix; ?>js/MyJSLib.js"></script>
	
	<!-- Add SimpleDropDown main JS and CSS files -->
	<link rel="stylesheet" href="<?php echo $project_common_url_prefix; ?>vendor/jquerysimpledropdowns/css/style.css" type="text/css" media="screen, projection"/>
	<!--[if lte IE 7]>
		<link rel="stylesheet" type="text/css" href="<?php echo $project_common_url_prefix; ?>vendor/jquerysimpledropdowns/css/ie.css" media="screen" />
	<![endif]-->
	<script type="text/javascript" src="<?php echo $project_common_url_prefix; ?>vendor/jquerysimpledropdowns/js/jquery.dropdownPlain.js"></script>
	
	<!-- Fancy LighBox -->
	<link rel="stylesheet" href="<?php echo $project_common_url_prefix; ?>vendor/jquerymyfancylightbox/css/style.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="<?php echo $project_common_url_prefix; ?>vendor/jquerymyfancylightbox/js/jquery.myfancybox.js"></script>
	
	<!-- Message -->
	<link rel="stylesheet" href="<?php echo $project_common_url_prefix; ?>vendor/jquerymystatusmessage/css/style.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="<?php echo $project_common_url_prefix; ?>vendor/jquerymystatusmessage/js/statusmessage.js"></script>
	
	<!-- Local CSS and JS -->
	<link rel="stylesheet" href="<?php echo $project_common_url_prefix; ?>module/common/module.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="<?php echo $project_common_url_prefix; ?>module/common/module.js"></script>
	
	<link rel="stylesheet" href="<?php echo $project_common_url_prefix; ?>module/common/admin.css" type="text/css" charset="utf-8" />

	<?= $head;?>
</head>
<body>
	<div class="main_content"><? echo $main_content; ?></div>
</body>
</html>

