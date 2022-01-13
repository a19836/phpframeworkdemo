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

include $EVC->getUtilPath("WorkFlowUIHandler"); $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowUIHandler->setTasksOrderByTag($tasks_order_by_tag); $head = $WorkFlowUIHandler->getHeader(); $head .= '<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/layer/diagram.js"></script>'; $head .= $WorkFlowUIHandler->getJS($workflow_path_id); $head .= '<link rel="stylesheet" href="' . $project_url_prefix . 'css/layer/diagram.css" type="text/css" charset="utf-8" />'; $menus = array( "Save" => array("class" => "save", "click" => "return saveDBDiagram();"), "Flush Cache" => array("class" => "flush_cache", "click" => "return flushCache();"), "Set Global Vars" => array("class" => "set_global_vars", "click" => "return openIframePopup('" . $project_url_prefix . "phpframework/layer/list_global_vars', {onOpen: onOpenGlobalSettingsAndVars});"), "Set Project Settings" => array("class" => "set_project_settings", "click" => "return openIframePopup('" . $project_url_prefix . "phpframework/layer/list_global_settings', {onOpen: onOpenGlobalSettingsAndVars});"), "Layers Settings" => array( "class" => "layers_settings", "childs" => array( "Expand Presentation Layer" => array("class" => "expand_layer", "click" => "jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize('layer_presentations', 0, $('.tasks_flow #layer_presentations').height() + 150);return false;"), "Shrink Presentation Layer" => array("class" => "shrink_layer", "click" => "jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize('layer_presentations', 0, $('.tasks_flow #layer_presentations').height() - 150);return false;"), "Expand Business-Logic Layer" => array("class" => "expand_layer", "click" => "jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize('layer_bls', 0, $('.tasks_flow #layer_bls').height() + 150);return false;"), "Shrink Business-Logic Layer" => array("class" => "shrink_layer", "click" => "jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize('layer_bls', 0, $('.tasks_flow #layer_bls').height() - 150);return false;"), "Expand Data-Access Layer" => array("class" => "expand_layer", "click" => "jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize('layer_dals', 0, $('.tasks_flow #layer_dals').height() + 150);return false;"), "Shrink Data-Access Layer" => array("class" => "shrink_layer", "click" => "jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize('layer_dals', 0, $('.tasks_flow #layer_dals').height() - 150);return false;"), "Expand DB Layer" => array("class" => "expand_layer", "click" => "jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize('layer_dbs', 0, $('.tasks_flow #layer_dbs').height() + 150);return false;"), "Shrink DB Layer" => array("class" => "shrink_layer", "click" => "jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize('layer_dbs', 0, $('.tasks_flow #layer_dbs').height() - 150);return false;"), "Expand DB-Drivers Layer" => array("class" => "expand_layer", "click" => "jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize('layer_drivers', 0, $('.tasks_flow #layer_drivers').height() + 150);return false;"), "Shrink DB-Drivers Layer" => array("class" => "shrink_layer", "click" => "jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize('layer_drivers', 0, $('.tasks_flow #layer_drivers').height() - 150);return false;"), ) ), ); $WorkFlowUIHandler->setMenus($menus); $main_content = $WorkFlowUIHandler->getContent(); $main_content .= '
<script>
	$(".tasks_flow #layer_presentations").html("<span class=\"layer_title\">PRESENTATION LAYERS</span>");
	$(".tasks_flow #layer_bls").html("<span class=\"layer_title\">BUSINESS LOGIC LAYERS</span>");
	$(".tasks_flow #layer_dals").html("<span class=\"layer_title\">DATA ACCESS LAYERS</span>");
	$(".tasks_flow #layer_dbs").html("<span class=\"layer_title\">DB LAYERS</span>");
	$(".tasks_flow #layer_drivers").html("<span class=\"layer_title\">DB DRIVERS</span>");
	
	//allow_connections_to_multiple_levels = false; //allow connections to only 1 level below.
</script>'; ?>
