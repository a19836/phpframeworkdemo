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

var FunctionUtilObj = {
	
	EditFunctionCodeMyFancyPopupObject : new MyFancyPopupClass(),
	EditFunctionCodeEditor : null,
	EditFunctionCodeJsPlumbWorkFlow : null,
	OriginalJsPlumbWorkFlowObject : null,
	set_tmp_workflow_file_url : null,
	get_tmp_workflow_file_url : null,
	create_code_from_workflow_file_url : null,
	create_workflow_file_from_code_url : null,
	auto_convert: false,
	
	loadMethodArgs : function(parent_elm, arguments) {
		if (arguments) {
			var add_icon = parent_elm.find(" > table thead th .icon.add");
			
			if ($.isPlainObject(arguments) && arguments.hasOwnProperty("name"))
				arguments = [ arguments ];
			
			$.each(arguments, function(idx, arg) {
				var new_item = FunctionUtilObj.addNewMethodArg(add_icon[0]);
				
				new_item.find(".name input").val(arg["name"]);
				new_item.find(".value input").val(arg["value"]);
				new_item.find(".var_type select").val(arg["var_type"]);
			});
		}
	},
	
	addNewMethodArg : function(elm) {
		var var_types = {"string": "string", "": "default"};
		var tbody = $(elm).parent().closest("table").children("tbody");
		var index_prefix = tbody.attr("index_prefix");
		var idx = getListNewIndex(tbody);
		
		var html = '<tr class="method_arg">'
				+ '	<td class="name">'
				+ '		<input class="task_property_field" name="' + index_prefix + '[' + idx + '][name]" type="text" value="" />'
				+ '	</td>'
				+ '	<td class="value">'
				+ '		<input class="task_property_field" name="' + index_prefix + '[' + idx + '][value]" type="text" value="" />'
				+ '	</td>'
				+ '	<td class="var_type">'
				+ '		<select class="task_property_field" name="' + index_prefix + '[' + idx + '][var_type]">';
		
		for (var k in var_types) 
			html += '<option value="' + k + '">' + var_types[k] + '</option>';
				
		html += '			</select>'
				+ '	</td>'
				+ '	<td class="icon_cell table_header"><span class="icon delete" onClick="$(this).parent().parent().remove();">Remove</span></td>'
				+ '</tr>';
		
		var new_item = $(html);
		
		tbody.append(new_item);
		
		return new_item;
	},
	
	editMethodCode : function(elm) {
		var function_code_textarea = $(elm).parent().children("textarea.function_code");
		var code = "<?php\n" + function_code_textarea.val() + "\n?>";
		
		//backup original JsPlumbWorkFlowObject
		this.OriginalJsPlumbWorkFlowObject = myWFObj.getJsPlumbWorkFlow();
		this.OriginalJsPlumbWorkFlowObject.getMyFancyPopupObj().getPopupCloseButton().hide();
		
		var main_tasks_flow_parent = $("#" + this.OriginalJsPlumbWorkFlowObject.jsPlumbTaskFlow.main_tasks_flow_obj_id).parent();
		var main_div_id = "edit_function_code_" + main_tasks_flow_parent.attr("id");
		
		this.auto_convert = typeof auto_convert != "undefined" ? auto_convert : false;
		
		//prepare html
		var html = '	<div class="myfancypopup edit_function_code">'
				+ '		<ul class="tabs tabs_transparent tabs_right tabs_icons">'
				+ '			<li id="code_editor_tab"><a href="#code" onClick="FunctionUtilObj.onClickCodeEditorTab(this);return false;"><i class="icon code_editor_tab"></i> Code Editor</a></li>'
				+ '			<li id="tasks_flow_tab"><a href="#ui" onClick="FunctionUtilObj.onClickTaskWorkflowTab(this);return false;"><i class="icon tasks_flow_tab"></i> Diagram Editor</a></li>'
				+ '		</ul>'
				+ '		'
				+ '		<span class="message"></span>'
				+ '		'
				+ '		<div id="code">'
				+ '			<div class="code_menu top_bar_menu">'
				+ '				<ul>'
				+ '					<li class="editor_settings" title="Open Editor Setings"><a onClick="FunctionUtilObj.openEditorSettings(this)"><i class="icon settings"></i> Open Editor Setings</a></li>'
				+ '					<li class="pretty_print" title="Pretty Print Code"><a onClick="FunctionUtilObj.prettyPrintCode(this)"><i class="icon pretty_print"></i> Pretty Print Code</a></li>'
				+ '					<li class="set_word_wrap" title="Set Word Wrap"><a class="active" onClick="FunctionUtilObj.setWordWrap(this)" wrap="0"><i class="icon word_wrap"></i> Word Wrap</a></li>'
				+ '				</ul>'
				+ '			</div>'
				+ '			<textarea></textarea>'
				+ '		</div>'
				+ '		'
				+ '		<div id="ui">'
				+ '			<div id="' + main_div_id + '" class="taskflowchart reverse with_top_bar_menu fixed_side_properties">'
				+ '				<div id="workflow_menu" class="workflow_menu top_bar_menu">'
				+ '					<ul class="dropdown">'
				+ '						<li class="sort_tasks" title="Sort Tasks">'
				+ '							<a onclick="FunctionUtilObj.sortWorkflowTask(this);return false;"><i class="icon sort"></i> Sort Tasks</a>'
				+ '							<ul>'
				+ '								<li class="sort_tasks"><a onclick="FunctionUtilObj.sortWorkflowTask(this, 1);return false;"><i class="icon sort"></i> Sort Type 1</a></li>'
				+ '								<li class="sort_tasks"><a onclick="FunctionUtilObj.sortWorkflowTask(this, 2);return false;"><i class="icon sort"></i> Sort Type 2</a></li>'
				+ '								<li class="sort_tasks"><a onclick="FunctionUtilObj.sortWorkflowTask(this, 3);return false;"><i class="icon sort"></i> Sort Type 3</a></li>'
				+ '								<li class="sort_tasks"><a onclick="FunctionUtilObj.sortWorkflowTask(this, 4);return false;"><i class="icon sort"></i> Sort Type 4</a></li>'
				+ '							</ul>'
				+ '						</li>'
				+ '						' + (this.create_workflow_file_from_code_url && this.get_tmp_workflow_file_url ? '<li class="generate_tasks_flow_from_code" title="Generate Diagram from Code"><a onclick="FunctionUtilObj.generateTasksFlowFromCode(this);return false;"><i class="icon generate_tasks_flow_from_code"></i> Generate Diagram from Code</a></li>' : '')
				+ '						' + (this.create_code_from_workflow_file_url && this.set_tmp_workflow_file_url ? '<li class="generate_code_from_tasks_flow" title="Generate Code From Diagram"><a onclick="FunctionUtilObj.generateCodeFromTasksFlow(this);return false;"><i class="icon generate_code_from_tasks_flow"></i> Generate Code From Diagram</a></li>' : '')
				+ '					</ul>'
				+ '				</div>'
				+ '				'
				+ '				<div class="tasks_menu scroll"></div>'
				+ '				'
				+ '				<div class="tasks_menu_hide">'
				+ '					<div class="button minimize" onClick="myWFObj.getJsPlumbWorkFlow().jsPlumbContextMenu.toggleTasksMenuPanel(this)"></div>'
				+ '				</div>'
				+ '				'
				+ '				<div class="tasks_flow scroll"></div>'
				+ '				'
				+ '				<div class="tasks_properties hidden"></div>'
				+ '				'
				+ '				<div class="connections_properties hidden"></div>'
				+ '			</div>'
				+ '		</div>'
				+ '		'
				+ '		<div class="button">'
				+ '			<input type="button" value="UPDATE CODE" onClick="FunctionUtilObj.EditFunctionCodeMyFancyPopupObject.settings.updateFunction(this)" />'
				+ '		</div>'
				+ '	</div>';
		
		var popup = $(html);
		main_tasks_flow_parent.append(popup);
		
		//init tabs
		popup.tabs();
		
		//create editor
		var textarea = popup.find(" > #code > textarea");
		textarea.val(code);
		this.createEditor(textarea[0]);
		
		//init workflow
		var tasks_menu = popup.find(" > #ui > .taskflowchart > .tasks_menu");
		tasks_menu.html( main_tasks_flow_parent.children("#" + this.OriginalJsPlumbWorkFlowObject.jsPlumbContextMenu.main_tasks_menu_obj_id).html() );
		tasks_menu.find(".cloned_task").remove();
		tasks_menu.find("." + this.OriginalJsPlumbWorkFlowObject.jsPlumbContextMenu.task_menu_class_name).off().removeClass("ui-draggable ui-draggable-handle");
		
		$.each(tasks_menu.find("." + this.OriginalJsPlumbWorkFlowObject.jsPlumbContextMenu.tasks_group_tasks_class_name), function(idx, item) {
			item = $(item);
			item.attr("bkp-height", item.css("height"));
		});
		
		popup.find(" > #ui > .taskflowchart > .tasks_properties").html( main_tasks_flow_parent.children(".tasks_properties").html() );
		
		popup.find(" > #ui > .taskflowchart > .connections_properties").html( main_tasks_flow_parent.children(".connections_properties").html() );
		
		this.EditFunctionCodeMyFancyPopupObject.init({
			elementToShow: popup,
			parentElement: main_tasks_flow_parent,
			popup_class: "edit_function_popup",
			onOpen: function() {
				popup.off().removeClass("ui-draggable ui-draggable-handle");
				
				var close_func = function(e) {
					e.preventDefault();
					
					if (confirm("If you close this popup you will loose your code changes.\nTo update your code changes please click in the 'UPDATE CODE' button instead.\nDo you still wish to proceed?"))
						FunctionUtilObj.hideEditFunctionCodeMyFancyPopup();
				};
				
				var close_btn = FunctionUtilObj.EditFunctionCodeMyFancyPopupObject.getPopupCloseButton();
				close_btn.unbind("click").off().click(close_func);
				
				var overlay = FunctionUtilObj.EditFunctionCodeMyFancyPopupObject.getOverlay();
				overlay.unbind("click").off().click(close_func);
			},
			
			targetField: function_code_textarea[0],
			updateFunction: this.updateFunctionCode
		});
		
		this.EditFunctionCodeMyFancyPopupObject.showPopup();
	},
	
	updateFunctionCode : function(elm) {
		//convert workflow to code first
		var edit_function_code = $(elm).parent().closest(".edit_function_code");
		var selected_tab = edit_function_code.children("ul.tabs").children("li.ui-tabs-selected, li.ui-tabs-active").first();
		
		if (FunctionUtilObj.auto_convert && FunctionUtilObj.EditFunctionCodeJsPlumbWorkFlow && selected_tab.attr("id") == "tasks_flow_tab")
			FunctionUtilObj.generateCodeFromTasksFlow(elm, true);
		
		var code = FunctionUtilObj.getEditFunctionCodeEditorValue(elm);
		code = "?>" + code + "<?php ";
		code = code.replace(/\?>\s*<\?(php|)/g, "").replace(/^\s+/g, "").replace(/\s+$/g, "");
		
		$(FunctionUtilObj.EditFunctionCodeMyFancyPopupObject.settings.targetField).val(code);
		FunctionUtilObj.hideEditFunctionCodeMyFancyPopup();
	},
	
	hideEditFunctionCodeMyFancyPopup : function() {
		if (this.EditFunctionCodeJsPlumbWorkFlow)
			this.EditFunctionCodeJsPlumbWorkFlow.destroy();
		
		this.EditFunctionCodeJsPlumbWorkFlow = null;
		
		myWFObj.setJsPlumbWorkFlow(this.OriginalJsPlumbWorkFlowObject);
		this.OriginalJsPlumbWorkFlowObject.getMyFancyPopupObj().getPopupCloseButton().show();
		
		this.EditFunctionCodeMyFancyPopupObject.hidePopup();
		
		setTimeout(function() {
			FunctionUtilObj.EditFunctionCodeMyFancyPopupObject.destroyPopup();
		}, 500);
	},
	
	getEditFunctionCodeEditorValue : function(elm) {
		var code = "";
		
		if (this.EditFunctionCodeEditor)
			code = this.EditFunctionCodeEditor.getValue(code);
		else
			code = $(elm).closest(".edit_function_code").find(" > #code > textarea").val();
		
		return code;
	},
	
	setEditFunctionCodeEditorValue : function(elm, code) {
		if (this.EditFunctionCodeEditor)
			code = this.EditFunctionCodeEditor.setValue(code, -1);
		else
			code = $(elm).closest(".edit_function_code").find(" > #code > textarea").val(code);
	},
	
	onClickCodeEditorTab : function(elm) {
		setTimeout(function() {
			if (FunctionUtilObj.EditFunctionCodeEditor && $(elm).closest(".edit_function_code").children("#code").is(":visible"))
				FunctionUtilObj.EditFunctionCodeEditor.focus();
			
			if (FunctionUtilObj.auto_convert && FunctionUtilObj.EditFunctionCodeJsPlumbWorkFlow)
				FunctionUtilObj.generateCodeFromTasksFlow(elm, true);
		}, 10);
	},
	
	onClickTaskWorkflowTab : function(elm) {
		elm = $(elm);
		var WF = this.EditFunctionCodeJsPlumbWorkFlow;
		
		if (!WF) {
			//prepare new jsPlumbWorkFlowHandler obj
			var selector = elm.attr("href");
			var main_div_id = elm.parent().closest(".edit_function_code").children(selector).children(".taskflowchart").attr("id");
			
			WF = new jsPlumbWorkFlowHandler(main_div_id, {
				on_init_function: function(innerWF) {
					//prepare tasks menus
					$("#" + innerWF.jsPlumbContextMenu.main_tasks_menu_obj_id + " ." + innerWF.jsPlumbContextMenu.tasks_group_tasks_class_name).each(function(idx, item) {
						item = $(item);
						item.css("height", item.attr("bkp-height"));
					});
				},
				is_droppable_connection: true
			});
			eval('window.' + main_div_id + ' = WF;');
			
			WF.jsPlumbTaskFlow.main_tasks_flow_obj_id = main_div_id + " .tasks_flow";
			WF.jsPlumbTaskFlow.main_tasks_properties_obj_id = main_div_id + " .tasks_properties";
			WF.jsPlumbTaskFlow.main_connections_properties_obj_id = main_div_id + " .connections_properties";
			WF.jsPlumbContextMenu.main_tasks_menu_obj_id = main_div_id + " .tasks_menu";
			WF.jsPlumbContextMenu.main_tasks_menu_hide_obj_id = main_div_id + " .tasks_menu_hide";
			WF.jsPlumbContextMenu.main_workflow_menu_obj_id = main_div_id + " .workflow_menu";
			
			WF.jsPlumbProperty.tasks_settings = Object.assign({}, this.OriginalJsPlumbWorkFlowObject.jsPlumbProperty.tasks_settings);
			WF.jsPlumbTaskFile.set_tasks_file_url = this.OriginalJsPlumbWorkFlowObject.jsPlumbTaskFile.set_tasks_file_url;
			WF.jsPlumbTaskFile.get_tasks_file_url = this.OriginalJsPlumbWorkFlowObject.jsPlumbTaskFile.get_tasks_file_url;
			
			WF.jsPlumbContainer.tasks_containers = [];
			
			//init flow
			WF.init();
			
			this.EditFunctionCodeJsPlumbWorkFlow = WF;
			
			setTimeout(function() {
				WF.resizePanels();
			}, 500);
		}
		
		//set the new JsPlumbWorkFlow
		myWFObj.setJsPlumbWorkFlow(WF);
		
		if (this.auto_convert)
			this.generateTasksFlowFromCode(elm, true);
		
		//prepare some z-index bc of the popup
		var zindex = $(elm).closest(".edit_function_code").css("z-index");
		var main_tasks_flow_elm = $("#" + WF.jsPlumbTaskFlow.main_tasks_flow_obj_id);
		
		main_tasks_flow_elm.children("#" + WF.jsPlumbContextMenu.task_context_menu_id).css("z-index", zindex);
		main_tasks_flow_elm.children("#" + WF.jsPlumbContextMenu.connection_context_menu_id).css("z-index", zindex);
		main_tasks_flow_elm.children("#" + WF.jsPlumbStatusMessage.message_html_obj_id).css("z-index", zindex);
	},
	
	generateCodeFromTasksFlow : function(elm, do_not_confirm) {
		var status = false;
		
		if (this.create_code_from_workflow_file_url && this.set_tmp_workflow_file_url) {
			status = true;
			
			var edit_function_code = $(elm).parent().closest(".edit_function_code");
			var ui_elm = edit_function_code.children("#ui");
			var code_elm = edit_function_code.children("#code");
			var old_workflow_id = ui_elm.attr("workflow_id");
			var WF = myWFObj.getJsPlumbWorkFlow();
			var data = WF.jsPlumbTaskFile.getWorkFlowData();
			var new_workflow_id = $.md5(JSON.stringify(data));
			
			var generated_code_id = code_elm.attr("generated_code_id");
			var code = this.getEditFunctionCodeEditorValue(ui_elm);
			var new_code_id = code ? $.md5(code) : null;
			
			if (old_workflow_id != new_workflow_id || (generated_code_id && generated_code_id != new_code_id)) {
				if (do_not_confirm || this.auto_convert || confirm("Do you wish to update this code accordingly with the workflow tasks?")) {
					status = false;
					
					var workflow_menu = ui_elm.find(" > .taskflowchart > .workflow_menu");
					
					this.EditFunctionCodeMyFancyPopupObject.showLoading();
					workflow_menu.hide();
					
					var save_options = {
						overwrite: true,
						success: function(data, textStatus, jqXHR) {
							if (typeof jquery_native_xhr_object != "undefined" && jquery_native_xhr_object && typeof isAjaxReturnedResponseLogin == "function" && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
								showAjaxLoginPopup(jquery_native_xhr_object.responseURL, set_tmp_workflow_file_url, function() {
									WF.jsPlumbStatusMessage.removeLastShownMessage("error");
									FunctionUtilObj.generateCodeFromTasksFlow(elm, true);
								});
						},
					};
					
					if (WF.jsPlumbTaskFile.save(set_tmp_workflow_file_url, save_options)) {
						$.ajax({
							type : "get",
							url : this.create_code_from_workflow_file_url,
							dataType : "json",
							success : function(data, textStatus, jqXHR) {
								if (data && data.hasOwnProperty("code")) {
									var code = "<?php\n" + data.code.replace(/^\s+/g, "").replace(/\s+$/g, "") + "\n?>"; 
									FunctionUtilObj.setEditFunctionCodeEditorValue(ui_elm, code);
									
									ui_elm.attr("workflow_id", new_workflow_id);
									code_elm.attr("generated_code_id", $.md5(code));
									
									if (data["error"] && data["error"]["infinit_loop"] && data["error"]["infinit_loop"][0]) {
										var loops = data["error"]["infinit_loop"];
										
										var msg = "";
										for (var i = 0; i < loops.length; i++) {
											var loop = loops[i];
											var slabel = WF.jsPlumbTaskFlow.getTaskLabelByTaskId(loop["source_task_id"]);
											var tlabel = WF.jsPlumbTaskFlow.getTaskLabelByTaskId(loop["target_task_id"]);
											
											msg += (i > 0 ? "\n" : "") + "- '" + slabel + "' => '" + tlabel + "'";
										}
										
										msg = "The system detected the following invalid loops and discarded them from the code:\n" + msg + "\n\nYou should remove them from the workflow and apply the correct 'loop task' for doing loops.";
										WF.jsPlumbStatusMessage.showError(msg);
										alert(msg);
									}
									else {
										var edit_tab = edit_function_code.find("#code_editor_tab a").first();
										edit_tab.click();
										
										status = true;
									}
								}
								else
									FunctionUtilObj.showMessage(elm, "There was an error trying to update this code. Please try again.");
								
								FunctionUtilObj.EditFunctionCodeMyFancyPopupObject.hideLoading();
								workflow_menu.show();
							},
							error : function(jqXHR, textStatus, errorThrown) { 
								var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
								FunctionUtilObj.showMessage(elm, "There was an error trying to update this code. Please try again." + msg);
								FunctionUtilObj.EditFunctionCodeMyFancyPopupObject.hideLoading();
								workflow_menu.show();
							},
							async : false,
						});
					}
					else {
						FunctionUtilObj.showMessage(elm, "There was an error trying to update this code. Please try again.");
						FunctionUtilObj.EditFunctionCodeMyFancyPopupObject.hideLoading();
						workflow_menu.show();
					}
				}
			}
			else
				FunctionUtilObj.showMessage(elm, "The tasks flow diagram has no changes. No need to update the code.");
		}
			
		return status;
	},
	
	generateTasksFlowFromCode : function(elm, do_not_confirm) {
		var status = false;
		
		if (this.create_workflow_file_from_code_url && this.get_tmp_workflow_file_url) {
			status = true;
			
			var edit_function_code = $(elm).parent().closest(".edit_function_code");
			var ui_elm = edit_function_code.children("#ui");
			var old_code_id = ui_elm.attr("code_id");
			var code = this.getEditFunctionCodeEditorValue(ui_elm);
			new_code_id = code ? $.md5(code) : null;
			
			if (!old_code_id || old_code_id != new_code_id) {
				if (do_not_confirm || this.auto_convert || confirm("Do you wish to update this workflow accordingly with the code in the editor?")) {
					status = false;
				
					var workflow_menu = ui_elm.find(" > .taskflowchart > .workflow_menu");
					var WF = myWFObj.getJsPlumbWorkFlow();
					
					this.EditFunctionCodeMyFancyPopupObject.showLoading();
					workflow_menu.hide();
					
					$.ajax({
						type : "post",
						url : this.create_workflow_file_from_code_url,
						data : code,
						dataType : "text",
						success : function(data, textStatus, jqXHR) {
							if (typeof jquery_native_xhr_object != "undefined" && jquery_native_xhr_object && typeof isAjaxReturnedResponseLogin == "function" && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
								showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_workflow_file_from_code_url, function() {
									FunctionUtilObj.generateTasksFlowFromCode(elm, true);
								});
							else if (data == 1) {
								var previous_callback = WF.jsPlumbTaskFile.on_success_read;
								
								WF.jsPlumbTaskFile.on_success_read = function(data, text_status, jqXHR) {
									if (!data)
										WF.jsPlumbStatusMessage.showError("There was an error trying to load the workflow's tasks.");
									else {
										ui_elm.attr("code_id", new_code_id);
										ui_elm.attr("workflow_id", $.md5(JSON.stringify(data)) );
									
										WF.jsPlumbTaskSort.sortTasks();
										
										status = true;
									}
									
									WF.jsPlumbTaskFile.on_success_read = previous_callback;
								}
								
								WF.jsPlumbTaskFile.reload(get_tmp_workflow_file_url, {"async": true});
							}
							else 
								WF.jsPlumbStatusMessage.showError("There was an error trying to update this workflow. Please try again." + (data ? "\n" + data : ""));
						
							FunctionUtilObj.EditFunctionCodeMyFancyPopupObject.hideLoading();
							workflow_menu.show();
						},
						error : function(jqXHR, textStatus, errorThrown) { 
							var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
							WF.jsPlumbStatusMessage.showError("There was an error trying to update this workflow. Please try again." + msg);
							
							FunctionUtilObj.EditFunctionCodeMyFancyPopupObject.hideLoading();
							workflow_menu.show();
						},
						async : false,
					});
				}
			}
			else
				this.showMessage(elm, "The code has no changes. No need to update the tasks flow diagram.");
		}
		
		return status;
	},
	
	sortWorkflowTask : function(elm, sort_type) {
		var WF = myWFObj.getJsPlumbWorkFlow();
		
		WF.getMyFancyPopupObj().init({
			parentElement: $(elm).closest(".edit_function_code").find("#" + WF.jsPlumbTaskFlow.main_tasks_flow_obj_id),
			popup_class: "edit_function_popup",
		});
		WF.getMyFancyPopupObj().showOverlay();
		WF.getMyFancyPopupObj().showLoading();
		
		if (!sort_type)
			sort_type = prompt("Please choose the sort type that you wish? You can choose 1, 2, 3 or 4.");
		
		if (sort_type) {
			WF.jsPlumbTaskSort.sortTasks(sort_type);
			WF.jsPlumbStatusMessage.showMessage("Done sorting tasks based in the sort type: " + sort_type + ".");
		}
		
		WF.getMyFancyPopupObj().hidePopup();
	},
	
	createEditor : function(textarea) {
		if (ace && ace.edit && textarea) {
			var parent = $(textarea).parent();
			
			ace.require("ace/ext/language_tools");
			var editor = ace.edit(textarea);
			editor.setTheme("ace/theme/chrome");
			editor.session.setMode("ace/mode/php");
			editor.setAutoScrollEditorIntoView(true);
			editor.setOption("minLines", 30);
			editor.setOptions({
				enableBasicAutocompletion: true,
				enableSnippets: true,
				enableLiveAutocompletion: false,
			});
		
			this.EditFunctionCodeEditor = editor;
			
			parent.find("textarea.ace_text-input").removeClass("ace_text-input"); //fixing problem with scroll up, where when focused or pressed key inside editor the page scrolls to top
			
			editor.focus();
		}
	},
	
	prettyPrintCode : function(elm) {
		if (typeof MyHtmlBeautify != "undefined") {
			var code = FunctionUtilObj.getEditFunctionCodeEditorValue(elm);
			code = MyHtmlBeautify.beautify(code);
			code = code.replace(/^\s+/g, "").replace(/\s+$/g, "");
			
			FunctionUtilObj.setEditFunctionCodeEditorValue(elm, code);
		}
	},

	setWordWrap : function(elm) {
		if (FunctionUtilObj.EditFunctionCodeEditor) {
			var wrap = $(elm).attr("wrap") != 1 ? false : true;
			$(elm).attr("wrap", wrap ? 0 : 1);
		
			FunctionUtilObj.EditFunctionCodeEditor.getSession().setUseWrapMode(wrap);
			FunctionUtilObj.showMessage(elm, "Wrap is now " + (wrap ? "enable" : "disable"));
		}
	},
	
	openEditorSettings : function(elm) {
		if (FunctionUtilObj.EditFunctionCodeEditor) {
			if (FunctionUtilObj.EditFunctionCodeEditor.execCommand("showSettingsMenu"))
				setTimeout(function() {
					var ace_settings_menu = $("#ace_settingsmenu").parent().parent();
					ace_settings_menu.css("z-index", $(elm).closest(".edit_function_code").css("z-index"));
				}, 500);
		}
		else
			FunctionUtilObj.showMessage(elm, "Error trying to open the editor settings...");
	},
	
	showMessage : function(elm, msg) {
		var message_elm = $(elm).closest(".edit_function_code").children(".message");
		message_elm.html(msg).show();
		
		setTimeout(function() { 
			message_elm.html("").hide();
		}, 3000);
	},
};
