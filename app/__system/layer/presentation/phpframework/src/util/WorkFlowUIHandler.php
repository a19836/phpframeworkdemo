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

include_once get_lib("org.phpframework.util.web.html.CssAndJSFilesOptimizer"); include_once $EVC->getUtilPath("PHPVariablesFileHandler", "phpframework"); class WorkFlowUIHandler { private $pecad7cca; private $pcd2aca48; private $v00161f0c07; private $v3b2000c17b; private $pb5790ec9; private $v3d55458bcd; private $v4bf8d90f04; private $pfce4d1b3; private $v524bbc0f84; private $v85ea3272a0; private $v4c6473be39; private $v521be90a09; private $v243e50bc1d; public function __construct($pecad7cca, $pcd2aca48, $v00161f0c07, $v3b2000c17b, $pb5790ec9, $v3d55458bcd, $v4bf8d90f04, $pfce4d1b3) { $this->pecad7cca = $pecad7cca; $this->pcd2aca48 = $pcd2aca48; $this->v00161f0c07 = $v00161f0c07; $this->v3b2000c17b = $v3b2000c17b; $this->pb5790ec9 = $pb5790ec9; $this->v3d55458bcd = $v3d55458bcd; $this->v4bf8d90f04 = $v4bf8d90f04; $this->pfce4d1b3 = $pfce4d1b3; $this->mcdfff12f30fd(); } private function mcdfff12f30fd() { $this->pecad7cca->initWorkFlowTasks(); $this->v524bbc0f84 = $this->pecad7cca->getLoadedTasksSettings(); $this->v85ea3272a0 = $this->pecad7cca->getParsedTasksContainers(); $this->v4c6473be39 = array(); $this->v521be90a09 = array(); } public function setTasksOrderByTag($v4c6473be39) { $this->v4c6473be39 = $v4c6473be39; } public function setTasksGroupsByTag($v521be90a09) { $this->v521be90a09 = $v521be90a09; } public function getDefaultTasksGroupsByTag() { $v521be90a09 = array(); foreach ($this->v524bbc0f84 as $v93feab0020 => $pcbf3c2f0) foreach ($pcbf3c2f0 as $pc8421459 => $v003bc751fd) $v521be90a09[$v93feab0020][] = $v003bc751fd["tag"]; return $v521be90a09; } public function addFoldersTasksToTasksGroups($v8f31bfcb8e) { if ($v8f31bfcb8e) foreach ($v8f31bfcb8e as $pd016d02f) $this->addFolderTasksToTasksGroups($pd016d02f); } public function addFolderTasksToTasksGroups($pd016d02f) { $pd016d02f = WorkFlowTaskHandler::prepareFolderPath($pd016d02f); $v6b3fa373ec = $this->pecad7cca->getFolderId($pd016d02f); $v58bdbebd81 = $this->pecad7cca->getLoadedTasks(); $pca82c260 = $v58bdbebd81[$v6b3fa373ec]; $pb6283403 = $this->v524bbc0f84[$v6b3fa373ec]; if ($pb6283403) foreach ($pb6283403 as $v8282c7dd58 => $v003bc751fd) { $pc1ded554 = $pca82c260[$v8282c7dd58]; $v730d5c80a0 = str_replace($pd016d02f, "", dirname(dirname($pc1ded554["path"]))); $v730d5c80a0 = substr($v730d5c80a0, 0, 1) == "/" ? substr($v730d5c80a0, 1) : $v730d5c80a0; $pbd1bc7b0 = strpos($v730d5c80a0, "/"); $v7ede7ac560 = $pbd1bc7b0 ? substr($v730d5c80a0, 0, $pbd1bc7b0) : $v730d5c80a0; if ($v7ede7ac560) { $v4fb7a0e86f = ucwords(strtolower(str_replace(array("_", "-"), " ", $v7ede7ac560))); if (isset($this->v521be90a09[$v4fb7a0e86f])) $this->v521be90a09[$v4fb7a0e86f][] = $v003bc751fd["tag"]; else $this->v521be90a09[$v4fb7a0e86f] = array($v003bc751fd["tag"]); } } } public function getHeader($v5d3813882f = array("tasks_css_and_js" => true)) { $v0a9dad1fe0 = ''; $v0a9dad1fe0 .= '
<!-- Add Jquery Tap-Hold Event JS file -->
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jquerytaphold/taphold.js"></script>

<!-- Jquery Touch Punch to work on mobile devices with touch -->
<script type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jqueryuitouchpunch/jquery.ui.touch-punch.min.js"></script>

<!-- Add JSPlumb main JS and CSS files -->
<script language="javascript" type="text/javascript" src="' . $this->v3b2000c17b . 'jqueryjsplumb/build/1.3.16/js/jquery.jsPlumb-1.3.16-all-min.js"></script>

<!-- Add TaskFlowChart main JS and CSS files -->
<link rel="stylesheet" href="' . $this->pb5790ec9 . 'jquerytaskflowchart/css/style.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="' . $this->pb5790ec9 . 'jquerytaskflowchart/css/print.css" type="text/css" charset="utf-8" media="print" />
<script type="text/javascript" src="' . $this->pb5790ec9 . 'jquerytaskflowchart/js/lib/jsPlumbCloneHandler.js"></script>
<script type="text/javascript" src="' . $this->pb5790ec9 . 'jquerytaskflowchart/js/task_flow_chart.js"></script>

<!-- Add ContextMenu main JS and CSS files -->
<link rel="stylesheet" href="' . $this->v00161f0c07 . 'vendor/jquerycontextmenu/css/jqcontextmenu.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jquerycontextmenu/js/jqcontextmenu.js"></script>

<!-- Parse_Str -->
<script type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/phpjs/functions/strings/parse_str.js"></script>

<!-- Add DropDowns main JS and CSS files -->
<link rel="stylesheet" href="' . $this->v00161f0c07 . 'vendor/jquerysimpledropdowns/css/style.css" type="text/css" charset="utf-8" />
<!--[if lte IE 7]>
        <link rel="stylesheet" href="' . $this->v00161f0c07 . 'vendor/jquerysimpledropdowns/css/ie.css" type="text/css" charset="utf-8" />
<![endif]-->
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jquerysimpledropdowns/js/jquery.dropdownPlain.js"></script>
'; if (!$v5d3813882f["icons_and_edit_code_already_included"]) $v0a9dad1fe0 .= '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $this->v00161f0c07 . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS files -->
<link rel="stylesheet" href="' . $this->pcd2aca48 . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Edit Layout JS files -->
<script type="text/javascript" src="' . $this->pcd2aca48 . 'js/layout.js"></script>
'; if ($v5d3813882f["ui_editor"] || $this->pecad7cca->getTasksByPrefix("createform", 1)) $v0a9dad1fe0 .= '
<!-- Layout UI Editor - MD5 -->
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jquery/js/jquery.md5.js"></script>

<!-- Layout UI Editor - Add ACE-Editor -->
<script type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
<script type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>

<!-- Layout UI Editor - Add Code Beautifier -->
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/mycodebeautifier/js/codebeautifier.js"></script>

<!-- Layout UI Editor - Add Html/CSS/JS Beautify code -->
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jsbeautify/js/lib/beautify.js"></script>
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jsbeautify/js/lib/beautify-css.js"></script>
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/myhtmlbeautify/MyHtmlBeautify.js"></script>

<!-- Layout UI Editor - Material-design-iconic-font -->
<link rel="stylesheet" href="' . $this->v00161f0c07 . 'vendor/jquerylayoutuieditor/vendor/materialdesigniconicfont/css/material-design-iconic-font.min.css">

<!-- Layout UI Editor - JQuery Nestable2 -->
<link rel="stylesheet" href="' . $this->v00161f0c07 . 'vendor/jquerylayoutuieditor/vendor/nestable2/jquery.nestable.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jquerylayoutuieditor/vendor/nestable2/jquery.nestable.min.js"></script>

<!-- Layout UI Editor - Html Entities Converter -->
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/he/he.js"></script>

<!-- Layout UI Editor - HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
	 <script src="' . $this->v00161f0c07 . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/html5_ie8/html5shiv.min.js"></script>
	 <script src="' . $this->v00161f0c07 . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/html5_ie8/respond.min.js"></script>
<![endif]-->

<!-- Layout UI Editor - Add Iframe droppable fix -->
<script type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/jquery-ui-droppable-iframe-fix.js"></script>    

<!-- Layout UI Editor - Add Iframe droppable fix - IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="' . $this->v00161f0c07 . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/ie10-viewport-bug-workaround.js"></script>

<!-- Layout UI Editor - Add Layout UI Editor -->
<link rel="stylesheet" href="' . $this->v00161f0c07 . 'vendor/jquerylayoutuieditor/css/some_bootstrap_style.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="' . $this->v00161f0c07 . 'vendor/jquerylayoutuieditor/css/style.css" type="text/css" charset="utf-8" />

<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jquerylayoutuieditor/js/TextSelection.js"></script>
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jquerylayoutuieditor/js/LayoutUIEditor.js"></script>
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jquerylayoutuieditor/js/CreateWidgetContainerClassObj.js"></script>
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jquerylayoutuieditor/js/LayoutUIEditorFormFieldUtil.js"></script>
'; else if ($v5d3813882f["tasks_css_and_js"]) $v0a9dad1fe0 .= '
<!-- Add MD5 JS File -->
<script language="javascript" type="text/javascript" src="' . $this->v00161f0c07 . 'vendor/jquery/js/jquery.md5.js"></script>
'; if ($v5d3813882f["tasks_css_and_js"]) $v0a9dad1fe0 .= "\n<!-- Add TASKS JS and CSS files -->\n" . $this->printTasksCSSAndJS(); return $v0a9dad1fe0; } public function getJS($v23b252dead = false, $v10d8a653cc = false, $pd05429f9 = null) { $v10d8a653cc = empty($v10d8a653cc) ? $v23b252dead : $v10d8a653cc; $v0a9dad1fe0 = '
<script>
	workflow_global_variables = ' . json_encode(PHPVariablesFileHandler::getVarsFromFileContent($this->v3d55458bcd)) . ';
	
	$(window).resize(function() {
		jsPlumbWorkFlow.jsPlumbContainer.automaticIncreaseContainersSize();
		
		jsPlumbWorkFlow.getMyFancyPopupObj().updatePopup();
	});
	
	;(function() {
'; if ($v23b252dead) $v0a9dad1fe0 .= 'jsPlumbWorkFlow.jsPlumbTaskFile.get_tasks_file_url = "' . $this->pcd2aca48 . 'workflow/get_workflow_file?path=' . $v23b252dead . '";'; if ($v10d8a653cc) $v0a9dad1fe0 .= 'jsPlumbWorkFlow.jsPlumbTaskFile.set_tasks_file_url = "' . $this->pcd2aca48 . 'workflow/set_workflow_file?path=' . $v10d8a653cc . '";'; if ($pd05429f9) foreach ($pd05429f9 as $pe5c5e2fe => $v956913c90f) $v0a9dad1fe0 .= '
		jsPlumbWorkFlow.setjsPlumbWorkFlowObjOption("' . $pe5c5e2fe . '", \'' . addcslashes(str_replace(array("\n", "\r"), "", $v956913c90f), "\\'") . '\')'; $v0a9dad1fe0 .= '
		jsPlumbWorkFlow.jsPlumbTaskFlow.default_connection_connector = "Straight";
		jsPlumbWorkFlow.jsPlumbTaskFlow.default_connection_hover_color = null;
		jsPlumbWorkFlow.jsPlumbTaskFlow.main_tasks_flow_obj_id = "taskflowchart > .tasks_flow";
		jsPlumbWorkFlow.jsPlumbTaskFlow.main_tasks_properties_obj_id = "taskflowchart > .tasks_properties";
		jsPlumbWorkFlow.jsPlumbTaskFlow.main_connections_properties_obj_id = "taskflowchart > .connections_properties";
		jsPlumbWorkFlow.jsPlumbContextMenu.main_tasks_menu_obj_id = "taskflowchart > .tasks_menu";
		jsPlumbWorkFlow.jsPlumbContextMenu.main_tasks_menu_hide_obj_id = "taskflowchart > .tasks_menu_hide";
		jsPlumbWorkFlow.jsPlumbContextMenu.main_workflow_menu_obj_id = "taskflowchart > .workflow_menu";
		
		jsPlumbWorkFlow.jsPlumbProperty.tasks_settings = ' . $this->getTasksSettingsObj() . ';
		jsPlumbWorkFlow.jsPlumbContainer.tasks_containers = ' . $this->f94ef746929($this->v85ea3272a0) . ';
		
		jsPlumbWorkFlow.jsPlumbTaskFile.save_options = {
			success: function(data, textStatus, jqXHR) {
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					StatusMessageHandler.showError("Please Login first!");
			}
		};
		
		jsPlumbWorkFlow.init();
	})();
	
	function flushCache() {
		var url = \'' . $this->pcd2aca48 . 'admin/flush_cache\';
		
		$.ajax({
			type : "get",
			url : url,
			success : function(data, textStatus, jqXHR) {
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, url, function() {
						flushCache();
					});
				else if (data == "1")
					jsPlumbWorkFlow.jsPlumbStatusMessage.showMessage("Cache flushed!");
				else
					jsPlumbWorkFlow.jsPlumbStatusMessage.showError("Error: Cache not flushed!\nPlease try again..." + (data ? "\n" + data : ""));
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
				jsPlumbWorkFlow.jsPlumbStatusMessage.showError("Error: Cache not flushed!\nPlease try again..." + msg);
			},
		});
		
		return false;
	}
	
	function emptyDiagam() {
		if (confirm("If you continue, all items will be deleted from this diagram and this diagram will be empty.\nDo you still want to proceed?"))
			jsPlumbWorkFlow.reinit();
	}
	
	function openIframePopup(url, options) {
		options = typeof options != "undefined" && options ? options : {};
		options["url"] = url;
		options["type"] = "iframe";
		
		jsPlumbWorkFlow.getMyFancyPopupObj().init(options);
		jsPlumbWorkFlow.getMyFancyPopupObj().showPopup();
	}
</script>'; return $v0a9dad1fe0; } public function setMenus($v243e50bc1d) { $this->v243e50bc1d = $v243e50bc1d; } public function getDefaultMenus() { $pabf7b139 = array_keys($this->v85ea3272a0); $pabf7b139 = $pabf7b139[0]; $v75c19c5e8a = $this->v85ea3272a0[$pabf7b139][0]; return array( "File" => array( "childs" => array( "Save" => array("click" => "jsPlumbWorkFlow.jsPlumbTaskFile.save();return false;"), "Flush Cache" => array("click" => "return flushCache();"), "Empty Diagram" => array("click" => "emptyDiagam();return false;"), ) ), "WorkFlow" => array( "childs" => array( "Add new task $v75c19c5e8a" => array("click" => "jsPlumbWorkFlow.jsPlumbContextMenu.addTaskByType('" . $v75c19c5e8a . "');return false;"), ) ), "Container" => array( "childs" => array( "Decrease Containers IF Size" => array("click" => "jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize('" . $pabf7b139 . "', 400, 100);return false;"), "Increase Containers IF Size" => array("click" => "jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize('" . $pabf7b139 . "', 1300, 250);return false;"), "Shrink containers" => array("click" => "jsPlumbWorkFlow.jsPlumbContainer.automaticDecreaseContainersSize();return false;"), ) ), ); } public function getMenusContent() { if (empty($this->v243e50bc1d) || !is_array($this->v243e50bc1d)) $this->v243e50bc1d = $this->getDefaultMenus(); return '<div id="workflow_menu" class="workflow_menu">' . $this->f5e52484713($this->v243e50bc1d, "dropdown") . '</div>'; } private function f5e52484713($v243e50bc1d, $v3ae55a9a2e = false) { $pf8ed4912 = ''; if (!empty($v243e50bc1d) && is_array($v243e50bc1d)) { $pf8ed4912 .= '<ul' . ($v3ae55a9a2e ? ' class="' . $v3ae55a9a2e . '"' : '') . '>'; foreach ($v243e50bc1d as $v134495e57e => $pdf6c365c) { $pf8ed4912 .= '<li class="' . $pdf6c365c["class"] . '" title="' . ($pdf6c365c["title"] ? $pdf6c365c["title"] : $v134495e57e) . '">'; if ($pdf6c365c["html"]) $pf8ed4912 .= $pdf6c365c["html"]; else $pf8ed4912 .= '<a' . ($pdf6c365c["click"] ? ' onClick="' . $pdf6c365c["click"] . '"' : '') . '>' . $v134495e57e . '</a>'; if (!empty($pdf6c365c["childs"])) $pf8ed4912 .= $this->f5e52484713($pdf6c365c["childs"]); $pf8ed4912 .= '</li>'; } $pf8ed4912 .= '</ul>'; } return $pf8ed4912; } public function getContent($v29ae981180 = "taskflowchart") { $v446c479876 = $_COOKIE["main_navigator_side"] == "main_navigator_reverse" ? "" : "reverse"; $v6e611b5051 = '
	<div id="' . $v29ae981180 . '" class="taskflowchart ' . $v446c479876 . '">
		' . $this->getMenusContent() . '
		
		<div class="tasks_menu scroll">
			' . $this->printTasksList() . '
		</div>
		
		<div class="tasks_menu_hide">
			<div class="button" onclick="jsPlumbWorkFlow.jsPlumbContextMenu.toggleTasksMenuPanel(this)"></div>
		</div>
		
		<div class="tasks_flow scroll">
			' . $this->f925abac613() . '
		</div>
	
		<div class="tasks_properties hidden">
			' . $this->printTasksProperties() . '
		</div>
	
		<div class="connections_properties hidden">
			' . $this->printConnectionsProperties() . '
		</div>
	</div>
'; return $v6e611b5051; } public function printTasksList() { $pf8ed4912 = "<ul>"; $v20fbb86a18 = array(); foreach ($this->v524bbc0f84 as $v93feab0020 => $pcbf3c2f0) foreach ($pcbf3c2f0 as $pc8421459 => $v003bc751fd) { $v1b1c6a10a2 = $v003bc751fd["tag"]; if ($v1b1c6a10a2) $v20fbb86a18[$v1b1c6a10a2] = array($pc8421459, $v003bc751fd); } $pc3b3502c = array(); if (is_array($this->v521be90a09)) { foreach ($this->v521be90a09 as $pfc00b2ed => $v8a9674a7c9) { if ($v8a9674a7c9) { $pf8ed4912 .= '<li>'; $pf8ed4912 .= '<div class="tasks_group_label">' . $pfc00b2ed . '</div>'; $pf8ed4912 .= '<div class="tasks_group_tasks">'; $pc37695cb = count($this->v4c6473be39); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1b1c6a10a2 = $this->v4c6473be39[$v43dd7d0051]; if (in_array($v1b1c6a10a2, $v8a9674a7c9)) { $v7f5911d32d = $v20fbb86a18[$v1b1c6a10a2]; $pc8421459 = $v7f5911d32d[0]; $v003bc751fd = $v7f5911d32d[1]; if ($pc8421459 && $v003bc751fd) { $pc3b3502c[] = $pc8421459; $pf8ed4912 .= $this->mff0e0fe7a0dd($pc8421459, $v003bc751fd); } } } $pc37695cb = count($v8a9674a7c9); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1b1c6a10a2 = $v8a9674a7c9[$v43dd7d0051]; $v7f5911d32d = $v20fbb86a18[$v1b1c6a10a2]; $pc8421459 = $v7f5911d32d[0]; $v003bc751fd = $v7f5911d32d[1]; if ($pc8421459 && $v003bc751fd && !in_array($pc8421459, $pc3b3502c)) { $pc3b3502c[] = $pc8421459; $pf8ed4912 .= $this->mff0e0fe7a0dd($pc8421459, $v003bc751fd); } } $pf8ed4912 .= '</div>
					<div style="clear:left; float:none;"></div></li>'; } } } $v3a29af29e1 = count($pc3b3502c) > 0; $pd04a32f6 = ""; foreach ($this->v524bbc0f84 as $v93feab0020 => $pcbf3c2f0) { if ($pcbf3c2f0) { $pc37695cb = count($this->v4c6473be39); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1b1c6a10a2 = $this->v4c6473be39[$v43dd7d0051]; $v7f5911d32d = $v20fbb86a18[$v1b1c6a10a2]; $pc8421459 = $v7f5911d32d[0]; if ($pcbf3c2f0[$pc8421459] && !in_array($pc8421459, $pc3b3502c)) { $pc3b3502c[] = $pc8421459; $pd04a32f6 .= $this->mff0e0fe7a0dd($pc8421459, $pcbf3c2f0[$pc8421459]); } } foreach ($pcbf3c2f0 as $pc8421459 => $v003bc751fd) if (!in_array($pc8421459, $pc3b3502c)) $pd04a32f6 .= $this->mff0e0fe7a0dd($pc8421459, $v003bc751fd); } } if ($pd04a32f6) { $pf8ed4912 .= '<li>'; $pf8ed4912 .= $v3a29af29e1 ? '<div class="tasks_group_label">Others</div>' : ''; $pf8ed4912 .= '<div class="tasks_group_tasks">' . $pd04a32f6 . '</div>
				<div style="clear:left; float:none;"></div>
			</li>'; } $pf8ed4912 .= '</ul>'; return $pf8ed4912; } private function mff0e0fe7a0dd($pc8421459, $v003bc751fd) { $pfbb6ee46 = str_replace(" ", "_", $v003bc751fd["tag"]); return '<div class="task task_menu task_' . $pc8421459 . ' task_' . $pfbb6ee46 . '" type="' . $pc8421459 . '" tag="' . $pfbb6ee46 . '" title="' . str_replace('"', "&quot;", $v003bc751fd["label"]) . '"><span>' . $v003bc751fd["label"] . '</span></div>'; } public function printTasksProperties() { $pf8ed4912 = ""; if (!empty($this->v4c6473be39)) { $v524bbc0f84 = $this->v524bbc0f84; $pc37695cb = count($this->v4c6473be39); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1b1c6a10a2 = $this->v4c6473be39[$v43dd7d0051]; foreach ($v524bbc0f84 as $v93feab0020 => $pcbf3c2f0) { foreach ($pcbf3c2f0 as $pc8421459 => $v003bc751fd) { if ($v003bc751fd["tag"] == $v1b1c6a10a2) { if (!empty($v003bc751fd["task_properties_html"])) { $pf8ed4912 .= '<div class="task_properties task_properties_' . $pc8421459 . '">' . (is_array($v003bc751fd) ? $v003bc751fd["task_properties_html"] : $v003bc751fd) . '</div>'; } unset($v524bbc0f84[$v93feab0020][$pc8421459]); break; } } } } foreach ($v524bbc0f84 as $v93feab0020 => $pcbf3c2f0) { foreach ($pcbf3c2f0 as $pc8421459 => $v003bc751fd) { if (!empty($v003bc751fd["task_properties_html"])) { $pf8ed4912 .= '<div class="task_properties task_properties_' . $pc8421459 . '">' . (is_array($v003bc751fd) ? $v003bc751fd["task_properties_html"] : $v003bc751fd) . '</div>'; } } } } else { foreach ($this->v524bbc0f84 as $v93feab0020 => $pcbf3c2f0) { foreach ($pcbf3c2f0 as $pc8421459 => $v003bc751fd) { if (!empty($v003bc751fd["task_properties_html"])) { $pf8ed4912 .= '<div class="task_properties task_properties_' . $pc8421459 . '">' . (is_array($v003bc751fd) ? $v003bc751fd["task_properties_html"] : $v003bc751fd) . '</div>'; } } } } return $pf8ed4912; } public function printConnectionsProperties() { $pf8ed4912 = ""; foreach ($this->v524bbc0f84 as $v93feab0020 => $pcbf3c2f0) { foreach ($pcbf3c2f0 as $pc8421459 => $v003bc751fd) { if (!empty($v003bc751fd["connection_properties_html"])) { $pf8ed4912 .= '<div class="connection_properties connection_properties_' . $pc8421459 . '">' . (is_array($v003bc751fd) ? $v003bc751fd["connection_properties_html"] : $v003bc751fd) . '</div>'; } } } return $pf8ed4912; } public function printTasksCSSAndJS() { $pa0ca8d24 = $v7c2b1a70d1 = array(); foreach ($this->v524bbc0f84 as $v93feab0020 => $pcbf3c2f0) foreach ($pcbf3c2f0 as $pc8421459 => $v003bc751fd) if (is_array($v003bc751fd)) { if ($v003bc751fd["files"]["css"]) $pa0ca8d24 = array_merge($pa0ca8d24, $v003bc751fd["files"]["css"]); if ($v003bc751fd["files"]["js"]) $v7c2b1a70d1 = array_merge($v7c2b1a70d1, $v003bc751fd["files"]["js"]); } $v136b6e03de = $this->v4bf8d90f04 ? $this->v4bf8d90f04 . "/" . WorkFlowTaskHandler::TASKS_WEBROOT_FOLDER_PREFIX . "files/" : null; $pd7e7a3e7 = $this->pfce4d1b3 ? $this->pfce4d1b3 . "/" . WorkFlowTaskHandler::TASKS_WEBROOT_FOLDER_PREFIX . "files/" : null; $v85acbf71cb = new CssAndJSFilesOptimizer($v136b6e03de, $pd7e7a3e7); $pf8ed4912 = $v85acbf71cb->getCssAndJSFilesHtml($pa0ca8d24, $v7c2b1a70d1); $pd51fd21c = $pc3e896c2 = ""; foreach ($this->v524bbc0f84 as $v93feab0020 => $pcbf3c2f0) { foreach ($pcbf3c2f0 as $pc8421459 => $v003bc751fd) { if (is_array($v003bc751fd)) { if (!empty(trim($v003bc751fd["css"]))) $pd51fd21c .= $v003bc751fd["css"] . "\n"; if (!empty(trim($v003bc751fd["settings"]["js_code"]))) $pc3e896c2 .= $v003bc751fd["settings"]["js_code"] . "\n"; } } } if (trim($pd51fd21c)) $pf8ed4912 .= '<style type="text/css">' . $pd51fd21c . '</style>' . "\n"; if (trim($pc3e896c2)) $pf8ed4912 .= '<script language="javascript" type="text/javascript">' . $pc3e896c2 . '</script>' . "\n"; return $pf8ed4912; } private function f925abac613() { $pf8ed4912 = ""; if (is_array($this->v85ea3272a0)) foreach ($this->v85ea3272a0 as $v0a9aceebca => $v3692be40aa) $pf8ed4912 .= '<div class="task_container" id="' . $v0a9aceebca . '"></div>'; return $pf8ed4912; } private function f94ef746929() { $v57316767de = array(); foreach ($this->v85ea3272a0 as $v0a9aceebca => $v189716ac66) if ($v189716ac66) { $pc37695cb = count($v189716ac66); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $v57316767de[ $v189716ac66[$v43dd7d0051] ][] = $v0a9aceebca; } return json_encode($v57316767de); } public function getTasksSettingsObj() { $pf8ed4912 = "{"; foreach ($this->v524bbc0f84 as $v93feab0020 => $pcbf3c2f0) { foreach ($pcbf3c2f0 as $pc8421459 => $v003bc751fd) { if (is_array($v003bc751fd)) { if (is_array($v003bc751fd["settings"])) { $pf8ed4912 .= ($pf8ed4912 != "{" ? ", " : "") . '"' . $pc8421459 . '" : {'; $pd69fb7d0 = 0; foreach ($v003bc751fd["settings"] as $pbfa01ed1 => $v67db1bd535) { if (is_array($v67db1bd535)) { $pa456327a = "{"; foreach ($v67db1bd535 as $v2300608a66 => $v08d1f6acf0) { if (strlen(trim($v08d1f6acf0)) > 0) { $pa456327a .= ($pa456327a != "{" ? ", " : "") . '"' . strtolower($v2300608a66) . '" : ' . $v08d1f6acf0; } } $pa456327a .= "}"; $v67db1bd535 = $pa456327a; } if (strlen(trim($v67db1bd535)) > 0) { $pf8ed4912 .= ($pd69fb7d0 > 0 ? ", " : "") . '"' . strtolower($pbfa01ed1) . '" : ' . $v67db1bd535; $pd69fb7d0++; } } $pf8ed4912 .= '}'; } } } } $pf8ed4912 .= "}"; return $pf8ed4912; } } ?>
