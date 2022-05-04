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

include $EVC->getViewPath("presentation/create_presentation_uis_diagram"); if ($new_path) { $page_name = $db_table . "_" . $task_tag . ($task_tag_action ? "_" . $task_tag_action : ""); $head .= '
	<style>
	.taskflowchart .tasks_menu_hide,
	  .taskflowchart .workflow_menu {
		display:none !important;
	}
	.taskflowchart.with_top_bar_menu .tasks_menu, 
	  .taskflowchart.with_top_bar_menu .tasks_menu_hide, 
	  .taskflowchart.with_top_bar_menu .tasks_flow {
		top:60px;
	}
	.taskflowchart .selected_task_properties {
		font-size:11px;
	}
	.taskflowchart .selected_task_properties .ui-widget {
		font-size:11px;
	}
	.taskflowchart.fixed_properties .selected_task_properties.maximize_properties, 
	  .taskflowchart.fixed_properties .selected_connection_properties.maximize_properties {
		top:60px !important;
	}
	
	.taskflowchart:not(.with_top_bar_menu):not(.reverse) .tasks_menu {
		width:130px !important;
		top: -50px !important;
	    	left: -20px !important;
	}
	.taskflowchart:not(.with_top_bar_menu):not(.reverse) .tasks_flow {
		top:0 !important;
		left:110px !important;
	}
	.taskflowchart:not(.with_top_bar_menu):not(.reverse) .selected_task_properties {
		left: 114px !important;
	    	bottom: 48px !important;
	}
	.taskflowchart:not(.with_top_bar_menu):not(.reverse) .workflow_message {
		bottom:48px !important;
	}

	.taskflowchart .tasks_flow .task_page {
		min-width:100% !important;
		min-height:100% !important;
		position:absolute !important;
		top:-2px !important;
		left:-2px !important;
		right:-2px !important;
		bottom:-2px !important;
		
		background-image:none;
		border:0;
		background:transparent;
	}
	.taskflowchart .tasks_flow .task_page > .task_info,
	  .taskflowchart .tasks_flow .task_page > .info,
	  .taskflowchart .tasks_flow .task_page > .eps,
	  .taskflowchart .tasks_flow .task:hover:after {
		display:none;
	}
	.taskflowchart .tasks_flow .task.task_page > .task_droppable {
		top:0;
	}

	.create_uis_files {
		left:15px !important;
		right:15px !important;
	}
	.create_uis_files .overwrite {
		display:none;
	}
	</style>

	<script>
	var file_block_to_search = "' . str_replace("/src/entity/", "/src/block/", $new_path) . $page_name . '";
	
	$(function () {
		$(".create_uis_files .overwrite input").removeAttr("checked").prop("checked", false);
		
		var top_bar_header = $(".top_bar header");
		top_bar_header.children("ul").remove();
		top_bar_header.append(\'<ul><li class="continue" title="Continue"><a onclick="createPageUIFiles(this)"><i class="icon continue"></i> Continue</a></li></ul>\');
		
		//prepare workflow
		var WF = jsPlumbWorkFlow;
		var taskflowchart = $(".taskflowchart");
		taskflowchart.children(".workflow_menu").remove();
		var tasks_menu = taskflowchart.children(".tasks_menu");
		var page_task_menu = tasks_menu.find(".task_page");
		var page_task_type = page_task_menu.attr("type");
		
		//hide page task
		page_task_menu.hide();
		
		//add default page task to the tasks_flow
		WF.jsPlumbProperty.tasks_settings[page_task_type]["is_resizable_task"] = false;
		var page_task_id = WF.jsPlumbContextMenu.addTaskByType(page_task_type);

		if (page_task_id) {
			var tasks_flow = $("#" + WF.jsPlumbTaskFlow.main_tasks_flow_obj_id);
			var task = WF.jsPlumbTaskFlow.getTaskById(page_task_id);
			var task_droppable = task.children(".task_droppable");
			
			WF.jsPlumbTaskFlow.setTaskLabelByTaskId(page_task_id, {label: "' . $page_name . '"});
			
			if (!WF.jsPlumbTaskFlow.tasks_properties[page_task_id])
				WF.jsPlumbTaskFlow.tasks_properties[page_task_id] = {};
			
			WF.jsPlumbTaskFlow.tasks_properties[page_task_id]["join_type"] = "list";
			
			//add selected task to page task
			var selected_task = tasks_menu.find(".task_' . $task_tag . '");
			var selected_task_type = selected_task.attr("type");
			
			var selected_task_id = WF.jsPlumbContextMenu.addTaskByType(selected_task_type, {top: 0, left: 0}, task_droppable);
			
			//prepare selected task properties
			PresentationTaskUtil.getDBTables("' . $db_driver . '", "' . $db_type . '"); //update db tables list
			var db_attributes = PresentationTaskUtil.getDBTableAttributes("' . $db_driver . '", "' . $db_type . '", "' . $db_table . '");
			var uis_attributes = [];
			
			if (db_attributes)
				for (var attribute_name in db_attributes)
					uis_attributes.push( {active: 1, name: attribute_name} );
			
			if (!WF.jsPlumbTaskFlow.tasks_properties[selected_task_id])
				WF.jsPlumbTaskFlow.tasks_properties[selected_task_id] = {};
			
			if (!WF.jsPlumbTaskFlow.tasks_properties[selected_task_id]["choose_db_table"])
				WF.jsPlumbTaskFlow.tasks_properties[selected_task_id]["choose_db_table"] = {};
			
			if (!WF.jsPlumbTaskFlow.tasks_properties[selected_task_id]["action"])
				WF.jsPlumbTaskFlow.tasks_properties[selected_task_id]["action"] = {};
			
			WF.jsPlumbTaskFlow.tasks_properties[selected_task_id]["choose_db_table"]["db_driver"] = "' . $db_driver . '";
			WF.jsPlumbTaskFlow.tasks_properties[selected_task_id]["choose_db_table"]["db_type"] = "' . $db_type . '";
			WF.jsPlumbTaskFlow.tasks_properties[selected_task_id]["choose_db_table"]["db_table"] = "' . $db_table . '";
			WF.jsPlumbTaskFlow.tasks_properties[selected_task_id]["attributes"] = uis_attributes;
			
			' . ($task_tag_action ? '
			WF.jsPlumbTaskFlow.tasks_properties[selected_task_id]["action"]["single_' . $task_tag_action . '"] = 1;
			' . ($task_tag == "listing" ? 'WF.jsPlumbTaskFlow.tasks_properties[selected_task_id]["action"]["multiple_' . $task_tag_action . '"] = 1;' : '') . '
			' : '') . '
			
			//prepare page properties
			var page_properties = $("#" + WF.jsPlumbTaskFlow.main_tasks_properties_obj_id + " .task_properties_" + page_task_type.toLowerCase());
			page_properties.find(".file_name, .template, .links, .authentication_tab, .advanced_settings_tab").hide();
			WF.jsPlumbProperty.tasks_settings[page_task_type]["task_menu"]["show_set_label_menu"] = false;
			WF.jsPlumbProperty.tasks_settings[page_task_type]["task_menu"]["show_start_task_menu"] = false;
			WF.jsPlumbProperty.tasks_settings[page_task_type]["task_menu"]["show_delete_menu"] = false;
			
			createPageUIFiles();
		}
	});

	function onCheckedUISFilesHtml(ul) {
		ul.find(".files_statuses table tbody tr td.file_path").each(function(idx, td) {
			td = $(td);
			var file_path = td.text();
			
			if (file_path == \'' . $new_path . $page_name . '\') {
				td.parent().find(".select_file input").removeAttr("checked").prop("checked", false);
				td.parent().hide();
			}
			else if (file_path.indexOf("/src/entity/") != -1 || file_path.indexOf("/src/block/") != -1)
				td.parent().find(".select_file input").attr("checked", "checked").prop("checked", true);
		});
	}

	function onSaveUISFiles(step_5) {
		var selected_block_path = null;
		var tds = step_5.find(".files_statuses table tbody tr td.file_path");
		
		$.each(tds, function(idx, td) {
			td = $(td);
			var file_path = td.text();
			
			if (file_path == \'' . $new_path . $page_name . '\')
				td.parent().hide();
			else if (td.parent().find(".status.status_ok").length > 0) {
				if (file_path.toLowerCase() == file_block_to_search.toLowerCase()) {
					selected_block_path = file_path;
					return false;
				}
				
				var m = file_path.match(/_[0-9]+$/);
				
				if (m && file_path.substr(0, file_path.length - m[0].length).toLowerCase() == file_block_to_search.toLowerCase())
					selected_block_path = file_path;
			}
		});
		
		if (selected_block_path) {
			step_5.find("> .button").hide();
			
			setTimeout(function() {
				if (parent && typeof parent.' . $parent_add_block_func . ' == "function")
					parent.' . $parent_add_block_func . '(selected_block_path);
				else
					alert("Block created successfully");
				
				//Refreshing entities and blocks folder in main tree of the admin advanced panel
				if (window.parent && window.parent.parent && window.parent.parent.refreshLastNodeChilds && window.parent.parent.mytree && window.parent.parent.mytree.tree_elm) {
					var project = window.parent.parent.$("#" + window.parent.parent.last_selected_node_id).parent().closest("li[data-jstree=\'{\"icon\":\"project\"}\']");
					
					var entities_folder_id = project.children("ul").children("li[data-jstree=\'{\"icon\":\"entities_folder\"}\']").attr("id");
					window.parent.parent.refreshNodeChildsByNodeId(entities_folder_id);
					
					var blocks_folder_id = project.children("ul").children("li[data-jstree=\'{\"icon\":\"blocks_folder\"}\']").attr("id");
					window.parent.parent.refreshNodeChildsByNodeId(blocks_folder_id);
				}
			}, 500); //setTimeout is to show the the popup with the step_5 info
		}
	}

	function onCloseCreateUIFiles() {
		$(".top_bar li.continue").show();
	}

	function createPageUIFiles() {
		var tasks = jsPlumbWorkFlow.jsPlumbTaskFlow.getAllTasks();
		var exists_tasks = false;
		var exists_permissions = false;
		
		for (var i = 0; i < tasks.length; i++) {
			var task = $(tasks[i]);
			var task_tag = task.attr("tag");
			
			if (task_tag == "listing" || task_tag == "form" || task_tag == "view") {
				exists_tasks = true;
				
				var task_id = task.attr("id");
				if (jsPlumbWorkFlow.jsPlumbTaskFlow.tasks_properties[task_id] && !$.isEmptyObject(jsPlumbWorkFlow.jsPlumbTaskFlow.tasks_properties[task_id]["users_perms"])) {
					exists_permissions = true;
					break;
				}
			}
		}
		
		if (exists_tasks) {
			var can_continue = exists_permissions ? confirm("We detected that you added some permissions. \nIn order to them to work, the page must be already configured to initialize the logged user. \nIf this is not the case, please click on the CANCEL button.") : true;
			
			if (can_continue) {
				$(".top_bar li.continue").hide();
				createUIFiles();
			}
		}
		else
			jsPlumbWorkFlow.jsPlumbStatusMessage.showError("Please create some tasks first...");
	}
	</script>'; $main_content .= '<script>
	$(".top_bar").addClass("in_popup");
	</script>'; } ?>
