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

if (typeof is_global_db_diagram_common_file_already_included == "undefined") {
	var is_global_db_diagram_common_file_already_included = 1;
	
	function onTableConnectionDrop(conn) {
		if (conn.sourceId == conn.targetId) {
			var connector_type = conn.connection.connector.type;
			
			if(connector_type == "Flowchart") {
				var connection = conn.connection;
				connection.setParameter("connection_exit_type", "StateMachine");
				
				myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.setNewConnectionConnector(connection);
			}
		}
		
		return true;
	}
	
	//Checks all existent connections and change them accordingly if exists any inconsistency...
	//To be call after a xml file loads.
	function prepareTasksTableConnections() {
		var connections = myWFObj.getJsPlumbWorkFlow().jsPlumbTaskFlow.getConnections();
		
		for (var i = 0; i < connections.length; i++)
			getConfiguredTaskTableConnection(connections[i]);
		
	}
	
	//checks if this connection is "One To Many" and flip it to "Many To One"
	//To be call on DBTableTaskPropertyObj.onCompleteConnectionProperties.
	function getConfiguredTaskTableConnection(conn) {
		var WF = myWFObj.getJsPlumbWorkFlow();
		var conn_overlay = conn.getParameter("connection_exit_overlay");
		
		if (conn_overlay == "One To Many") {
			var new_conn = WF.jsPlumbTaskFlow.flipConnection(conn.id);
			
			if (new_conn) {
				WF.jsPlumbTaskFlow.changeConnectionOverlayType(new_conn, "Many To One");
				
				var conn_connector = conn.getParameter("connection_exit_type");
				WF.jsPlumbTaskFlow.changeConnectionConnectorType(new_conn, conn_connector);
				
				//flip connection properties
				var aux = WF.jsPlumbTaskFlow.connections_properties[new_conn.id]["source_columns"];
				WF.jsPlumbTaskFlow.connections_properties[new_conn.id]["source_columns"] = WF.jsPlumbTaskFlow.connections_properties[new_conn.id]["target_columns"];
				WF.jsPlumbTaskFlow.connections_properties[new_conn.id]["target_columns"] = aux;
				
				return new_conn;
			}
		}
		
		return conn;
	}
	
	function isTaskTableLabelValid(label_obj, task_id, ignore_msg) {
		var valid = false;
		var is_repeated = false;
		
		if (label_obj.label && label_obj.label.length > 0) {
			var text = label_obj.label;
			text = text.replace(/\n/g, ""); //if text has \n then the regex won't work. So we need to use .replace(/\n/g, "")
			
			//var m = text.match(/^[\p{L}\w\.]+$/giu); //\p{L} and /../u is to get parameters with accents and ç. Already includes the a-z. Cannot use this bc it does not work in IE.
			var m = text.match(/^[\w\.\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u024F\u1EBD\u1EBC]+$/gi); //'\w' means all words with '_' and 'u' means with accents and ç too.
			var valid = m && m[0];
			
			if (valid) {
				m = text.match(/[a-z]+/i); //checks if label has at least one letter
				valid = m && m[0];
			}
			
			if (valid) {
				is_repeated = isTaskLabelRepeated(label_obj, task_id, ignore_msg);
				valid = is_repeated == false;
			}
			
			if (valid)
				isTaskTableNameAdvisable(text);
		}
		
		if (!valid)
			myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError((is_repeated ? "\n" : "") + "Invalid label. Please choose a different label.\nOnly this letters are allowed: a-z, A-Z, 0-9, '_', '.' and you must have at least 1 character.\nNote that by adding the '.' char you are adding a schema to your table.");
		
		return valid;
	}
	
	function isTaskTableNameAdvisable(name) {
		if (name) {
			var normalized = ("" + name);
			
			if (typeof normalized.normalize == "function") //This doesn't work in IE11
				normalized = normalized.normalize("NFD");
				
			normalized = normalized.replace(/[\u0300-\u036f]/g, ""); //replaces all characters with accents with non accented characters including 'ç' to 'c'
			
			if (name != normalized)
				myWFObj.getJsPlumbWorkFlow().jsPlumbStatusMessage.showError("Is NOT advisable to add names with accents and with non-standard characters. Please try to only use A-Z 0-9 and '_'.");
		}
	}
	
	function normalizeTaskTableName(name) {
		//return name ? ("" + name).replace(/\n/g, "").replace(/[ \-]+/g, "_").match(/[\p{L}\w\.]+/giu).join("") : name; //\p{L} and /../u is to get parameters with accents and ç. Already includes the a-z. Cannot use this bc it does not work in IE.
		return name ? ("" + name).replace(/\n/g, "").replace(/[ \-]+/g, "_").match(/[\w\.\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u024F\u1EBD\u1EBC]+/gi).join("") : name; //'\w' means all words with '_' and 'u' means with accents and ç too.
	}
	
	function resizeTableTaskBasedOnAttributes(task_id) {
		var WF = myWFObj.getJsPlumbWorkFlow();
		var task = WF.jsPlumbTaskFlow.getTaskById(task_id);
		var items = task.find(" > ." + WF.jsPlumbTaskFlow.task_eps_class_name + " ." + WF.jsPlumbTaskFlow.task_ep_class_name + " .table_attr .name p");
		
		var name_elm = null;
		var n = "";
		$.each(items, function(idx, item) {
			item = $(item);
			name_elm = item.parent();
			var str = item.text();
			
			n = n.length < ("" + str).length ? str : n;
		});
		
		if (name_elm) {
			var span = document.createElement("SPAN");
			span = $(span);
			span.html(n);
			span.css("font-size", name_elm.css("font-size"));
			$('body').append(span);
			var diff = span.width() - name_elm.width();
			//console.log(task_id+"("+n+"=="+span.text()+"):"+(span.width() - name_elm.width()) +" = "+span.width()+" - "+name_elm.width());
			span.remove();
			
			if (diff > 0) {
				task.css("width", (parseInt(task.css("width")) + diff + 5) + "px");
				
				WF.jsPlumbTaskFlow.repaintTask(task);
			}
		}
	}
}
