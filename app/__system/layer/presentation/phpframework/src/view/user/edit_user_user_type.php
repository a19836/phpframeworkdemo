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

include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler"); include $EVC->getUtilPath("UserAuthenticationUIHandler"); $head = '
<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/user/user.css" type="text/css" charset="utf-8" />
'; $form_settings = array( "with_form" => 1, "form_id" => "", "form_method" => "post", "form_class" => "", "form_on_submit" => "", "form_action" => "", "form_containers" => array( 0 => array( "container" => array( "class" => "form_fields", "previous_html" => "", "next_html" => "", "elements" => array( 0 => array( "field" => array( "class" => "form_field", "label" => array( "value" => "User: ", ), "input" => array( "type" => ($user_id && $user_type_id ? "label" : "select"), "name" => "user_user_type_data[user_id]", "value" => "#user_id#", "options" => $users_options, "available_values" => $available_users, ) ) ), 1 => array( "field" => array( "class" => "form_field", "label" => array( "value" => "User Type: ", ), "input" => array( "type" => ($user_id && $user_type_id ? "label" : "select"), "name" => "user_user_type_data[user_type_id]", "value" => "#user_type_id#", "options" => $user_types_options, "available_values" => $available_user_types, ) ) ), ) ) ), 1 => array( "container" => array( "class" => "buttons", "elements" => array( 0 => array( "field" => array( "class" => "submit_button" . ($user_id && $user_type_id ? " hidden" : ""), "input" => array( "type" => "submit", "name" => "save", "value" => "Add", ) ) ), 1 => array( "field" => array( "class" => "submit_button" . ($user_id && $user_type_id ? "" : " hidden"), "input" => array( "type" => "submit", "name" => "delete", "value" => "Delete", "extra_attributes" => array( 0 => array( "name" => "confirmation", "value" => true ), 1 => array( "name" => "confirmation_message", "value" => "Do you wish to remove this user user type?" ) ), ) ) ) ) ) ) ) ); $main_content = '
<div id="menu">' . UserAuthenticationUIHandler::getMenu($UserAuthenticationHandler, $project_url_prefix) . '</div>
<div id="content">
	<div class="edit_user_user_type">
		<h1>' . ($user_id && $user_type_id ? 'Edit' : 'Add') . ' User USer Type</h1>'; $main_content .= HtmlFormHandler::createHtmlForm($form_settings, $user_user_type_data); $main_content .= '</div>
</div>'; ?>
