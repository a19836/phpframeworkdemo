<?php 

//PAGE PROPERTIES:
$EVC->getCMSLayer()->getCMSPagePropertyLayer()->setParseFullHtml(true);
$EVC->getCMSLayer()->getCMSPagePropertyLayer()->setFilterByPermission(false);
$EVC->getCMSLayer()->getCMSPagePropertyLayer()->setIncludeBlocksWhenCallingResources(false);

//Regions-Blocks:
$block_local_variables = array();
$EVC->getCMSLayer()->getCMSJoinPointLayer()->resetRegionBlockJoinPoints("Menu", "menu_show_menu");
$EVC->getCMSLayer()->getCMSTemplateLayer()->addRegionBlock("Menu", "menu_show_menu");
include $EVC->getBlockPath("menu_show_menu");

$EVC->getCMSLayer()->getCMSTemplateLayer()->addRegionHtml("Content", "<div class=\"align-items-center justify-content-between clearfix\">
	<div class=\"col-xl-3 col-md-4 col-sm-6 float-left\">
		<a class=\"text-white text-decoration-none\" href=\"{$project_url_prefix}schools\" title=\"Sub Items\">
			<div class=\"card bg-danger mt-2 mb-2 ml-2 mr-2\">
				<div class=\"card-body\">Schools</div>
				<div class=\"card-footer d-flex align-items-center justify-content-between\">
					<span class=\"small text-white\" href=\"#\">View Details</span>
					<div class=\"small text-white\">
						<svg aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"angle-right\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 256 512\" data-fa-i2svg=\"\">
							<path fill=\"currentColor\" d=\"M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z\"></path>
						</svg>
						<!-- <i class=\"fas fa-angle-right\"></i> -->
					</div></div>
			</div>
		</a>
	</div>
	<div class=\"col-xl-3 col-md-4 col-sm-6 float-left\">
		<a class=\"text-white text-decoration-none\" href=\"{$project_url_prefix}teachers\" title=\"Items\">
			<div class=\"card bg-danger mt-2 mb-2 ml-2 mr-2\">
				<div class=\"card-body\">Teachers</div>
				<div class=\"card-footer d-flex align-items-center justify-content-between\">
					<span class=\"small text-white\" href=\"#\">View Details</span>
					<div class=\"small text-white\">
						<svg aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"angle-right\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 256 512\" data-fa-i2svg=\"\">
							<path fill=\"currentColor\" d=\"M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z\"></path>
						</svg>
						<!-- <i class=\"fas fa-angle-right\"></i> -->
					</div></div>
			</div>
		</a>
	</div>
	<div class=\"col-xl-3 col-md-4 col-sm-6 float-left\">
		<a class=\"text-white text-decoration-none\" href=\"{$project_url_prefix}students\" title=\"Items\">
			<div class=\"card bg-danger mt-2 mb-2 ml-2 mr-2\">
				<div class=\"card-body\">Students</div>
				<div class=\"card-footer d-flex align-items-center justify-content-between\">
					<span class=\"small text-white\" href=\"#\">View Details</span>
					<div class=\"small text-white\">
						<svg aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"angle-right\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 256 512\" data-fa-i2svg=\"\">
							<path fill=\"currentColor\" d=\"M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z\"></path>
						</svg>
						<!-- <i class=\"fas fa-angle-right\"></i> -->
					</div></div>
			</div>
		</a>
	</div></div>");
?>