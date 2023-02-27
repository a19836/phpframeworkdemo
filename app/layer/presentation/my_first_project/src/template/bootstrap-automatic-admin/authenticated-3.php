<?php 
//TEMPLATE PARAMS:
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Page Keywords", "Admin Panel");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Page Description", "Automatic Admin Panel Template");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Icon Url", "{$original_project_url_prefix}template/bootstrap-automatic-admin/img/favicon.ico");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Header Title", "Admin Panel");

$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Admin Url", $admin_url ? $admin_url : "#");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Profile Url", $edit_profile_url ? $edit_profile_url : null);
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Users Url", $list_users_url ? $list_users_url : null);
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Logout Url", $logout_url ? $logout_url : "#");
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

	<?php include_once $EVC->getTemplatePath("bootstrap-automatic-admin/lib/head"); ?> 
	
	<!-- Custom styles for this template -->
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/css/authenticated-3.css" rel="stylesheet">
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/css/authenticated.css" rel="stylesheet">

	<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Head"); ?>
</head>
<body>

  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading">
        <i class="fas fa-laugh-wink mr-2"></i> <?= translateProjectLabel($EVC, $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Header Title")); ?>
      </div>
      <div class="list-group list-group-flush">
        <a href="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Admin Url"); ?>" class="list-group-item list-group-item-action bg-light" style="opacity:.8"><i class="fas fa-fw fa-tachometer-alt"></i> Dashboard</a>
      </div>
      
      <div class="list-group list-group-flush bg-light">
        <?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Sub Menu"); ?>
      </div>
      
 	 <!-- Footer -->
      <footer class="py-4 bg-light mt-auto">
         <div class="container-fluid">
	         <div class="d-flex align-items-center justify-content-between small">
	             <div class="text-muted m-auto">Copyright &copy; Your Website 2019</div>
	         </div>
         </div>
      </footer>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-light bg-light border-bottom align-items-center">
        <!-- Sidebar Toggle (Topbar) -->
        <!--button class="btn btn-primary" id="menu-toggle">Toggle Menu</button-->
	   <button class="btn btn-link border p-1 pl-2 pr-2 mr-auto" id="menu-toggle">
          <i class="fa fa-arrow-left text-secondary m-0 mt-1"></i>
        </button>
        
        <?php if ($user_data) { ?>
		<!-- Nav Item - User Information -->
		<div class="nav-item dropdown">
			<a class="nav-link dropdown-toggle text-secondary" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<small class="mr-2 text-wrap">Logged in as: <?php echo $user_data["name"] ? $user_data["name"] : $user_data["username"]; ?></small>
				<i class="fas fa-user fa-fw"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-right animated--grow-in mt-2 p-0" aria-labelledby="userDropdown">
			    <?
				$profile_url = $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Profile Url"); 
				$users_url = $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Users Url"); 
				
				if ($profile_url) 
					echo '
				    <a class="dropdown-item pt-2 pb-2" href="' . $profile_url . '">
					   <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
					   Profile
				    </a>';
			   
			    if ($users_url) 
					echo '
				    <a class="dropdown-item pt-2 pb-2" href="' . $users_url . '">
					   <i class="fas fa-users fa-sm fa-fw mr-2 text-gray-400"></i>
					   Manage Users
				    </a>';
			   	
				if ($profile_url || $users_url)
					echo '<div class="dropdown-divider m-0"></div>';
				?>
			    <a class="dropdown-item pt-2 pb-2" href="#" data-toggle="modal" data-target="#logoutModal">
				   <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
				   Logout
			    </a>
			</div>
		</div>
        <?php } ?>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0 text-right" id="sidenavAccordion">
			<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Menu"); ?>
         	</ul>
        </div>
      </nav>

      <div class="container-fluid pt-4 pb-4 admin-page-content">
      	<small>
		    <ol class="breadcrumb mb-4">
			    	<?php
			    		include_once $EVC->getTemplatePath("bootstrap-automatic-admin/lib/utils");
			    		showBreadcrumbs("{$project_url_prefix}$entity", $project_url_prefix, $admin_url);
			    	?>
		    		<li class="go-back">
					<a href="javascript:void(0)" onClick="MyJSLib.BrowserHistoryHandler.goBack()"><i class="fa fa-undo"></i> Go Back</a>
				</li>
		    </ol>
        	</small>
        	
        	<h1 class="h3 mb-4"><?= translateProjectLabel($EVC, $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Title")); ?></h1>
         
        	<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Content"); ?>
      </div>
    </div>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Logout Url"); ?>">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <?php include_once $EVC->getTemplatePath("bootstrap-automatic-admin/lib/foot"); ?> 

  <!-- Custom scripts for all pages-->
  <script src="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/js/authenticated-3.js"></script>
  <script src="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/js/pos.js"></script>
</body>

</html>
