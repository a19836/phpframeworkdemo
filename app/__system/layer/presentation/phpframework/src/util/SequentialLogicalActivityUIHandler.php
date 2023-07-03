<?php
/*
 * Copyright (c) 2007 PHPMyFrameWork - Joao Pinto
 * AUTHOR: Joao Paulo Lopes Pinto -- http://jplpinto.com
 * 
 * The use of this code must be allowed first by the creator Joao Pinto, since this is a private and proprietary code.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS 
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY 
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR 
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL 
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER 
 * IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT 
 * OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. IN NO EVENT SHALL 
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN 
 * AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE 
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler"); include_once $EVC->getUtilPath("WorkFlowBrokersSelectedDBVarsHandler"); include_once $EVC->getUtilPath("WorkFlowUIHandler"); include_once $EVC->getUtilPath("WorkFlowQueryHandler"); include_once $EVC->getUtilPath("WorkFlowPresentationHandler"); include_once $EVC->getUtilPath("CMSPresentationUIAutomaticFilesHandler"); include_once $EVC->getUtilPath("LayoutTypeProjectHandler"); include_once $EVC->getUtilPath("LayoutTypeProjectUIHandler"); class SequentialLogicalActivityUIHandler { public static function getHeader($v08d9602741, $v188b4f5fa6, $pdf77ee66, $v8ffce2a791, $pa0462a8e, $pa32be502, $peb014cfd, $v37d269c4fa, $v3b2000c17b, $pb5790ec9, $v3d55458bcd, $v5039a77f9d, $v4bf8d90f04, $pfce4d1b3, $pb154d332, $pebb3f429 = false) { $v0a9dad1fe0 = ""; $v1fac4509df = $pebb3f429["main_div_selector"]; $v125cfae519 = $pebb3f429["allowed_tasks_tag"]; $pd0cdca01 = $pebb3f429["extra_tasks_folder_path"]; $v521be90a09 = $pebb3f429["tasks_groups_by_tag"]; $pac87cfac = $pebb3f429["ui_menu_widgets_selector"]; if (!$v125cfae519) $v125cfae519 = array("slaitemsingle", "slaitemgroup"); if (!$v521be90a09) $v521be90a09 = array( "SLA Groups/Actions" => array("slaitemsingle", "slaitemgroup") ); if (!$pac87cfac) $pac87cfac = ".sla_ui_menu_widgets_backup"; $v9ab35f1f0d = $v188b4f5fa6->getPresentationLayer(); $v6c1c99fc85 = CMSPresentationLayerHandler::getPresentationLayerProjectsFiles($v3d55458bcd, $v5039a77f9d, $pa0462a8e, $v8ffce2a791); $v1eb9193558 = new LayoutTypeProjectHandler($pdf77ee66, $v3d55458bcd, $v5039a77f9d, $pa0462a8e, $v8ffce2a791); $v1eb9193558->filterPresentationLayerProjectsByUserAndLayoutPermissions($v6c1c99fc85, $pb154d332, null, array( "do_not_filter_by_layout" => array( "bean_name" => $v8ffce2a791, "bean_file_name" => $pa0462a8e, "project" => $v9ab35f1f0d->getSelectedPresentationId(), ) )); $v6c1c99fc85 = array_keys($v6c1c99fc85); $pc4223ce1 = $v9ab35f1f0d->getBrokers(); $v7a0994a134 = WorkFlowBeansFileHandler::getLayerBrokersSettings($v3d55458bcd, $v5039a77f9d, $pc4223ce1, '$EVC->getBroker'); $v8671b32a11 = WorkFlowBeansFileHandler::getLayerNameFromBeanObject($v8ffce2a791, $v9ab35f1f0d) . " (Self)"; $pb0e92e25 = array( array($v8671b32a11, $pa0462a8e, $v8ffce2a791) ); $v89e7b130c6 = array("default" => '$EVC->getPresentationLayer()'); $v7a0994a134["presentation_brokers"] = $pb0e92e25; $v7a0994a134["presentation_brokers_obj"] = $v89e7b130c6; $v6e9af47944 = $v7a0994a134["business_logic_brokers"]; $v5421227efb = $v7a0994a134["business_logic_brokers_obj"]; $v9fda9fad47 = $v7a0994a134["data_access_brokers"]; $pdeced6cd = $v7a0994a134["data_access_brokers_obj"]; $pf864769c = $v7a0994a134["ibatis_brokers"]; $v6a3a9f9182 = $v7a0994a134["ibatis_brokers_obj"]; $paf75a67c = $v7a0994a134["hibernate_brokers"]; $pbf7e8fcb = $v7a0994a134["hibernate_brokers_obj"]; $v5483bfa973 = $v7a0994a134["db_brokers"]; $v78844cd25d = $v7a0994a134["db_brokers_obj"]; $pb7b19dbe = array( $v8671b32a11 => $pb0e92e25[0] ); $v1f410c3f6b = array( $v89e7b130c6["default"] => $v8671b32a11, ); foreach ($v6e9af47944 as $v7aeaf992f5) { $v2b2cf4c0eb = $v7aeaf992f5[0]; $pb7b19dbe[$v2b2cf4c0eb] = $v7aeaf992f5; $v1f410c3f6b[ $v5421227efb[$v2b2cf4c0eb] ] = $v2b2cf4c0eb; } foreach ($v9fda9fad47 as $v7aeaf992f5) { $v2b2cf4c0eb = $v7aeaf992f5[0]; $pb7b19dbe[$v2b2cf4c0eb] = $v7aeaf992f5; $v1f410c3f6b[ $pdeced6cd[$v2b2cf4c0eb] ] = $v2b2cf4c0eb; } $v2aa21ff4fa = array("createform", "callfunction", "callobjectmethod", "restconnector", "soapconnector"); $v125cfae519 = is_array($v125cfae519) ? $v125cfae519 : ($v125cfae519 ? array($v125cfae519) : array()); $v125cfae519 = $v125cfae519 ? array_merge($v2aa21ff4fa, $v125cfae519) : $v2aa21ff4fa; if ($pdeced6cd) { $v125cfae519[] = "query"; $v125cfae519[] = "getquerydata"; $v125cfae519[] = "setquerydata"; if ($v6a3a9f9182) $v125cfae519[] = "callibatisquery"; if ($pbf7e8fcb) $v125cfae519[] = "callhibernatemethod"; } else if ($v78844cd25d) { $v125cfae519[] = "query"; $v125cfae519[] = "getquerydata"; $v125cfae519[] = "setquerydata"; } if ($v5421227efb) $v125cfae519[] = "callbusinesslogic"; $pecad7cca = new WorkFlowTaskHandler($v4bf8d90f04, $pfce4d1b3); $pecad7cca->setCacheRootPath(LAYER_CACHE_PATH); $pecad7cca->setAllowedTaskTags($v125cfae519); if ($pd0cdca01) $pecad7cca->addTasksFolderPath($pd0cdca01); $v0fec18f1f9 = array( "disabled" => array("createform", "callfunction", "callobjectmethod", "restconnector", "soapconnector", "query", "getquerydata", "setquerydata", "callibatisquery", "callhibernatemethod", "callbusinesslogic"), ); $v521be90a09 = is_array($v521be90a09) ? $v521be90a09 : ($v521be90a09 ? array($v521be90a09) : array()); $v521be90a09 = $v521be90a09 ? array_merge($v0fec18f1f9, $v521be90a09) : $v0fec18f1f9; $pcfdeae4e = new WorkFlowUIHandler($pecad7cca, $peb014cfd, $v37d269c4fa, $v3b2000c17b, $pb5790ec9, $v3d55458bcd, $v4bf8d90f04, $pfce4d1b3); $pcfdeae4e->setTasksGroupsByTag($v521be90a09); $v524bbc0f84 = $pecad7cca->getLoadedTasksSettings(); $v830c74e006 = array(); $v0e9b8a0e96 = array(); foreach ($v524bbc0f84 as $v93feab0020 => $pcbf3c2f0) { foreach ($pcbf3c2f0 as $pc8421459 => $v003bc751fd) { if (is_array($v003bc751fd)) { $v1b1c6a10a2 = $v003bc751fd["tag"]; $v830c74e006[$v1b1c6a10a2] = $v003bc751fd["task_properties_html"]; $v0e9b8a0e96[$v1b1c6a10a2] = $v003bc751fd["settings"]["callback"]["on_load_task_properties"]; } } } $pe44aa1fe = WorkFlowBrokersSelectedDBVarsHandler::getBrokersSelectedDBVars($pc4223ce1); $v9b98e0e818 = $pe44aa1fe["db_brokers_drivers"]; $pd7f46171 = $pe44aa1fe["dal_broker"]; $pc66a0204 = $pe44aa1fe["db_driver"]; $v5a331eab7e = $pe44aa1fe["type"]; if ($v9b98e0e818) foreach ($v9b98e0e818 as $v0a5deb92d8 => &$pb56484b3) $v1eb9193558->filterLayerBrokersDBDriversNamesFromLayoutName($v9ab35f1f0d, $pb56484b3, $pb154d332); $pea5fed5d = LayoutTypeProjectUIHandler::getFilterByLayoutURLQuery($pb154d332); $pa0c6ce9a = $peb014cfd . "phpframework/dataaccess/get_query_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&db_driver=#db_driver#&db_type=#db_type#&path=#path#&query_type=#query_type#&query=#query#&obj=#obj#&relationship_type=#relationship_type#"; $v4ee0f690b1 = $peb014cfd . "phpframework/dataaccess/get_query_result_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&db_driver=" . $pc66a0204 . "&module_id=#module_id#&query_type=#query_type#&query=#query#&rel_name=#rel_name#&obj=#obj#&relationship_type=#relationship_type#"; $v9d21f98a2c = $peb014cfd . "phpframework/businesslogic/get_business_logic_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&service=#service#"; $v06d0841ec2 = $peb014cfd . "phpframework/businesslogic/get_business_logic_result_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&module_id=#module_id#&service=#service#&db_driver=" . $pc66a0204; $v4d78e2ccd0 = $peb014cfd . "phpframework/presentation/get_util_result_properties?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=$pa32be502&class_path=#class_path#&class_name=#class_name#&method=#method#&db_driver=" . $pc66a0204; $v26f1ca5c9c = $peb014cfd . "phpframework/db/get_broker_db_drivers?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&broker=#broker#&item_type=presentation"; $v79a281fbbc = $peb014cfd . "phpframework/admin/edit_task_source?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=$pa32be502"; $pac9213a9 = $peb014cfd . "phpframework/sequentiallogicalactivity/get_sla_action_result_properties"; $v0bc2b49b83 = $peb014cfd . "phpframework/sequentiallogicalactivity/convert_sla_settings_to_php_code?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e&path=$pa32be502"; $pb03d3981 = $peb014cfd . "phpframework/sequentiallogicalactivity/create_sla_settings_code"; $v960400fcd0 = $peb014cfd . "phpframework/sequentiallogicalactivity/create_sla_resource?bean_name=$v8ffce2a791&bean_file_name=$pa0462a8e$pea5fed5d&path=$pa32be502"; $pa8dedb03 = '
		var default_extension = "' . $v9ab35f1f0d->getPresentationFileExtension() . '";
		
		//prepare generic urls
		var get_query_properties_url = \'' . $pa0c6ce9a . '\';
		var get_query_result_properties_url = \'' . $v4ee0f690b1 . '\';
		var get_business_logic_properties_url = \'' . $v9d21f98a2c . '\';
		var get_business_logic_result_properties_url = \'' . $v06d0841ec2 . '\';
		var get_util_result_properties_url = \'' . $v4d78e2ccd0 . '\';
		var get_broker_db_drivers_url = \'' . $v26f1ca5c9c . '\';
		var edit_task_source_url = \'' . $v79a281fbbc . '\';
		
		//prepare sla urls
		var get_sla_action_result_properties_url = \'' . $pac9213a9 . '\';
		var convert_sla_settings_to_php_code_url = \'' . $v0bc2b49b83 . '\';
		var create_sla_settings_code_url = \'' . $pb03d3981 . '\';
		var create_sla_resource_url = \'' . $v960400fcd0 . '\';
		
		//prepare workflow tasks
		var js_load_functions = ' . json_encode($v0e9b8a0e96) . ';
		
		ProgrammingTaskUtil.on_programming_task_edit_source_callback = onProgrammingTaskEditSource;
		ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback = onProgrammingTaskChooseCreatedVariable;
		ProgrammingTaskUtil.on_programming_task_choose_object_method_callback = onProgrammingTaskChooseObjectMethod;
		ProgrammingTaskUtil.on_programming_task_choose_function_callback = onProgrammingTaskChooseFunction;
		ProgrammingTaskUtil.on_programming_task_choose_file_path_callback = onIncludeFileTaskChooseFile;
		ProgrammingTaskUtil.on_programming_task_choose_folder_path_callback = onIncludeFolderTaskChooseFile;
		ProgrammingTaskUtil.on_programming_task_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
		ProgrammingTaskUtil.on_programming_task_choose_image_url_callback = onIncludeImageUrlTaskChooseFile;
		
		if (typeof CreateFormTaskPropertyObj != "undefined" && CreateFormTaskPropertyObj) {
			CreateFormTaskPropertyObj.editor_ready_func = initLayoutUIEditorWidgetResourceOptions;
			CreateFormTaskPropertyObj.layout_ui_editor_menu_widgets_elm_selector = \'' . $pac87cfac . '\';
		}
		
		if (typeof LayerOptionsUtilObj != "undefined" && LayerOptionsUtilObj)
			LayerOptionsUtilObj.on_choose_db_driver_callback = onChooseDBDriver;
		
		if (typeof CallBusinessLogicTaskPropertyObj != "undefined" && CallBusinessLogicTaskPropertyObj) {
			CallBusinessLogicTaskPropertyObj.on_choose_business_logic_callback = onBusinessLogicTaskChooseBusinessLogic;
			CallBusinessLogicTaskPropertyObj.brokers_options = ' . json_encode($v5421227efb) . ';
		}

		if (typeof CallIbatisQueryTaskPropertyObj != "undefined" && CallIbatisQueryTaskPropertyObj) {
			CallIbatisQueryTaskPropertyObj.on_choose_query_callback = onChooseIbatisQuery;
			CallIbatisQueryTaskPropertyObj.brokers_options = ' . json_encode($v6a3a9f9182) . ';
		}

		if (typeof CallHibernateMethodTaskPropertyObj != "undefined" && CallHibernateMethodTaskPropertyObj) {
			CallHibernateMethodTaskPropertyObj.on_choose_hibernate_object_method_callback = onChooseHibernateObjectMethod;
			CallHibernateMethodTaskPropertyObj.brokers_options = ' . json_encode($pbf7e8fcb) . ';
		}

		if (typeof GetQueryDataTaskPropertyObj != "undefined" && GetQueryDataTaskPropertyObj) {
			GetQueryDataTaskPropertyObj.brokers_options = ' . json_encode(array_merge($v78844cd25d, $pdeced6cd)) . ';
		}

		if (typeof SetQueryDataTaskPropertyObj != "undefined" && SetQueryDataTaskPropertyObj) {
			SetQueryDataTaskPropertyObj.brokers_options = ' . json_encode(array_merge($v78844cd25d, $pdeced6cd)) . ';
		}
		
		if (typeof DBQueryTaskPropertyObj != "undefined" && DBQueryTaskPropertyObj) {
			DBQueryTaskPropertyObj.show_properties_on_connection_drop = true;
		}
		
		//prepare brokers
		var brokers_settings = ' . json_encode($pb7b19dbe) . ';
		var brokers_name_by_obj_code = ' . json_encode($v1f410c3f6b) . ';
		'; if ($v9b98e0e818) { $v546cf76c77 = new WorkFlowTaskHandler($v4bf8d90f04, $pfce4d1b3); $v546cf76c77->setCacheRootPath(LAYER_CACHE_PATH); $v546cf76c77->setAllowedTaskTags(array("query")); $pb692084d = new WorkFlowUIHandler($v546cf76c77, $peb014cfd, $v37d269c4fa, $v3b2000c17b, $pb5790ec9, $v3d55458bcd, $v4bf8d90f04, $pfce4d1b3); $v0a1f4a55aa = new WorkFlowQueryHandler($pb692084d, $peb014cfd, $v37d269c4fa, $v9b98e0e818, $pd7f46171, $pc66a0204, $v5a331eab7e, "", array(), array(), array(), array()); $pbb688020 .= $v0a1f4a55aa->getDataAccessJavascript($v8ffce2a791, $pa0462a8e, $pa32be502, "presentation", null, null); $pa8dedb03 .= str_replace('<script>', '', str_replace('</script>', '', $pbb688020)); $pa8dedb03 .= 'get_broker_db_data_url += "&global_default_db_driver_broker=' . $GLOBALS["default_db_broker"] . '";'; $pf8ed4912 = $v0a1f4a55aa->getGlobalTaskFlowChar(); $pf8ed4912 .= $v0a1f4a55aa->getQueryBlockHtml(); $pa8dedb03 .= 'var query_task_html = \'' . addcslashes(str_replace("\n", "", $pf8ed4912), "\\'") . '\';'; if ($v1fac4509df) { $pf8ed4912 = $v0a1f4a55aa->getChooseQueryTableOrAttributeHtml("choose_db_table_or_attribute"); $pa8dedb03 .= '
				var choose_db_table_or_attribute_elm = $( \'' . addcslashes(str_replace("\n", "", $pf8ed4912), "\\'") . '\' );
				
				$(function() {
					if ($("#choose_db_table_or_attribute").length == 0)
						$("' . $v1fac4509df . '").append(choose_db_table_or_attribute_elm);
				});
				'; } $pa8dedb03 .= WorkFlowBrokersSelectedDBVarsHandler::printSelectedDBVarsJavascriptCode($peb014cfd, $v8ffce2a791, $pa0462a8e, null); $pa8dedb03 .= '
			getDBTables("' . $pd7f46171 . '", "' . $pc66a0204 . '", "' . $v5a331eab7e . '");
		
			var db_tables = db_brokers_drivers_tables_attributes["' . $pd7f46171 . '"] && db_brokers_drivers_tables_attributes["' . $pd7f46171 . '"]["' . $pc66a0204 . '"] ? db_brokers_drivers_tables_attributes["' . $pd7f46171 . '"]["' . $pc66a0204 . '"]["' . $v5a331eab7e . '"] : null;
			
			if (db_tables) {
				var html = "<option></option>";
				for (var db_table in db_tables) {
					html += "<option>" + db_table + "</option>";
				}
				choose_db_table_or_attribute_elm.find(".db_table select").html(html);
			}
			
			choose_db_table_or_attribute_elm.find(".db_broker > select").change(function() {
				onChangePopupDBBrokers(this);
			});
			
			choose_db_table_or_attribute_elm.find(".db_driver > select").change(function() {
				onChangePopupDBDrivers(this);
			});
			
			choose_db_table_or_attribute_elm.find(".type > select").change(function() {
				onChangePopupDBTypes(this);
			});
			'; $v0a9dad1fe0 .= '
			<!-- DBQUERY TASK - Add Edit-Query JS and CSS files -->
			<link rel="stylesheet" href="' . $peb014cfd . 'css/dataaccess/edit_query.css" type="text/css" charset="utf-8" />
			<script language="javascript" type="text/javascript" src="' . $peb014cfd . 'js/dataaccess/edit_query.js"></script>'; } $v3ddc0d1bd3 = CMSPresentationUIAutomaticFilesHandler::isUserModuleInstalled($v188b4f5fa6); $v902a67f557 = $v18e8e0c60b = array(); if ($v3ddc0d1bd3) { $v902a67f557 = CMSPresentationUIAutomaticFilesHandler::getAvailableUserTypes($v188b4f5fa6); $v18e8e0c60b = CMSPresentationUIAutomaticFilesHandler::getAvailableActivities($v188b4f5fa6); } $pa8dedb03 .= '
		var available_user_types = ' . json_encode($v902a67f557) . ';
		var available_activities = ' . json_encode($v18e8e0c60b) . ';'; $v50890f6f30 = self::getWorkflowHeader($v188b4f5fa6, $pdf77ee66, $v8ffce2a791, $pa0462a8e, $pa32be502, $peb014cfd, $v37d269c4fa, $v3b2000c17b, $pb5790ec9, $v3d55458bcd, $v5039a77f9d, $v4bf8d90f04, $pfce4d1b3, $pb154d332, $pecad7cca, $pebb3f429); if ($v50890f6f30) { $pcb6a2cab = $v50890f6f30["js_head"]; $v8555f2f905 = $v50890f6f30["set_workflow_file_url"]; $v238161ae8d = $v50890f6f30["get_workflow_file_url"]; $pa8dedb03 .= $pcb6a2cab; } $pa8dedb03 .= '
		var php_numeric_types = ' . json_encode(ObjTypeHandler::getPHPNumericTypes()) . ';
		var db_numeric_types = ' . json_encode(ObjTypeHandler::getDBNumericTypes()) . ';'; $v7726c9ee2f = array_values( array_map('strtolower', array_unique( array_merge( ObjTypeHandler::getDBAttributeNameCreatedDateAvailableValues(), ObjTypeHandler::getDBAttributeNameModifiedDateAvailableValues(), ObjTypeHandler::getDBAttributeNameCreatedUserIdAvailableValues(), ObjTypeHandler::getDBAttributeNameModifiedUserIdAvailableValues() ) ) ) ); $pa8dedb03 .= '
		var internal_attribute_names = ' . json_encode($v7726c9ee2f) . ';'; return array( "head" => $v0a9dad1fe0, "js_head" => $pa8dedb03, "tasks_contents" => $v830c74e006, "layer_brokers_settings" => $v7a0994a134, "presentation_projects" => $v6c1c99fc85, "db_drivers" => $v9b98e0e818, "WorkFlowTaskHandler" => $pecad7cca, "WorkFlowUIHandler" => $pcfdeae4e, "set_workflow_file_url" => $v8555f2f905, "get_workflow_file_url" => $v238161ae8d, ); } public static function getWorkflowHeader($v188b4f5fa6, $pdf77ee66, $v8ffce2a791, $pa0462a8e, $pa32be502, $peb014cfd, $v37d269c4fa, $v3b2000c17b, $pb5790ec9, $v3d55458bcd, $v5039a77f9d, $v4bf8d90f04, $pfce4d1b3, $pb154d332, $pecad7cca, $pebb3f429 = false) { $v8484328271 = $pebb3f429["workflow_tasks_id"]; $pd9acc397 = $pebb3f429["path_extra"]; if ($v8484328271) { $pec987416 = $v8484328271 . "&path_extra=_$pd9acc397"; $v240665d486 = $v8484328271 . "_tmp&path_extra=_{$pd9acc397}_" . rand(0, 1000); $v8555f2f905 = $peb014cfd . "workflow/set_workflow_file?path={$pec987416}"; $v238161ae8d = $peb014cfd . "workflow/get_workflow_file?path={$pec987416}"; $pfb41478d = $peb014cfd . "workflow/get_workflow_file?path={$v240665d486}"; $paa9d13a1 = $peb014cfd . "workflow/set_workflow_file?path={$v240665d486}"; $pc8fa5eaa = $peb014cfd . "phpframework/sequentiallogicalactivity/create_sla_workflow_file_from_settings?path={$v240665d486}&loaded_tasks_settings_cache_id=" . $pecad7cca->getLoadedTasksSettingsCacheId(); $v5335d566a3 = $peb014cfd . "phpframework/sequentiallogicalactivity/create_sla_settings_from_workflow_file?path={$v240665d486}"; $pa8dedb03 = '
			//prepare workflow urls
			var get_workflow_file_url = \'' . $v238161ae8d . '\';
			var get_tmp_workflow_file_url = \'' . $pfb41478d . '\';
			var set_tmp_workflow_file_url = \'' . $paa9d13a1 . '\';
			var create_sla_workflow_file_from_settings_url = \'' . $pc8fa5eaa . '\';
			var create_sla_settings_from_workflow_file_url = \'' . $v5335d566a3 . '\';
			'; return array( "js_head" => $pa8dedb03, "set_workflow_file_url" => $v8555f2f905, "get_workflow_file_url" => $v238161ae8d, ); } return null; } public static function getUIMenuWidgetsHTML($v08d9602741, $v37d269c4fa, $v3e187ca1b8, $v4bf8d90f04, $pfce4d1b3) { $v0345b66144 = $v08d9602741->getWebrootPath($v08d9602741->getCommonProjectName()); $pefdd2109 = WorkFlowPresentationHandler::getUIEditorWidgetsHtml($v0345b66144, $v37d269c4fa, $v4bf8d90f04, $pfce4d1b3, array("avoided_widgets" => array("php"))); $pefdd2109 .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($v0345b66144, $v08d9602741->getViewsPath() . "presentation/common_editor_widget/", $v4bf8d90f04, $pfce4d1b3); $pefdd2109 .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml($v0345b66144, $v3e187ca1b8, $v4bf8d90f04, $pfce4d1b3); return $pefdd2109; } public static function getSLAHtml($v08d9602741, $peb014cfd, $v37d269c4fa, $v3e187ca1b8, $v4bf8d90f04, $pfce4d1b3, $v6490ea3a15, $v9b98e0e818, $v6c1c99fc85, $pcfdeae4e, $pebb3f429 = null) { $v79d9dffbb9 = self::getGroupsFlowHtml($v08d9602741, $peb014cfd, $v37d269c4fa, $v3e187ca1b8, $v4bf8d90f04, $pfce4d1b3, $v6490ea3a15, $v9b98e0e818, $v6c1c99fc85, $pebb3f429); $v166b6fac8f = self::getTasksFlowHtml($v08d9602741, $peb014cfd, $v37d269c4fa, $v3e187ca1b8, $v4bf8d90f04, $pfce4d1b3, $v6490ea3a15, $v9b98e0e818, $v6c1c99fc85, $pcfdeae4e, $pebb3f429); $pf8ed4912 = '
			<div class="sla">
				<ul class="tabs tabs_transparent tabs_right tabs_icons">
					<li id="sla_groups_flow_tab"><a href="#sla_groups_flow" onClick="onClickSLAGroupsFlowTab(this);return false;"><i class="icon sla_tab"></i> By Sequential Actions</a></li>
					<li id="tasks_flow_tab"><a href="#ui" onClick="onClickSLATaskWorkflowTab(this);return false;"><i class="icon tasks_flow_tab"></i> By Diagram</a></li>
				</ul>
				
				' . $v79d9dffbb9 . '
				' . $v166b6fac8f . '
			</div>'; return $pf8ed4912; } public static function getTasksFlowHtml($v08d9602741, $peb014cfd, $v37d269c4fa, $v3e187ca1b8, $v4bf8d90f04, $pfce4d1b3, $v6490ea3a15, $v9b98e0e818, $v6c1c99fc85, $pcfdeae4e, $pebb3f429 = null) { $pb085e184 = $pebb3f429 ? $pebb3f429["save_func"]: null; $pf8ed4912 = '
			<div id="ui">
				' . WorkFlowPresentationHandler::getTaskFlowContentHtml($pcfdeae4e, array( "save_func" => $pb085e184, "generate_code_from_tasks_flow_label" => "Generate Groups from Diagram", "generate_code_from_tasks_flow_func" => "generateSLAGroupsFromTasksFlow", "generate_tasks_flow_from_code_label" => "Generate Diagram from Groups/Actions", "generate_tasks_flow_from_code_func" => "generateSLATasksFlowFromGroups", )) . '
				<div class="sla_tasks_flow_ui_menu_widgets_backup hidden">
					' . self::getUIMenuWidgetsHTML($v08d9602741, $v37d269c4fa, $v3e187ca1b8, $v4bf8d90f04, $pfce4d1b3) . '
				</div>
				
				<script>
					var ui = $(".sla #ui");
					var mwb = ui.find(".sla_tasks_flow_ui_menu_widgets_backup");
					var create_form_task_html = ui.find(".create_form_task_html");
					create_form_task_html.find(".ptl_settings > .layout-ui-editor > .menu-widgets").append( mwb.contents().clone() );
					ui.find(".inlinehtml_task_html > .layout-ui-editor > .menu-widgets").append( mwb.contents() );
					mwb.remove();
					
						create_form_task_html.children(".separate_settings_from_input, .form_input, .form_input_data, .separate_input_from_result, .result, .task_property_exit").remove();
				</script>
			</div>'; return $pf8ed4912; } public static function getGroupsFlowHtml($v08d9602741, $peb014cfd, $v37d269c4fa, $v3e187ca1b8, $v4bf8d90f04, $pfce4d1b3, $v6490ea3a15, $v9b98e0e818, $v6c1c99fc85, $pebb3f429 = null) { $v7cb32cf01b = $pebb3f429 ? $pebb3f429["extra_short_actions_html"] : ""; $pf8ed4912 = '
			<div id="sla_groups_flow" class="sla_groups_flow">
				<nav>
					<a class="add_sla_group" onClick="addAndInitNewSLAGroup(this)">Add Action <i class="icon add"></i></a>
					<a class="collapse_sla_groups" onClick="collapseSLAGroups(this)">Collapse Actions <i class="icon collapse_content"></i></a>
					<a class="expand_sla_groups" onClick="expandSLAGroups(this)">Expand Actions <i class="icon expand_content"></i></a>
					
					' . $v7cb32cf01b . '
				</nav>
				
				<ul class="sla_groups sla_main_groups">
					<li class="sla_group_item sla_group_default">
						' . self::getGroupItemHtml($v08d9602741, $peb014cfd, $v37d269c4fa, $v3e187ca1b8, $v4bf8d90f04, $pfce4d1b3, $v6490ea3a15, $v9b98e0e818, $v6c1c99fc85) . '
					</li>
					<li class="sla_group_empty_items">There are no groups available...</li>
				</ul>
			</div>'; return $pf8ed4912; } public static function getGroupItemHtml($v08d9602741, $peb014cfd, $v37d269c4fa, $v3e187ca1b8, $v4bf8d90f04, $pfce4d1b3, $v6490ea3a15, $v9b98e0e818, $v6c1c99fc85) { $pefdd2109 .= self::getUIMenuWidgetsHTML($v08d9602741, $v37d269c4fa, $v3e187ca1b8, $v4bf8d90f04, $pfce4d1b3); $pf8ed4912 = '
		<header class="sla_group_header">
			<i class="icon expand_content toggle" onClick="toggleGroupBody(this)"></i>
			<input class="result_var_name result_var_name_output" type="text" placeHolder="Result Variable Name or leave it empty for direct output" title="This action will only appear in the output if this field is empty. If this \'Result Variable Name\' cotains a value, the output will be putted to this correspondent variable." />
			
			<i class="icon remove" onClick="removeGroupItem(this)"></i>
			<i class="icon move_down" onClick="moveDownGroupItem(this)"></i>
			<i class="icon move_up" onClick="moveUpGroupItem(this)"></i>
			
			<select class="action_type" onChange="onChangeSLAInputType(this)">
				' . ($v9b98e0e818 ? '<optgroup label="DataBase Actions">
					<option value="insert">Insert object into data-base</option>
					<option value="update">Update object into data-base</option>
					<option value="delete">Delete object into data-base</option>
					<option value="select">Get object(s) from data-base</option>
					<option value="count">Count objects from data-base</option>
					<option value="procedure">Call Procedure from data-base</option>
					<option value="getinsertedid">Get inserted object id</option>
				<optgroup>' : '') . '
				
				<optgroup label="Broker Actions">
					<option value="callbusinesslogic">Call business logic service</option>
					' . ($v9b98e0e818 ? '
					<option value="callibatisquery">Call ibatis rule</option>
					<option value="callhibernatemethod">Call hibernate rule</option>
					<option value="getquerydata">Get sql query results</option>
					<option value="setquerydata">Set sql query</option>' : '') . '
					<option value="callfunction">Call function</option>
					<option value="callobjectmethod">Call object method</option>
					<option value="restconnector">Call rest connector</option>
					<option value="soapconnector">Call soap connector</option>
				<optgroup>
				
				<optgroup label="Message Actions">
					<option value="show_ok_msg">Show OK message</option>
					<option value="show_ok_msg_and_stop">Show OK message and stop</option>
					<option value="show_ok_msg_and_die">Show OK message and die</option>
					<option value="show_ok_msg_and_redirect">Show OK message and redirect</option>
					<option value="show_error_msg">Show error message</option>
					<option value="show_error_msg_and_stop">Show error message and stop</option>
					<option value="show_error_msg_and_die">Show error message and die</option>
					<option value="show_error_msg_and_redirect">Show error message and redirect</option>
					<option value="alert_msg">Alert message</option>
					<option value="alert_msg_and_stop">Alert message and stop</option>
					<option value="alert_msg_and_redirect">Alert message and redirect</option>
				<optgroup>
				
				<optgroup label="Page Actions">
					<option value="refresh">Refresh page</option>
					<option value="redirect">Redirect to page</option>
					<option value="return_previous_record" title="Filter a records list and return previous record">Return previous record</option>
					<option value="return_next_record" title="Filter a records list and return next record">Return next record</option>
					<option value="return_specific_record" title="Filter a records list and return specific record">Return a specific record</option>
				<optgroup>
				
				<optgroup label="Other Actions">
					<option value="include_file">Include File</option>
					<option value="html">Design HTML Form</option>
					
					<option disabled></option>
					<option value="code">Execute code</option>
					<option value="array">Result from array</option>
					<option value="string">Result from string/value</option>
					<option value="variable">Result from variable</option>
					<option value="sanitize_variable">Sanitize variable</option>
					
					<option disabled></option>
					<option value="check_logged_user_permissions">Check Logged User Permissions</option>
					<option value="list_report">List Report</option>
					<option value="call_block">Call Block</option>
					<option value="draw_graph">Draw Graph</option>
					
					<option disabled></option>
					<option value="loop">Loop</option>
					<option value="group">Group</option>
				<optgroup>
			</select>
			
			<div class="clear"></div>
			
			<div class="sla_group_sub_header">
				<select class="condition_type" onChange="onGroupConditionTypeChange(this)">
					<option value="execute_always">Always execute</option>
					<option value="execute_if_var">Only execute if variable exists:</option>
					<option value="execute_if_not_var">Only execute if variable doesn\'t exists:</option>
					<option value="execute_if_post_button">Only execute if submit button was clicked via POST:</option>
					<option value="execute_if_not_post_button">Only execute if submit button was not clicked via POST:</option>
					<option value="execute_if_get_button">Only execute if submit button was clicked via GET:</option>
					<option value="execute_if_not_get_button">Only execute if submit button was not clicked via GET:</option>
					<option value="execute_if_previous_action">Only execute if previous action executed correctly</option>
					<option value="execute_if_not_previous_action">Only execute if previous action was not executed correctly</option>
					<option value="execute_if_condition" title="This is relative php code that will execute when the module runs. Aditionally this code will be parsed as a string! This is, quotes will be added to this code! MUST 1 LINE CODE!">Only execute if condition is valid:</option>
					<option value="execute_if_not_condition" title="This is relative php code that will execute when the module runs. Aditionally this code will be parsed as a string! This is, quotes will be added to this code! MUST 1 LINE CODE!">Only execute if condition is invalid:</option>
					<option value="execute_if_code" title="This is absolute php code that will execute directly, before this module runs. Which means when this module runs, this condition can only be true or false. Note that this code will not be parsed as a string! This is, no quotes will be added to this code! MUST 1 LINE CODE!">Only execute if code is valid:</option>
					<option value="execute_if_not_code" title="This is absolute php code that will execute directly, before this module runs. Which means when this module runs, this condition can only be true or false Note that this code will not be parsed as a string! This is, no quotes will be added to this code! MUST 1 LINE CODE!">Only execute if code is invalid:</option>
				</select>
				
				<input class="condition_value" type="text" placeHolder="Variable/Name/Condition here" />
				
				<div class="clear"></div>
				
				<div class="action_description">
					<label>Description</label>
					<textarea placeHolder="Explain this action here..."></textarea>
				</div>
			</div>
		</header>
		
		<div class="selected_task_properties sla_group_body">
			<textarea class="undefined_action_value hidden"></textarea> <!-- This will be used everytime a broker-action or database-action does not exists -->
			
			<section class="html_action_body">
				<!-- FORM -->
				' . $v6490ea3a15["createform"] . '
				
				<div class="sla_ui_menu_widgets_backup hidden">
					' . $pefdd2109 . '
				</div>
				
				<script>
					var sla_main_groups = $(".sla_groups_flow .sla_main_groups");
					var mwb = sla_main_groups.find(".sla_ui_menu_widgets_backup");
					var create_form_task_html = sla_main_groups.find(".create_form_task_html");
					create_form_task_html.find(".ptl_settings > .layout-ui-editor > .menu-widgets").append( mwb.contents().clone() );
					sla_main_groups.find(".inlinehtml_task_html > .layout-ui-editor > .menu-widgets").append( mwb.contents() );
					mwb.remove();
					
						create_form_task_html.children(".separate_settings_from_input, .form_input, .form_input_data, .separate_input_from_result, .result, .task_property_exit").remove();
				</script>
			</section>
			
			' . ($v9b98e0e818 ? '
			<section class="database_action_body">
				<header>
					<div class="dal_broker">
						<label>Broker: </label>
						<select class="task_property_field" onChange="updateDALActionBroker(this);"></select>
					</div>
					<div class="db_driver">
						<label>DB Driver: </label>
						<select class="task_property_field" onChange="updateDBActionDriver(this);"></select>
					</div>
					<div class="db_type">
						<label>DB Type: </label>
						<select class="task_property_field" onChange="updateDBActionType(this);">
							<option value="db">From DB Server</option>
							<option value="diagram">From DB Diagram</option>
						</select>
					</div>
				</header>
				<article></article>
				<footer>
					<div class="opts">
						<label class="main_label">Options:</label>
						<input type="text" class="task_property_field options_code" name="options" />
						<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
						<select class="task_property_field options_type" name="options_type" onChange="LayerOptionsUtilObj.onChangeOptionsType(this)">
							<option value="">code</option>
							<option>string</option>
							<option>variable</option>
							<option>array</option>
						</select>
						<div class="options array_items"></div>
					</div>
				</footer>
			</section>
			' : '') . '
			
			<section class="broker_action_body">
				' . $v6490ea3a15["callbusinesslogic"] . '
				' . $v6490ea3a15["callibatisquery"] . '
				' . $v6490ea3a15["callhibernatemethod"] . '
				' . $v6490ea3a15["getquerydata"] . '
				' . $v6490ea3a15["setquerydata"] . '
				' . $v6490ea3a15["callfunction"] . '
				' . $v6490ea3a15["callobjectmethod"] . '
				' . $v6490ea3a15["restconnector"] . '
				' . $v6490ea3a15["soapconnector"] . '
			</section>
			
			<section class="message_action_body">
				<div class="message">
					<label>Message: </label>
					<input class="task_property_field" />
					<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
				<div class="redirect_url">
					<label>Redirect Url: </label>
					<input class="task_property_field" />
					<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
				</div>
			</section>
			
			<section class="redirect_action_body" title="Redirect URL must be a string!!!">
				<label>Redirect URL: </label>
				<input class="task_property_field" />
				<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
			</section>
			
			<section class="records_action_body">
				<div class="records_variable_name" title="Name of the variable with the records that you wish to filter. Note that this variable must be an array with multiple items, where each item is a db record! This field can contains directly the array variable too...">
					<label>Records Variable Name: </label>
					<input class="task_property_field" placeHolder="Name of the variable with the records that you wish to filter" />
					<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
				<div class="index_variable_name" title="Variable name which contains the index to filter. This variable name corresponds to a _GET variable. Note that this can contains directly the numeric index value too.">
					<label>Index Variable Name: </label>
					<input class="task_property_field" placeHolder="Variable name of index to filter" />
					<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
			</section>
			
			<section class="check_logged_user_permissions_action_body">
				<p>Please edit the users and their permissions that the logged user should have.</p>
				<p>Note that the logged user only need to contain one of the added permissions bellow.</p>
				<input class="entity_path_var_name" type="hidden" value="$entity_path" />
				
				<div class="all_permissions_checked">
					<input type="checkbox" value="1" />
					<label>Please select this field, if the logged user should have all the added permissions bellow...</label>
				</div>
				
				<div class="logged_user_id">
					<label>Logged User Id:</label>
					<input type="text" placeHolder="eg: $GLOBALS[\'logged_user_id\']" />
					<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
				
				<div class="users_perms">
					<table>
						<thead>
							<tr>
								<th class="user_type_id">User</th>
								<th class="activity_id">Permission</th>
								<th class="actions">
									<i class="icon add" onClick="addUserPermission(this)"></i>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr class="no_users"><td colspan="2">There are no configured users...</td></tr>
						</tbody>
					</table>
				</div>
			</section>
			
			<section class="code_action_body">
				<textarea class="task_property_field">&lt;?

	?&gt;</textarea>
			</section>
			
			<section class="array_action_body array_items"></section>
			
			<section class="string_action_body">
				<label>String: </label>
				<input class="task_property_field" />
				<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</section>
			
			<section class="variable_action_body" title="Variable input could be a PHP variable name (like $foo[\'bar\']) or something like #foo[bar]#">
				<label>Variable: </label>
				<input class="task_property_field" />
				<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</section>
			
			<section class="sanitize_variable_action_body" title="Variable input could be a PHP variable name (like $foo[\'bar\']) or something like #foo[bar]#">
				<label>Variable: </label>
				<input class="task_property_field" />
				<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</section>
			
			<section class="list_report_action_body" title="Variable input could be a PHP variable name (like $foo[\'bar\']) or something like #foo[bar]#">
				<div class="type">
					<label>Type: </label>
					<select class="task_property_field">
						<option value="txt">Text - Tab delimiter</option>
						<option value="csv">CSV - Comma Separated Values</option>
						<option value="xls">Excel</option>
					</select>
				</div>
				
				<div class="doc_name">
					<label>Document Name: </label>
					<input class="task_property_field" />
					<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
				
				<div class="variable">
					<label>Variable: </label>
					<input class="task_property_field" />
					<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
				
				<div class="continue">
					<label>Stop Action: </label>
					<select class="task_property_field">
						<option value="">Continue</option>
						<option value="stop">Stop</option>
						<option value="die">Die</option>
					</select>
				</div>
				
				<div class="info">
					This variable should be an array with other associative sub-arrays. <br>
					Something similar with a result array returned from a query made to a Data-Base...
				</div>
			</section>
			
			<section class="call_block_action_body">
				<div class="block">
					<label>Block to be called: </label>
					<input class="task_property_field" />
					<span class="icon search search_page_url" onclick="onIncludeBlockTaskChooseFile(this)" title="Search Block">Search block</span>
				</div>
				
				<div class="project">
					<label>Block Project: </label>
					<select class="task_property_field project">
						<option value="">-- Current Project --</option>'; if ($v6c1c99fc85) foreach ($v6c1c99fc85 as $v93756c94b3) $pf8ed4912 .= '<option>' . $v93756c94b3 . '</option>'; $pf8ed4912 .= '
					</select>
				</div>
			</section>
			
			<section class="include_file_action_body">
				<label>File to include: </label>
				<input class="task_property_field path" />
				<input class="once task_property_field once" type="checkbox" value="1" title="Check here to active the include ONCE feature">
				<span class="icon search search_page_url" onclick="onIncludeFileTaskChooseFile(this)" title="Search File">Search file</span>
			</section>
			
			<section class="draw_graph_action_body">
				<div class="info">For more information or options about "Drawing a Graph" and how it works, please open the "<a href="https://www.chartjs.org/" target="chartjs">https://www.chartjs.org/</a>" web-page.</div>
				
				<ul>
					<li><a href="#draw_graph_settings">Settings</a></li>
					<li><a href="#draw_graph_js_code" onClick="onDrawGraphJSCodeTabClick(this)">JS Code</a></li>
				</ul>
				
				<div class="draw_graph_settings" id="draw_graph_settings">
					<div class="include_graph_library">
						<label>Include Graph Library: </label>
						<select class="task_property_field">
							<option value="">Don\'t load, because was previously loaded</option>
							<option value="cdn_even_if_exists">Always load from CDN</option>
							<option value="cdn_if_not_exists">Only load from CDN if doesn\'t exists yet</option>
						</select>
					</div>
					<div class="graph_width">
						<label>Graph Width: </label>
						<input class="task_property_field" />
						<span class="icon add_variable" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
					</div>
					<div class="graph_height">
						<label>Graph Height: </label>
						<input class="task_property_field" />
						<span class="icon add_variable" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
					</div>
					
					<div class="labels_and_values_type">
						<label>Labels and Values Type: </label>
						<select class="task_property_field" onChange="onDrawGraphSettingsLabelsAndValuesTypeChange(this)">
							<option value="">Labels and Values are in different variables</option>
							<option value="associative">Labels and Values are in the same array variable where the keys are the labels</option>
						</select>
					</div>
					<div class="labels_variable">
						<label>Labels Variable (Name): </label>
						<input class="task_property_field" />
						<span class="icon add_variable" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
					</div>
					
					<div class="graph_data_sets">
						<label>Data Sets: <span class="icon add" onClick="addDrawGraphSettingsDataSet(this)">Add</span></label>
						<ul>
							<li class="no_data_sets">No data sets defined yet...</li>
						</ul>
					</div>
				</div>
				
				<div class="draw_graph_js_code" id="draw_graph_js_code">
					<textarea class="task_property_field"></textarea>
				</div>
			</section>
			
			<section class="loop_action_body">
				<header>
					<a onclick="addAndInitNewSLASubGroup(this)">Add new sub-action</a>
					
					<div class="records_variable_name" title="Name of the variable with the records that you wish to loop. Note that this variable must be an array with multiple items. This field can contains directly the array variable too...">
						<label>Records Variable Name: </label>
						<input class="task_property_field" placeHolder="Name of the variable with the records that you wish to loop" />
						<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
					</div>
					<div class="records_start_index" title="Numeric value with the start index for the loop. If no value especified, the system will loop from the beginning of the main array. Default: 0">
						<label>Start Index: </label>
						<input class="task_property_field" placeHolder="numeric start index for loop. Default: 0" />
						<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
					</div>
					<div class="records_end_index" title="Numeric value with the end index for the loop. If no value especified, the system will loop until the end of the main array. Default count($array)">
						<label>End Index: </label>
						<input class="task_property_field" placeHolder="numeric end index for loop. Default: count(array)" />
						<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
					</div>
					<div class="array_item_key_variable_name" title="Variable name which contains the current key in the loop. This variable name corresponds to the variable that will be initialize when the loop is running with the correspondent item key/index.">
						<label>Item Key Variable Name: </label>
						<input class="task_property_field" placeHolder="Variable name of array item key" />
						<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
					</div>
					<div class="array_item_value_variable_name" title="Variable name which contains the current item in the loop. This variable name corresponds to the variable that will be initialize when the loop is running with the correspondent item value.">
						<label>Item Value Variable Name: </label>
						<input class="task_property_field" placeHolder="Variable name of array item value" />
						<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
					</div>
				</header>
				
				<article class="sla_sub_groups">
					<div class="sla_group_empty_items">There are no sub-groups available...</div>
				</article>
			</section>
			
			<section class="group_action_body">
				<header>
					<span>It works like a method/function where the \'result variables\' from the sub-groups are locals...</span>
					<a onclick="addAndInitNewSLASubGroup(this)">Add new sub-group</a>
					
					<div class="group_name" title="Group name which corresponds to the method/function name. This name is used to access this group \'result variables\' outside of the group. If no group name is filled, we cannot access the inner \'result variables\' from outside this group.">
						<label>Group Name: </label>
						<input class="task_property_field" placeHolder="Group Name" />
						<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
					</div>
				</header>
				
				<article class="sla_sub_groups">
					<div class="sla_group_empty_items">There are no sub-groups available...</div>
				</article>
			</section>
		</div>'; return $pf8ed4912; } } ?>
