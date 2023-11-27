<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	<meta name="keywords" content="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Keywords"); ?>" />
    	<meta name="description" content="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Description"); ?>" />
    	<meta name="author" content="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Author"); ?>">
    	<link href="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Icon Url"); ?>" rel="shortcut icon" type="image/x-icon" />
    
    	<title><?= translateProjectLabel($EVC, $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Title")); ?></title>
    	
    	<link href="<?php echo $original_project_url_prefix; ?>vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	    <script src="<?php echo $original_project_url_prefix; ?>vendor/bootstrap/js/bootstrap.min.js"></script>
		
		<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Head"); ?>
	</head>
	<body>
	<? echo $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Content"); ?>
	</body>
</html>
