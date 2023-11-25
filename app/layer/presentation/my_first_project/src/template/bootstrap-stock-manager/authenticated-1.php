<?php
//Template params:
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Page Keywords", "Admin Panel");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Page Description", "Automatic Admin Panel Template");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Icon Url", "{$original_project_url_prefix}template/bootstrap-stock-manager/img/favicon.png");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Profile Url", $edit_profile_url ? $edit_profile_url : null);
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Users Url", $list_users_url ? $list_users_url : null);
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Admin Url", $admin_url ? $admin_url : "{$project_url_prefix}");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Logout Url", $logout_url ? $logout_url : "#");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Template Background Color", "#000");
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Template Text Color", "#fff");
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
		
		$user_type_ids_classes = $user_data && $user_data["user_type_ids"] ? "user_type_id_" . implode(" user_type_id_", $user_data["user_type_ids"]) : "";
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
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/css/authenticated-1.css" rel="stylesheet">
	<link href="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/css/authenticated.css" rel="stylesheet">
	
	<style>
	    :root {
		  --template-bg-color:<?php echo $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Template Background Color"); ?>;
	   	  --template-text-color:<?php echo $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Template Text Color"); ?>;
	    }
	    .template-color {
		   background-color:var(--template-bg-color) !important;
		   color:var(--template-text-color) !important;
	    }
	</style>

	<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Head"); ?>
