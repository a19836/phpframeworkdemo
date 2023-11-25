<?php 

//Template params:
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Page Keywords", "Admin Panel");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Page Description", "Company Name - Automatic Admin Panel Template");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Icon Url", "{$original_project_url_prefix}template/bootstrap-stock-manager/img/favicon.png");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Template Color", "#000");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<meta name="keywords" content="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Keywords"); ?>" />
	<meta name="description" content="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Description"); ?>">
	<meta name="author" content="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Author"); ?>"> 
	<link href="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Icon Url"); ?>" rel="shortcut icon" type="image/x-icon" />
		
	<title><?= translateProjectLabel($EVC, $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Title")); ?></title>
		
	<?php include_once $EVC->getTemplatePath("bootstrap-stock-manager/lib/head"); ?>
	
	<!-- Custom styles for this template-->
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/css/non-authenticated-1.css" rel="stylesheet">
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/css/non-authenticated.css" rel="stylesheet">
	
	<style>
	    :root {
		  --template-color:<?php echo $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Template Color"); ?>;
	    }
	    .template-bg {
		   background-color:var(--template-color) !important;
	    }
	</style>

	<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Head"); ?>
</head>
<body class="template-bg">
	<div id="layoutAuthentication">
		<div id="layoutAuthentication_content">
			<main>
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-7 col-lg-7 admin-page-content">
							<div class="card shadow-lg border-0 rounded-lg mt-5 mb-5">
								<div class="card-header">
									<img class="casa-animais-logo d-block ml-auto mr-auto mt-3 mb-4" src="<?= $original_project_url_prefix ?>template/bootstrap-stock-manager/img/client_logo.png" width="120"/>
									<h5 class="text-center font-weight-light mb-0">Company Name</h5>
									<p class="text-center mb-0"><?= translateProjectLabel($EVC, $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Title"));?></p>
								</div>
								<div class="card-body">
									<? echo $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Content"); ?>
								</div>
								<div class="card-footer text-center">
									<?= $login_url ? '<a class="d-block mb-3" href="' . $login_url . '">Login</a>' : '' ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</main>
		</div>
	</div>
	
	<?php include_once $EVC->getTemplatePath("bootstrap-stock-manager/lib/foot"); ?>
	
	<!-- Custom scripts for all pages-->
	<script src="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/js/non-authenticated.js"></script>
	<!--script src="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/js/pos.js"></script-->
</body>
</html>
