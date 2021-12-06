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

class CMSPresentationLayerJoinPointsUIHandler { public static function convertBlockSettingsArrayToObj($pfb662071) { $v972f1a5c2b = array(); if (is_array($pfb662071)) { $v43dd7d0051 = 0; foreach ($pfb662071 as $v342a134247) { if (!$v342a134247["key"] && $v342a134247["key_type"] == "null") { if (isset($v342a134247["items"])) $v972f1a5c2b[$v43dd7d0051] = self::convertBlockSettingsArrayToObj($v342a134247["items"]); else $v972f1a5c2b[$v43dd7d0051] = array("value" => $v342a134247["value"], "value_type" => $v342a134247["value_type"]); $v43dd7d0051++; } else { $pe5c5e2fe = $v342a134247["key"]; $v972f1a5c2b[$pe5c5e2fe] = $v342a134247; if (isset($v972f1a5c2b[$pe5c5e2fe]["items"])) $v972f1a5c2b[$pe5c5e2fe]["items"] = self::convertBlockSettingsArrayToObj($v972f1a5c2b[$pe5c5e2fe]["items"]); if (is_numeric($pe5c5e2fe) && (int)$pe5c5e2fe >= $v43dd7d0051) $v43dd7d0051 = (int)$pe5c5e2fe + 1; } } } return $v972f1a5c2b; } public static function getHeader() { return '
		<script>
			var join_points_html = \'' . addcslashes(str_replace("\n", "", self::getJoinPointMethodHtml()), "\\'") . '\';
			var input_mapping_from_join_point_to_method_item_html = \'' . addcslashes(str_replace("\n", "", self::getInputMappingFromJoinPointToMethodHtml()), "\\'") . '\';
			var method_arg_html = \'' . addcslashes(str_replace("\n", "", self::getMethodArgHtml()), "\\'") . '\';
			var output_mapping_from_method_to_join_point_item_html = \'' . addcslashes(str_replace("\n", "", self::getOutputMappingFromMethodToJoinPointHtml()), "\\'") . '\';
		</script>'; } public static function getRegionBlocksJoinPointsJavascriptObjs($v63208850d1) { $v66e18e6931 = array(); if (is_array($v63208850d1)) { foreach ($v63208850d1 as $v9b9b8653bc => $pb0f26d6a) { foreach ($pb0f26d6a as $peebaaf55 => $pf9d1c559) { foreach ($pf9d1c559 as $pe603f3eb => $v0fa547ce72) { foreach ($v0fa547ce72 as $pc5f2e454) { $v34bca6a112 = $pc5f2e454["join_point_name"]; if ($v34bca6a112) { $v77784c4ecd = isset($pc5f2e454["join_point_settings"]["key"]) ? array($pc5f2e454["join_point_settings"]) : $pc5f2e454["join_point_settings"]; $v221de5d5ea = self::convertBlockSettingsArrayToObj($v77784c4ecd); $v66e18e6931[$v9b9b8653bc][$peebaaf55][$pe603f3eb][$v34bca6a112][] = $v221de5d5ea; } } } } } } return '
		<script>
			var blocks_join_points_settings_objs = prepareBlocksJoinPointsSettingsObjs(' . json_encode($v66e18e6931) . ');
		</script>'; } public static function getBlockJoinPointsJavascriptObjs($v0fa547ce72, $v12d5543831 = null) { $pc26bab42 = array(); if (is_array($v0fa547ce72)) { foreach ($v0fa547ce72 as $pc5f2e454) { $v34bca6a112 = $pc5f2e454["join_point_name"]; if ($v34bca6a112) { $v77784c4ecd = isset($pc5f2e454["join_point_settings"]["key"]) ? array($pc5f2e454["join_point_settings"]) : $pc5f2e454["join_point_settings"]; $v221de5d5ea = self::convertBlockSettingsArrayToObj($v77784c4ecd); $pc26bab42[$v34bca6a112][] = $v221de5d5ea; } } } $v7edb8f4e8e = array(); if (is_array($v12d5543831)) { foreach ($v12d5543831 as $pee21e4cf) { if ($pee21e4cf["join_point_name"]) { $v7edb8f4e8e[ $pee21e4cf["join_point_name"] ] = true; } } } return '
		<script>
			var block_join_points_settings_objs = prepareBlockJoinPointsSettingsObjs(' . json_encode($pc26bab42) . ');
			var available_block_local_join_point = ' . json_encode($v7edb8f4e8e) . ';
		</script>'; } public static function getBlockJoinPointsHtml($pd84094b3, $v29fec2ceaa, $pe34a3d0d = false, $v5030d91f53 = false) { $pf8ed4912 = ''; if ($pd84094b3) { $pf8ed4912 .= '<div class="module_join_points">
					<label>Module\'s Join Points:</label>'; if ($v29fec2ceaa && $v5030d91f53) { $pf8ed4912 .= '<span class="view_module_source_code" onClick="openModuleSourceCode(this, \'' . $v29fec2ceaa . '\')">View join points in the module\'s source code</span>
					
					<div class="module_source_code">
						<span class="close" onClick="closeModuleSourceCode(this)">Close</span>
						<textarea readonly="readonly"></textarea>
					</div>'; } $pf8ed4912 .= '
					<div class="join_points">'; $pc37695cb = count($pd84094b3); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v5d2ebe3c1a = $pd84094b3[$v43dd7d0051]; $v34bca6a112 = $v5d2ebe3c1a["join_point_name"]; if ($v34bca6a112) { $pdcf670f6 = 'join_point[' . $v34bca6a112 . ']'; $pf8ed4912 .= '
							<div class="join_point" joinPointName="' . $v34bca6a112 . '" prefix="' . $pdcf670f6 . '">
								<label><span>' . $v34bca6a112 . '</span></label>
								<select class="module_join_points_property join_point_active" name="' . $pdcf670f6 . '[active]" onChange="onChangeJoinPointActive(this);">
									<option value="0">Inactive</option>
									<option value="1">Active - Only here</option>
									<option value="2">Active - Here and on Page Level</option>
								</select>
								<span class="icon maximize" onClick="maximizeJoinPointsSettings(this)" title="Maximize/Minimize join point methods">Maximize/Minimize</span>
								<span class="icon add" onClick="addJoinPointMethod(this, \'' . $pdcf670f6 . '\')" title="Add new join point method">Add</span>
								<span class="icon info" onClick="showJoinPointDetails(this)" title="Show join point details">Add</span>
								<div class="join_point_details">
									<div class="join_point_description">
										<label>Join Point Description: "' . $v5d2ebe3c1a["join_point_description"] . '"</label>
									</div>
									<div class="join_point_method_type">
										<label>Join Point Method Type: "' . $v5d2ebe3c1a["method"] . '"</label>
									</div>
									<div class="join_point_args">
										<label>Join Point Method Args: </label>
										<table>
											<tr>
												<th class="table_header key">Key</th>
												<th class="table_header value">Value</th>
												<th class="table_header type">Type</th>
											</tr>'; if (is_array($v5d2ebe3c1a["join_point_settings"])) { $v77784c4ecd = self::convertBlockSettingsArrayToObj($v5d2ebe3c1a["join_point_settings"]); foreach ($v77784c4ecd as $pd164db70 => $pf725626e) { $v67db1bd535 = $pf725626e["items"] ? json_encode($pf725626e["items"]) : $pf725626e["value"]; $pc3e857ed = $pf725626e["items"] ? "array" : $pf725626e["value_type"]; $pc3e857ed = !$pc3e857ed && is_numeric($v67db1bd535) ? "numeric" : $pc3e857ed; $pf8ed4912 .= '		<tr>
												<td class="key">' . $pd164db70 . '</td>
												<td class="value">' . $v67db1bd535 . '</td>
												<td class="type">' . $pc3e857ed . '</td>
											</tr>'; } } else { $pf8ed4912 .= '			<tr class="empty_table">
												<td colspan="3">Empty Args...</td>
											</tr>'; } $pf8ed4912 .= '						
										</table>
									</div>
								</div>
						
								<div class="empty_items">There are NO available elements for this join point... <br/>Please click in the add icon to add a new join point method.</div>
								' . ($pe34a3d0d ? str_replace("#prefix#", $pdcf670f6 . "[0]", self::getJoinPointMethodHtml()) : '') . '
							</div>'; } } $pf8ed4912 .= '
					</div>
				</div>'; } return $pf8ed4912; } public static function getJoinPointMethodHtml() { $pf8ed4912 = '
		<div class="join_point_method">
			<label>Join Point Method</label>
			<span class="icon delete" onClick="removeJoinPointMethod(this)">Remove</span>
						
			<div class="method_file">
				<label>Method File: </label>
				<input class="module_join_points_property" type="text" name="#prefix#[method_file]" value="" />
				<span class="icon add_variable inline" onclick="onProgrammingTaskChooseCreatedVariable(this)" title="Choose a variable">Add Variable</span>
				<span class="icon search" onclick="onIncludeFileTaskChooseFile(this)" title="Choose a file to include">Search</span>
			</div>
			
			<div class="method_type">
				<label>Type: </label>
				<select class="module_join_points_property" name="#prefix#[method_type]" onChange="onChangeJoinPointMethodType(this);">
					<option value="function">Function</option>
					<option value="method">Object Method</option>
				</select>
			</div>
	
			<div class="method_obj">
				<label>Method Obj: </label>
				<input class="module_join_points_property" type="text" name="#prefix#[method_obj]" value="" />
				<span class="icon add_variable inline" onclick="onProgrammingTaskChooseCreatedVariable(this)" title="Choose a variable">Add Variable</span>
			</div>
	
			<div class="method_name">
				<label>Method Name: </label>
				<input class="module_join_points_property" type="text" name="#prefix#[method_name]" value="" />
				<span class="icon add_variable inline" onclick="onProgrammingTaskChooseCreatedVariable(this)" title="Choose a variable">Add Variable</span>
				<span class="icon search" onClick="onChooseJoinPointMethodOrFunction(this)">Search Method</span>
			</div>
	
			<div class="method_static">
				<label>Is Method Static: </label>
				<input class="module_join_points_property" type="checkbox" name="#prefix#[method_static]" value="1" />
			</div>
	
			<div class="input_mapping">
				<label>Input mapping from join point to method: </label>
				<span class="info">This mapping consists in translating the input array from the module to the user method input...</span>
				<table>
					<tr>
						<th class="table_header join_point_input">Join Point Input</th>
						<th class="table_header from_to"></th>
						<th class="table_header method_input">Method Input</th>
						<th class="table_header erase_from_input" title="Erase item from input array">Erase</th>
						<th class="table_header icons">
							<span class="icon add" onClick="addJoinPointTableItem(this, \'#prefix#[input_mapping]\', input_mapping_from_join_point_to_method_item_html)">Add</span>
						</th>
					</tr>
					<tr class="empty_table">
						<td colspan="5">No items...</td>
					</tr>
				</table>
			</div>
	
			<div class="method_args">
				<label>Method Args: </label>
				<span class="info">Here is where you assign the input items to the correspondent method args, based in the "$input" variable.</span>
				<table>
					<tr>
						<th class="table_header value">Value</th>
						<th class="table_header type">Type</th>
						<th class="table_header icons">
							<span class="icon add" onClick="addJoinPointTableItem(this, \'#prefix#[method_args]\', method_arg_html)">Add</span>
						</th>
					</tr>
					<tr class="empty_table hidden">
						<td colspan="3">No items...</td>
					</tr>' . str_replace("#prefix#", "#prefix#[method_args][0]", self::getMethodArgHtml(array( "value" => '\\$input', "type" => "", ))) . '
				</table>
			</div>
	
			<div class="output_mapping">
				<label>Input mapping from join point to method: </label>
				<span class="info">Here is where you manage the method\'s output result so it can be correctly used in the module.</span>
				<table>
					<tr>
						<th class="table_header method_output">Method Output</th>
						<th class="table_header from_to"></th>
						<th class="table_header join_point_output">Join Point Output</th>
						<th class="table_header erase_from_output" title="Erase item from output array">Erase</th>
						<th class="table_header icons">
							<span class="icon add" onClick="addJoinPointTableItem(this, \'#prefix#[output_mapping]\', output_mapping_from_method_to_join_point_item_html)">Add</span>
						</th>
					</tr>
					<tr class="empty_table">
						<td colspan="5">No items...</td>
					</tr>
				</table>
			</div>
		</div>'; return $pf8ed4912; } public static function getInputMappingFromJoinPointToMethodHtml($v539082ff30 = null) { return '
		<tr>
			<td class="join_point_input">
				$input["
				<input class="module_join_points_property" type="text" name="#prefix#[join_point_input]" value="' . $v539082ff30["join_point_input"] . '" />
				<span class="icon add_variable small inline" onclick="onProgrammingTaskChooseCreatedVariable(this)" title="Choose a variable">Add Variable</span>
				"]
			</td>
			<td class="from_to">=&gt;</td>
			<td class="method_input">
				$input["
				<input class="module_join_points_property" type="text" name="#prefix#[method_input]" value="' . $v539082ff30["method_input"] . '" />
				<span class="icon add_variable small inline" onclick="onProgrammingTaskChooseCreatedVariable(this)" title="Choose a variable">Add Variable</span>
				"]
			</td>
			<td class="erase_from_input">
				<input class="module_join_points_property" type="checkbox" name="#prefix#[erase_from_input]" value="1" title="Erase item from input array" ' . (!isset($v539082ff30["erase_from_input"]) || $v539082ff30["erase_from_input"] ? "checked" : "") . ' />
			</td>
			<td class="icons">
				<span class="icon delete" onClick="removeJoinPointTableItem(this)">Remove</span>
			</td>
		</tr>'; } public static function getMethodArgHtml($v539082ff30 = null) { return '
		<tr>
			<td class="value">
				<input class="module_join_points_property" type="text" name="#prefix#[value]" value="' . $v539082ff30["value"] . '" />
				<span class="icon add_variable small inline" onclick="onProgrammingTaskChooseCreatedVariable(this)" title="Choose a variable">Add Variable</span>
			</td>
			<td class="type">
				<select class="module_join_points_property" name="#prefix#[type]">
					<option value="">code</option>
					<option ' . ($v539082ff30["type"] == "string" ? "selected" : "") . '>string</option>
					<option ' . ($v539082ff30["type"] == "variable" ? "selected" : "") . '>variable</option>
				</select>
			</td>
			<td class="icons">
				<span class="icon delete" onClick="removeJoinPointTableItem(this)">Remove</span>
			</td>
		</tr>'; } public static function getOutputMappingFromMethodToJoinPointHtml($v539082ff30 = null) { return '
		<tr>
			<td class="method_output">
				$output["
				<input class="module_join_points_property" type="text" name="#prefix#[method_output]" value="' . $v539082ff30["method_output"] . '" />
				<span class="icon add_variable small inline" onclick="onProgrammingTaskChooseCreatedVariable(this)" title="Choose a variable">Add Variable</span>
				"]
			</td>
			<td class="from_to">=&gt;</td>
			<td class="join_point_output">
				$output["
				<input class="module_join_points_property" type="text" name="#prefix#[join_point_output]" value="' . $v539082ff30["join_point_output"] . '" />
				<span class="icon add_variable small inline" onclick="onProgrammingTaskChooseCreatedVariable(this)" title="Choose a variable">Add Variable</span>
				"]
			</td>
			<td class="erase_from_output">
				<input class="module_join_points_property" type="checkbox" name="#prefix#[erase_from_output]" value="1" title="Erase item from output array" ' . (!isset($v539082ff30["erase_from_output"]) || $v539082ff30["erase_from_output"] ? "checked" : "") . ' />
			</td>
			<td class="icons">
				<span class="icon delete" onClick="removeJoinPointTableItem(this)">Remove</span>
			</td>
		</tr>'; } } ?>
