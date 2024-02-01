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
include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler"); $head = '
<!-- Bootstrap core CSS -->
<link href="' . $project_url_prefix . 'vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/auth/login.css" type="text/css" charset="utf-8" />
'; $form_settings = array( "with_form" => 1, "form_id" => "", "form_method" => "post", "form_class" => "", "form_on_submit" => "", "form_action" => "", "form_containers" => array( 0 => array( "container" => array( "class" => "form_fields", "previous_html" => "", "next_html" => "", "elements" => array( 0 => array( "field" => array( "class" => "form_field form-group", "input" => array( "class" => "form-control", "type" => "text", "name" => "username", "value" => "#username#", "extra_attributes" => array( 0 => array( "name" => "allowNull", "value" => "false" ), 1 => array( "name" => "validationMessage", "value" => "Username cannot be undefined!" ), 2 => array( "name" => "placeHolder", "value" => "&nbsp;" ), 3 => array( "name" => "required", "value" => "" ) ), ), "label" => array( "value" => 'Username <i class="asterisk">*</i>', ), ) ), 1 => array( "field" => array( "class" => "form_field form-group", "input" => array( "class" => "form-control", "type" => "password", "name" => "password", "value" => "#password#", "extra_attributes" => array( 0 => array( "name" => "allowNull", "value" => "false" ), 1 => array( "name" => "validationMessage", "value" => "Password cannot be undefined!" ), 2 => array( "name" => "placeHolder", "value" => " " ), 3 => array( "name" => "required", "value" => "" ) ), ), "label" => array( "value" => 'Password <i class="asterisk">*</i>', ), ) ), ) ) ), 1 => array( "container" => array( "class" => "buttons", "elements" => array( 0 => array( "field" => array( "class" => "submit_button", "input" => array( "class" => "btn btn-default", "type" => "submit", "name" => "login", "value" => "Log In", ) ) ), ) ) ) ) ); $main_content = '<div class="login' . ($popup ? ' with_popup' : '') . '">
    <div class="row justify-content-center">
        <div class="col-11 col-sm-10 col-md-9 col-lg-8 admin-page-content">
            <div class="card shadow-lg border-0 rounded-lg">
                ' . ($popup ? '<div class="card-header"><h5>Log in to your account</h5></div>' : '<div class="side_image row">
            		<img class="col-12 col-sm-12 col-md-11 col-lg-10" src="' . $project_url_prefix . 'img/login_bg_' . rand(1, 3) . '.svg" />
            		<div class="slogan d-none d-sm-block">
		       		<div class="sentence_1">Creating apps has never been</div>
		       		<div class="sentence_2">cheaper and easier</div>
		       	</div>
            	</div>') . '
               <div class="card-body">
                	<img src="' . $project_url_prefix . 'img/logo_full.svg" />
                	
		  		' . HtmlFormHandler::createHtmlForm($form_settings, $login_data) . '
               </div>
            </div>
        </div>
    </div>
</div>

<script>$(".login .form_fields input[name=username]").focus()</script>'; ?>
