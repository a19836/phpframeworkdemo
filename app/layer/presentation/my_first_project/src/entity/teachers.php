<?php 

//PAGE PROPERTIES:
$EVC->getCMSLayer()->getCMSPagePropertyLayer()->setParseFullHtml(true);
$EVC->getCMSLayer()->getCMSPagePropertyLayer()->setFilterByPermission(false);
$EVC->getCMSLayer()->getCMSPagePropertyLayer()->setIncludeBlocksWhenCallingResources(false);

//SLA ACTIONS SETTINGS:
$EVC->getCMSLayer()->getCMSSequentialLogicalActivityLayer()->addSequentialLogicalActivities(array(
	array(
		"result_var_name" => "teachers_group",
		"action_type" => "group",
		"condition_type" => "execute_if_get_resource",
		"condition_value" => "teachers",
		"action_description" => "Get records from table: teacher.",
		"action_value" => array(
			"group_name" => "",
			"actions" => array(
				array(
					"result_var_name" => "teachers",
					"action_type" => "callobjectmethod",
					"condition_type" => "execute_always",
					"condition_value" => "",
					"action_description" => "",
					"action_value" => array(
						"method_obj" => "TeacherResourceUtil",
						"method_name" => "getAll",
						"method_static" => 1,
						"method_args" => array(
							$EVC,
							$_GET["items_limit_per_page"],
							$_GET["page_items_start"],
							$_GET["search_attrs"],
							$_GET["search_types"],
							$_GET["search_cases"],
							$_GET["search_operators"],
							$_GET["sort_attrs"]
						),
						"include_file_path" => $EVC->getUtilPath("resource/TeacherResourceUtil"),
						"include_once" => 1
					)
				)
			)
		)
	),
	array(
		"result_var_name" => "count_teachers_group",
		"action_type" => "group",
		"condition_type" => "execute_if_get_resource",
		"condition_value" => "count_teachers",
		"action_description" => "Count records from table: teacher.",
		"action_value" => array(
			"group_name" => "",
			"actions" => array(
				array(
					"result_var_name" => "count_teachers",
					"action_type" => "callobjectmethod",
					"condition_type" => "execute_always",
					"condition_value" => "",
					"action_description" => "",
					"action_value" => array(
						"method_obj" => "TeacherResourceUtil",
						"method_name" => "count",
						"method_static" => 1,
						"method_args" => array(
							$EVC,
							$_GET["search_attrs"],
							$_GET["search_types"],
							$_GET["search_cases"],
							$_GET["search_operators"]
						),
						"include_file_path" => $EVC->getUtilPath("resource/TeacherResourceUtil"),
						"include_once" => 1
					)
				)
			)
		)
	),
	array(
		"result_var_name" => "delete_all_teachers_group",
		"action_type" => "group",
		"condition_type" => "execute_if_condition",
		"condition_value" => "\$_GET[\"resource\"] == \"delete_all_teachers\" && \$_POST",
		"action_description" => "Delete multiple records at once from table: teacher.",
		"action_value" => array(
			"group_name" => "",
			"actions" => array(
				array(
					"result_var_name" => "delete_all_teachers",
					"action_type" => "callobjectmethod",
					"condition_type" => "execute_always",
					"condition_value" => "",
					"action_description" => "",
					"action_value" => array(
						"method_obj" => "TeacherResourceUtil",
						"method_name" => "multipleDelete",
						"method_static" => 1,
						"method_args" => array(
							$EVC,
							$_POST["conditions"]
						),
						"include_file_path" => $EVC->getUtilPath("resource/TeacherResourceUtil"),
						"include_once" => 1
					)
				),
				array(
					"result_var_name" => "delete_all_teachers_unsuccessfully",
					"action_type" => "string",
					"condition_type" => "execute_if_not_var",
					"condition_value" => "delete_all_teachers",
					"action_description" => "",
					"action_value" => 1
				)
			)
		)
	),
	array(
		"result_var_name" => "delete_teacher_group",
		"action_type" => "group",
		"condition_type" => "execute_if_condition",
		"condition_value" => "\$_GET[\"resource\"] == \"delete_teacher\" && \$_POST",
		"action_description" => "Delete record from table: teacher.",
		"action_value" => array(
			"group_name" => "",
			"actions" => array(
				array(
					"result_var_name" => "delete_teacher",
					"action_type" => "callobjectmethod",
					"condition_type" => "execute_always",
					"condition_value" => "",
					"action_description" => "",
					"action_value" => array(
						"method_obj" => "TeacherResourceUtil",
						"method_name" => "delete",
						"method_static" => 1,
						"method_args" => array(
							$EVC,
							$_POST["conditions"]
						),
						"include_file_path" => $EVC->getUtilPath("resource/TeacherResourceUtil"),
						"include_once" => 1
					)
				),
				array(
					"result_var_name" => "delete_teacher_unsuccessfully",
					"action_type" => "string",
					"condition_type" => "execute_if_not_var",
					"condition_value" => "delete_teacher",
					"action_description" => "",
					"action_value" => 1
				)
			)
		)
	),
	array(
		"result_var_name" => "insert_teacher_group",
		"action_type" => "group",
		"condition_type" => "execute_if_condition",
		"condition_value" => "\$_GET[\"resource\"] == \"insert_teacher\" && \$_POST",
		"action_description" => "Insert data into table: teacher.",
		"action_value" => array(
			"group_name" => "",
			"actions" => array(
				array(
					"result_var_name" => "insert_teacher",
					"action_type" => "callobjectmethod",
					"condition_type" => "execute_always",
					"condition_value" => "",
					"action_description" => "",
					"action_value" => array(
						"method_obj" => "TeacherResourceUtil",
						"method_name" => "insert",
						"method_static" => 1,
						"method_args" => array(
							$EVC,
							$_POST["attributes"]
						),
						"include_file_path" => $EVC->getUtilPath("resource/TeacherResourceUtil"),
						"include_once" => 1
					)
				),
				array(
					"result_var_name" => "insert_teacher_unsuccessfully",
					"action_type" => "string",
					"condition_type" => "execute_if_not_var",
					"condition_value" => "insert_teacher",
					"action_description" => "",
					"action_value" => 1
				)
			)
		)
	),
	array(
		"result_var_name" => "get_school_school_id_options_group",
		"action_type" => "group",
		"condition_type" => "execute_if_get_resource",
		"condition_value" => "get_school_school_id_options",
		"action_description" => "Get key-value pair list from table: school, where the key is the table primary key and the value is the table attribute label.",
		"action_value" => array(
			"group_name" => "",
			"actions" => array(
				array(
					"result_var_name" => "get_school_school_id_options",
					"action_type" => "callobjectmethod",
					"condition_type" => "execute_always",
					"condition_value" => "",
					"action_description" => "",
					"action_value" => array(
						"method_obj" => "SchoolResourceUtil",
						"method_name" => "getAllOptions",
						"method_static" => 1,
						"method_args" => array(
							$EVC,
							$_GET["items_limit_per_page"],
							$_GET["page_items_start"],
							$_GET["search_attrs"],
							$_GET["search_types"],
							$_GET["search_cases"],
							$_GET["search_operators"],
							$_GET["sort_attrs"]
						),
						"include_file_path" => $EVC->getUtilPath("resource/SchoolResourceUtil"),
						"include_once" => 1
					)
				)
			)
		)
	),
	array(
		"result_var_name" => "teacher_group",
		"action_type" => "group",
		"condition_type" => "execute_if_get_resource",
		"condition_value" => "teacher",
		"action_description" => "Get a record from table: teacher.",
		"action_value" => array(
			"group_name" => "",
			"actions" => array(
				array(
					"result_var_name" => "teacher",
					"action_type" => "callobjectmethod",
					"condition_type" => "execute_always",
					"condition_value" => "",
					"action_description" => "",
					"action_value" => array(
						"method_obj" => "TeacherResourceUtil",
						"method_name" => "get",
						"method_static" => 1,
						"method_args" => array(
							$EVC,
							$_GET["search_attrs"]
						),
						"include_file_path" => $EVC->getUtilPath("resource/TeacherResourceUtil"),
						"include_once" => 1
					)
				)
			)
		)
	),
	array(
		"result_var_name" => "update_teacher_group",
		"action_type" => "group",
		"condition_type" => "execute_if_condition",
		"condition_value" => "\$_GET[\"resource\"] == \"update_teacher\" && \$_POST",
		"action_description" => "Update data into table: teacher.",
		"action_value" => array(
			"group_name" => "",
			"actions" => array(
				array(
					"result_var_name" => "update_teacher",
					"action_type" => "callobjectmethod",
					"condition_type" => "execute_always",
					"condition_value" => "",
					"action_description" => "",
					"action_value" => array(
						"method_obj" => "TeacherResourceUtil",
						"method_name" => "update",
						"method_static" => 1,
						"method_args" => array(
							$EVC,
							$_POST["attributes"],
							$_POST["conditions"]
						),
						"include_file_path" => $EVC->getUtilPath("resource/TeacherResourceUtil"),
						"include_once" => 1
					)
				),
				array(
					"result_var_name" => "update_teacher_unsuccessfully",
					"action_type" => "string",
					"condition_type" => "execute_if_not_var",
					"condition_value" => "update_teacher",
					"action_description" => "",
					"action_value" => 1
				)
			)
		)
	)
));

