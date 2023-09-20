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

var ResetRegionBlockJoinPointsTaskPropertyObj = {
	
	brokers_options : null,
	
	onLoadTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.createTaskLabelField(properties_html_elm, task_id);
		
		var task_html_elm = $(properties_html_elm).find(".reset_region_block_join_points_task_html");
		
		BrokerOptionsUtilObj.initFields(task_html_elm.find(".broker_method_obj"), ResetRegionBlockJoinPointsTaskPropertyObj.brokers_options, task_property_values["method_obj"]);
		
		var region_id = task_property_values["region_id"] ? "" + task_property_values["region_id"] : "";
		region_id = task_property_values["region_id_type"] == "variable" && region_id.trim().substr(0, 1) == '$' ? region_id.trim().substr(1) : region_id;
		task_html_elm.find(".region_id input").val(region_id);
		
		var block_id = task_property_values["block_id"] ? "" + task_property_values["block_id"] : "";
		block_id = task_property_values["block_id_type"] == "variable" && block_id.trim().substr(0, 1) == '$' ? block_id.trim().substr(1) : block_id;
		task_html_elm.find(".block_id input").val(block_id);
	},
	
	onSubmitTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.saveTaskLabelField(properties_html_elm, task_id);
		
		return true;
	},
	
	onCompleteTaskProperties : function(properties_html_elm, task_id, task_property_values, status) {
		if (status) {
			var label = ResetRegionBlockJoinPointsTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
			
			var default_method_obj_str = BrokerOptionsUtilObj.getDefaultBroker(ResetRegionBlockJoinPointsTaskPropertyObj.brokers_options);
			if (!task_property_values["method_obj"] && default_method_obj_str) {
				task_property_values["method_obj"] = default_method_obj_str;
			}
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
			var task_property_values = myWFObj.getTaskFlowChart().TaskFlow.tasks_properties[task_id];
			var label = ResetRegionBlockJoinPointsTaskPropertyObj.getDefaultExitLabel(task_property_values);
			ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
		
			onEditLabel(task_id);
			
			var default_method_obj_str = BrokerOptionsUtilObj.getDefaultBroker(ResetRegionBlockJoinPointsTaskPropertyObj.brokers_options);
			if (!task_property_values["method_obj"] && default_method_obj_str) {
				myWFObj.getTaskFlowChart().TaskFlow.tasks_properties[task_id]["method_obj"] = default_method_obj_str;
			}
			
			ProgrammingTaskUtil.onTaskCreation(task_id);
		}, 100);
	},
	
	getDefaultExitLabel : function(task_property_values) {
		var method_obj = (task_property_values["method_obj"] && task_property_values["method_obj"].trim().substr(0, 1) != "$" ? "$" : "") + task_property_values["method_obj"];
		
		return method_obj + "->resetRegionBlockJoinPoints(" + ProgrammingTaskUtil.getValueString(task_property_values["region_id"], task_property_values["region_id_type"]) + ", " + ProgrammingTaskUtil.getValueString(task_property_values["block_id"], task_property_values["block_id_type"]) + ")";
	},
};
