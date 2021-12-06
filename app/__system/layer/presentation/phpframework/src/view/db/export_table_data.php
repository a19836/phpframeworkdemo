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
 include $EVC->getViewPath("dataaccess/edit_query"); $head .= '
<link rel="stylesheet" href="' . $project_url_prefix . 'css/db/export_table_data.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/db/export_table_data.js"></script>
'; $form_html = '
<form class="export_form" method="post">
	<input type="hidden" name="sql"/>
	
	<select name="export_type">
		<option value="txt">Export in Text Format - Tab delimiter</option>
		<option value="csv"' . ($export_type == "csv" ? " selected" : "") . '>Export in CSV Format - Comma Separated Values</option>
		<option value="xls"' . ($export_type == "xls" ? " selected" : "") . '>Export To Excel</option>
	</select>
	
	<input type="text" name="doc_name" value="' . $doc_name . '" placeHolder="Document name"/>
</form>'; $main_content .= '
<script>
//change title
$(".title").html("Export Table \'' . $table . '\'");

//remove edit_query fields
var data_access_obj = $(".data_access_obj");
var relationship = data_access_obj.find(" > .relationships > .rels > .relationship");
relationship.find(".rel_type").hide();
relationship.find(".rel_name, .parameter_class_id, .parameter_map_id, .result_class_id, .result_map_id").remove();

//add new field:
var html = \'' . str_replace("\n", "", addcslashes($form_html, "'")) . '\';
relationship.prepend(html);

//remove handlers from edit_query just in case
save_data_access_object_url = remove_data_access_object_url = null;

//change save function
data_access_obj.find(".save_button input").attr("value", "EXPORT").attr("onClick", "exportTable(this)");

saveQueryObject = function(btn) {
	exportTable(btn);
};
</script>'; ?>
