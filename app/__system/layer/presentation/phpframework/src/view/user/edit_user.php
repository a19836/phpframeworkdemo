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
'; $form_settings = array( "with_form" => 1, "form_id" => "", "form_method" => "post", "form_class" => "", "form_on_submit" => "", "form_action" => "", "form_containers" => array( 0 => array( "container" => array( "class" => "form_fields", "previous_html" => "", "next_html" => "", "elements" => array( 0 => array( "field" => array( "class" => "form_field" . (!$user_id ? " hidden" : ""), "label" => array( "value" => "User Id: ", ), "input" => array( "type" => "label", "value" => "#user_id#", ) ) ), 1 => array( "field" => array( "class" => "form_field", "label" => array( "value" => "Username: ", ), "input" => array( "type" => $is_own_user || $is_user_editable ? "text" : "label", "name" => "user_data[username]", "value" => "#username#", "extra_attributes" => array( 0 => array( "name" => "allowNull", "value" => "false" ), 1 => array( "name" => "validationMessage", "value" => "Username cannot be undefined!" ) ), ) ) ), 2 => array( "field" => array( "class" => "form_field" . ($is_own_user || $is_user_editable ? "" : " hidden"), "label" => array( "value" => "Password: ", ), "input" => array( "type" => $is_own_user || $is_user_editable ? "password" : "label", "name" => "user_data[password]", "value" => "#password#", "extra_attributes" => array( 0 => array( "name" => "allowNull", "value" => "false" ), 1 => array( "name" => "validationMessage", "value" => "Password cannot be undefined!" ) ), ) ) ), 3 => array( "field" => array( "class" => "form_field", "label" => array( "value" => "Name: ", ), "input" => array( "type" => $is_own_user || $is_user_editable ? "text" : "label", "name" => "user_data[name]", "value" => "#name#", "extra_attributes" => array( 0 => array( "name" => "allowNull", "value" => "false" ), 1 => array( "name" => "validationMessage", "value" => "Name cannot be undefined!" ) ), ) ) ), ) ) ), 1 => array( "container" => array( "class" => "buttons", "elements" => array( 0 => array( "field" => array( "class" => "submit_button" . ($is_own_user || $is_user_editable ? "" : " hidden"), "input" => array( "type" => "submit", "name" => "save", "value" => "Save", ) ) ), 1 => array( "field" => array( "class" => "submit_button" . ($user_id && $is_user_deletable ? "" : " hidden"), "input" => array( "type" => "submit", "name" => "delete", "value" => "Delete", "extra_attributes" => array( 0 => array( "name" => "confirmation", "value" => true ), 1 => array( "name" => "confirmationMessage", "value" => "Do you wish to remove this user?" ) ), ) ) ) ) ) ) ) ); $main_content = '
<div id="menu">' . UserAuthenticationUIHandler::getMenu($UserAuthenticationHandler, $project_url_prefix) . '</div>
<div id="content">
	<div class="edit_user">
		<h1>' . ($user_id ? 'Edit' : 'Add') . ' User</h1>'; if ($is_own_user || $is_user_editable || $is_user_deletable) $main_content .= HtmlFormHandler::createHtmlForm($form_settings, $user_data); else $main_content .= '<div class="error">User undefined!</div>'; $main_content .= '</div>
</div>'; ?>
