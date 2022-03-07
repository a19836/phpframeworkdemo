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

var ForeachTaskPropertyObj = {
	
	onLoadTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.createTaskLabelField(properties_html_elm, task_id);
		
		var task_html_elm = $(properties_html_elm).find(".foreach_task_html");
		
		var obj = task_property_values["obj"] ? "" + task_property_values["obj"] + "" : "";
		var key = task_property_values["key"] ? "" + task_property_values["key"] + "" : "";
		var value = task_property_values["value"] ? "" + task_property_values["value"] + "" : "";
		
		task_html_elm.find(".obj input").val( obj.substr(0, 1) == "$" ? obj.substr(1) : obj );
		task_html_elm.find(".key input").val( key.substr(0, 1) == "$" ? key.substr(1) : key );
		task_html_elm.find(".value input").val( value.substr(0, 1) == "$" ? value.substr(1) : value );
	},
		
	onSubmitTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		ProgrammingTaskUtil.saveTaskLabelField(properties_html_elm, task_id);
		
		return true;
	},
	
	onCompleteTaskProperties : function(properties_html_elm, task_id, task_property_values, status) {
		if (status) {
			var labels = ForeachTaskPropertyObj.getExitLabels(task_property_values);
			ProgrammingTaskUtil.updateTaskExitsLabels(task_id, labels);
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
			var labels = ForeachTaskPropertyObj.getExitLabels(task_property_values);
			ProgrammingTaskUtil.updateTaskExitsLabels(task_id, labels);
		
			onEditLabel(task_id);
		}, 80);
	},
	
	getExitLabels : function(task_property_values) {
		return {"start_exit": "Start Loop", "default_exit": "End Loop"};
	},
};
