	<!-- 
		JQUERY script must be called here in the head tag, bc this is used by the modules, like in the article module. If this is loaded in the foot.php, the browser will return a javascript error, bc some modules are using jquery and the jquery.js file was not loaded yet... 
		The samething happens to jquery-ui library!
	-->
	
	<!-- Jquery core JavaScript-->
	<script src="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/vendor/jquery/jquery.min.js"></script>

	<!-- Add Jquery UI JS and CSS files -->
	<link rel="stylesheet" href="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/vendor/jquery-ui/jquery-ui.min.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/vendor/jquery-ui/jquery-ui.min.js"></script>
	
	<!-- Bootstrap core CSS -->
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom fonts for this template-->
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/vendor/fonts/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/vendor/fonts/google/google-fonts.css" rel="stylesheet">
	
	<!-- Fancy LighBox -->
	<link rel="stylesheet" href="<?= $project_common_url_prefix;?>vendor/jquerymyfancylightbox/css/style.css" type="text/css" charset="utf-8" />
	
	<!-- TimePicker -->
	<link rel="stylesheet" media="all" type="text/css" href="<?= $project_common_url_prefix;?>vendor/jquerytimepickeraddon/dist/jquery-ui-timepicker-addon.min.css" />
	
   	<!-- Global script with some native javascript functions -->
	<script src="<?php echo $project_common_url_prefix; ?>js/global.js"></script>
	
	<!-- MyJSLib --> <!-- Must be in the head bc the automatic UIs will call the MyJSLib.FormHandler.initForm() method inside of the body html -->
	<script type="text/javascript" src="<?php echo $project_common_url_prefix; ?>js/MyJSLib.js"></script>
	
	