</head>
<body class="sb-nav-fixed">
	<nav class="sb-topnav navbar navbar-expand navbar-light bg-white">
		<button class="btn btn-link btn-lg" id="sidebarToggle" href="#">
			<i class="fas fa-bars"></i>
		</button>
		<img class="client-logo h-100" src="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/img/client_logo_small.png"/>
		<!-- Navbar-->
		<ul class="navbar-nav ml-auto mr-0">
			<li class="nav-item dropdown notifications-dropdown">
				<a class="nav-link" id="notificationsDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-bell"></i>
					<!--sup><span class="tag badge badge-pill badge-danger small up">3</span></sup-->
				</a>
				<div class="dropdown-menu dropdown-menu-end animated--grow-in mt-2 pb-0 bg-light" aria-labelledby="notificationsDropdown">
					<div class="dropdown-menu-header p-3" role="presentation">
						<h5 class="small d-inline text-uppercase">
							<?= translateProjectText($EVC, "Notifications");
							?>
						</h5>
						<!--span class="tag badge badge-danger float-right">3</span-->
					</div>
					<div class="list-group bg-white" role="presentation">
						<div data-role="container">
							<ul class="list-group border-0 small" data-widget-list-tree data-widget-props="{&quot;load&quot;:&quot;MyWidgetResourceLib.ListHandler.loadListTableAndTreeResource&quot;,&quot;end&quot;:{&quot;load&quot;:&quot;hideLoadingBar&quot;}}" data-widget-resources="{&quot;load&quot;:[{&quot;name&quot;:&quot;notifications&quot;}]}">
								<!-- Note that if you wish to active the Notifications resource you must add the data-widget-resources-load attribute to the [data-widget-list-tree] html element. -->
								<li class="list-group-item" data-widget-item>
									<div class="p-0 m-0" data-widget-item-attribute-field-view data-widget-resource-value="{&quot;attribute&quot;:&quot;name&quot;}"></div>
									<div class="small text-muted p-0 m-0" data-widget-item-attribute-field-view data-widget-resource-value="{&quot;attribute&quot;:&quot;description&quot;}"></div>
								</li>
							</ul>
						</div></div>
				</div>
			</li>
			<?php
			if ($user_data) {
				?>
			<li class="nav-item dropdown ml-auto">
				<a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-user fa-fw"></i>
				</a>
				<div class="dropdown-menu dropdown-menu-end animated--grow-in mt-2" aria-labelledby="userDropdown">
					<?
					$profile_url = $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Profile Url");
					$users_url = $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Users Url");
					
					if ($profile_url)
					echo '<a class="dropdown-item" href="' . $profile_url . '"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>' . translateProjectLabel($EVC, "Profile") . '</a>';
					
					if ($users_url)
					echo '<a class="dropdown-item" href="' . $users_url . '"><i class="fas fa-users fa-sm fa-fw mr-2 text-gray-400"></i>' . translateProjectLabel($EVC, "Manage Users") . '</a>';
					
					if ($profile_url || $users_url)
					echo '<div class="dropdown-divider"></div>';
					?>
					<a class="dropdown-item" href="#logoutModal" data-bs-toggle="modal">
						<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
						<?= translateProjectLabel($EVC, "Logout");
						?>
					</a>
				</div>
			</li>
			<?php
			}
			?>
		</ul>
	</nav>
	<div id="layoutSidenav">
		<div id="layoutSidenav_nav">
			<nav class="sb-sidenav accordion template-color" id="sidenavAccordion">
				<img class="bloxtor-logo" src="<?= $original_project_url_prefix ?>template/bootstrap-stock-manager/img/bloxtor_logo_white.svg"/>
				<div class="sb-sidenav-menu">
					<ul class="nav">
						<!-- Nav Item - Dashboard -->
						<li class="nav-item">
							<a class="nav-link" href="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Admin Url"); ?>">
								<span class="sb-nav-link-icon">
									<i class="fas fa-fw fa-tachometer-alt mr-1"></i>
								</span>
								<span>
									<small>Dashboard</small>
								</span>
							</a>
						</li>
						<!-- Divider -->
						<hr class="border-dark w-100 m-0"/>
						<?
						echo $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Menu");
						?>
					</ul>
				</div>
				<?php
				if ($user_data) {
					?>
				<div class="sb-sidenav-footer">
					<div class="small">
						<?= translateProjectText($EVC, "Logged in as");
						?>
						:</div>
					<?php
					echo $user_data["name"] ? $user_data["name"] : $user_data["username"];
					?>
				</div>
				<?php
				}
				?>
			</nav>
		</div>
		<div id="layoutSidenav_content">
			<main>
				<div class="sb-topnav-menu">
					<ul class="nav template-color">
						<? echo $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Sub Menu"); ?>
					</ul>
				</div>
				<div class="container-fluid pt-4 pb-4 admin-page-content">
					<h1 class="h4 font-weight-bold admin-page-content-title"><?= translateProjectLabel($EVC, $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Title")); ?></h1>
					<small>
						<ol class="breadcrumb mt-2 mb-4">
							<?php
							include_once $EVC->getTemplatePath("bootstrap-stock-manager/lib/utils");
							showBreadcrumbs($EVC, "{$project_url_prefix}$entity", $project_url_prefix, $admin_url);
							?>
															<!--li class="go-back">
						<a href="javascript:void(0)" onClick="MyJSLib.BrowserHistoryHandler.goBack()"><i class="fa fa-undo"></i> Go Back</a>
					</li-->
						</ol>
					</small>
					
					<? echo $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Content"); ?>
				</div>
			</main>
			
			<!-- Footer -->
			<footer class="py-4 bg-light mt-3">
				<div class="container-fluid">
					<div class="d-flex align-items-center justify-content-between small">
						<div class="text-muted m-auto">Powered by<a href="//bloxtor.com" target="bloxtor">
								<img class="bloxtor-logo" src="<?= $project_url_prefix; ?>template/bootstrap-stock-manager/img/bloxtor_logo_black.svg"/>
							</a>
						</div></div>
				</div>
			</footer>
		</div>
	</div>
	<!-- Logout Modal-->
	<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">
						<?= translateProjectLabel($EVC, "Ready to Leave?");
						?>
					</h5>
					<button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?= translateProjectLabel($EVC, 'Select "Logout" below if you are ready to end your current session.');
					?>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
						<?= translateProjectLabel($EVC, "Cancel");
						?>
					</button>
					<a class="btn btn-danger" href="<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Logout Url"); ?>">Logout</a>
				</div></div>
		</div>
	</div>
	
	<div class="loading-bar" style="display: none;">Loading...</div>
	
	<?php include_once $EVC->getTemplatePath("bootstrap-stock-manager/lib/foot"); ?>
	
	<!-- Custom scripts for all pages-->
	<script src="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/js/authenticated-1.js"></script>
	<!--script src="<?php echo $original_project_url_prefix; ?>template/bootstrap-stock-manager/js/pos.js"></script-->
</body>
</html>
