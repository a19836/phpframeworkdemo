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

var RestConnectorTaskPropertyObj = null;

if (!GetUrlContentsTaskPropertyObj)
	alert("GetUrlContentsTaskPropertyObj must be defined before the RestConnectorTaskPropertyObj gets defined!");
else
	RestConnectorTaskPropertyObj = {
		
		dependent_file_path_to_include : "LIB_PATH . 'org/phpframework/connector/RestConnector.php'",
	
		onLoadTaskProperties : function(properties_html_elm, task_id, task_property_values) {
			GetUrlContentsTaskPropertyObj.onLoadTaskProperties(properties_html_elm, task_id, task_property_values);
			
			var task_html_elm = $(properties_html_elm).find(".get_url_contents_task_html");
			
			if (!task_property_values["result_type"]) {
				task_html_elm.find(".result_type select[name=result_type]").val("content");
				
				var select = task_html_elm.find(".result_type select[name=result_type_type]");
				select.val("options");
				GetUrlContentsTaskPropertyObj.onChangeResultType(select[0]);
			}
		},
		
		onSubmitTaskProperties : function(properties_html_elm, task_id, task_property_values) {
			return GetUrlContentsTaskPropertyObj.onSubmitTaskProperties(properties_html_elm, task_id, task_property_values);
		},
		
		onCompleteTaskProperties : function(properties_html_elm, task_id, task_property_values, status) {
			GetUrlContentsTaskPropertyObj.onCompleteTaskProperties(properties_html_elm, task_id, task_property_values, status);
			
			if (status) {
				var label = RestConnectorTaskPropertyObj.getDefaultExitLabel(task_property_values);
				ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
			}
		},
		
		onCancelTaskProperties : function(properties_html_elm, task_id, task_property_values) {
			return GetUrlContentsTaskPropertyObj.onCancelTaskProperties(properties_html_elm, task_id, task_property_values);	
		},
		
		onCompleteLabel : function(task_id) {
			return GetUrlContentsTaskPropertyObj.onCompleteLabel(task_id);
		},
		
		onTaskCloning : function(task_id) {
			ProgrammingTaskUtil.onTaskCloning(task_id);
		
		ProgrammingTaskUtil.addIncludeFileTaskBeforeTaskIfNotExistsYet(task_id, RestConnectorTaskPropertyObj.dependent_file_path_to_include, '', 1);
		},
		
		onTaskCreation : function(task_id) {
			GetUrlContentsTaskPropertyObj.onTaskCreation(task_id);
			
			setTimeout(function() {
				var task_property_values = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.tasks_properties[task_id];
				
				if (task_property_values && !task_property_values["result_type"]) {
					task_property_values["result_type"] = "content";
					task_property_values["result_type_type"] = "string";
				}
				
				var label = RestConnectorTaskPropertyObj.getDefaultExitLabel(task_property_values);
				ProgrammingTaskUtil.updateTaskDefaultExitLabel(task_id, label);
			
				onEditLabel(task_id);
				
				ProgrammingTaskUtil.onTaskCreation(task_id);
			}, 200);
		},
		
		getDefaultExitLabel : function(task_property_values) {
			var label = GetUrlContentsTaskPropertyObj.getDefaultExitLabel(task_property_values);
			
			if (label)
				label = label.replace("MyCurl::getUrlContents(", "RestConnector::connect(");
				
			return label;
		},
	};
