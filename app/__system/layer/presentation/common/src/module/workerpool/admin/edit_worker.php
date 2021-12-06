<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$path_to_filter = $_GET["path_to_filter"]; //this comes from the amin/admin_citizen.php UI.

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getConfigPath("config");

$tasks = array("setarray", "createform"); //createform bc we need to use the FormFieldsUtilObj.js
include $EVC->getModulePath("common/init_tasks_flow", $common_project_name);

$task_head = $tasks_data["head"];
$task_contents = $tasks_data["contents"]["setarray"];
$js_load_function = $tasks_data["js_load_functions"]["setarray"];

include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getUtilPath("WorkFlowPresentationHandler");
	include $EVC->getModulePath("workerpool/admin/WorkerPoolAdminUtil", $common_project_name);
	
	/* START: PREPARING INIT LAYER SETTINGS */
	//preparing brokers
	$layer_brokers_settings = WorkFlowBeansFileHandler::getLayerBrokersSettings($user_global_variables_file_path, $user_beans_folder_path, $brokers, '$EVC->getBroker');
	
	$layer_name = WorkFlowBeansFileHandler::getLayerNameFromBeanObject($bean_name, $P);
	$layer_folder_name = WorkFlowBeansConverter::getFileNameFromRawLabel($layer_name);
	
	$presentation_brokers = array();
	$presentation_brokers[] = array($layer_name . " (Self)", $bean_file_name, $bean_name);
	$presentation_brokers_obj = array("default" => '$EVC->getPresentationLayer()');
	
	$cms_path_length = strlen(CMS_PATH);
	$layer_types_prefix_paths = array(
		"lib" => substr(LIB_PATH, $cms_path_length),
		"vendor" => substr(VENDOR_PATH, $cms_path_length),
		"dao" => substr(DAO_PATH, $cms_path_length),
		"test_unit" => substr(TEST_UNIT_PATH, $cms_path_length),
		$bean_name => substr($P->getLayerPathSetting(), $cms_path_length),
	);
	
	$worker_pool_class_abs_path = $PEVC->getModulePath("workerpool/work/WorkerPoolWork", $common_project_name);
	$worker_pool_class_cms_path = substr($worker_pool_class_abs_path, $cms_path_length);
	$worker_pool_class_pres_path = substr($worker_pool_class_abs_path, strlen($P->getLayerPathSetting()));
	
	$send_email_worker_pool_class_abs_path = $PEVC->getModulePath("workerpool/work/SendEmailWorkerPoolWork", $common_project_name);
	$send_email_worker_pool_class_cms_path = substr($send_email_worker_pool_class_abs_path, $cms_path_length);
	$send_email_worker_pool_class_pres_path = substr($send_email_worker_pool_class_abs_path, strlen($P->getLayerPathSetting()));
	
	//preparing urls code
	$choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#";
	$choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#";
	$choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#";
	$choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#";

	$get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#";
	
	//preparing head and content
	$head = '
	<!-- Parse_Str -->
	<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/phpjs/functions/strings/parse_str.js"></script>
	
	<!-- Layout UI Editor - MD5 -->
	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquery/js/jquery.md5.js"></script>

	<!-- Layout UI Editor - Add ACE-Editor -->
	<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
	<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>
	<script type="text/javascript" src="' . $proprietary_js_url_prefix . 'jquerytaskflowchart/js/lib/jsPlumbCloneHandler.js"></script>
	<script type="text/javascript" src="' . $proprietary_js_url_prefix . 'jquerytaskflowchart/js/task_flow_chart.js"></script>
	
	<!-- Add Code Beautifier -->
	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/mycodebeautifier/js/codebeautifier.js"></script>

	<!-- Add Html/CSS/JS Beautify code -->
	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jsbeautify/js/lib/beautify.js"></script>
	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jsbeautify/js/lib/beautify-css.js"></script>
	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/myhtmlbeautify/MyHtmlBeautify.js"></script>

	<!-- Layout UI Editor - Jquery Touch Punch to work on mobile devices with touch -->
	<script language="javascript" type="text/javascript" src="http://jplpinto.localhost/__system/common/vendor/jqueryuitouchpunch/jquery.ui.touch-punch.min.js"></script>

	<!-- Layout UI Editor - Jquery Tap-Hold Event JS file -->
	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerytaphold/taphold.js"></script>

	<!-- Layout UI Editor - Material-design-iconic-font -->
	<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/materialdesigniconicfont/css/material-design-iconic-font.min.css">

	<!-- Layout UI Editor - JQuery Nestable2 -->
	<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/nestable2/jquery.nestable.min.css" type="text/css" charset="utf-8" />
	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/nestable2/jquery.nestable.min.js"></script>
	
	<!-- Layout UI Editor - Html Entities Converter -->
	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/he/he.js"></script>
	
	<!-- Layout UI Editor - HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		 <script src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/html5_ie8/html5shiv.min.js"></script>
		 <script src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/html5_ie8/respond.min.js"></script>
	<![endif]-->

	<!-- Layout UI Editor - Add Iframe droppable fix -->
	<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/jquery-ui-droppable-iframe-fix.js"></script>    

	<!-- Layout UI Editor - Add Iframe droppable fix - IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/ie10-viewport-bug-workaround.js"></script>

	<!-- Layout UI Editor - Add Layout UI Editor -->
	<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/css/some_bootstrap_style.css" type="text/css" charset="utf-8" />
	<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/css/style.css" type="text/css" charset="utf-8" />

	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/js/TextSelection.js"></script>
	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/js/LayoutUIEditor.js"></script>
	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/js/CreateWidgetContainerClassObj.js"></script>
	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerylayoutuieditor/js/LayoutUIEditorFormFieldUtil.js"></script>

	<!-- Add MyTree main JS and CSS files -->
	<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>

	<!-- Add FileManager JS file -->
	<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $project_url_prefix . 'js/file_manager.js"></script>
		
	<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />
	<link rel="stylesheet" href="' . $project_url_prefix . 'css/edit_php_code.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $project_url_prefix . 'js/edit_php_code.js"></script>
	
	' . $task_head . '

	<!-- Add Other Settings CSS and JS -->
	<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/common/other_settings.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $project_common_url_prefix . 'module/common/other_settings.js"></script>
	
	<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_worker.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_worker.js"></script>
	<script>
	var js_load_function = ' . ($js_load_function ? $js_load_function : 'null') . ';
	
	var layer_types_prefix_paths = ' . json_encode($layer_types_prefix_paths) . ';
	var layer_folder_name = \'' . $layer_folder_name . '\';
	var path_to_filter = \'' . $path_to_filter . '\';
	var bean_name = \'' . $bean_name . '\';
	var bean_file_name = \'' . $bean_file_name . '\';
	';
	
	$head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url);
	$head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url);
	$head .= '</script>';
	
	$main_content = WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, null, null, null, null, null, $presentation_brokers);
	/* END: PREPARING INIT LAYER SETTINGS */
	
	/* START: PREPARING EDIT PANEL */
	$WorkerPoolAdminUtil = new WorkerPoolAdminUtil($CommonModuleAdminUtil);
	$worker_id = $_GET["worker_id"];
	
	//preparing post action
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			if ($_POST["args_type"] == "array")
				$_POST["args"] = json_decode($_POST["args"], true);
			
			//prepare worker parameters according with the selected type.
			$type = $_POST["type"];
			
			if ($type == "app.layer.$layer_folder_name.common.src.module.workerpool.work.CallExternalFunctionWorkerPoolWork") {
				$_POST["class"] = $type;
				$_POST["args"] = array(
					"function_file" => $_POST["function_file"],
					"function_name" => $_POST["function_name"],
					"function_args" => $_POST["args"],
				);
			}
			else if ($type == "app.layer.$layer_folder_name.common.src.module.workerpool.work.CallExternalClassMethodWorkerPoolWork") {
				$_POST["class"] = $type;
				$_POST["args"] = array(
					"class_method_file" => $_POST["class_method_file"],
					"class_name" => $_POST["class_name"],
					"method_name" => $_POST["method_name"],
					"method_args" => $_POST["args"],
				);
			}
			else if ($type == "app.layer.$layer_folder_name.common.src.module.workerpool.work.SendEmailWorkerPoolWork") {
				$_POST["class"] = $type;
				$_POST["args"] = array(
					"from" => $_POST["from"],
					"to" => $_POST["to"],
					"subject" => $_POST["subject"],
					"content" => $_POST["content"],
					"smtp_host" => $_POST["smtp_host"],
					"smtp_host" => $_POST["smtp_host"],
					"smtp_port" => $_POST["smtp_port"],
					"smtp_user" => $_POST["smtp_user"],
					"smtp_pass" => $_POST["smtp_pass"],
					"smtp_secure" => $_POST["smtp_secure"],
					"args" => $_POST["args"],
				);
			}
			
			$begin_time = $_POST["begin_date"] ? strtotime($_POST["begin_date"]) : 0;
			$end_time = $_POST["end_date"] ? strtotime($_POST["end_date"]) : 0;
			
			//echo "<textarea>".print_r($_POST, 1)."</textarea>";die();
			
			$data = array(
				"worker_id" => $worker_id,
				"class" => $_POST["class"],
				"args" => $_POST["args"],
				"status" => is_numeric($_POST["status"]) ? $_POST["status"] : 0,
				"thread_id" => $_POST["thread_id"],
				"begin_time" => $begin_time,
				"end_time" => $end_time,
				"failed_attempts" => is_numeric($_POST["failed_attempts"]) ? $_POST["failed_attempts"] : 0,
				"description" => $_POST["description"],
			);
			$status = $_POST["add"] ? WorkerPoolUtil::insertWorker($brokers, $data) : WorkerPoolUtil::updateWorker($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = WorkerPoolUtil::deleteWorker($brokers, $worker_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Worker ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_worker") . "worker_id=$status&path_to_filter=$path_to_filter";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this worker. Please try again...";
			}
		}
	}
	
	//preparing get action
	$data = WorkerPoolUtil::getWorker($brokers, $worker_id, null, true);
	//echo "<pre>";print_r($data);die();
	
	//prepare $data according with the selected class. Creates: type, function_name, class_name, method_name...
	if ($data["class"] == "app.layer.$layer_folder_name.common.src.module.workerpool.work.CallExternalFunctionWorkerPoolWork" && is_array($data["args"])) {
		$data["type"] = $data["class"];
		
		if (is_array($data["args"])) {
			$data["function_file"] = $data["args"]["function_file"];
			$data["function_name"] = $data["args"]["function_name"];
			$data["args"] = $data["args"]["function_args"];
		}
	}
	else if ($data["class"] == "app.layer.$layer_folder_name.common.src.module.workerpool.work.CallExternalClassMethodWorkerPoolWork" && is_array($data["args"])) {
		$data["type"] = $data["class"];
		
		if (is_array($data["args"])) {
			$data["class_method_file"] = $data["args"]["class_method_file"];
			$data["class_name"] = $data["args"]["class_name"];
			$data["method_name"] = $data["args"]["method_name"];
			$data["args"] = $data["args"]["method_args"];
		}
	}
	else if ($data["class"] == "app.layer.$layer_folder_name.common.src.module.workerpool.work.SendEmailWorkerPoolWork") {
		$data["type"] = $data["class"];
		
		if (is_array($data["args"])) {
			$data["from"] = $data["args"]["from"];
			$data["to"] = $data["args"]["to"];
			$data["subject"] = $data["args"]["subject"];
			$data["content"] = $data["args"]["content"];
			$data["smtp_host"] = $data["args"]["smtp_host"];
			$data["smtp_port"] = $data["args"]["smtp_port"];
			$data["smtp_user"] = $data["args"]["smtp_user"];
			$data["smtp_pass"] = $data["args"]["smtp_pass"];
			$data["smtp_secure"] = $data["args"]["smtp_secure"];
			$data["args"] = $data["args"]["args"];
		}
	}
	
	if ($data["begin_time"])
		$data["begin_date"] = str_replace(" ", "T", date("Y-m-d H:i:s", $data["begin_time"]));
	
	if ($data["end_time"])
		$data["end_date"] = str_replace(" ", "T", date("Y-m-d H:i:s", $data["end_time"]));
	
	if ($data["args"] && is_array($data["args"]))
		$data["args"] = json_encode($data["args"]);
	
	//preparing HTML
	$statuses_options = array();
	$worker_available_statuses = $WORKER_AVAILABLE_STATUSES = WorkerPoolUtil::getConstantVariable("WORKER_AVAILABLE_STATUSES");
	
	if ($worker_available_statuses)
		foreach ($worker_available_statuses as $status_id => $status_label)
			$statuses_options[] = array("value" => $status_id, "label" => $status_label);
	
	$class_contents = '
		<span class="icon search search_page_url" onclick="onIncludeWorkerFileTaskChooseFile(this)" title="Search File">Search file</span>
		<div class="info">
			The Worker class must implements the "WorkerPoolWork" class in "<a href="' . $project_url_prefix . 'admin/view_file?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $worker_pool_class_pres_path . '" target="WorkerPoolWork">' . $worker_pool_class_cms_path . '</a>".<br/>
			You can see an example in "<a href="' . $project_url_prefix . 'admin/view_file?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $send_email_worker_pool_class_pres_path . '" target="SendEmailWorkerPoolWork">' . $send_email_worker_pool_class_cms_path . '</a>".
		</div>';
	
	$args_contents = '<div class="selected_task_properties">
			<select class="args_type" name="args_type" onChange="onEditWorkerArgsTypeChange(this)">
				<option value="">json code</option>
				<option>array</option>
			</select>
			<textarea class="args_code"></textarea>
			' . $task_contents . '
		</div>';
	
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Worker '$worker_id'" : "Add Worker",
		"fields" => array(
			"worker_id" => $data ? "label" : "hidden",
			"type" => array("type" => "select", "label" => "Worker Type: ", "options" => array(
					"" => "Call a Worker class. Must implements the 'WorkerPoolWork' class",
					"app.layer.$layer_folder_name.common.src.module.workerpool.work.CallExternalFunctionWorkerPoolWork" => "Call external function",
					"app.layer.$layer_folder_name.common.src.module.workerpool.work.CallExternalClassMethodWorkerPoolWork" => "Call external class method",
					"app.layer.$layer_folder_name.common.src.module.workerpool.work.SendEmailWorkerPoolWork" => "Send an email",
				), "extra_attributes" => array(
					"onChange" => "onChangeWorkerType(this)",
				)
			),
			"class" => array("type" => "text", "label" => "Worker Class: ", "next_html" => $class_contents),
			
			//CallExternalFunctionWorkerPoolWork fields
			"function_file" => array("type" => "text", "class" => "function", "label" => "File: ", 
				"next_html" => '<span class="icon search search_page_url" onclick="onIncludeWorkerFileTaskChooseFunction(this)" title="Search File">Search Function</span>'
			),
			"function_name" => array("type" => "text", "class" => "function"),
			
			//CallExternalClassMethodWorkerPoolWork fields
			"class_method_file" => array("type" => "text", "class" => "class_method", "label" => "File: ", 
				"next_html" => '<span class="icon search search_page_url" onclick="onIncludeWorkerFileTaskChooseClassMethod(this)" title="Search File">Search Class Method</span>'
			),
			"class_name" => array("type" => "text", "class" => "class_method"),
			"method_name" => array("type" => "text", "class" => "class_method"),
			
			//SendEmailWorkerPoolWork fields
			"from" => array("type" => "text", "class" => "send_email"),
			"to" => array("type" => "text", "class" => "send_email"),
			"subject" => array("type" => "text", "class" => "send_email"),
			"content" => array("type" => "textarea", "class" => "send_email"),
			"smtp_host" => array("type" => "text", "class" => "send_email"),
			"smtp_port" => array("type" => "text", "class" => "send_email"),
			"smtp_user" => array("type" => "text", "class" => "send_email"),
			"smtp_pass" => array("type" => "password", "class" => "send_email"),
			"smtp_secure" => array("type" => "select", "class" => "send_email", "options" => array(
				"" => "-- none --", 
				"ssl" => "SSL",
				"tls" => "TLS",
			)),
			
			//generic fields
			"args" => array("type" => "textarea", "label" => "Worker Args: ", "next_html" => $args_contents),
			"description" => "textarea",
			"status" => array("type" => "select", "class" => "worker_advanced_field", "options" => $statuses_options),
			"thread_id" => array("type" => "text", "class" => "worker_advanced_field"),
			"failed_attempts" => array("type" => "number", "class" => "worker_advanced_field"),
			"begin_date" => array("type" => "datetime-local", "class" => "worker_advanced_field"),
			"end_date" => array("type" => "datetime-local", "class" => "worker_advanced_field"),
			"created_date" => array("type" => ($data ? "label" : "hidden"), "class" => "worker_advanced_field"),
			"modified_date" => array("type" => ($data ? "label" : "hidden"), "class" => "worker_advanced_field"),
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$menu_settings = $WorkerPoolAdminUtil->getMenuSettings("&path_to_filter=$path_to_filter");
	$main_content .= $CommonModuleAdminUtil->getFormContent($form_settings);
	/* END: PREPARING EDIT PANEL */
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
