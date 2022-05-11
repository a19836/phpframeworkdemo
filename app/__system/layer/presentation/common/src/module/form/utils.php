<?php
//prepare some utils functions
function getActionHtml($action, $with_options = 1) {
	$label = ucwords(str_replace(array("-", "_"), " ", $action));
	$parts = explode("_", $action);
	
	if ($parts[0] == "single")
		$title = $label . ": action to " . $parts[1] . " a single item.";
	else if ($parts[0] == "multiple")
		$title = $label . ": action to " . ($action == "multiple_insert_update" ? "insert and update" : $parts[1]) . " multiple items at once.";
	
	$html = '
	<div class="action action-' . str_replace(array(" ", "_"), "-", $action) . '">
		<input type="checkbox" onClick="activateFormWizardAction(this)" />
		<label title="' . $title . '">' . $label . '</label>';
	
	if ($with_options) {
		$html .= '<i class="icon expand_content toggle" onClick="toggleFormWizardActionOptions(this)"></i>
		<div class="action-options">';
		
		if ($with_options == 1)
			$html .= '
				<div class="action-option action-type">
					<label>Choose the type of action that you wish:</label>
					<select onChange="toggleFormWizardActionTypeOptions(this)">
						<option value=""> Default </option>
						<option value="ajax_on_click">Ajax - on click</option>
						' . ($action == "single_update" ? '<option value="ajax_on_blur">Ajax - on blur</option>' : '') . '
					</select>
				</div>
				<div class="action-option ajax-url">
					<label>Ajax Url:</label>
					<input placeHolder="Write a url here" title="This url should correspond to the ajax request, that if successfully should return ' . ($action == "single_insert" ? "the inserted object id" : ($action == "multiple_insert" || $action == "multiple_insert_update" ? "the inserted object ids in an array ordered by the same row index" : "1")) . ' or a json object with a status property." />
				</div>
				<div class="action-option successful-msg-options">
					<label>When this action is successful:</label>
					' . getMessageOptionsHtml($action) . '
				</div>
				<div class="action-option unsuccessful-msg-options">
					<label>When this action is unsuccessful:</label>
					' . getMessageOptionsHtml() . '
				</div>';
		else if ($with_options == 2)
			$html .= '
				<div class="action-option action-links">
					<label>Links:</label>
					<i class="icon add" onClick="addFormWizardActionLinkOptionUrl(this)"></i>
					<div class="info">The primary keys of the selected table will be append to the urls!</div>
					
					<div class="action-link">
						<input class="action-link-url" placeHolder="Write a url here or leave it blank" />
						<input class="action-link-title" placeHolder="url title" />
						<input class="action-link-class" placeHolder="link css class" />
						<i class="icon delete" onClick="$(this).parent().remove()"></i>
					</div>
				</div>';
	
		$html .= '</div>';
	}
	
	$html .= '</div>';
	
	return $html;
}

function getMessageOptionsHtml($action = false) {
	return '<ul>
		<li class="msg-type">
			<label>Message Type:</label>
			<select>
				<option>show</option>
				<option>alert</option>
			</select>
		</li>
		<li class="msg-message">
			<label>Message:</label>
			<input placeHolder="Write a message here" />
		</li>
		<li class="msg-redirect-url">
			<label>Redirerct Url:</label>
			<input placeHolder="Write a url here" title="If a URL is presented, the system will redirect the user to this url after this action been executed." />
			<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
		</li>
	</ul>';
}

