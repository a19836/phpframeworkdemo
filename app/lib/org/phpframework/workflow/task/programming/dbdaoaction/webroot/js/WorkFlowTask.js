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

var DBDAOActionTaskPropertyObj = {
	
	brokers_options : null,
	on_choose_table_callback : null,
	
	onLoadTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.createTaskLabelField(properties_html_elm, task_id);
		
		var task_html_elm = $(properties_html_elm).find(".db_dao_action_task_html");
		ProgrammingTaskUtil.setResultVariableType(task_property_values, task_html_elm);
		
		BrokerOptionsUtilObj.initFields(task_html_elm.find(".broker_method_obj"), DBDAOActionTaskPropertyObj.brokers_options, task_property_values["method_obj"]);
		
		LayerOptionsUtilObj.onLoadTaskProperties(task_html_elm, task_property_values);
		
		task_property_values = DBDAOActionTaskPropertyObj.convertArrayToSimpleSettings(task_property_values, "attributes");
		task_property_values = DBDAOActionTaskPropertyObj.convertArrayToSimpleSettings(task_property_values, "conditions");
		task_property_values = DBDAOActionTaskPropertyObj.convertArrayToSimpleSettings(task_property_values, "relations");
		task_property_values = DBDAOActionTaskPropertyObj.convertArrayToSimpleSettings(task_property_values, "parent_conditions");
		
		//PREPARE ATTRIBUTES
		var attributes = task_property_values["attributes"];
		var attributes_type_select = task_html_elm.find(".attrs > .attributes_type");
		
		if (task_property_values["attributes_type"] == "array") {
			ArrayTaskUtilObj.onLoadArrayItems( task_html_elm.find(".attrs > .attributes").first(), attributes, "");
			task_html_elm.find(".attrs > .attributes_code").val("");
		}
		else if (task_property_values["attributes_type"] == "options") {
			DBDAOActionTaskPropertyObj.loadSavedTableAttributesOptions( task_html_elm.find(".attrs > .attributes_options").first(), attributes);
			attributes_type_select.val("options");
		}
		else {
			attributes = attributes ? "" + attributes + "" : "";
			attributes = task_property_values["attributes_type"] == "variable" && attributes.trim().substr(0, 1) == '$' ? attributes.trim().substr(1) : attributes;
			task_html_elm.find(".attrs > .attributes_code").val(attributes);
		}
		DBDAOActionTaskPropertyObj.onChangeAttributesType(task_html_elm.find(".attrs > .attributes_type")[0]);
		
		//PREPARE CONDITIONS
		var conditions = task_property_values["conditions"];
		var conditions_type_select = task_html_elm.find(".conds > .conditions_type");
		
		if (task_property_values["conditions_type"] == "array") {
			ArrayTaskUtilObj.onLoadArrayItems( task_html_elm.find(".conds > .conditions").first(), conditions, "");
			task_html_elm.find(".conds > .conditions_code").val("");
		}
		else if (task_property_values["conditions_type"] == "options") {
			DBDAOActionTaskPropertyObj.loadSavedTableAttributesOptions( task_html_elm.find(".conds > .conditions_options").first(), conditions);
			conditions_type_select.val("options");
		}
		else {
			conditions = conditions ? "" + conditions + "" : "";
			conditions = task_property_values["conditions_type"] == "variable" && conditions.trim().substr(0, 1) == '$' ? conditions.trim().substr(1) : conditions;
			task_html_elm.find(".conds > .conditions_code").val(conditions);
		}
		DBDAOActionTaskPropertyObj.onChangeConditionsType(conditions_type_select[0]);
		
		//PREPARE REL_ELM
		var relations = task_property_values["relations"];
		var relations_type_select = task_html_elm.find(".rels > .relations_type");
		
		if (task_property_values["relations_type"] == "array") {
			ArrayTaskUtilObj.onLoadArrayItems( task_html_elm.find(".rels > .relations").first(), relations, "");
			task_html_elm.find(".rels > .relations_code").val("");
		}
		else if (task_property_values["relations_type"] == "options") {
			DBDAOActionTaskPropertyObj.loadSavedTableAttributesOptions( task_html_elm.find(".rels > .relations_options").first(), relations);
			relations_type_select.val("options");
		}
		else {
			relations = relations ? "" + relations + "" : "";
			relations = task_property_values["relations_type"] == "variable" && relations.trim().substr(0, 1) == '$' ? relations.trim().substr(1) : relations;
			task_html_elm.find(".rels > .relations_code").val(relations);
		}
		DBDAOActionTaskPropertyObj.onChangeRelElmType(relations_type_select[0]);
		
		//PREPARE PARENT CONDITIONS
		var parent_conditions = task_property_values["parent_conditions"];
		var parent_conditions_type_select = task_html_elm.find(".parent_conds > .parent_conditions_type");
		
		if (task_property_values["parent_conditions_type"] == "array") {
			ArrayTaskUtilObj.onLoadArrayItems( task_html_elm.find(".parent_conds > .parent_conditions").first(), parent_conditions, "");
			task_html_elm.find(".parent_conds > .parent_conditions_code").val("");
		}
		else if (task_property_values["parent_conditions_type"] == "options") {
			DBDAOActionTaskPropertyObj.loadSavedTableAttributesOptions( task_html_elm.find(".parent_conds > .parent_conditions_options").first(), parent_conditions);
			parent_conditions_type_select.val("options");
		}
		else {
			parent_conditions = parent_conditions ? "" + parent_conditions + "" : "";
			parent_conditions = task_property_values["parent_conditions_type"] == "variable" && parent_conditions.trim().substr(0, 1) == '$' ? parent_conditions.trim().substr(1) : parent_conditions;
			task_html_elm.find(".parent_conds > .parent_conditions_code").val(parent_conditions);
		}
		DBDAOActionTaskPropertyObj.onChangeParentConditionsType(parent_conditions_type_select[0]);
		
		DBDAOActionTaskPropertyObj.onChangeMethodName( task_html_elm.find(".method_name select")[0] );
	},
	
	onSubmitTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.saveTaskLabelField(properties_html_elm, task_id);
		
		var task_html_elm = $(properties_html_elm).find(".db_dao_action_task_html");
		ProgrammingTaskUtil.saveNewVariableInWorkflowAccordingWithType(task_html_elm);
		ProgrammingTaskUtil.onSubmitResultVariableType(task_html_elm);
		
		//prepare attributes
		if (task_html_elm.find(".attrs > .attributes_type").val() == "array")
			task_html_elm.find(".attrs > .attributes_code, .attrs > .attributes_options").remove();
		else if (task_html_elm.find(".attrs > .attributes_type").val() == "options")  { 
			task_html_elm.find(".attrs > .attributes_type").val("array");
			
			//convert .attributes_options to array
			var items = DBDAOActionTaskPropertyObj.convertSimpleSettingsToArray(task_html_elm, task_html_elm.find(".attrs > .attributes_options") );
			ArrayTaskUtilObj.onLoadArrayItems( task_html_elm.find('.attrs > .attributes').first(), items, "");
			
			task_html_elm.find(".attrs > .attributes_code, .attrs > .attributes_options").remove();
		}
		else
			task_html_elm.find(".attrs > .attributes, .attrs > .attributes_options").remove();
		
		//prepare conditions
		if (task_html_elm.find(".conds > .conditions_type").val() == "array")
			task_html_elm.find(".conds > .conditions_code, .conds > .conditions_options").remove();
		else if (task_html_elm.find(".conds > .conditions_type").val() == "options")  { 
			task_html_elm.find(".conds > .conditions_type").val("array");
			
			//convert .conditions to array
			var items = DBDAOActionTaskPropertyObj.convertSimpleSettingsToArray(task_html_elm, task_html_elm.find(".conds > .conditions_options") );
			ArrayTaskUtilObj.onLoadArrayItems( task_html_elm.find('.conds > .conditions').first(), items, "");
			
			task_html_elm.find(".conds > .conditions_code, .conds > .conditions_options").remove();
		}
		else
			task_html_elm.find(".conds > .conditions, .conds > .conditions_options").remove();
		
		//prepare relations
		if (task_html_elm.find(".rels > .relations_type").val() == "array")
			task_html_elm.find(".rels > .relations_code, .rels > .relations_options").remove();
		else if (task_html_elm.find(".rels > .relations_type").val() == "options")  { 
			task_html_elm.find(".rels > .relations_type").val("array");
			
			//convert .relations to array
			var items = DBDAOActionTaskPropertyObj.convertSimpleSettingsToArray(task_html_elm, task_html_elm.find(".rels > .relations_options") );
			ArrayTaskUtilObj.onLoadArrayItems( task_html_elm.find('.rels > .relations').first(), items, "");
			
			task_html_elm.find(".rels > .relations_code, .rels > .relations_options").remove();
		}
		else
			task_html_elm.find(".rels > .relations, .rels > .relations_options").remove();
		
		//prepare parent_conditions
		if (task_html_elm.find(".parent_conds > .parent_conditions_type").val() == "array")
			task_html_elm.find(".parent_conds > .parent_conditions_code, .parent_conds > .parent_conditions_options").remove();
		else if (task_html_elm.find(".parent_conds > .parent_conditions_type").val() == "options") { 
			task_html_elm.find(".parent_conds > .parent_conditions_type").val("array");
			
			//convert .parent_conditions to array
			var items = DBDAOActionTaskPropertyObj.convertSimpleSettingsToArray(task_html_elm, task_html_elm.find(".parent_conds > .parent_conditions_options") );
			ArrayTaskUtilObj.onLoadArrayItems( task_html_elm.find('.parent_conds > .parent_conditions').first(), items, "");
			
			task_html_elm.find(".parent_conds > .parent_conditions_code, .parent_conds > .parent_conditions_options").remove();
		}
		else
			task_html_elm.find(".parent_conds > .parent_conditions, .parent_conds > .parent_conditions_options").remove();
		
		if (task_html_elm.find(".opts .options_type").val() == "array")
			task_html_elm.find(".opts .options_code").remove();
		else
			task_html_elm.find(".opts .options").remove();
		
		return true;
	},
	
	onCompleteTaskProperties : function(properties_html_elm, task_id, task_property_values, status) {
		if (status) {
			var label = DBDAOActionTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
			
			var default_method_obj_str = BrokerOptionsUtilObj.getDefaultBroker(DBDAOActionTaskPropertyObj.brokers_options);
			if (!task_property_values["method_obj"] && default_method_obj_str)
				task_property_values["method_obj"] = default_method_obj_str;
		}
	},
	
	onCancelTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		return true;	
	},
	
	onCompleteLabel : function(task_id) {
		return ProgrammingTaskUtil.onEditLabel(task_id);
	},
	
	onTaskCreation : function(task_id) {
		setTimeout(function() {
			var task_property_values = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[task_id];
			ProgrammingTaskUtil.saveNewVariableInWorkflowAccordingWithTaskPropertiesValues(task_property_values);
		
			var label = DBDAOActionTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
		
			onEditLabel(task_id);
		
			var default_method_obj_str = BrokerOptionsUtilObj.getDefaultBroker(DBDAOActionTaskPropertyObj.brokers_options);
			if (!task_property_values["method_obj"] && default_method_obj_str)
				myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[task_id]["method_obj"] = default_method_obj_str;
			
			ProgrammingTaskUtil.onTaskCreation(task_id);
		}, 30);
	},
	
	getDefaultExitLabel : function(task_property_values) {
		if (task_property_values["method_name"]) {
			var method_obj = (task_property_values["method_obj"].trim().substr(0, 1) != "$" ? "$" : "") + task_property_values["method_obj"];
			var method_name = task_property_values["method_name"];
			var table_name = ProgrammingTaskUtil.getValueString(task_property_values["table_name"], task_property_values["table_name_type"]);
			
			var attributes = task_property_values["attributes_type"] == "array" ? ArrayTaskUtilObj.arrayToString(task_property_values["attributes"]) : ProgrammingTaskUtil.getValueString(task_property_values["attributes"], task_property_values["attributes_type"]);
			attributes = attributes ? attributes : "null";
			
			var conditions = task_property_values["conditions_type"] == "array" ? ArrayTaskUtilObj.arrayToString(task_property_values["conditions"]) : ProgrammingTaskUtil.getValueString(task_property_values["conditions"], task_property_values["conditions_type"]);
			conditions = conditions ? conditions : "null";
			
			var relations = task_property_values["relations_type"] == "array" ? ArrayTaskUtilObj.arrayToString(task_property_values["relations"]) : ProgrammingTaskUtil.getValueString(task_property_values["relations"], task_property_values["relations_type"]);
			relations = relations ? relations : "null";
			
			var parent_conditions = task_property_values["parent_conditions_type"] == "array" ? ArrayTaskUtilObj.arrayToString(task_property_values["parent_conditions"]) : ProgrammingTaskUtil.getValueString(task_property_values["parent_conditions"], task_property_values["parent_conditions_type"]);
			parent_conditions = parent_conditions ? parent_conditions : "null";
			
			var options = task_property_values["options_type"] == "array" ? ArrayTaskUtilObj.arrayToString(task_property_values["options"]) : ProgrammingTaskUtil.getValueString(task_property_values["options"], task_property_values["options_type"]);
			options = options ? options : "null";
			
			var label = ProgrammingTaskUtil.getResultVariableString(task_property_values) + method_obj + '->' + method_name + '(' + table_name;
			
			if (method_name == "insertObject" || method_name == "findObjectsColumnMax")
				label += ', ' + attributes;
			else if (method_name == "updateObject" || method_name == "findObjects")
				label += ', ' + attributes + ', ' + conditions;
			else if (method_name == "deleteObject" || method_name == "countObjects")
				label += ', ' + conditions;
			else if (method_name == "findRelationshipObjects" || method_name == "countRelationshipObjects")
				label += ', ' + relations + ', ' + parent_conditions;
			
			label += ', ' + options + ')';
			
			return label;
		}
		return "";
	},
	
	convertSimpleSettingsToArray : function(task_html_elm, html_elm) {
		//prepare atribuets, conditions, relations and parent_conditions
		var method_name = task_html_elm.find(".method_name select").val();
		var checkboxes = html_elm.find(".attr_active:checked");
		
		html_elm.find(".task_property_field").removeClass("task_property_field");
		
		for (var i = 0; i < checkboxes.length; i++) {
			if (method_name == "findObjectsColumnMax") //remove all checked boxes for attributes on findObjectsColumnMax, bc only 1 column can be selected.
				$(checkboxes[i]).addClass("task_property_field");
			else
				$(checkboxes[i]).parent().find("input.attr_alias:visible, input.attr_value:visible").addClass("task_property_field");
		}
		
		//get html item to plain object
		var obj = FormFieldsUtilObj.getFormSettingsDataSettings(html_elm);
		//console.log(obj);
		
		//convert plain object to array
		return FormFieldsUtilObj.convertFormSettingsObjectToArray(obj);
	},
	
	convertArrayToSimpleSettings : function(task_property_values, key) {
		if (task_property_values[key + "_type"] == "array" && (
			$.isPlainObject(task_property_values[key]) || $.isArray(task_property_values[key])
		)) {
			var obj = FormFieldsUtilObj.convertFormSettingsDataArrayToSettings(task_property_values[key]);
			var arr = FormFieldsUtilObj.convertFormSettingsObjectToArray(obj);
			
			//prepare numeric indexes to be as an array
			var new_arr_data = [];
			$.each(task_property_values[key], function(i, item) {
				new_arr_data.push(item);
			});
			
			if (JSON.stringify(arr) == JSON.stringify(new_arr_data)) {
				task_property_values[key + "_type"] = "options";
				task_property_values[key] = obj;
			}
			/*else {
				console.log(JSON.stringify(arr));
				console.log(JSON.stringify(new_arr_data));
			}*/
		}
		
		return task_property_values;
	},
	
	onChangeMethodName : function(elm) {
		var method_name = $(elm).val();
		var task_html_elm = $(elm).parent().parent();
		
		task_html_elm.children(".get_automatically, .table_name, .attrs, .conds, .rels, .parent_conds").hide();
		task_html_elm.removeClass("insertObject findObjectsColumnMax updateObject findObjects deleteObject countObjects findRelationshipObjects countRelationshipObjects");
		
		if (method_name) {
			task_html_elm.addClass(method_name);
			task_html_elm.children(".get_automatically, .table_name").show();
			
			if (method_name == "insertObject" || method_name == "findObjectsColumnMax") {
				task_html_elm.children(".attrs").show();
				
				if (method_name == "findObjectsColumnMax") //remove all checked boxes for attributes on findObjectsColumnMax, bc only 1 column can be selected.
					task_html_elm.find(".attrs > .attributes_options li").removeClass("attr_activated").find("input.attr_active").removeAttr("checked").prop("checked", false);
			}
			else if (method_name == "updateObject" || method_name == "findObjects")
				task_html_elm.children(".attrs, .conds").show();
			else if (method_name == "deleteObject" || method_name == "countObjects")
				task_html_elm.children(".conds").show();
			else if (method_name == "findRelationshipObjects" || method_name == "countRelationshipObjects")
				task_html_elm.children(".rels, .parent_conds").show();
		}
	},
	
	onChangeAttributesType : function(elm) {
		this.onChangeParametersType(elm, "attributes");
	},
	
	onChangeConditionsType : function(elm) {
		this.onChangeParametersType(elm, "conditions");
	},
	
	onChangeRelElmType : function(elm) {
		this.onChangeParametersType(elm, "relations");
	},
	
	onChangeParentConditionsType : function(elm) {
		this.onChangeParametersType(elm, "parent_conditions");
	},
	
	onChangeParametersType : function(elm, prefix_class) {
		elm = $(elm);
		var type = elm.val();
		
		var parent = elm.parent();
		var arr_elm = parent.children("." + prefix_class);
		var code_elm = parent.children("." + prefix_class + "_code");
		var options_elm = parent.children("." + prefix_class + "_options");
		
		if (type == "array") {
			code_elm.hide();
			arr_elm.show();
			
			if (elm.attr("current_type") == "options" && options_elm.children("li:not(.no_items):not(.add)").length > 0 && confirm("Do you wish to convert these options to an array?")) {
				var items = this.convertSimpleSettingsToArray(parent.parent().closest(".db_dao_action_task_html"), options_elm);
				ArrayTaskUtilObj.onLoadArrayItems(arr_elm, items, "");
			}
			else if (!arr_elm.find(".items")[0]) {
				var items = {0: {key_type: "null", value_type: "string"}};
				ArrayTaskUtilObj.onLoadArrayItems(arr_elm, items, "");
			}
			
			options_elm.hide();
		}
		else if (type == "options") {
			code_elm.hide();
			options_elm.show();
			arr_elm.hide();
			
			if (elm.attr("current_type") == "array" && arr_elm.find(".item").length > 0 && confirm("Do you wish to convert this array to options?")) {
				var WF = myWFObj.getJsPlumbWorkFlow();
				var query_string = WF.jsPlumbProperty.getPropertiesQueryStringFromHtmlElm(arr_elm, "task_property_field");
				var items = {};
				parse_str(query_string, items);
				
				var first_key = arr_elm.children("ul").attr("parent_name");
				items = items[first_key] ? items[first_key] : {};
				
				var options = FormFieldsUtilObj.convertFormSettingsDataArrayToSettings(items);
				this.loadSavedTableAttributesOptions(options_elm, options);
			}
		}
		else {
			code_elm.show();
			options_elm.hide();
			arr_elm.hide();
		}
		
		elm.attr("current_type", type);
		
		ProgrammingTaskUtil.onChangeTaskFieldType(elm[0]);
	},
	
	onChooseTable : function(elm) {
		if (typeof this.on_choose_table_callback == "function")
			this.on_choose_table_callback(elm, function(table_and_attributes) {
				DBDAOActionTaskPropertyObj.chooseTable(elm, table_and_attributes);
			});
			
	},
	
	chooseTable : function(elm, table_and_attributes) {
		if (table_and_attributes && $.isPlainObject(table_and_attributes)) {
			var table = table_and_attributes["table"];
			var attributes = table_and_attributes["attributes"];
			var task_html_elm = $(elm).parent().closest(".db_dao_action_task_html");
			
			//convert attributes array into a plain object where keys are the attribute names.
			if ($.isArray(attributes)) {
				var attributes_obj = {};
				
				for (var i = 0; i < attributes.length; i++) 
					attributes_obj[ attributes[i] ] = {};
				
				attributes = attributes_obj;
			}
			
			//prepare table name
			task_html_elm.find(".table_name input").val(table);
			task_html_elm.find(".table_name select").val("string");
			
			//prepare table attributes fields html
			var method_name = task_html_elm.find(".method_name select").val();
			var attributes_options = task_html_elm.find(".attrs > .attributes_options");
			var conditions_options = task_html_elm.find(".conds > .conditions_options");
			var relations_options = task_html_elm.find(".rels > .relations_options");
			var parent_conditions_options = task_html_elm.find(".parent_conds > .parent_conditions_options");
			
			DBDAOActionTaskPropertyObj.loadNewTableAttributesOptions(attributes_options, attributes, method_name);
			DBDAOActionTaskPropertyObj.loadNewTableAttributesOptions(conditions_options, attributes, method_name);
			DBDAOActionTaskPropertyObj.loadNewTableAttributesOptions(relations_options, null, method_name);
			DBDAOActionTaskPropertyObj.loadNewTableAttributesOptions(parent_conditions_options, null, method_name);
			
			//prepare fields types
			var attributes_type = task_html_elm.find(".attrs > .attributes_type");
			var conditions_type = task_html_elm.find(".conds > .conditions_type");
			var relations_type = task_html_elm.find(".rels > .relations_type");
			var parent_conditions_type = task_html_elm.find(".parent_conds > .parent_conditions_type");
			
			attributes_type.val("options");
			conditions_type.val("options");
			relations_type.val("options");
			parent_conditions_type.val("options");
			
			DBDAOActionTaskPropertyObj.onChangeAttributesType(attributes_type[0]);
			DBDAOActionTaskPropertyObj.onChangeConditionsType(conditions_type[0]);
			DBDAOActionTaskPropertyObj.onChangeRelElmType(relations_type[0]);
			DBDAOActionTaskPropertyObj.onChangeParentConditionsType(parent_conditions_type[0]);
		}
	},
	
	onAddTableAttributeOption : function(elm) {
		var name = prompt("Write the attribute name you pretend to add?");
		
		if (name && name.replace(/\s+/g, "") != "") {
			DBDAOActionTaskPropertyObj.addTableAttributeOption(elm, {
				name : name.replace(/\s+/g, ""),
				checked : true,
			});
		}
	},
	
	loadNewTableAttributesOptions : function(table_items_elm, table_items_options, method_name) {
		if (table_items_elm[0]) {
			//clean html
			table_items_elm.children("li:not(.no_items):not(.add)").remove();
			
			if (table_items_options && $.isPlainObject(table_items_options) && !$.isEmptyObject(table_items_options)) {
				//hide no_items element
				table_items_elm.children("li.no_items").hide();
				
				//prepare html
				var add_icon = table_items_elm.children(".add");
				
				for (var item_name in table_items_options) {
					var item_props = table_items_options[item_name];
					item_props = item_props && $.isPlainObject(item_props) ? item_props : {};
					
					var item_settings = {name : item_name};
					
					if (table_items_elm.is(".conditions_options, .parent_conditions_options"))
						item_settings["checked"] = item_props["primary_key"];
					else
						item_settings["checked"] = method_name != "findObjectsColumnMax";
					
					this.addTableAttributeOption(add_icon[0], item_settings);
				}
			}
			else //show no_items element
				table_items_elm.children("li.no_items").show();
		}
	},
	
	loadSavedTableAttributesOptions : function(table_items_elm, table_items_options) {
		if (table_items_elm[0]) {
			//clean html
			table_items_elm.children("li:not(.no_items):not(.add)").remove();
			
			if (table_items_options && $.isPlainObject(table_items_options) && !$.isEmptyObject(table_items_options)) {
				//hide no_items element
				table_items_elm.children("li.no_items").hide();
				
				//prepare html
				var add_icon = table_items_elm.children(".add");
				
				for (var item_name in table_items_options) {
					var item_value = table_items_options[item_name];
					
					var item_settings = {
						name : item_name,
						checked : true,
						value : item_value,
						alias : item_value,
					};
					this.addTableAttributeOption(add_icon[0], item_settings);
				}
			}
			else //show no_items element
				table_items_elm.children("li.no_items").show();
		}
	},
	
	addTableAttributeOption : function(elm, settings) {
		var name = settings["name"];
		
		if (name) {
			elm = $(elm);
			var p = elm.parent();
			var checked = settings.hasOwnProperty("checked") ? settings["checked"] : false;
			var value = settings.hasOwnProperty("value") ? settings["value"] : "";
			var alias = settings.hasOwnProperty("alias") ? settings["alias"] : "";
			var html = '';
			
			if (p.is(".conditions_options, .parent_conditions_options")) {
				html = '<li ' + (checked ? ' class="attr_activated"' : '') + '>'
					+ '	<input class="attr_active" type="checkbox" onclick="DBDAOActionTaskPropertyObj.activateDBActionTableAttributeOption(this)" ' + (checked ? 'checked' : '') + '>'
					+ '	<label>' + name + '</label>'
					+ '	<input class="attr_value" type="text" name="' + name + '" value="' + value + '" PlaceHolder="Write the value here">'
					+ '	<span class="icon add_variable" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" input_selector=".attr_value">Add Variable</span>'
					+ '	<span class="icon delete" title="Remove item" onClick="$(this).parent().remove();"></span>'
					+ '</li>';
			}
			else if (p.is(".relations_options")) {
				html = ''; //TODO
			}
			else {
				html = '<li ' + (checked ? ' class="attr_activated"' : '') + '>'
					+ '	<input class="attr_active" type="checkbox" name="' + name + '" value="" onclick="DBDAOActionTaskPropertyObj.activateDBActionTableAttributeOption(this)" ' + (checked ? 'checked' : '') + '>'
					+ '	<label>' + name + '</label>'
					+ '	<input class="attr_value" type="text" name="' + name + '" value="' + value + '" PlaceHolder="Write the value here">'
					+ '	<input class="attr_alias" type="text" name="' + name + '" value="' + alias + '" PlaceHolder="Write the alias here">'
					+ '	<span class="icon add_variable" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" input_selector=".attr_value, .attr_alias">Add Variable</span>'
					+ '	<span class="icon delete" title="Remove item" onClick="$(this).parent().remove();"></span>'
					+ '</li>';
			}
			
			var item = $(html);
			p.append(item);
			
			ProgrammingTaskUtil.onProgrammingTaskPropertiesNewHtml(item);
		
			return item;
		}
		
		return null;
	},
	
	activateDBActionTableAttributeOption : function(elm) {
		elm = $(elm);
		var parent_li = elm.parent();
		
		if (elm.is(":checked"))
			parent_li.addClass("attr_activated");
		else 
			parent_li.removeClass("attr_activated");
		
		var ul = elm.parent().closest("ul");
		
		if (ul.is(".attributes_options")) { //only if attributes
			var task_html_elm = elm.parent().closest(".db_dao_action_task_html");
			var parent_attr_name = parent_li.children(".attr_value").attr("name");
			
			if (task_html_elm.is(".findObjectsColumnMax")) {
				ul.find("li").each(function(idx, li) {
					li = $(li);
					
					if (li.children(".attr_value").attr("name") != parent_attr_name)
						li.removeClass("attr_activated").find("input.attr_active").removeAttr("checked").prop("checked", false);
				});
			}
		}
	},
};
