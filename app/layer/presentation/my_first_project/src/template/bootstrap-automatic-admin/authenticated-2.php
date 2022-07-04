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
	
	<!-- Custom styles for this template-->
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/css/authenticated-2.css" rel="stylesheet">
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/css/authenticated.css" rel="stylesheet">

	<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Head"); ?>
</head>
<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="sidenavAccordion">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Admin Url"); ?>">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3"> <?= translateProjectLabel($EVC, $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Header Title")); ?></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Admin Url"); ?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">
   	
   	 <?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Menu"); ?>
	 
      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php if ($user_data) { ?>
		   <!-- Topbar -->
		   <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

		     <!-- Sidebar Toggle (Topbar) -->
		     <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
		       <i class="fa fa-bars"></i>
		     </button>
		
		     <!-- Topbar Navbar -->
		     <ul class="navbar-nav ml-auto">
		       <!-- Nav Item - User Information -->
		       <li class="nav-item dropdown">
		          <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		          	<small class="mr-2 text-wrap">Logged in as: <?php echo $user_data["name"] ? $user_data["name"] : $user_data["username"]; ?></small>
		          	<i class="fas fa-user fa-fw"></i>
		          </a>
		          <div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="userDropdown">
		              <?
					$profile_url = $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Profile Url"); 
					$users_url = $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Users Url"); 
					
					if ($profile_url) 
						echo '
					    <a class="dropdown-item" href="' . $profile_url . '">
						   <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
						   Profile
					    </a>';
				   
				    if ($users_url) 
						echo '
					    <a class="dropdown-item" href="' . $users_url . '">
						   <i class="fas fa-users fa-sm fa-fw mr-2 text-gray-400"></i>
						   Manage Users
					    </a>';
				   	
					if ($profile_url || $users_url)
						echo '<div class="dropdown-divider"></div>';
					?>
				    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
				        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
				        Logout
				    </a>
		          </div>
		      </li>
		     </ul>

		   </nav>
		   <!-- End of Topbar -->
		<?php } ?>
        
  	   <!-- Begin Sub Menu -->
        <div class="sb-topnav-menu">
		   <ul class="nav bg-gradient-primary">
		   	<? echo $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Sub Menu"); ?>
		   </ul>
	   </div>

        <!-- Begin Page Content -->
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
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white mt-3">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2019</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

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
  <script src="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/js/authenticated-2.js"></script>
  <script src="<?php echo $original_project_url_prefix; ?>template/bootstrap-automatic-admin/js/pos.js"></script>
</body>

</html>
