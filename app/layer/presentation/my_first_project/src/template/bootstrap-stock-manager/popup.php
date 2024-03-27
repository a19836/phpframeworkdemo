<?php 
//TEMPLATE PARAMS:
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Page Keywords", "Admin Panel");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Page Description", "Automatic Admin Panel Template");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Icon Url", "{$original_project_url_prefix}template/bootstrap-stock-manager/img/favicon.ico");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Header Title", "Admin Panel");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php
		if (!$GLOBALS["UserSessionActivitiesHandler"]) {
			include_once $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());
			initUserSessionActivitiesHandler($EVC);
		}
		
		if ($GLOBALS["UserSessionActivitiesHandler"])
			$user_data = $GLOBALS["UserSessionActivitiesHandler"]->getUserData();
	?>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="keywords" content="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Keywords"); ?>" />
	<meta name="description" content="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Description"); ?>" />
	<meta name="author" content="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Author"); ?>">
	<link href="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Icon Url"); ?>" rel="shortcut icon" type="image/x-icon" />

	<title><?= translateProjectLabel($EVC, $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Title")); ?></title>

	<?php include_once $EVC->getTemplatePath("bootstrap-stock-manager/lib/head"); ?>
	
	<!-- Custom styles for this template-->
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/css/authenticated.css" rel="stylesheet">
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/css/authenticated-style.css" rel="stylesheet">
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/css/popup.css" rel="stylesheet">
	
	<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Head"); ?>
</head>
<body class="">
	<div id="layoutSidenav">
	  <div id="layoutSidenav_content">
		 <main>
		     <div class="container-fluid pt-4 pb-4 admin-page-content">
			    <h1 class="h3 mb-4"><?= translateProjectLabel($EVC, $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Title")); ?></h1>
			    
			    <?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Content"); ?>
		     </div>
		 </main>
		 
	  </div>
	</div>
	
	<?php include_once $EVC->getTemplatePath("bootstrap-stock-manager/lib/foot"); ?>
	
	<!-- Custom scripts for all pages-->
	<script src="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/js/authenticated.js"></script>
	<!--script src="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/js/pos.js"></script-->
</body>
</html>
