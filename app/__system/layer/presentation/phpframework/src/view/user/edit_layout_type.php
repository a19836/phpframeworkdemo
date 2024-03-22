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
include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler"); include $EVC->getUtilPath("UserAuthenticationUIHandler"); $extra_html = '<select name="choose_project" onChange="onChooseProject(this)">
				<option value="">- Choose a project -</option>'; foreach ($presentation_projects_by_folders as $layer_label => $projs) { $extra_html .= '<optgroup label="' . $layer_label . '">'; $extra_html .= getProjectsHtml($projs, $layout_type_data ? $layout_type_data["name"] : null); $extra_html .= '</optgroup>'; } function getProjectsHtml($v12ed481092, $v1a222c94d4, $pdcf670f6 = "") { $pf8ed4912 = ""; if (is_array($v12ed481092)) foreach ($v12ed481092 as $pcfd27d54 => $v5c37a7b23d) { if (is_array($v5c37a7b23d)) $pf8ed4912 .= '<option disabled>' . $pdcf670f6 . $pcfd27d54 . '</option>' . getProjectsHtml($v5c37a7b23d, $v1a222c94d4, $pdcf670f6 . "&nbsp;&nbsp;&nbsp;"); else $pf8ed4912 .= '<option value="' . $pcfd27d54 . '" ' . ($v1a222c94d4 == $pcfd27d54 ? ' selected' : '') . '>' . $pdcf670f6 . $v5c37a7b23d . '</option>'; } return $pf8ed4912; } $extra_html .= '</select>'; $head = '
<!-- Add MD5 JS File -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquery/js/jquery.md5.js"></script>

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/user/user.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/user/user.js"></script>

<script>
function onChangeLayoutType(elm) {
	elm = $(elm);
	var p = elm.parent();
	var select = p.children("select[name=choose_project]");
	var type_id = elm.val();
	
	if (type_id == 0) 
		select.show();
	else
		select.hide();
}

function onChooseProject(elm) {
	elm = $(elm);
	var proj = elm.val();
	
	if (proj)
		elm.parent().parent().find("> .name input").val(proj);
}

$(function() {
	onChangeLayoutType( $(".type select[name=\'layout_type_data[type_id]\']") );
});
</script>'; $form_settings = array( "with_form" => 1, "form_id" => "", "form_method" => "post", "form_class" => "", "form_on_submit" => "", "form_action" => "", "form_containers" => array( 0 => array( "container" => array( "class" => "form_fields", "previous_html" => "", "next_html" => "", "elements" => array( 0 => array( "field" => array( "class" => "form_field hidden", "input" => array( "type" => "hidden", "name" => "layout_type_data[layout_type_id]", "value" => "#layout_type_id#", ) ) ), 1 => array( "field" => array( "class" => "form_field type", "label" => array( "value" => "Type: ", ), "input" => array( "type" => "select", "name" => "layout_type_data[type_id]", "value" => "#type_id#", "extra_attributes" => array( 0 => array( "name" => "allowNull", "value" => "false" ), 1 => array( "name" => "validationMessage", "value" => "Type cannot be undefined!" ), 2 => array( "name" => "onChange", "value" => "onChangeLayoutType(this)" ) ), "options" => $available_types, "next_html" => $extra_html ) ) ), 2 => array( "field" => array( "class" => "form_field name", "label" => array( "value" => "Name: ", ), "input" => array( "type" => "text", "name" => "layout_type_data[name]", "value" => "#name#", "extra_attributes" => array( 0 => array( "name" => "allowNull", "value" => "false" ), 1 => array( "name" => "validationMessage", "value" => "Name cannot be undefined!" ) ), ) ) ), ) ) ), 1 => array( "container" => array( "class" => "buttons", "elements" => array( 0 => array( "field" => array( "class" => "submit_button", "input" => array( "type" => "submit", "name" => "save", "value" => "Save", "class" => "save", ) ) ), 1 => array( "field" => array( "class" => "submit_button" . ($layout_type_id ? "" : " hidden"), "input" => array( "type" => "submit", "name" => "delete", "value" => "Delete", "class" => "delete", "extra_attributes" => array( 0 => array( "name" => "confirmation", "value" => true ), 1 => array( "name" => "confirmationMessage", "value" => "Do you wish to remove this layout type?" ) ), ) ) ) ) ) ) ) ); $main_content = '
<div id="menu">' . UserAuthenticationUIHandler::getMenu($UserAuthenticationHandler, $project_url_prefix, $entity) . '</div>
<div id="content">
	<div class="edit_layout_type">
		<div class="top_bar">
			<header>
				<div class="title">' . ($layout_type_id ? 'Edit' : 'Add') . ' Layout Type</div>
				<ul>
					<li class="delete" data-title="Delete"><a onClick="submitForm(this, \'delete\')"><i class="icon delete"></i> Delete</a></li>
					<li class="save" data-title="Save"><a onClick="submitForm(this, \'save\')"><i class="icon save"></i> Save</a></li>
				</ul>
			</header>
		</div>'; $main_content .= HtmlFormHandler::createHtmlForm($form_settings, $layout_type_data); $main_content .= '</div>
</div>'; ?>
