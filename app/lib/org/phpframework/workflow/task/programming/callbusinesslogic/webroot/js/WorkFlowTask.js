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

var CallBusinessLogicTaskPropertyObj = {
	
	on_choose_business_logic_callback : null,
	brokers_options : null,
	
	onLoadTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.createTaskLabelField(properties_html_elm, task_id);
		
		var task_html_elm = $(properties_html_elm).find(".call_business_logic_task_html");
		ProgrammingTaskUtil.setResultVariableType(task_property_values, task_html_elm);
		
		BrokerOptionsUtilObj.initFields(task_html_elm.find(".broker_method_obj"), CallBusinessLogicTaskPropertyObj.brokers_options, task_property_values["method_obj"]);
		
		var module_id = task_property_values["module_id"] ? "" + task_property_values["module_id"] + "" : "";
		module_id = task_property_values["module_id_type"] == "variable" && module_id.trim().substr(0, 1) == '$' ? module_id.trim().substr(1) : module_id;
		task_html_elm.find(".module_id input").val(module_id);
		
		var service_id = task_property_values["service_id"] ? "" + task_property_values["service_id"] + "" : "";
		service_id = task_property_values["service_id_type"] == "variable" && service_id.trim().substr(0, 1) == '$' ? service_id.trim().substr(1) : service_id;
		task_html_elm.find(".service_id input").val(service_id);
		
		var parameters = task_property_values["parameters"];
		if (task_property_values["parameters_type"] == "array") {
			ArrayTaskUtilObj.onLoadArrayItems( task_html_elm.find(".params .parameters").first(), parameters, "");
			task_html_elm.find(".params .parameters_code").val("");
		}
		else {
			parameters = parameters ? "" + parameters + "" : "";
			parameters = task_property_values["parameters_type"] == "variable" && parameters.trim().substr(0, 1) == '$' ? parameters.trim().substr(1) : parameters;
			task_html_elm.find(".params .parameters_code").val(parameters);
		}
		CallBusinessLogicTaskPropertyObj.onChangeParametersType(task_html_elm.find(".params .parameters_type")[0]);
		
		LayerOptionsUtilObj.onLoadTaskProperties(task_html_elm, task_property_values);
	},
	
	onChangeParametersType : function(elm) {
		var parameters_type = $(elm).val();
		
		var parent = $(elm).parent();
		var parameters_elm = parent.children(".parameters");
		
		if (parameters_type == "array") {
			parent.find(".parameters_code").hide();
			parameters_elm.show();
			
			if (!parameters_elm.find(".items")[0]) {
				var items = {0: {key_type: "null", value_type: "string"}};
				ArrayTaskUtilObj.onLoadArrayItems(parameters_elm, items, "");
			}
		}
		else {
			parent.find(".parameters_code").show();
			parameters_elm.hide();
		}
		
		ProgrammingTaskUtil.onChangeTaskFieldType(elm);
	},
	
	onSubmitTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.saveTaskLabelField(properties_html_elm, task_id);
		
		var task_html_elm = $(properties_html_elm).find(".call_business_logic_task_html");
		ProgrammingTaskUtil.saveNewVariableInWorkflowAccordingWithType(task_html_elm);
		ProgrammingTaskUtil.onSubmitResultVariableType(task_html_elm);
		
		if (task_html_elm.find(".params .parameters_type").val() == "array") {
			task_html_elm.find(".params .parameters_code").remove();
		}
		else {
			task_html_elm.find(".params .parameters").remove();
		}
		
		if (task_html_elm.find(".opts .options_type").val() == "array") {
			task_html_elm.find(".opts .options_code").remove();
		}
		else {
			task_html_elm.find(".opts .options").remove();
		}
		
		return true;
	},
	
	onCompleteTaskProperties : function(properties_html_elm, task_id, task_property_values, status) {
		if (status) {
			var label = CallBusinessLogicTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
			
			var default_method_obj_str = BrokerOptionsUtilObj.getDefaultBroker(CallBusinessLogicTaskPropertyObj.brokers_options);
			if (!task_property_values["method_obj"] && default_method_obj_str) {
				task_property_values["method_obj"] = default_method_obj_str;
			}
		}
	},
	
	onCancelTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		return true;	
	},
	
	onCompleteLabel : function(task_id) {
		onEditLabel(task_id);
		myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.repaintTaskByTaskId(task_id);
		
		return true;
	},
	
	onTaskCreation : function(task_id) {
		setTimeout(function() {
			var task_property_values = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[task_id];
			ProgrammingTaskUtil.saveNewVariableInWorkflowAccordingWithTaskPropertiesValues(task_property_values);
		
			var label = CallBusinessLogicTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
		
			onEditLabel(task_id);
		
			var default_method_obj_str = BrokerOptionsUtilObj.getDefaultBroker(CallBusinessLogicTaskPropertyObj.brokers_options);
			if (!task_property_values["method_obj"] && default_method_obj_str) {
				myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[task_id]["method_obj"] = default_method_obj_str;
			}
		}, 80);
	},
	
	getDefaultExitLabel : function(task_property_values) {
		if (task_property_values["module_id"] && task_property_values["service_id"]) {
			var method_obj = (task_property_values["method_obj"].trim().substr(0, 1) != "$" ? "$" : "") + task_property_values["method_obj"];
			var module = ProgrammingTaskUtil.getValueString(task_property_values["module_id"], task_property_values["module_id_type"]);
			var service = ProgrammingTaskUtil.getValueString(task_property_values["service_id"], task_property_values["service_id_type"]);
			var parameters = task_property_values["parameters_type"] == "array" ? ArrayTaskUtilObj.arrayToString(task_property_values["parameters"]) : ProgrammingTaskUtil.getValueString(task_property_values["parameters"], task_property_values["parameters_type"]);
			parameters = parameters ? parameters : "null";
			
			var options = task_property_values["options_type"] == "array" ? ArrayTaskUtilObj.arrayToString(task_property_values["options"]) : ProgrammingTaskUtil.getValueString(task_property_values["options"], task_property_values["options_type"]);
			options = options ? options : "null";
			
			return ProgrammingTaskUtil.getResultVariableString(task_property_values) + method_obj + '->callBusinessLogic(' + module + ", " + service + ", " + parameters + ", " + options + ")";
		}
		return "";
	},
	
	onChooseBusinessLogic : function(elm) {
		if (typeof this.on_choose_business_logic_callback == "function") {
			this.on_choose_business_logic_callback(elm);
		}
	},
};