//Regions-Blocks:
$block_local_variables = array();
$EVC->getCMSLayer()->getCMSJoinPointLayer()->resetRegionBlockJoinPoints("Menu", "menu_show_menu");
$EVC->getCMSLayer()->getCMSTemplateLayer()->addRegionBlock("Menu", "menu_show_menu");
include $EVC->getBlockPath("menu_show_menu");

$EVC->getCMSLayer()->getCMSTemplateLayer()->addRegionHtml("Content", "<div class=\"text-right text-end\" data-widget-group-list data-widget-props=\"{&quot;db_broker&quot;:&quot;&quot;,&quot;db_driver&quot;:&quot;mysql&quot;,&quot;db_type&quot;:&quot;diagram&quot;,&quot;db_table&quot;:&quot;teacher&quot;,&quot;db_table_alias&quot;:&quot;&quot;}\">
	<div class=\"card mb-4 text-left text-start\" id=\"widget_search_1_1\" data-widget-search data-widget-props=\"{&quot;dependent_widgets_id&quot;:[&quot;widget_list_1&quot;,&quot;widget_bottom_pagination_1_1&quot;]}\">
		<div class=\"card-body\">
			<label class=\"text-muted mb-1 small\">Filter by:</label>
			<div class=\"input-group input-group-sm\" data-widget-search-input data-widget-props=\"{&quot;search_attrs&quot;:&quot;teacher_id,school_id,name,age&quot;, &quot;search_operator&quot;:&quot;or&quot;}\">
				<input class=\"form-control border border-secondary\" placeholder=\"Type to search...\" onkeyup=\"MyWidgetResourceLib.SearchHandler.onKeyUpSearchWidgetThroughInput(this, 1); return false;\" onblur=\"MyWidgetResourceLib.SearchHandler.refreshSearchWidgetThroughInput(this); return false;\"/>
				<button class=\"btn btn-sm btn-outline-secondary text-nowrap\" onclick=\"MyWidgetResourceLib.SearchHandler.refreshSearchWidgetThroughInput(this, true); return false;\" title=\"Search\">
					<i class=\"bi bi-search icon icon-search overflow-visible\"></i>
				</button>
				<button class=\"btn btn-sm btn-outline-secondary text-nowrap\" onclick=\"MyWidgetResourceLib.SearchHandler.resetSearchWidgetThroughInput(this, true); return false;\" title=\"Reset\">
					<i class=\"bi bi-x icon icon-reset overflow-visible\"></i>
				</button>
			</div></div>
	</div>
	<div class=\"mb-4 btn-group text-center mw-100\" id=\"widget_short_actions_1_1\" data-widget-short-actions style=\"overflow:auto;\">
		<button class=\"btn btn-sm btn-outline-danger text-nowrap\" onclick=\"MyWidgetResourceLib.ShortActionHandler.executeResourceMultipleRemoveAction(this); return false;\" data-widget-button-multiple-remove data-widget-props=\"{&quot;empty_message&quot;:&quot;Please select some records first...&quot;,&quot;dependent_widgets_id&quot;:[&quot;widget_list_1&quot;]}\" data-widget-resources=\"{&quot;error_message&quot;:&quot;Error trying to remove. Please try again...&quot;,&quot;success_message&quot;:&quot;Removed successfully!&quot;,&quot;confirmation_message&quot;:&quot;Are you sure you want to continue?&quot;,&quot;name&quot;:&quot;delete_all_teachers&quot;}\" title=\"Remove\">
			<i class=\"bi bi-trash icon icon-remove mr-1 me-1 overflow-visible\"></i>Remove</button>
		<button class=\"btn btn-sm btn-outline-success text-nowrap\" onclick=\"MyWidgetResourceLib.PopupHandler.openButtonAddPopup(this); return false;\" data-widget-button-add data-widget-popup-id=\"widget_popup_add_1\" title=\"Add\">
			<i class=\"bi bi-plus-lg icon icon-add mr-1 me-1 overflow-visible\"></i>Add</button>
		<button class=\"btn btn-sm btn-outline-secondary text-nowrap\" onclick=\"MyWidgetResourceLib.ShortActionHandler.resetWidgetListResourceSort(this); return false;\" data-widget-props=\"{&quot;dependent_widgets_id&quot;:[&quot;widget_list_1&quot;]}\" data-widget-button-reset-sorting title=\"Reset Sorting\">
			<i class=\"bi bi-x-circle icon icon-reset mr-1 me-1 overflow-visible\"></i>Reset Sorting</button>
		<button class=\"btn btn-sm btn-outline-secondary text-nowrap\" onclick=\"MyWidgetResourceLib.ShortActionHandler.toggleWidgetListAttributeSelectCheckboxes(this); return false;\" data-widget-props=\"{&quot;dependent_widgets_id&quot;:[&quot;widget_list_1&quot;]}\" data-widget-button-toggle-list-attribute-select-checkboxes title=\"Select All\">
			<i class=\"bi bi-check2-square icon icon-reset mr-1 me-1 overflow-visible\"></i>Select All</button>
		<button class=\"btn btn-sm btn-outline-secondary text-nowrap\" onclick=\"MyWidgetResourceLib.ShortActionHandler.refreshDependentWidgets(this); return false;\" data-widget-props=\"{&quot;dependent_widgets_id&quot;:[&quot;widget_list_1&quot;]}\" title=\"Refresh\">
			<i class=\"bi bi-arrow-clockwise icon icon-refresh mr-1 me-1 overflow-visible\"></i>Refresh</button>
	</div>
	<div class=\"card mb-3 text-left text-start\" id=\"widget_list_1\" data-widget-list data-widget-props=\"{&quot;items_limit_per_page&quot;:50,&quot;pks_attrs_names&quot;:&quot;teacher_id&quot;,&quot;load&quot;:&quot;MyWidgetResourceLib.ListHandler.loadListTableAndTreeResource&quot;,&quot;dependent_widgets_id&quot;:&quot;widget_list_caption_1&quot;,&quot;complete&quot;:{&quot;remove&quot;:&quot;MyWidgetResourceLib.ListHandler.onRemoveResourceItem&quot;}}\" data-widget-resources=\"{&quot;load&quot;:[{&quot;name&quot;:&quot;teachers&quot;}],&quot;remove&quot;:[{&quot;error_message&quot;:&quot;Error trying to remove. Please try again...&quot;,&quot;success_message&quot;:&quot;Removed successfully!&quot;,&quot;confirmation_message&quot;:&quot;Are you sure you want to continue?&quot;,&quot;name&quot;:&quot;delete_teacher&quot;}]}\" data-widget-resources-load=\"\">
		<div class=\"card-body\">
			<div class=\"list-responsive table-responsive\">
				<table class=\"table table-sm table-striped table-hover text-left text-start list-table mb-0 small\" data-widget-list-table>
					<thead>
						<tr>
							<th class=\"border-0 pt-0 text-center text-muted fw-normal align-middle small\" data-widget-list-select-items-head>
								<input type=\"checkbox\" onclick=\"MyWidgetResourceLib.ListHandler.toggleListAttributeSelectCheckboxes(this); return true;\" data-widget-list-select-items-checkbox/>
							</th>
							<th class=\"border-0 pt-0 text-muted fw-normal align-middle small text-nowrap\" data-widget-item-head onclick=\"MyWidgetResourceLib.ListHandler.sortListResource(this, event); return false;\" data-widget-item-attribute-name=\"teacher_id\">Teacher Id<i class=\"bi bi-filter-left ml-1 ms-1 overflow-visible icon icon-sort text-center\"></i>
								<i class=\"bi bi-sort-down-alt ml-1 ms-1 overflow-visible icon icon-sort-asc text-center\"></i>
								<i class=\"bi bi-sort-up ml-1 ms-1 overflow-visible icon icon-sort-desc text-center\"></i>
								<i class=\"bi bi-x-circle-fill ml-1 ms-1 overflow-visible icon icon-sort-reset text-center\" onclick=\"MyWidgetResourceLib.ListHandler.resetListResourceSortAttribute(this, event); return false;\"></i>
							</th>
							<th class=\"border-0 pt-0 text-muted fw-normal align-middle small text-nowrap\" data-widget-item-head onclick=\"MyWidgetResourceLib.ListHandler.sortListResource(this, event); return false;\" data-widget-item-attribute-name=\"school_id\">School Id<i class=\"bi bi-filter-left ml-1 ms-1 overflow-visible icon icon-sort text-center\"></i>
								<i class=\"bi bi-sort-down-alt ml-1 ms-1 overflow-visible icon icon-sort-asc text-center\"></i>
								<i class=\"bi bi-sort-up ml-1 ms-1 overflow-visible icon icon-sort-desc text-center\"></i>
								<i class=\"bi bi-x-circle-fill ml-1 ms-1 overflow-visible icon icon-sort-reset text-center\" onclick=\"MyWidgetResourceLib.ListHandler.resetListResourceSortAttribute(this, event); return false;\"></i>
							</th>
							<th class=\"border-0 pt-0 text-muted fw-normal align-middle small text-nowrap\" data-widget-item-head onclick=\"MyWidgetResourceLib.ListHandler.sortListResource(this, event); return false;\" data-widget-item-attribute-name=\"name\">Name<i class=\"bi bi-filter-left ml-1 ms-1 overflow-visible icon icon-sort text-center\"></i>
								<i class=\"bi bi-sort-down-alt ml-1 ms-1 overflow-visible icon icon-sort-asc text-center\"></i>
								<i class=\"bi bi-sort-up ml-1 ms-1 overflow-visible icon icon-sort-desc text-center\"></i>
								<i class=\"bi bi-x-circle-fill ml-1 ms-1 overflow-visible icon icon-sort-reset text-center\" onclick=\"MyWidgetResourceLib.ListHandler.resetListResourceSortAttribute(this, event); return false;\"></i>
							</th>
							<th class=\"border-0 pt-0 text-muted fw-normal align-middle small text-nowrap\" data-widget-item-head onclick=\"MyWidgetResourceLib.ListHandler.sortListResource(this, event); return false;\" data-widget-item-attribute-name=\"age\">Age<i class=\"bi bi-filter-left ml-1 ms-1 overflow-visible icon icon-sort text-center\"></i>
								<i class=\"bi bi-sort-down-alt ml-1 ms-1 overflow-visible icon icon-sort-asc text-center\"></i>
								<i class=\"bi bi-sort-up ml-1 ms-1 overflow-visible icon icon-sort-desc text-center\"></i>
								<i class=\"bi bi-x-circle-fill ml-1 ms-1 overflow-visible icon icon-sort-reset text-center\" onclick=\"MyWidgetResourceLib.ListHandler.resetListResourceSortAttribute(this, event); return false;\"></i>
							</th>
							<th class=\"border-0 pt-0 text-right text-end text-muted fw-normal align-middle small\" data-widget-item-actions-head>
								<button class=\"btn btn-sm btn-success text-nowrap m-1\" onclick=\"MyWidgetResourceLib.PopupHandler.openButtonAddPopup(this); return false;\" data-widget-button-add data-widget-popup-id=\"widget_popup_add_1\" title=\"Add\">
									<i class=\"bi bi-plus-lg icon icon-add mr-1 me-1 overflow-visible\"></i>Add new Record</button>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr data-widget-item>
							<td class=\"border-0 text-center align-middle\" data-widget-item-selected-column>
								<input type=\"checkbox\" data-widget-item-selected-checkbox/>
							</td>
							<td class=\"border-0 align-middle\" data-widget-item-column data-widget-item-attribute-name=\"teacher_id\">
								<span class=\"form-control-plaintext\" data-widget-item-attribute-field-view data-widget-resource-value=\"{&quot;attribute&quot;:&quot;teacher_id&quot;}\"></span>
							</td>
							<td class=\"border-0 align-middle\" data-widget-item-column data-widget-item-attribute-name=\"school_id\">
								<span class=\"form-control-plaintext\" data-widget-item-attribute-field-view data-widget-resource-value=\"{&quot;attribute&quot;:&quot;school_id&quot;, &quot;available_values&quot;:{&quot;0&quot;:{&quot;name&quot;:&quot;get_school_school_id_options&quot;}}}\" data-widget-props=\"{&quot;load&quot;:&quot;MyWidgetResourceLib.FieldHandler.cacheFieldResource&quot;}\" data-widget-resources=\"get_school_school_id_options\" data-widget-item-resources-load=\"\"></span>
							</td>
							<td class=\"border-0 align-middle\" data-widget-item-column data-widget-item-attribute-name=\"name\">
								<span class=\"form-control-plaintext\" data-widget-item-attribute-field-view data-widget-resource-value=\"{&quot;attribute&quot;:&quot;name&quot;}\"></span>
							</td>
							<td class=\"border-0 align-middle\" data-widget-item-column data-widget-item-attribute-name=\"age\">
								<span class=\"form-control-plaintext\" data-widget-item-attribute-field-view data-widget-resource-value=\"{&quot;attribute&quot;:&quot;age&quot;}\"></span>
							</td>
							<td class=\"border-0 text-right text-end align-middle\" data-widget-item-actions-column>
								<div class=\"btn-group justify-content-center align-items-center\">
									<button class=\"btn btn-sm btn-primary text-nowrap\" data-widget-item-button-edit onclick=\"MyWidgetResourceLib.ItemHandler.openItemEditPopupById(this); return false;\" data-widget-popup-id=\"widget_popup_edit_1\" title=\"Edit\">
										<i class=\"bi bi-pencil icon icon-edit mr-1 me-1 overflow-visible\"></i>Edit</button>
									<button class=\"btn btn-sm btn-danger text-nowrap float-left float-start\" data-widget-item-button-remove onclick=\"MyWidgetResourceLib.ItemHandler.removeResourceItem(this); return false;\" title=\"Remove\">
										<i class=\"bi bi-trash icon icon-remove mr-1 me-1 overflow-visible\"></i>Remove</button>
								</div>
							</td>
						</tr>
						<tr data-widget-loading>
							<td class=\"border-0 text-center text-muted small p-3\" colspan=\"6\">Loading new data... Please wait a while...</td>
						</tr>
						<tr data-widget-empty>
							<td class=\"border-0 text-center text-muted small p-3\" colspan=\"6\">There are no records available.<div data-widget-empty-add>Please click in the button below to add new records:<br/>
									<br/>
									<button class=\"btn btn-sm btn-success text-nowrap m-1\" onclick=\"MyWidgetResourceLib.PopupHandler.openButtonAddPopup(this); return false;\" data-widget-button-add data-widget-popup-id=\"widget_popup_add_1\" title=\"Add\">
										<i class=\"bi bi-plus-lg icon icon-add mr-1 me-1 overflow-visible\"></i>Add new Record</button>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class=\"text-muted small mt-3\" id=\"widget_list_caption_1\" data-widget-list-caption data-widget-props=\"{&quot;load&quot;:&quot;MyWidgetResourceLib.ListHandler.loadListCaptionResource&quot;}\" data-widget-resources=\"{&quot;load&quot;:&quot;count_teachers&quot;}\"></div>
		</div></div>
	<div class=\"btn-toolbar justify-content-between text-left text-start mb-3\" id=\"widget_bottom_pagination_1_1\" data-widget-pagination data-widget-props=\"{&quot;number_of_pages_to_show_at_once&quot;:10,&quot;load&quot;:&quot;MyWidgetResourceLib.PaginationHandler.loadPaginationResource&quot;,&quot;dependent_widgets_id&quot;:[&quot;widget_list_1&quot;]}\" data-widget-resources=\"count_teachers\" data-widget-resources-load=\"\">
		<div class=\"input-group input-group-sm col-6 col-sm-4 col-md-3 col-lg-3 col-xl-1 align-items-start p-0\" data-widget-pagination-go-to-page style=\"min-width:150px;\">
			<select class=\"form-control custom-select form-select border-secondary text-muted\" data-widget-pagination-go-to-page-dropdown onchange=\"MyWidgetResourceLib.PaginationHandler.goToDropdownPage(this); return false;\"></select>
			<button class=\"btn btn-sm btn-outline-secondary text-nowrap\" href=\"javascript:void(0)\" onclick=\"MyWidgetResourceLib.PaginationHandler.goToDropdownPage(this, true); return false;\" title=\"Refresh\">
				<i class=\"bi bi-arrow-clockwise icon icon-refresh mr-1 me-1 overflow-visible\"></i>Refresh</button>
		</div>
		<div class=\"btn-group justify-content-end align-items-start\" data-widget-pagination-pages role=\"group\">
			<a class=\"btn btn-sm btn-outline-secondary\" data-widget-pagination-pages-first href=\"javascript:void(0)\" onclick=\"MyWidgetResourceLib.PaginationHandler.goToFirstPage(this); return false;\">First</a>
			<a class=\"btn btn-sm btn-outline-secondary\" data-widget-pagination-pages-previous href=\"javascript:void(0)\" onclick=\"MyWidgetResourceLib.PaginationHandler.goToPreviousPage(this); return false;\">Previous</a>
			<div class=\"btn-group\" data-widget-pagination-pages-numbers role=\"group\">
				<a class=\"btn btn-sm btn-outline-secondary\" data-widget-pagination-pages-numbers-item href=\"javascript:void(0)\" onclick=\"MyWidgetResourceLib.PaginationHandler.goToElementPage(this); return false;\">
					<span data-widget-pagination-pages-numbers-item-value>dummy page #</span>
				</a>
			</div>
			<a class=\"btn btn-sm btn-outline-secondary\" data-widget-pagination-pages-next href=\"javascript:void(0)\" onclick=\"MyWidgetResourceLib.PaginationHandler.goToNextPage(this); return false;\">Next</a>
			<a class=\"btn btn-sm btn-outline-secondary\" data-widget-pagination-pages-last href=\"javascript:void(0)\" onclick=\"MyWidgetResourceLib.PaginationHandler.goToLastPage(this); return false;\">Last</a>
		</div></div>
	<div class=\"modal fade text-left text-start\" id=\"widget_popup_add_1\" data-widget-popup data-widget-popup-add tabindex=\"-1\" role=\"dialog\" data-widget-props=\"{&quot;dependent_widgets_id&quot;:&quot;widget_popup_add_form_1&quot;}\" style=\"background-color:rgba(0, 0, 0, .5);\">
		<div class=\"modal-dialog\">
			<div class=\"modal-content\">
				<div class=\"modal-header\">
					<h5 class=\"modal-title text-muted\">Add Teacher</h5>
					<button class=\"btn-close\" data-dismiss=\"modal\" data-bs-dismiss=\"modal\" aria-label=\"Close\" title=\"Close Popup\"></button>
				</div>
				<div class=\"modal-body\">
					<form class=\"show-add-fields\" id=\"widget_popup_add_form_1\" method=\"post\" onsubmit=\"return false;\" data-widget-form data-widget-resources=\"{&quot;add&quot;:{&quot;error_message&quot;:&quot;Error trying to add. Please try again...&quot;,&quot;success_message&quot;:&quot;Added successfully!&quot;,&quot;name&quot;:&quot;insert_teacher&quot;}}\" data-widget-props=\"{&quot;pks_attrs_names&quot;:&quot;&quot;,&quot;dependent_widgets_id&quot;:[&quot;widget_list_1&quot;,&quot;widget_bottom_pagination_1_1&quot;],&quot;complete&quot;:{&quot;add&quot;:&quot;MyWidgetResourceLib.FormHandler.onAddPopupResourceItem&quot;},&quot;enter_key_press_button&quot;:&quot;[data-widget-item-button-add]&quot;}\">
						<div class=\"row mb-3\" data-widget-item-column data-widget-item-attribute-name=\"school_id\">
							<label class=\"col-sm-4 col-form-label\" data-widget-item-head>School Id<span class=\"label-colon\">:</span>
								<span class=\"text-danger label-mandatory\">*</span>
							</label>
							<div class=\"col-sm-8\">
								<div data-widget-item-attribute-field-add data-widget-item-attribute-field-toggle-select-input>
									<div class=\"input-group show\">
										<select class=\"form-control custom-select form-select\" data-widget-resource-value=\"{&quot;attribute&quot;:&quot;school_id&quot;}\" data-allow-null=\"0\" required data-validation-type=\"bigint\" data-validation-message=\"'School Id' field is not a valid number.\" data-validation-label=\"School Id\" placeholder=\"0\" maxlength=\"20\" min=\"0\" max=\"99999999999999999999\" data-widget-props=\"{&quot;load&quot;:&quot;MyWidgetResourceLib.FieldHandler.loadFieldResource&quot;}\" data-widget-resources=\"get_school_school_id_options\" data-widget-item-resources-load=\"\">
											<option value=\"\"></option>
										</select>
										<div class=\"input-group-append\">
											<button class=\"btn btn-outline-secondary\" onclick=\"MyWidgetResourceLib.ItemHandler.toggleItemAttributeSelectFieldToInputField(this); return false;\">
												<span class=\"bi bi-plus-lg icon icon-add\"></span>
											</button>
										</div></div>
									<div class=\"input-group\">
										<input class=\"form-control\" type=\"number\"/>
										<div class=\"input-group-append\">
											<button class=\"btn btn-outline-secondary\" onclick=\"MyWidgetResourceLib.ItemHandler.toggleItemAttributeInputFieldToSelectField(this); return false;\">
												<span class=\"bi bi-search icon icon-search\"></span>
											</button>
										</div></div>
								</div></div>
						</div>
						<div class=\"row mb-3\" data-widget-item-column data-widget-item-attribute-name=\"name\">
							<label class=\"col-sm-4 col-form-label\" data-widget-item-head>Name<span class=\"label-colon\">:</span>
								<span class=\"text-danger label-mandatory\">*</span>
							</label>
							<div class=\"col-sm-8\">
								<input class=\"form-control\" type=\"text\" data-widget-item-attribute-field-add data-widget-resource-value=\"{&quot;attribute&quot;:&quot;name&quot;}\" data-allow-null=\"0\" required data-validation-label=\"Name\" maxlength=\"50\"/>
							</div></div>
						<div class=\"row mb-3\" data-widget-item-column data-widget-item-attribute-name=\"age\">
							<label class=\"col-sm-4 col-form-label\" data-widget-item-head>Age<span class=\"label-colon\">:</span>
								<span class=\"text-danger label-mandatory\">*</span>
							</label>
							<div class=\"col-sm-8\">
								<input class=\"form-control\" type=\"number\" data-widget-item-attribute-field-add data-widget-resource-value=\"{&quot;attribute&quot;:&quot;age&quot;}\" data-allow-null=\"0\" required data-validation-type=\"number\" data-validation-message=\"'Age' field is not a valid number.\" data-validation-label=\"Age\" placeholder=\"0\" maxlength=\"2\" max=\"99\"/>
							</div></div>
						<div class=\"text-right text-end mt-4\" data-widget-item-actions-column>
							<button class=\"btn btn-sm btn-secondary text-nowrap m-1 cancel\" onclick=\"MyWidgetResourceLib.PopupHandler.closeParentPopup(this); return false;\" title=\"Cancel\">
								<i class=\"bi bi-backspace icon icon-cancel mr-1 me-1 overflow-visible\"></i>Cancel</button>
							<button class=\"btn btn-sm btn-primary text-nowrap m-1\" data-widget-item-button-add onclick=\"MyWidgetResourceLib.ItemHandler.addResourceItem(this); return false;\" title=\"Add\">
								<i class=\"bi bi-plus-lg icon icon-add mr-1 me-1 overflow-visible\"></i>Add</button>
						</div>
					</form>
				</div></div>
		</div></div>
	<div class=\"modal fade text-left text-start\" id=\"widget_popup_edit_1\" data-widget-popup data-widget-popup-edit tabindex=\"-1\" role=\"dialog\" data-widget-props=\"{&quot;dependent_widgets_id&quot;:&quot;widget_popup_edit_form_1&quot;,&quot;load&quot;:&quot;MyWidgetResourceLib.PopupHandler.loadPopupResource&quot;}\" style=\"background-color:rgba(0, 0, 0, .5);\">
		<div class=\"modal-dialog\">
			<div class=\"modal-content\">
				<div class=\"modal-header\">
					<h5 class=\"modal-title text-muted\">Edit Teacher</h5>
					<button class=\"btn-close\" data-dismiss=\"modal\" data-bs-dismiss=\"modal\" aria-label=\"Close\" title=\"Close Popup\"></button>
				</div>
				<div class=\"modal-body\">
					<form id=\"widget_popup_edit_form_1\" method=\"post\" onsubmit=\"return false;\" data-widget-form data-widget-resources=\"{&quot;load&quot;:[{&quot;error_message&quot;:&quot;No record available...&quot;,&quot;name&quot;:&quot;teacher&quot;}],&quot;update&quot;:[{&quot;error_message&quot;:&quot;Error trying to update. Please try again...&quot;,&quot;success_message&quot;:&quot;Updated successfully!&quot;,&quot;name&quot;:&quot;update_teacher&quot;}],&quot;remove&quot;:[{&quot;error_message&quot;:&quot;Error trying to remove. Please try again...&quot;,&quot;success_message&quot;:&quot;Removed successfully!&quot;,&quot;confirmation_message&quot;:&quot;Are you sure you want to continue?&quot;,&quot;name&quot;:&quot;delete_teacher&quot;}]}\" data-widget-props=\"{&quot;pks_attrs_names&quot;:&quot;teacher_id&quot;,&quot;dependent_widgets_id&quot;:[&quot;widget_list_1&quot;],&quot;load&quot;:&quot;MyWidgetResourceLib.FormHandler.loadFormResource&quot;,&quot;complete&quot;:{&quot;update&quot;:&quot;MyWidgetResourceLib.FormHandler.onUpdatePopupResourceItem&quot;,&quot;remove&quot;:&quot;MyWidgetResourceLib.FormHandler.onRemovePopupResourceItem&quot;},&quot;enter_key_press_button&quot;:&quot;[data-widget-item-button-edit],[data-widget-item-button-update]&quot;}\">
						<div class=\"row mb-3\" data-widget-item-column data-widget-item-attribute-name=\"teacher_id\">
							<label class=\"col-sm-4 col-form-label\" data-widget-item-head>Teacher Id<span class=\"label-colon\">:</span>
							</label>
							<div class=\"col-sm-8\">
								<span class=\"form-control-plaintext\" data-widget-item-attribute-field-edit data-widget-resource-value=\"{&quot;attribute&quot;:&quot;teacher_id&quot;}\"></span>
							</div></div>
						<div class=\"row mb-3\" data-widget-item-column data-widget-item-attribute-name=\"school_id\">
							<label class=\"col-sm-4 col-form-label\" data-widget-item-head>School Id<span class=\"label-colon\">:</span>
								<span class=\"text-danger label-mandatory\">*</span>
							</label>
							<div class=\"col-sm-8\">
								<div data-widget-item-attribute-field-edit data-widget-item-attribute-field-toggle-select-input>
									<div class=\"input-group show\">
										<select class=\"form-control custom-select form-select\" data-widget-resource-value=\"{&quot;attribute&quot;:&quot;school_id&quot;}\" data-allow-null=\"0\" required data-validation-type=\"bigint\" data-validation-message=\"'School Id' field is not a valid number.\" data-validation-label=\"School Id\" placeholder=\"0\" maxlength=\"20\" min=\"0\" max=\"99999999999999999999\" data-widget-props=\"{&quot;load&quot;:&quot;MyWidgetResourceLib.FieldHandler.loadFieldResource&quot;}\" data-widget-resources=\"get_school_school_id_options\" data-widget-item-resources-load=\"\">
											<option value=\"\"></option>
										</select>
										<div class=\"input-group-append\">
											<button class=\"btn btn-outline-secondary\" onclick=\"MyWidgetResourceLib.ItemHandler.toggleItemAttributeSelectFieldToInputField(this); return false;\">
												<span class=\"bi bi-plus-lg icon icon-add\"></span>
											</button>
										</div></div>
									<div class=\"input-group\">
										<input class=\"form-control\" type=\"number\"/>
										<div class=\"input-group-append\">
											<button class=\"btn btn-outline-secondary\" onclick=\"MyWidgetResourceLib.ItemHandler.toggleItemAttributeInputFieldToSelectField(this); return false;\">
												<span class=\"bi bi-search icon icon-search\"></span>
											</button>
										</div></div>
								</div></div>
						</div>
						<div class=\"row mb-3\" data-widget-item-column data-widget-item-attribute-name=\"name\">
							<label class=\"col-sm-4 col-form-label\" data-widget-item-head>Name<span class=\"label-colon\">:</span>
								<span class=\"text-danger label-mandatory\">*</span>
							</label>
							<div class=\"col-sm-8\">
								<input class=\"form-control\" type=\"text\" data-widget-item-attribute-field-edit data-widget-resource-value=\"{&quot;attribute&quot;:&quot;name&quot;}\" data-allow-null=\"0\" required data-validation-label=\"Name\" maxlength=\"50\"/>
							</div></div>
						<div class=\"row mb-3\" data-widget-item-column data-widget-item-attribute-name=\"age\">
							<label class=\"col-sm-4 col-form-label\" data-widget-item-head>Age<span class=\"label-colon\">:</span>
								<span class=\"text-danger label-mandatory\">*</span>
							</label>
							<div class=\"col-sm-8\">
								<input class=\"form-control\" type=\"number\" data-widget-item-attribute-field-edit data-widget-resource-value=\"{&quot;attribute&quot;:&quot;age&quot;}\" data-allow-null=\"0\" required data-validation-type=\"number\" data-validation-message=\"'Age' field is not a valid number.\" data-validation-label=\"Age\" placeholder=\"0\" maxlength=\"2\" max=\"99\"/>
							</div></div>
						<div class=\"text-right text-end mt-4\" data-widget-item-actions-column>
							<button class=\"btn btn-sm btn-secondary text-nowrap m-1 cancel\" onclick=\"MyWidgetResourceLib.PopupHandler.closeParentPopup(this); return false;\" title=\"Cancel\">
								<i class=\"bi bi-backspace icon icon-cancel mr-1 me-1 overflow-visible\"></i>Cancel</button>
							<button class=\"btn btn-sm btn-danger text-nowrap float-left float-start m-1\" data-widget-item-button-remove onclick=\"MyWidgetResourceLib.ItemHandler.removeResourceItem(this); return false;\" title=\"Remove\">
								<i class=\"bi bi-trash icon icon-remove mr-1 me-1 overflow-visible\"></i>Remove</button>
							<button class=\"btn btn-sm btn-primary text-nowrap m-1\" data-widget-item-button-update onclick=\"MyWidgetResourceLib.ItemHandler.updateResourceItem(this); return false;\" title=\"Save\">
								<i class=\"bi bi-save icon icon-save mr-1 me-1 overflow-visible\"></i>Save</button>
						</div>
					</form>
				</div></div>
		</div></div>
</div>");
?>