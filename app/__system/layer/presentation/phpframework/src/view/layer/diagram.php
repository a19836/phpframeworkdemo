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

include $EVC->getUtilPath("WorkFlowUIHandler"); $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowUIHandler->setTasksOrderByTag($tasks_order_by_tag); $head = $WorkFlowUIHandler->getHeader(); $head .= '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local JS and CSS files -->
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/layer/diagram.js"></script>
'; $head .= $WorkFlowUIHandler->getJS($workflow_path_id); $head .= '<link rel="stylesheet" href="' . $project_url_prefix . 'css/layer/diagram.css" type="text/css" charset="utf-8" />'; $menus = array( "Flush Cache" => array( "class" => "flush_cache", "html" => '<a onClick="return flushCache();"><i class="icon flush_cache"></i> Flush Cache</a>', ), 0 => array( "class" => "separator", "title" => " ", "html" => " ", ), "Set Global Vars" => array( "class" => "set_global_vars", "html" => '<a onClick="return openGlobalSettingsAndVarsPopup(\'' . $project_url_prefix . 'phpframework/layer/list_global_vars?popup=1\', {onOpen: onOpenGlobalSettingsAndVars});"><i class="icon global_vars"></i> Globar Vars</a>', ), "Set Global Settings" => array( "class" => "set_global_settings", "html" => '<a onClick="return openGlobalSettingsAndVarsPopup(\'' . $project_url_prefix . 'phpframework/layer/list_global_settings?popup=1\', {onOpen: onOpenGlobalSettingsAndVars});"><i class="icon global_settings"></i> Global Settings</a>', ), 1 => array( "class" => "separator", "title" => " ", "html" => " ", ), "Layers Settings" => array( "class" => "layers_settings", "html" => '<a onClick="javascript:void(0)"><i class="icon layers_settings"></i> Layers Settings</a>', "childs" => array( "Expand Presentation Layer" => array( "class" => "expand_layer", "html" => '<a onClick="jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize(\'layer_presentations\', 0, $(\'.tasks_flow #layer_presentations\').height() + 150);return false;"><i class="icon maximize"></i> Expand Presentation Layer</a>', ), "Shrink Presentation Layer" => array( "class" => "shrink_layer", "html" => '<a onClick="jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize(\'layer_presentations\', 0, $(\'.tasks_flow #layer_presentations\').height() - 150);return false;"><i class="icon minimize"></i> Shrink Presentation Layer</a>', ), "Expand Business-Logic Layer" => array( "class" => "expand_layer", "html" => '<a onClick="jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize(\'layer_bls\', 0, $(\'.tasks_flow #layer_bls\').height() + 150);return false;"><i class="icon maximize"></i> Expand Business-Logic Layer</a>', ), "Shrink Business-Logic Layer" => array( "class" => "shrink_layer", "html" => '<a onClick="jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize(\'layer_bls\', 0, $(\'.tasks_flow #layer_bls\').height() - 150);return false;"><i class="icon minimize"></i> Shrink Business-Logic Layer</a>', ), "Expand Data-Access Layer" => array( "class" => "expand_layer", "html" => '<a onClick="jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize(\'layer_dals\', 0, $(\'.tasks_flow #layer_dals\').height() + 150);return false;"><i class="icon maximize"></i> Expand Data-Access Layer</a>', ), "Shrink Data-Access Layer" => array( "class" => "shrink_layer", "html" => '<a onClick="jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize(\'layer_dals\', 0, $(\'.tasks_flow #layer_dals\').height() - 150);return false;"><i class="icon minimize"></i> Shrink Data-Access Layer</a>', ), "Expand DB Layer" => array( "class" => "expand_layer", "html" => '<a onClick="jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize(\'layer_dbs\', 0, $(\'.tasks_flow #layer_dbs\').height() + 150);return false;"><i class="icon maximize"></i> Expand DB Layer</a>', ), "Shrink DB Layer" => array( "class" => "shrink_layer", "html" => '<a onClick="jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize(\'layer_dbs\', 0, $(\'.tasks_flow #layer_dbs\').height() - 150);return false;"><i class="icon minimize"></i> Shrink DB Layer</a>', ), "Expand DB-Drivers Layer" => array( "class" => "expand_layer", "html" => '<a onClick="jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize(\'layer_drivers\', 0, $(\'.tasks_flow #layer_drivers\').height() + 150);return false;"><i class="icon maximize"></i> Expand DB-Drivers Layer</a>', ), "Shrink DB-Drivers Layer" => array( "class" => "shrink_layer", "html" => '<a onClick="jsPlumbWorkFlow.jsPlumbContainer.changeContainerSize(\'layer_drivers\', 0, $(\'.tasks_flow #layer_drivers\').height() - 150);return false;"><i class="icon minimize"></i> Shrink DB-Drivers Layer</a>', ), ) ), 2 => array( "class" => "separator", "title" => " ", "html" => " ", ), "Maximize/Minimize Editor Screen" => array( "class" => "tasks_flow_full_screen", "html" => '<a onClick="toggleFullScreen(this);return false;"><i class="icon full_screen"></i> Maximize Editor Screen</a>', ), 3 => array( "class" => "separator", "title" => " ", "html" => " ", ), "Save" => array( "class" => "save", "html" => '<a onClick="return saveLayersDiagram();"><i class="icon save"></i> Save</a>', ), ); $WorkFlowUIHandler->setMenus($menus); $main_content = '
	<div class="top_bar">
		<header>
			<div class="title">Layers Diagram</div>
			<ul>
				<li class="save" data-title="Save"><a onClick="saveLayersDiagram()"><i class="icon save"></i> Save</a></li>
			</ul>
		</header>
	</div>'; $main_content .= $WorkFlowUIHandler->getContent(); $main_content .= '
<script>
	$(".tasks_flow #layer_presentations").html("<span class=\"layer_title\">Presentation Layers</span>");
	$(".tasks_flow #layer_bls").html("<span class=\"layer_title\">Business Logic Layers</span>");
	$(".tasks_flow #layer_dals").html("<span class=\"layer_title\">Data-Access Layers (SQL)</span>");
	$(".tasks_flow #layer_dbs").html("<span class=\"layer_title\">DB Layers</span>");
	$(".tasks_flow #layer_drivers").html("<span class=\"layer_title\">DB Drivers</span>");
	
	//allow_connections_to_multiple_levels = false; //allow connections to only 1 level below.
	
	//add default function to reset the top positon of the tasksflow panels, if with_top_bar class exists
	jsPlumbWorkFlow.setjsPlumbWorkFlowObjOption("on_resize_panels_function", onResizeTaskFlowChartPanels);
</script>'; ?>
