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
 
if (typeof is_global_common_file_already_included == "undefined") {
	var is_global_common_file_already_included = 1;
	
	var myWFObj = {
		WF: jsPlumbWorkFlow,
		
		setJsPlumbWorkFlow : function(WF) {
			this.WF = WF;
		},
		
		getJsPlumbWorkFlow : function() {
			return this.WF;
		}
	};
	
	function isInputTextValid(text, invalid_text_regex) {
		if (text && text.length > 0) {
			text = text.replace(/\n/g, ""); //if text has \n then the regex won't work. So we need to use .replace(/\n/g, "")
				
			var invalid = typeof invalid_text_regex == "string" ? (new RegExp(invalid_text_regex)).test(text) : invalid_text_regex.exec(text); 
			var at_least_one_character = /[a-z]+/i.exec(text);
			
			return !invalid && at_least_one_character;
		}
		
		return false;
	}
	
	function isLabelValid(label_obj) {
		//if (!isInputTextValid(label_obj.label, /[^\p{L}\w\-\.\$ ]+/u)) { //'\w' means all words with '_' and '/u' means with accents and ç too. Cannot use this bc it does not work in IE.
		if (!isInputTextValid(label_obj.label, /[^\w\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u024F\u1EBD\u1EBC\-\.\$ ]+/)) { //'\w' means all words with '_' and 'u' means with accents and ç too.
			var msg = "Invalid label. Please choose a different label.\nOnly this letters are allowed: a-z, A-Z, 0-9, '-', '_', '.', '$' and you must have at least 1 character.";
			alert(msg);
			myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError(msg);
			return false;
		}
		return true;
	}
	
	function isTaskLabelValid(label_obj, task_id) {
		if (!isLabelValid(label_obj))
			return false;
		
		return isTaskLabelRepeated(label_obj, task_id) == false;
	}
	
	function isTaskLabelRepeated(label_obj, task_id, ignore_msg) {
		var WF = myWFObj.getJsPlumbWorkFlow();
		var l = label_obj.label.toLowerCase();
		
		var tasks = WF.jsPlumbTaskFlow.getAllTasks();
		var total = tasks.length;
		
		for (var i = 0; i < total; i++) {
			var t = $(tasks[i]);
			var elm_label = WF.jsPlumbTaskFlow.getTaskLabel(t);
			
			if (l == elm_label.toLowerCase() && t.attr("id") != task_id) {
				if (!ignore_msg) {
					var msg = "Error: Repeated label.\nYou cannot have repeated labels!\nPlease try again...";
					alert(msg);
					WF.jsPlumbStatusMessage.showError(msg);
				}
				
				return true;
			}
		}
		return false;
	}
	
	function isConnectionLabelValid(label_obj, task_id) {
		if (!isLabelValid(label_obj)) {
			return false;
		}
		
		return true;
	}
	
	function prepareLabelIfUserLabelIsInvalid(task_id) {
		var WF = myWFObj.getJsPlumbWorkFlow();
		
		//console.debug(task_id);
		var tasks = WF.jsPlumbTaskFlow.getAllTasks();
		var total = tasks.length;
		
		var task_label = WF.jsPlumbTaskFlow.getTaskLabelByTaskId(task_id);
		
		for (var i = 0; i < total; i++) {
			var t = $(tasks[i]);
			var elm_label = WF.jsPlumbTaskFlow.getTaskLabel(t);
			
			if (task_label == elm_label && t.attr("id") != task_id) {
				var r = parseInt(Math.random() * 10000);
				var new_label = task_label + "_" + r;
				
				WF.jsPlumbTaskFlow.getTaskLabelElementByTaskId(task_id).html(new_label);
				WF.jsPlumbTaskFlow.centerTaskInnerElements(task_id);
				
				break;
			}
		}
		
		return true;
	}
	
	function isTaskConnectionToItSelf(conn) {
		return conn.sourceId == conn.targetId;
	}
	
	function invalidateTaskConnectionIfItIsToItSelf(conn) {
		if (isTaskConnectionToItSelf(conn)) {
			myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError("WARNING: Sorry but you cannot create a connection to a task it-self!");
			return false;	
		}
		return true;
	}
	
	function onlyAllowOneConnectionPerExitAndNotToItSelf(conn) {
		if (invalidateTaskConnectionIfItIsToItSelf(conn)) {
			var source_id = conn.sourceId;
			var connection_exit_id = conn.connection.getParameter("connection_exit_id");
			
			if (connection_exit_id) {
				var connections = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getSourceConnections(source_id);
				
				for (var i = 0; i < connections.length; i++) {
					var c = connections[i];
					var ceid = c.getParameter("connection_exit_id");
					
					if (ceid && c.id != conn.connection.id && ceid == connection_exit_id) {
						myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError("You can only have 1 connection from the each exit point.");
						return false
					}
				}
			}
			return true;
		}
		return false;
	}
	
	function onTaskCloning(task_id) {
		var WF = myWFObj.getJsPlumbWorkFlow();
		WF.jsPlumbTaskFlow.setTaskLabelByTaskId(task_id, {label: null}); //set {label: null}, so the jsPlumbTaskFlow.setTaskLabel method ignores the prompt and adds the default label or an auto generated label.
		
		//open properties
		WF.jsPlumbProperty.showTaskProperties(task_id);
	}
	
	function checkIfValueIsTrue(value) {
		var v = typeof value == "string" ? value.toLowerCase() : "";
		
		return (value && value != null && value != 0 && v != "null" && v != "false" && v != "0");
	}
	
	function onEditLabel(task_id) {
		var task = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getTaskById(task_id);
		var info = task.find(".info");
		var span = info.find("span").first();
		
		var width = span.width() + 50;
		task.css("width", width + "px");
		
		var num = 5;
		while (span.height() > info.height()) {
			width = info.width() + 50;
			task.css("width", width + "px");
			--num;
			
			if (num < 0) {
				break;
			}
		}
	}
	
	function stringToUCWords(str) {
		var parts = str.split(" ");
		
		for (var i = 0; i < parts.length; i++) 
			if (parts[i])
				parts[i] = parts[i].substr(0, 1).toUpperCase() + parts[i].substr(1);
		
		return parts.join(" ");
	}
	
	function checkIfValueIsAssociativeArray(value) {
		var is_associative = false;
		
		if ($.isPlainObject(value) && !$.isArray(value)) {
			var idx = 0;
			
			$.each(value, function(i, v) {
				if (idx != i) {
					is_associative = true;
					return false;
				}
				
				idx++;
			});
		}
		
		return is_associative;
	}
	
	function checkIfValueIsAssociativeNumericArray(value) {
		if (checkIfValueIsAssociativeArray(value)) {
			var is_numeric_keys = true;
			
			$.each(value, function(i, v) {
				if (!$.isNumeric(i)) {
					is_numeric_keys = false;
					return false;
				}
			});
			
			return is_numeric_keys;
		}
	}
	
	function checkIfValueIsSurroundedWithQuotesAndIsNotAPHPCode(value) {
	 	if (value) {
			var fc = value.charAt(0);
			var lc = value.charAt(value.length - 1);
			
			//Check if exists quotes in the beginning and end of the value and in the middle (which are encapsulated), that means, there is not a php code in between and is a encapsulated string that should be decapsulated.
			//DO NOT USE /^"(.*)"$/.test(value) because if the value contains an end-line, this regex will never work!
			if (fc == '"' && lc == '"' && !/^"(.*)([^\\])"(.*)"$/.test(value.replace(/\n/g, ""))) 
				return 1;
			else if (fc == "'" && lc == "'" && !/^'(.*)([^\\])'(.*)'$/.test(value.replace(/\n/g, ""))) 
				return 2;
		}
		
		return false;
	}
	
	function convertToNormalTextIfValueIsSurroundedWithQuotesAndIsNotAPHPCode(value) {
	 	if (value) {
	 		var r = checkIfValueIsSurroundedWithQuotesAndIsNotAPHPCode(value);
	 		
	 		if (r == 1)
	 			value = value.substr(1, value.length - 2).replace(/\\"/g, '"').replace(/\\\\/g, "\\");
	 		else if (r == 2)
	 			value = value.substr(1, value.length - 2).replace(/\\'/g, "'").replace(/\\\\/g, "\\");
		}
		
		return value;
	}
}
