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

var ViewTaskPropertyObj = {
	
	onLoadTaskProperties : function(properties_html_elm, task_id, task_property_values) {
		var task_html_element = properties_html_elm.find(".view_task_html"); //do not use .page_content_task_html bc if there is listing or form task in the same parent, it will get the wrong element.
		task_html_element.attr("updateTableUIHandler", "ViewTaskPropertyObj.onChangeDBTable");
		task_html_element.find(" > .ui > .add_ui_table_attribute > .icon").attr("onClick", "ViewTaskPropertyObj.addUITableAttribute(this)");
		
		PresentationTaskUtil.onLoadTaskProperties(task_html_element, task_id, task_property_values);
		PresentationTaskUtil.setSortableUITableRows(task_html_element);
	},
	
	onChangeDBTable : function(task_html_element, table_attributes) {
		var ui_elm = task_html_element.children(".ui");
		var add_ui_table_attribute = ui_elm.children(".add_ui_table_attribute");
		var ui_html_elm = ui_elm.children(".ui_html");
		
		ui_html_elm.html("");
		add_ui_table_attribute.hide();
		
		if (!$.isEmptyObject(table_attributes)) {
			var html = '<table><tbody index_prefix="attributes">';
			var attribute_idx = 0;
			
			for (var attribute_name in table_attributes) {
				html += ViewTaskPropertyObj.getDBTableAttributeHtml(task_html_element, attribute_idx, attribute_name, table_attributes[attribute_name]);
				attribute_idx++;
			}
			
			html += '</tbody></table>';
			
			ui_html_elm.html(html);
			add_ui_table_attribute.show();
			
			PresentationTaskUtil.setSortableUITableRows(task_html_element);
		}
		else 
			ui_html_elm.html('No available attributes...');
	},
	
	getDBTableAttributeHtml : function(task_html_element, attribute_idx, attribute_name, attribute_details) {
		var input_type = PresentationTaskUtil.getTableAttributeInputType(attribute_details);
		
		var popup_props_html = PresentationTaskUtil.getUITableAttributePropertiesHtml(task_html_element, attribute_idx, attribute_name);
		
		return '<tr ui_table_attribute_name="' + attribute_name + '">'
			+ '	<td class="ui_table_attribute_label"><input class="task_property_field" type="hidden" name="attributes[' + attribute_idx + '][name]" value="' + attribute_name + '"/><input class="task_property_field" type="hidden" name="attributes[' + attribute_idx + '][active]" value="1"/>' + PresentationTaskUtil.attributeNameToLabel(attribute_name) + ': </td>'
			+ '	<td class="ui_table_attribute_value ' + input_type + '"></td>'
			+ '	<td class="ui_table_attribute_action"><i class="icon update" onClick="PresentationTaskUtil.editUITableAttributeRowProperties(this)"></i><i class="icon remove" onClick="PresentationTaskUtil.removeUITableAttributeRow(this)"></i>' + popup_props_html + '</td>'
			+ '</tr>';
	},
	
	addUITableAttribute : function(elm) {
		elm = $(elm);
		var attribute_name = elm.parent().children("select").val();
		var task_html_element = elm.parent().closest(".page_content_task_html"); //in here I can use .page_content_task_html
		var table_attributes = PresentationTaskUtil.getSelectedDBTableAttributes( task_html_element.find(".choose_db_table") );
		var attribute_details = table_attributes ? table_attributes[attribute_name] : null;
		
		if (attribute_details) {
			var tbody = task_html_element.find(" > .ui > .ui_html > table > tbody");
			var attribute_idx = getListNewIndex(tbody);
			var html = ViewTaskPropertyObj.getDBTableAttributeHtml(task_html_element, attribute_idx, attribute_name, attribute_details);
			tbody.append(html);
		}
		else
			alert("Error: There is no table attribute detals for the attribute: '" + attribute_name + "'!\nError in ViewTaskPropertyObj.addUITableAttribute function!");
	},
};
