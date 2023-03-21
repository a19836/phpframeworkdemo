<?php 

//PAGE PROPERTIES:
$EVC->getCMSLayer()->getCMSPagePropertyLayer()->setParseFullHtml(true);
$EVC->getCMSLayer()->getCMSPagePropertyLayer()->setFilterByPermission(false);
$EVC->getCMSLayer()->getCMSPagePropertyLayer()->setIncludeBlocksWhenCallingResources(false);

//Regions-Blocks:
$block_local_variables = array();
$EVC->getCMSLayer()->getCMSJoinPointLayer()->resetRegionBlockJoinPoints("Menu", "menu");
$EVC->getCMSLayer()->getCMSTemplateLayer()->addRegionBlock("Menu", "menu");
include $EVC->getBlockPath("menu");

$EVC->getCMSLayer()->getCMSTemplateLayer()->addRegionHtml("Content", "<div class=\"align-items-center justify-content-between\">
	<div class=\"col-xl-3 col-md-4 col-sm-6 float-left\">
		<a class=\"text-white stretched-link text-decoration-none\" href=\"{$project_url_prefix}schools\" title=\"Sub Items\">
			<div class=\"card bg-danger mt-2 mb-2 ml-2 mr-2\">
				<div class=\"card-body\">Schools</div>
				<div class=\"card-footer d-flex align-items-center justify-content-between\">
					<span class=\"small text-white stretched-link\" href=\"#\">View Details</span>
					<div class=\"small text-white\">
						<svg class=\"svg-inline--fa fa-angle-right fa-w-8 html-tag template_widget_html-tag_yeggc_497 template_widget_droppable_column_bwwl8_999 template_widget_droppable_column_5cr7p_457 template_widget_droppable_column_gmob5_751 template_widget_droppable_column_0n72r_282\" aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"angle-right\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 256 512\" data-fa-i2svg=\"\">
							<path fill=\"currentColor\" d=\"M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z\"></path>
						</svg>
						<!-- <i class=\"droppable template-widget template-widget-html-tag html-tag fas fa-angle-right template_widget_html-tag_yeggc_497 template_widget_droppable_column_bwwl8_999 template_widget_droppable_column_5cr7p_457 template_widget_droppable_column_gmob5_751 template_widget_droppable_column_0n72r_282\"></i> -->
					</div></div>
			</div>
		</a>
	</div>
	<div class=\"col-xl-3 col-md-4 col-sm-6 float-left\">
		<a class=\"text-white stretched-link text-decoration-none\" href=\"{$project_url_prefix}teachers\" title=\"Items\">
			<div class=\"card bg-danger mt-2 mb-2 ml-2 mr-2\">
				<div class=\"card-body\">Teachers</div>
				<div class=\"card-footer d-flex align-items-center justify-content-between\">
					<span class=\"small text-white stretched-link\" href=\"#\">View Details</span>
					<div class=\"small text-white\">
						<svg class=\"svg-inline--fa fa-angle-right fa-w-8 html-tag template_widget_html-tag_w1ysw_187 template_widget_droppable_column_30p3i_841 template_widget_droppable_column_qlyla_269 template_widget_droppable_column_bxdj8_294 template_widget_droppable_column_6cnjp_909\" aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"angle-right\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 256 512\" data-fa-i2svg=\"\">
							<path fill=\"currentColor\" d=\"M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z\"></path>
						</svg>
						<!-- <i class=\"droppable template-widget template-widget-html-tag html-tag fas fa-angle-right template_widget_html-tag_w1ysw_187 template_widget_droppable_column_30p3i_841 template_widget_droppable_column_qlyla_269 template_widget_droppable_column_bxdj8_294 template_widget_droppable_column_6cnjp_909\"></i> -->
					</div></div>
			</div>
		</a>
	</div>
	<div class=\"col-xl-3 col-md-4 col-sm-6 float-left\">
		<a class=\"text-white stretched-link text-decoration-none\" href=\"{$project_url_prefix}students\" title=\"Items\">
			<div class=\"card bg-danger mt-2 mb-2 ml-2 mr-2\">
				<div class=\"card-body\">Students</div>
				<div class=\"card-footer d-flex align-items-center justify-content-between\">
					<span class=\"small text-white stretched-link\" href=\"#\">View Details</span>
					<div class=\"small text-white\">
						<svg class=\"svg-inline--fa fa-angle-right fa-w-8 html-tag template_widget_html-tag_w1ysw_187 template_widget_droppable_column_xf5y7_962 template_widget_droppable_column_e34tm_922 template_widget_droppable_column_285fi_958 template_widget_droppable_column_dor36_166\" aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"angle-right\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 256 512\" data-fa-i2svg=\"\">
							<path fill=\"currentColor\" d=\"M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z\"></path>
						</svg>
						<!-- <i class=\"droppable template-widget template-widget-html-tag html-tag fas fa-angle-right template_widget_html-tag_w1ysw_187 template_widget_droppable_column_xf5y7_962 template_widget_droppable_column_e34tm_922 template_widget_droppable_column_285fi_958 template_widget_droppable_column_dor36_166\"></i> -->
					</div></div>
			</div>
		</a>
	</div></div>");
?>