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

include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler"); $form_settings = array( "with_form" => 1, "form_id" => "", "form_method" => "post", "form_class" => "", "form_on_submit" => "", "form_action" => "", "form_containers" => array( 0 => array( "container" => array( "class" => "form_fields", "previous_html" => "", "next_html" => "", "elements" => array( 0 => array( "field" => array( "class" => "form_field var_name", "label" => array( "value" => "Default timezone: ", ), "input" => array( "type" => "text", "name" => "data[default_timezone]", "value" => "#default_timezone#", ) ) ), 1 => array( "field" => array( "class" => "form_field var_name", "label" => array( "value" => "Die when throw exception: ", ), "input" => array( "type" => "select", "name" => "data[die_when_throw_exception]", "value" => "#die_when_throw_exception#", "options" => array( array("value" => "true", "label" => "YES"), array("value" => "false", "label" => "NO") ), ) ) ), 2 => array( "field" => array( "class" => "form_field var_name", "label" => array( "value" => "Log level: ", ), "input" => array( "type" => "select", "name" => "data[log_level]", "value" => "#log_level#", "options" => array( array("value" => 0, "label" => "NONE"), array("value" => 1, "label" => "EXCEPTION"), array("value" => 2, "label" => "EXCEPTION+ERROR"), array("value" => 3, "label" => "EXCEPTION+ERROR+INFO"), array("value" => 4, "label" => "EXCEPTION+ERROR+INFO+DEBUG") ), ) ) ), 3 => array( "field" => array( "class" => "form_field var_name", "label" => array( "value" => "Log echo active: ", ), "input" => array( "type" => "select", "name" => "data[log_echo_active]", "value" => "#log_echo_active#", "options" => array( array("value" => "true", "label" => "YES"), array("value" => "false", "label" => "NO") ), ) ) ), 4 => array( "field" => array( "class" => "form_field var_name", "label" => array( "value" => "Log file path: ", ), "input" => array( "type" => "text", "name" => "data[log_file_path]", "value" => "#log_file_path#", ) ) ), 5 => array( "field" => array( "class" => "form_field var_name", "label" => array( "value" => "Temporary folder path: ", ), "input" => array( "type" => "text", "name" => "data[tmp_path]", "value" => "#tmp_path#", ) ) ) ) ) ), 1 => array( "container" => array( "class" => "buttons", "elements" => array( 0 => array( "field" => array( "class" => "save", "input" => array( "type" => "submit", "name" => "", "value" => "save", ) ) ) ) ) ) ) ); $head = '<link rel="stylesheet" href="' . $project_url_prefix . 'css/layer/list_global_settings.css" type="text/css" charset="utf-8" />'; $main_content = '<div class="global_settings">
		<h1>Global Settings</h1>'; $main_content .= HtmlFormHandler::createHtmlForm($form_settings, $vars); $main_content .= '</div>'; ?>