function getFormGroupItemHtml($EVC, $project_url_prefix, $project_common_url_prefix, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url, $contents, $db_drivers, $presentation_projects) {
	$common_webroot_path = $EVC->getWebrootPath($EVC->getCommonProjectName());
	$ui_menu_widgets_html = WorkFlowPresentationHandler::getUIEditorWidgetsHtml($common_webroot_path, $project_common_url_prefix, $webroot_cache_folder_path, $webroot_cache_folder_url, array("avoided_widgets" => array("php")));
	$ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/common_editor_widget/", $webroot_cache_folder_path, $webroot_cache_folder_url);
	$ui_menu_widgets_html .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml($common_webroot_path, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url);
	
	
	$html = '
	<header class="form-group-header">
		<i class="icon expand_content toggle" onClick="toggleGroupBody(this)"></i>
		<input class="result-var-name result-var-name-output" type="text" placeHolder="Result Variable Name or leave it empty for direct output" title="This action will only appear in the output if this field is empty. If this \'Result Variable Name\' cotains a value, the output will be putted to this correspondent variable." />
		
		<i class="icon remove" onClick="removeItem(this)"></i>
		<i class="icon move_down" onClick="moveDownItem(this)"></i>
		<i class="icon move_up" onClick="moveUpItem(this)"></i>
		
		<select class="action-type" onChange="onChangeFormInputType(this)">
			' . ($db_drivers ? '<optgroup label="DataBase Actions">
				<option value="insert">Insert object into data-base</option>
				<option value="update">Update object into data-base</option>
				<option value="delete">Delete object into data-base</option>
				<option value="select">Get object from data-base</option>
				<option value="procedure">Call Procedure from data-base</option>
				<option value="getinsertedid">Get inserted object id</option>
			<optgroup>' : '') . '
			
			<optgroup label="Broker Actions">
				<option value="callbusinesslogic">Call business logic service</option>
				' . ($db_drivers ? '
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
		
		<div class="form-group-sub-header">
			<select class="condition-type" onChange="onGroupConditionTypeChange(this)">
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
			
			<input class="condition-value" type="text" placeHolder="Variable/Name/Condition here" />
			
			<div class="clear"></div>
			
			<div class="action-description">
				<label>Description</label>
				<textarea placeHolder="Explain this action here..."></textarea>
			</div>
		</div>
	</header>
	
	<div class="selected_task_properties form-group-body">
		<textarea class="undefined-action-value hidden"></textarea> <!-- This will be used everytime a broker-action or database-action does not exists -->
		
		<section class="html-action-body">
			<!-- FORM -->
			' . $contents["createform"] . '
			
			<div class="ui-menu-widgets-backup hidden">
				' . $ui_menu_widgets_html . '
			</div>
			
			<script>
				var mwb = $(".module_form_settings .ui-menu-widgets-backup");
				var create_form_task_html = $(".module_form_settings .create_form_task_html");
				create_form_task_html.find(".ptl_settings > .layout-ui-editor > .menu-widgets").append( mwb.contents().clone() );
				$(".module_form_settings .inlinehtml_task_html > .layout-ui-editor > .menu-widgets").append( mwb.contents() );
				mwb.remove();
				
					create_form_task_html.children(".separate_settings_from_input, .form_input, .form_input_data, .separate_input_from_result, .result, .task_property_exit").remove();
			</script>
		</section>
		
		' . ($db_drivers ? '
		<section class="database-action-body">
			<header>
				<div class="dal-broker">
					<label>Broker: </label>
					<select class="task_property_field" onChange="updateDALActionBroker(this);"></select>
				</div>
				<div class="db-driver">
					<label>DB Driver: </label>
					<select class="task_property_field" onChange="updateDBActionDriver(this);"></select>
				</div>
				<div class="db-type">
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
		
		<section class="broker-action-body">
			' . $contents["callbusinesslogic"] . '
			' . $contents["callibatisquery"] . '
			' . $contents["callhibernatemethod"] . '
			' . $contents["getquerydata"] . '
			' . $contents["setquerydata"] . '
			' . $contents["callfunction"] . '
			' . $contents["callobjectmethod"] . '
			' . $contents["restconnector"] . '
			' . $contents["soapconnector"] . '
		</section>
		
		<section class="message-action-body">
			<div class="message">
				<label>Message: </label>
				<input class="task_property_field" />
				<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</div>
			<div class="redirect-url">
				<label>Redirect Url: </label>
				<input class="task_property_field" />
				<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
			</div>
		</section>
		
		<section class="redirect-action-body" title="Redirect URL must be a string!!!">
			<label>Redirect URL: </label>
			<input class="task_property_field" />
			<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
		</section>
		
		<section class="records-action-body">
			<div class="records-variable-name" title="Name of the variable with the records that you wish to filter. Note that this variable must be an array with multiple items, where each item is a db record! This field can contains directly the array variable too...">
				<label>Records Variable Name: </label>
				<input class="task_property_field" placeHolder="Name of the variable with the records that you wish to filter" />
				<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</div>
			<div class="index-variable-name" title="Variable name which contains the index to filter. This variable name corresponds to a _GET variable. Note that this can contains directly the numeric index value too.">
				<label>Index Variable Name: </label>
				<input class="task_property_field" placeHolder="Variable name of index to filter" />
				<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</div>
		</section>
		
		<section class="check-logged-user-permissions-action-body">
			<p>Please edit the users and their permissions that the logged user should have.</p>
			<p>Note that the logged user only need to contain one of the added permissions bellow.</p>
			<input class="entity-path-var-name" type="hidden" value="$entity_path" />
			
			<div class="all-permissions-checked">
				<input type="checkbox" value="1" />
				<label>Please select this field, if the logged user should have all the added permissions bellow...</label>
			</div>
			
			<div class="logged-user-id">
				<label>Logged User Id:</label>
				<input type="text" placeHolder="$GLOBALS[\'logged_user_id\']" />
				<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</div>
			
			<div class="users-perms">
				<table>
					<thead>
						<tr>
							<th class="user-type-id">User</th>
							<th class="activity-id">Permission</th>
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
		
		<section class="code-action-body">
			<textarea class="task_property_field">&lt;?

?&gt;</textarea>
		</section>
		
		<section class="array-action-body array_items"></section>
		
		<section class="string-action-body">
			<label>String: </label>
			<input class="task_property_field" />
			<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</section>
		
		<section class="variable-action-body" title="Variable input could be a PHP variable name (like $foo[\'bar\']) or something like #foo[bar]#">
			<label>Variable: </label>
			<input class="task_property_field" />
			<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</section>
		
		<section class="sanitize-variable-action-body" title="Variable input could be a PHP variable name (like $foo[\'bar\']) or something like #foo[bar]#">
			<label>Variable: </label>
			<input class="task_property_field" />
			<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</section>
		
		<section class="list-report-action-body" title="Variable input could be a PHP variable name (like $foo[\'bar\']) or something like #foo[bar]#">
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
		
		<section class="call-block-action-body">
			<div class="block">
				<label>Block to be called: </label>
				<input class="task_property_field" />
				<span class="icon search search_page_url" onclick="onIncludeBlockTaskChooseFile(this)" title="Search Block">Search block</span>
			</div>
			
			<div class="project">
				<label>Block Project: </label>
				<select class="task_property_field project">
					<option value="">-- Current Project --</option>';
	
	if ($presentation_projects)
		foreach ($presentation_projects as $project)
			$html .= '<option>' . $project . '</option>';
	
	$html .= '
				</select>
			</div>
		</section>
		
		<section class="include-file-action-body">
			<label>File to include: </label>
			<input class="task_property_field path" />
			<input class="once task_property_field once" type="checkbox" value="1" title="Check here to active the include ONCE feature">
			<span class="icon search search_page_url" onclick="onIncludeFileTaskChooseFile(this)" title="Search File">Search file</span>
		</section>
		
		<section class="draw-graph-action-body">
			<div class="info">For more information or options about "Drawing a Graph" and how it works, please open the "<a href="https://www.chartjs.org/" target="chartjs">https://www.chartjs.org/</a>" web-page.</div>
			
			<ul>
				<li><a href="#draw-graph-settings">Settings</a></li>
				<li><a href="#draw-graph-js-code" onClick="onDrawGraphJSCodeTabClick(this)">JS Code</a></li>
			</ul>
			
			<div class="draw-graph-settings" id="draw-graph-settings">
				<div class="include-graph-library">
					<label>Include Graph Library: </label>
					<select class="task_property_field">
						<option value="">Don\'t load, because was previously loaded</option>
						<option value="cdn_even_if_exists">Always load from CDN</option>
						<option value="cdn_if_not_exists">Only load from CDN if doesn\'t exists yet</option>
					</select>
				</div>
				<div class="graph-width">
					<label>Graph Width: </label>
					<input class="task_property_field" />
					<span class="icon add_variable" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
				<div class="graph-height">
					<label>Graph Height: </label>
					<input class="task_property_field" />
					<span class="icon add_variable" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
				
				<div class="labels-and-values-type">
					<label>Labels and Values Type: </label>
					<select class="task_property_field" onChange="onDrawGraphSettingsLabelsAndValuesTypeChange(this)">
						<option value="">Labels and Values are in different variables</option>
						<option value="associative">Labels and Values are in the same array variable where the keys are the labels</option>
					</select>
				</div>
				<div class="labels-variable">
					<label>Labels Variable (Name): </label>
					<input class="task_property_field" />
					<span class="icon add_variable" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
				
				<div class="graph-data-sets">
					<label>Data Sets: <span class="icon add" onClick="addDrawGraphSettingsDataSet(this)">Add</span></label>
					<ul>
						<li class="no-data-sets">No data sets defined yet...</li>
					</ul>
				</div>
			</div>
			
			<div class="draw-graph-js-code" id="draw-graph-js-code">
				<textarea class="task_property_field"></textarea>
			</div>
		</section>
		
		<section class="loop-action-body">
			<header>
				<a onclick="addAndInitNewFormSubGroup(this)">Add new sub-group</a>
				
				<div class="records-variable-name" title="Name of the variable with the records that you wish to loop. Note that this variable must be an array with multiple items. This field can contains directly the array variable too...">
					<label>Records Variable Name: </label>
					<input class="task_property_field" placeHolder="Name of the variable with the records that you wish to loop" />
					<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
				<div class="records-start-index" title="Numeric value with the start index for the loop. If no value especified, the system will loop from the beginning of the main array. Default: 0">
					<label>Start Index: </label>
					<input class="task_property_field" placeHolder="numeric start index for loop. Default: 0" />
					<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
				<div class="records-end-index" title="Numeric value with the end index for the loop. If no value especified, the system will loop until the end of the main array. Default count($array)">
					<label>End Index: </label>
					<input class="task_property_field" placeHolder="numeric end index for loop. Default: count(array)" />
					<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
				<div class="array-item-key-variable-name" title="Variable name which contains the current key in the loop. This variable name corresponds to the variable that will be initialize when the loop is running with the correspondent item key/index.">
					<label>Item Key Variable Name: </label>
					<input class="task_property_field" placeHolder="Variable name of array item key" />
					<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
				<div class="array-item-value-variable-name" title="Variable name which contains the current item in the loop. This variable name corresponds to the variable that will be initialize when the loop is running with the correspondent item value.">
					<label>Item Value Variable Name: </label>
					<input class="task_property_field" placeHolder="Variable name of array item value" />
					<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
			</header>
			
			<article class="form-sub-groups"></article>
		</section>
		
		<section class="group-action-body">
			<header>
				<span>It works like a method/function where the \'result variables\' from the sub-groups are locals...</span>
				<a onclick="addAndInitNewFormSubGroup(this)">Add new sub-group</a>
				
				<div class="group-name" title="Group name which corresponds to the method/function name. This name is used to access this group \'result variables\' outside of the group. If no group name is filled, we cannot access the inner \'result variables\' from outside this group.">
					<label>Group Name: </label>
					<input class="task_property_field" placeHolder="Group Name" />
					<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
			</header>
			
			<article class="form-sub-groups"></article>
		</section>
	</div>';
	
	return $html;
}
?>
