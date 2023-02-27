<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Description"); ?>">
		<meta name="author" content="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Author"); ?>">
		<link rel="icon" href="data:;base64,=" />

		<title><?= translateProjectLabel($EVC, $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Title")); ?></title>

		<?php include_once $EVC->getTemplatePath("bootstrap-automatic-admin/lib/head"); ?> 
		
		<!-- Custom styles for this template-->
		<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/css/non-authenticated-1.css" rel="stylesheet">
		<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/css/non-authenticated.css" rel="stylesheet">

		<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Head"); ?>
    </head>
    <body class="bg-dark">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7 admin-page-content">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header">
                                    	<h3 class="text-center font-weight-light my-4"><?= translateProjectLabel($EVC, $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Title")); ?></h3>
                                    </div>
                                    <div class="card-body">
            				  		<? echo $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Content"); ?>
                                    </div>
                                    <div class="card-footer text-center">
                                        <?= $login_url ? '<a class="d-block mb-3" href="' . $login_url . '">Login</a>' : '' ?>
                                        <div class="small">Copyright &copy; Your Website 2019</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
	   
		<?php include_once $EVC->getTemplatePath("bootstrap-automatic-admin/lib/foot"); ?> 
        	
		<script src="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/js/non-authenticated.js"></script>
    </body>
</html>
