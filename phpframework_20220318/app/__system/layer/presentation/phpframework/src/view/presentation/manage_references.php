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

if ($layout_type_id) { include $EVC->getUtilPath("WorkFlowPresentationHandler"); $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $head = '
	<!-- Add MD5 JS File -->
	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquery/js/jquery.md5.js"></script>

	<!-- Add Fontawsome Icons CSS -->
	<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

	<!-- Add Icons CSS -->
	<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

	<!-- Add MyTree main JS and CSS files -->
	<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
	<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>

	<!-- Add FileManager JS file -->
	<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />
	<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/file_manager.js"></script>

	<!-- Edit code JS -->
	<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/edit_code.js"></script>

	<!-- Add User CSS and JS -->
	<link rel="stylesheet" href="' . $project_url_prefix . 'css/user/user.css" type="text/css" charset="utf-8" />
	<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/user/user.js"></script>
	
	<!-- Top-Bar CSS file -->
	<link rel="stylesheet" href="' . $project_url_prefix . 'css/top_bar.css" type="text/css" charset="utf-8" />
	
	<!-- Add Local CSS and JS -->
	<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/manage_references.css" type="text/css" charset="utf-8" />
	<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/manage_references.js"></script>

	<script>
	var get_layout_type_permissions_url = \'' . $project_url_prefix . 'user/get_layout_type_permissions?layout_type_id=#layout_type_id#\';
	'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getBusinessLogicBrokersHtml($business_logic_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDataAccessBrokersHtml($data_access_brokers, $choose_bean_layer_files_from_file_manager_url); $head .= '
		var permissions = ' . json_encode($permissions) . ';
		var permission_belong_name = "' . UserAuthenticationHandler::$PERMISSION_BELONG_NAME . '";
		var permission_referenced_name = "' . UserAuthenticationHandler::$PERMISSION_REFERENCED_NAME . '";
		var layer_object_type_id = ' . $layer_object_type_id . ';
		var loaded_layout_type_permissions = {};
		var layout_type_id = ' . $layout_type_id . ';
	</script>'; $main_content = '
	<div id="content">
		<div class="top_bar">
			<header>
				<div class="title">Manage References for project "' . $path . '"</div>
				<ul>
					<li class="save" title="Save"><a onclick="submitForm(this)"><i class="icon save"></i> Save</a>
				</ul>
			</header>
		</div>
		<div class="layout_type_permissions_list">
			<form method="post" onSubmit="return saveProjectLayoutTypePermissions();">
				<div class="layout_type_permissions_content">
					<div id="referenced_in_layout">
						<ul>
					' . getLayersHtml($layers_to_be_referenced, $layers_props, $layers_object_id, $layers_label, $layer_object_id_prefix, $choose_bean_layer_files_from_file_manager_url, $layer_object_type_id, $permissions[UserAuthenticationHandler::$PERMISSION_REFERENCED_NAME], "removeAllThatCannotBeReferencedFromTree") . '
						</ul>
					</div>
					
					<div class="loaded_permissions_by_objects hidden"></div>
				</div>
			</form>
		</div>
	</div>'; } function getLayersHtml($v2635bad135, $v830cc461b7, $pbffdab91, $paeab4070, $v9bfd456213, $pf7b73b3a, $v0a035c60aa, $pb76ee81a, $pf3f2367a) { $pf8ed4912 = ''; foreach ($v2635bad135 as $v43974ff697 => $pfd248cca) { $pf8ed4912 .= '<li id="file_tree_' . $pb76ee81a . '_' . $v43974ff697 . '" class="mytree">
					<label><i class="icon main_node main_node_' . $v43974ff697 . '"></i> ' . strtoupper(str_replace("_", " ", $v43974ff697)) . '</label>
					<ul>'; if ($pfd248cca) foreach ($pfd248cca as $v0a5deb92d8 => $v4a24304713) { $v54307eb686 = $v830cc461b7[$v43974ff697][$v0a5deb92d8]; $v3fab52f440 = "$v9bfd456213/" . $pbffdab91[$v43974ff697][$v0a5deb92d8]; $pf8ed4912 .= '<li data-jstree=\'{"icon":"main_node_' . $v54307eb686["item_type"] . '"}\'>
							<label>
								<input type="checkbox" name="permissions_by_objects[' . $v0a035c60aa . '][' . $v3fab52f440 . '][]" value="' . $pb76ee81a . '" />
								' . $paeab4070[$v43974ff697][$v0a5deb92d8] . '
							</label>'; if ($v43974ff697 == "db_layers") { $pf8ed4912 .= '<ul>'; foreach ($v4a24304713 as $v13eedf3e61 => $v25dfe304be) { $v3fab52f440 = "$v9bfd456213/" . $pbffdab91[$v43974ff697][$v0a5deb92d8] . "/$v13eedf3e61"; $pf8ed4912 .= '<li data-jstree=\'{"icon":"db_driver"}\'>
										<label>
											<input type="checkbox" name="permissions_by_objects[' . $v0a035c60aa . '][' . $v3fab52f440 . '][]" value="' . $pb76ee81a . '" />
											' . $v13eedf3e61 . '
										</label>
									</li>'; } $pf8ed4912 .= '</ul>'; } else { $v6f3a2700dd = $pf7b73b3a; $v6f3a2700dd = str_replace("#bean_name#", $v54307eb686["bean_name"], $v6f3a2700dd); $v6f3a2700dd = str_replace("#bean_file_name#", $v54307eb686["bean_file_name"], $v6f3a2700dd); $v6f3a2700dd = str_replace("#path#", "", $v6f3a2700dd); $pf8ed4912 .= '<ul url="' . $v6f3a2700dd . '" object_id_prefix="' . $v3fab52f440 . '"></ul>'; } $pf8ed4912 .= '</li>'; } $pf8ed4912 .= '	</ul>
					<script>				
						var layerFromFileManagerTree_' . $pb76ee81a . '_' . $v43974ff697 . ' = new MyTree({
							multiple_selection : true,
							ajax_callback_before : prepareLayerNodes1,
							ajax_callback_after : ' . $pf3f2367a . ',
							on_select_callback : toggleFileTreeCheckbox,
						});
						layerFromFileManagerTree_' . $pb76ee81a . '_' . $v43974ff697 . '.init("file_tree_' . $pb76ee81a . '_' . $v43974ff697 . '");
					</script>
				</li>'; } return $pf8ed4912; } ?>
