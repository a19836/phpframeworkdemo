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
include_once get_lib("org.phpframework.phpscript.PHPUICodeExpressionHandler"); include_once $EVC->getUtilPath("WorkFlowPresentationHandler"); include_once $EVC->getUtilPath("CMSPresentationLayerJoinPointsUIHandler"); include_once $EVC->getUtilPath("WorkFlowUIHandler"); class CMSPresentationLayerUIHandler { public static function getHeader($peb014cfd, $v37d269c4fa, $v7577b57ccf, $pef9e169d, $v304acc4dcf, $v5aaf0d3496, $v2b1e634696, $peb496cef, $pf1fdc6ee, $pf9d1c559, $v1fb4b254d3, $v2508589a4c, $v62ed6d4992 = false, $v2de4fbd75c = "", $pfb8fca1c = null, $v45e5141563 = null) { $pf8ed4912 = ''; if (strpos($v2de4fbd75c, 'vendor/phpjs/functions/strings/parse_str.js') === false) $pf8ed4912 .= '
			<!-- Add PHPJS functions -->
			<script type="text/javascript" src="' . $v37d269c4fa . 'vendor/phpjs/functions/strings/parse_str.js"></script>'; if (strpos($v2de4fbd75c, 'vendor/phpjs/functions/strings/stripslashes.js') === false) $pf8ed4912 .= '
			<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/phpjs/functions/strings/stripslashes.js"></script>
			<script type="text/javascript" src="' . $v37d269c4fa . 'vendor/phpjs/functions/strings/addcslashes.js"></script>'; if (strpos($v2de4fbd75c, 'vendor/jquery/js/jquery.md5.js') === false) $pf8ed4912 .= '
			<!-- Add MD5 JS Files -->
			<script type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquery/js/jquery.md5.js"></script>'; if (strpos($v2de4fbd75c, 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/jquery-ui-droppable-iframe-fix.js') === false) $pf8ed4912 .= '
			<!-- Add Droppable Iframe Js - to be used by the .tab_content_template_layout  -->
			<script type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/jquery-ui-droppable-iframe-fix.js"></script>'; $pf8ed4912 .= '
		<script>
		var get_available_blocks_list_url = \'' . $v7577b57ccf . '\';
		var get_block_params_url = \'' . $pef9e169d . '\';
		var create_entity_code_url = \'' . $v304acc4dcf . '\';

		var region_block_html = \'' . addcslashes(str_replace("\n", "", self::getRegionBlockHtml("#region#", "#block#", null, false, $v5aaf0d3496, null, null, "#rb_index#")), "\\'") . '\';
		var defined_region_html = \'' . addcslashes(str_replace("\n", "", self::getDefinedRegionHtml("#region#")), "\\'") . '\';
		var block_param_html = \'' . addcslashes(str_replace("\n", "", self::getBlockParamHtml("#param#", "#value#")), "\\'") . '\';
		var include_html = \'' . addcslashes(str_replace("\n", "", self::getIncludeHtml("#path#", false)), "\\'") . '\';
		var template_param_html = \'' . addcslashes(str_replace("\n", "", self::getTemplateParamHtml("#param#", "#value#")), "\\'") . '\';
		var defined_template_param_html = \'' . addcslashes(str_replace("\n", "", self::getDefinedTemplateParamHtml("#param#")), "\\'") . '\';
		
		var regions_blocks_list = ' . json_encode($v2b1e634696) . ';
		var defined_regions_list = ' . json_encode($pfb8fca1c) . ';
		var available_blocks_list = ' . json_encode($v5aaf0d3496) . ';
		var block_params_values_list = ' . json_encode($peb496cef) . ';
		var template_params_values_list = ' . json_encode($v1fb4b254d3) . ';
		var defined_template_params_values = ' . json_encode($v45e5141563) . ';
		var includes_list = ' . json_encode($pf1fdc6ee) . ';
		
		var selected_project_id = \'' . $v2508589a4c . '\';
		</script>'; $pf8ed4912 .= CMSPresentationLayerJoinPointsUIHandler::getHeader(); $pf8ed4912 .= CMSPresentationLayerJoinPointsUIHandler::getRegionBlocksJoinPointsJavascriptObjs($pf9d1c559); if ($v62ed6d4992) $pf8ed4912 .= '
<!-- Layout UI Editor - Color -->
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'js/color.js"></script>

<!-- Add Jquery Tap-Hold Event JS file -->
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerytaphold/taphold.js"></script>

<!-- Jquery Touch Punch to work on mobile devices with touch -->
<script type="text/javascript" src="' . $v37d269c4fa . 'vendor/jqueryuitouchpunch/jquery.ui.touch-punch.min.js"></script>

<!-- Layout UI Editor - Add ACE-Editor -->
<script type="text/javascript" src="' . $v37d269c4fa . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
<script type="text/javascript" src="' . $v37d269c4fa . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>

<!-- Layout UI Editor - Add Code Beautifier -->
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/mycodebeautifier/js/MyCodeBeautifier.js"></script>

<!-- Layout UI Editor - Add Html/CSS/JS Beautify code -->
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jsbeautify/js/lib/beautify.js"></script>
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jsbeautify/js/lib/beautify-css.js"></script>
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/myhtmlbeautify/MyHtmlBeautify.js"></script>

<!-- Add Auto complete -->
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/myautocomplete/js/MyAutoComplete.js"></script>
<link rel="stylesheet" href="' . $v37d269c4fa . 'vendor/myautocomplete/css/style.css">

<!-- Layout UI Editor - Material-design-iconic-font -->
<link rel="stylesheet" href="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/vendor/materialdesigniconicfont/css/material-design-iconic-font.min.css">

<!-- Layout UI Editor - JQuery Nestable2 -->
<link rel="stylesheet" href="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/vendor/nestable2/jquery.nestable.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/vendor/nestable2/jquery.nestable.min.js"></script>

<!-- Layout UI Editor - Html Entities Converter -->
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/he/he.js"></script>

<!-- Layout UI Editor - HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
	 <script src="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/html5_ie8/html5shiv.min.js"></script>
	 <script src="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/html5_ie8/respond.min.js"></script>
<![endif]-->

<!-- Layout UI Editor - Add Iframe droppable fix - IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/vendor/jqueryuidroppableiframe/js/ie10-viewport-bug-workaround.js"></script>

<!-- Layout UI Editor - Add Layout UI Editor -->
<link rel="stylesheet" href="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/css/some_bootstrap_style.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/css/style.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/css/widget_resource.css" type="text/css" charset="utf-8" />

<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/js/TextSelection.js"></script>
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/js/LayoutUIEditor.js"></script>
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/js/CreateWidgetContainerClassObj.js"></script>
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/js/LayoutUIEditorFormField.js"></script>
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerylayoutuieditor/js/LayoutUIEditorWidgetResource.js"></script>

<!-- Layout UI Editor - Add Layout UI Editor Widget Resource Options/Handlers -->
<link rel="stylesheet" href="' . $peb014cfd . 'css/layout_ui_editor_widget_resource_options.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $peb014cfd . 'js/layout_ui_editor_widget_resource_options.js"></script>
'; return $pf8ed4912; } public static function getChoosePresentationIncludeFromFileManagerPopupHtml($v8ffce2a791, $pa0462a8e, $pf7b73b3a, $v54c4a1fbb7, $pe2f0f7f1, $v828ff69e5c, $pb0e92e25, $v8aefdcedb9 = "MyFancyPopup") { $pdf10c8d2 = $pb0e92e25[0][0] ? $pb0e92e25[0][0] : $v8ffce2a791; return '<div id="choose_presentation_include_from_file_manager" class="myfancypopup choose_from_file_manager with_title">
			<div class="title">Choose a File</div>
			<ul class="mytree">
				<li>
					<label>' . $pdf10c8d2 . '</label>
					<ul url="' . str_replace("#bean_file_name#", $pa0462a8e, str_replace("#bean_name#", $v8ffce2a791, $pf7b73b3a)) . '"></ul>
				</li>
				<!--li>
					<label>DAO</label>
					<ul url="' . $v54c4a1fbb7 . '"></ul>
				</li-->
				<li>
					<label>LIB</label>
					<ul url="' . $pe2f0f7f1 . '"></ul>
				</li>
				<li>
					<label>VENDOR</label>
					<ul url="' . $v828ff69e5c . '"></ul>
				</li>
			</ul>
			<div class="button">
				<input type="button" value="Update" onClick="' . $v8aefdcedb9 . '.settings.updateFunction(this)" />
			</div>
		</div>'; } public static function getTemplateRegionBlockHtmlEditorPopupHtml($pefdd2109) { $v446c479876 = $_COOKIE["main_navigator_side"] == "main_navigator_reverse" ? "" : "reverse"; return '<div class="template_region_block_html_editor_popup myfancypopup">
			<div class="layout-ui-editor ' . $v446c479876 . ' fixed-side-properties hide-template-widgets-options">
				<ul class="menu-widgets hidden">
					' . $pefdd2109 . '
				</ul>
			</div>
		</div>'; } public static function getRegionsBlocksAndIncludesHtml($v7b2ad4afbf, $v3f8f1acfdb, $v2b1e634696, $v5aaf0d3496, $v5e5b435544, $peb496cef, $pc06f1034, $v3fd37663c7, $v1fb4b254d3, $pfb8fca1c = null, $v45e5141563 = null) { $pf8ed4912 = '
			<div class="region_blocks">
				<label>Selected Template Regions:</label>
			
				<div class="template_region_items">'; $pc9cb1421 = array(); if ($v7b2ad4afbf) { if ($v3f8f1acfdb) { $v3f0149c66c = count($v3f8f1acfdb); $pa13c1238 = count($v2b1e634696); $v5bd013bcfe = array(); for ($v43dd7d0051 = 0; $v43dd7d0051 < $v3f0149c66c; $v43dd7d0051++) { $v9b9b8653bc = $v3f8f1acfdb[$v43dd7d0051]; for ($v9d27441e80 = 0; $v9d27441e80 < $pa13c1238; $v9d27441e80++) { $v49cb7db1ab = $v2b1e634696[$v9d27441e80]; if ($v49cb7db1ab[0] == $v9b9b8653bc) { $peebaaf55 = $v49cb7db1ab[1]; $pd6ec966e = $v49cb7db1ab[2]; $v3fb9f41470 = $v49cb7db1ab[3]; $v8eaa4de79a = $v3fb9f41470 == 1 ? md5($peebaaf55) : ($v3fb9f41470 == 2 || $v3fb9f41470 == 3 ? "block_" : "view_") . $peebaaf55; $pc9cb1421[$v9b9b8653bc][ $v8eaa4de79a ][ $pd6ec966e ] = true; if (isset($v5bd013bcfe["$v9b9b8653bc-$v8eaa4de79a-$pd6ec966e"])) $v5bd013bcfe["$v9b9b8653bc-$v8eaa4de79a-$pd6ec966e"]++; else $v5bd013bcfe["$v9b9b8653bc-$v8eaa4de79a-$pd6ec966e"] = 0; $pf8ed4912 .= self::getRegionBlockHtml($v9b9b8653bc, $peebaaf55, $pd6ec966e, $v3fb9f41470, $v5aaf0d3496, $v5e5b435544, $peb496cef, $v5bd013bcfe["$v9b9b8653bc-$v8eaa4de79a-$pd6ec966e"]); } } if (empty($pc9cb1421[$v9b9b8653bc])) $pf8ed4912 .= self::getRegionBlockHtml($v9b9b8653bc, null, null, false, $v5aaf0d3496, $v5e5b435544, null, 0); } } } $pf8ed4912 .= '</div>
				<div class="no_items' . ($v7b2ad4afbf && $v3f8f1acfdb ? ' hidden' : '') . '">There are no regions in this template</div>
			</div>
		
			<div class="other_region_blocks">
				<label>Extra Regions:</label>
				<span class="icon add" onClick="addOtherRegionBlock(this)" title="Add">Add</span>
			
				<div class="template_region_items">'; $v7959970a41 = false; if ($v2b1e634696) { $pc37695cb = count($v2b1e634696); $v5bd013bcfe = array(); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v49cb7db1ab = $v2b1e634696[$v43dd7d0051]; $v9b9b8653bc = $v49cb7db1ab[0]; $peebaaf55 = $v49cb7db1ab[1]; $pd6ec966e = $v49cb7db1ab[2]; $v3fb9f41470 = $v49cb7db1ab[3]; $v8eaa4de79a = $v3fb9f41470 == 1 ? md5($peebaaf55) : ($v3fb9f41470 == 2 || $v3fb9f41470 == 3 ? "block_" : "view_") . $peebaaf55; if (!isset($pc9cb1421[$v9b9b8653bc][$v8eaa4de79a][$pd6ec966e])) { if (isset($v5bd013bcfe["$v9b9b8653bc-$v8eaa4de79a-$pd6ec966e"])) $v5bd013bcfe["$v9b9b8653bc-$v8eaa4de79a-$pd6ec966e"]++; else $v5bd013bcfe["$v9b9b8653bc-$v8eaa4de79a-$pd6ec966e"] = 0; $pf8ed4912 .= self::getRegionBlockHtml($v9b9b8653bc, $peebaaf55, $pd6ec966e, $v3fb9f41470, $v5aaf0d3496, $v5e5b435544, $peb496cef, $v5bd013bcfe["$v9b9b8653bc-$v8eaa4de79a-$pd6ec966e"]); $v7959970a41 = true; } } } $pf8ed4912 .= '	</div>
				<div class="no_items' . ($v7959970a41 ? ' hidden' : '') . '">There are no extra regions in this file</div>
			</div>
			
			<div class="defined_regions">
				<label>Defined Regions in the Template:</label>
			
				<div class="template_region_items">'; $v7959970a41 = false; if ($pfb8fca1c) { $pc37695cb = count($pfb8fca1c); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v9b9b8653bc = $pfb8fca1c[$v43dd7d0051]; $pf8ed4912 .= self::getDefinedRegionHtml($v9b9b8653bc); $v7959970a41 = true; } } $pf8ed4912 .= '</div>
				<div class="no_items' . ($v7959970a41 ? ' hidden' : '') . '">There are no defined regions in the template</div>
			</div>
		
			<div class="includes">
				<label>Includes:</label>
				<span class="icon add" onClick="addInclude(this)" title="Add">Add</span>
			
				<div class="items">'; if ($pc06f1034) { $pc37695cb = count($pc06f1034); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pc24afc88 = $pc06f1034[$v43dd7d0051]; $v154d33eec4 = PHPUICodeExpressionHandler::getArgumentCode($pc24afc88["path"], $pc24afc88["path_type"]); $pf8ed4912 .= self::getIncludeHtml($v154d33eec4, $pc24afc88["once"]); } } $pf8ed4912 .= '	</div>
				<div class="no_items' . ($pc06f1034 ? ' hidden' : '') . '">There are no includes in this file</div>
			</div>
			
			<div class="template_params">
				<label>Selected Template Params:</label>
			
				<div class="items">'; $v0db218b458 = array(); if ($v7b2ad4afbf) { if ($v3fd37663c7) { $pc37695cb = count($v3fd37663c7); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v58b61e02bc = $v3fd37663c7[$v43dd7d0051]; if ($v58b61e02bc && !isset($v0db218b458[$v58b61e02bc])) { $v72eb975550 = $v1fb4b254d3[$v58b61e02bc]; $pd6bf10d2 = $v45e5141563[$v58b61e02bc]; $pf8ed4912 .= self::getTemplateParamHtml($v58b61e02bc, $v72eb975550, $pd6bf10d2); } $v0db218b458[$v58b61e02bc] = true; } } if ($v45e5141563) { foreach ($v45e5141563 as $v58b61e02bc => $pd6bf10d2) { if ($v58b61e02bc && !isset($v0db218b458[$v58b61e02bc])) { $v72eb975550 = $v1fb4b254d3[$v58b61e02bc]; $pf8ed4912 .= self::getTemplateParamHtml($v58b61e02bc, $v72eb975550, $pd6bf10d2); $v0db218b458[$v58b61e02bc] = true; } } } } $pf8ed4912 .= '</div>
				<div class="no_items' . ($v7b2ad4afbf && $v3fd37663c7 ? ' hidden' : '') . '">There are no params in this template</div>
			</div>
		
			<div class="other_template_params">
				<label>Extra Params:</label>
				<span class="icon add" onClick="addOtherTemplateParam(this)" title="Add">Add</span>
			
				<div class="items">'; $v7959970a41 = false; foreach ($v1fb4b254d3 as $v58b61e02bc => $v72eb975550) { if ($v58b61e02bc && !isset($v0db218b458[$v58b61e02bc])) { $pf8ed4912 .= self::getTemplateParamHtml($v58b61e02bc, isset($v72eb975550) ? $v72eb975550 : ""); $v7959970a41 = true; } } $pf8ed4912 .= '	</div>
				<div class="no_items' . ($v7959970a41 ? ' hidden' : '') . '">There are no extra params in this file</div>
			</div>
		
			<div class="defined_template_params">
				<label>Defined Params in the Template:</label>
			
				<div class="items">'; $v7959970a41 = false; foreach ($v0db218b458 as $v58b61e02bc => $v72eb975550) if ($v58b61e02bc) { $pf8ed4912 .= self::getDefinedTemplateParamHtml($v58b61e02bc); $v7959970a41 = true; } $pf8ed4912 .= '	</div>
				<div class="no_items' . ($v7959970a41 ? ' hidden' : '') . '">There are no defined params in the template</div>
			</div>'; return $pf8ed4912; } public static function getDefinedRegionHtml($v9b9b8653bc) { $v7a1b9c07b3 = substr($v9b9b8653bc, 0, 1) == '"' ? str_replace('"', '', $v9b9b8653bc) : $v9b9b8653bc; return '<div class="template_region_item">' . $v7a1b9c07b3 . '</div>'; } public static function getRegionBlockHtml($v9b9b8653bc, $paa7b7454, $v7eefa5ee2c, $v94104ac8c0, $v5aaf0d3496, $v5e5b435544 = array(), $peb496cef = array(), $pe603f3eb = 0) { $v5aaf0d3496 = is_array($v5aaf0d3496) ? $v5aaf0d3496 : array(); $v23caa16bce = $v36aefa195e = array(); $pcbe60070 = $v94104ac8c0 == 1; $v90a480265d = $v94104ac8c0 == 2 || $v94104ac8c0 == 3; $v522e51ac5f = $v94104ac8c0 == 4 || $v94104ac8c0 == 5; if (!$v90a480265d && !$pcbe60070 && !$v522e51ac5f) $v90a480265d = true; if ($pcbe60070) { $v3fb9f41470 = PHPUICodeExpressionHandler::getValueType($paa7b7454, array("empty_string_type" => "string", "non_set_type" => "string")); $pf8ed4912 = PHPUICodeExpressionHandler::getArgumentCode($paa7b7454, $v3fb9f41470); $v3ae55a9a2e = ' is_html has_edit'; } else { $v56b1e1a2b7 = substr($paa7b7454, 0, 1) == '"' ? str_replace('"', '', $paa7b7454) : $paa7b7454; $pc611e727 = substr($v7eefa5ee2c, 0, 1) == '"' ? str_replace('"', '', $v7eefa5ee2c) : $v7eefa5ee2c; $v7959970a41 = false; if ($v90a480265d) { $pb29e9f6d = strpos($v56b1e1a2b7, "\n") || strip_tags($v56b1e1a2b7) != $v56b1e1a2b7; $pd45d0d0d = $v5aaf0d3496[$pc611e727]; $v7959970a41 = empty($v56b1e1a2b7) || ($pd45d0d0d && !$pb29e9f6d && in_array($v56b1e1a2b7, $pd45d0d0d)); $v23caa16bce = $v5e5b435544[$v9b9b8653bc][$paa7b7454]; $v36aefa195e = $peb496cef[$v9b9b8653bc][$paa7b7454][$pe603f3eb]; } $peb283674 = strpos($paa7b7454, "\n") !== false; $pada21496 = strpos($paa7b7454, "\n") === false && substr($paa7b7454, 0, 1) == '$' && strpos($paa7b7454, "->") === false; $pe7eba739 = !$pada21496 && !$peb283674 && strlen($paa7b7454); $pade4502c = $pe7eba739 && substr($paa7b7454, 0, 1) == '"'; $pb30921ad = $pe7eba739 && !$pade4502c; $v7fa8301bf0 = ($v7959970a41 && !$pb30921ad) || !strlen($paa7b7454); if ($v7fa8301bf0) $peb283674 = $pada21496 = $pe7eba739 = $pade4502c = $pb30921ad = false; $v3ae55a9a2e = $pe7eba739 || $pada21496 ? ' is_input' : ($peb283674 ? ' is_text' : ''); $v3ae55a9a2e .= $paa7b7454 && ($v7fa8301bf0 || $pade4502c) ? ' has_edit' : ''; } $v7a1b9c07b3 = substr($v9b9b8653bc, 0, 1) == '"' ? str_replace('"', '', $v9b9b8653bc) : $v9b9b8653bc; $v9b9b8653bc = str_replace('"', "&quot;", $v9b9b8653bc); $pf8ed4912 = '<div class="template_region_item' . $v3ae55a9a2e . '" rb_index="' . $pe603f3eb . '">
			<span class="icon info invisible" onClick="openTemplateRegionInfoPopup(this)" title="View region samples">View region samples</span>
			<label title="' . $v7a1b9c07b3 . '">' . $v7a1b9c07b3 . ':</label>
			<input class="region" type="hidden" value="' . $v9b9b8653bc . '" />
			<select class="type" onChange="onChangeTemplateRegionItemType(this)">
				<option value="1"' . ($pcbe60070 ? ' selected' : '') . '>Html</option>
				<option value="2"' . ($v90a480265d ? ' selected' : '') . '>Module</option>
				<option value="4"' . ($v522e51ac5f ? ' selected' : '') . '>View</option>
			</select>
			<select class="block_options ' . ($v7fa8301bf0 ? '' : ' hidden') . '" onChange="onChangeRegionBlock(this)">
				<option class="loading" value="-1" disabled>Loading...</option>
				<option value=""></option>'; if ($v5aaf0d3496) foreach ($v5aaf0d3496 as $v29e5d6d712 => $v9158f5ed68) { $pf8ed4912 .= '<optgroup label="' . $v29e5d6d712 . '">'; if ($v9158f5ed68) { $pc37695cb = count($v9158f5ed68); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $peebaaf55 = $v9158f5ed68[$v43dd7d0051]; $v291201ba48 = strpos($peebaaf55, "\n") || strip_tags($peebaaf55) != $peebaaf55; if (!$v291201ba48) $pf8ed4912 .= '<option value="' . $peebaaf55 . '"' . ($v90a480265d && $peebaaf55 == $v56b1e1a2b7 && $v29e5d6d712 == $pc611e727 ? ' selected' : '') . ' project="' . $v29e5d6d712 . '">' . $peebaaf55 . '</option>'; } } $pf8ed4912 .= '</optgroup>'; } $pf8ed4912 .= '</select>
			<input class="block' . ($pe7eba739 || $pada21496 ? '' : ' hidden') . '" type="text" value="' . (!$peb283674 ? $v56b1e1a2b7 : '') . '" onBlur="onBlurRegionBlock(this)" />
			
			<select class="region_block_type invisible' . ($pcbe60070 ? ' hidden' : '') . '" onChange="onChangeRegionBlockType(this)">
				<option value=""' . ($pe7eba739 && !$pade4502c ? ' selected' : '') . '>default</option>
				<option' . ($pade4502c ? ' selected' : '') . '>string</option>
				<option' . ($peb283674 ? ' selected' : '') . '>text</option>
				<option' . ($pada21496 ? ' selected' : '') . '>variable</option>
				<option' . ($v7fa8301bf0 ? ' selected' : '') . '>options</option>
			</select>
			
			<span class="icon delete invisible" onClick="deleteRegionBlock(this)" title="Remove this region-block">Remove</span>
			<span class="icon add invisible" onClick="addRepeatedRegionBlock(this)" title="Add new block for region: ' . $v7a1b9c07b3 . '">Add</span>
			<span class="icon up invisible" onClick="moveUpRegionBlock(this)" title="Move up this region-block">Move up</span>
			<span class="icon down invisible" onClick="moveDownRegionBlock(this)" title="Move down this region-block">Move down</span>
			<span class="icon edit invisible" onClick="editRegionBlock(this)" title="Edit this block">Edit</span>
			'; $pf8ed4912 .= '
			<div class="block_text' . (!$peb283674 ? ' hidden' : '') . '"><textarea onBlur="onBlurRegionBlock(this)">' . htmlspecialchars(stripslashes(substr($paa7b7454, 0, 1) == '"' && substr($paa7b7454, -1) == '"' ? substr($paa7b7454, 1, -1) : $paa7b7454)) . '</textarea></div>
			
			<div class="block_html editor' . (!$pcbe60070 ? ' hidden' : '') . '"><textarea onBlur="onBlurRegionBlock(this)">' . ($pcbe60070 ? htmlspecialchars($paa7b7454) : "") . '</textarea></div>'; $pf8ed4912 .= '<div class="block_params' . (!$v90a480265d ? ' hidden' : '') . '">'; if ($v23caa16bce) { $pc37695cb = count($v23caa16bce); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v9acf40c110 = $v23caa16bce[$v43dd7d0051]; $pf8ed4912 .= self::getBlockParamHtml($v9acf40c110, $v36aefa195e[$v9acf40c110]); } } $pf8ed4912 .= '</div>
		</div>'; return $pf8ed4912; } public static function getBlockParamHtml($v58b61e02bc, $v67db1bd535) { $v9acf40c110 = substr($v58b61e02bc, 0, 1) == '"' ? str_replace('\\"', '"', substr($v58b61e02bc, 1, -1)) : $v58b61e02bc; $v956913c90f = substr($v67db1bd535, 0, 1) == '"' ? str_replace('\\"', '"', substr($v67db1bd535, 1, -1)) : $v67db1bd535; $v58b61e02bc = str_replace('"', "&quot;", $v58b61e02bc); return '<div class="block_param" param="' . $v58b61e02bc . '">
			<label title="' . str_replace('"', "&quot;", $v9acf40c110) . '">' . $v9acf40c110 . ':</label>
			<input class="block_param_name" type="hidden" value="' . $v58b61e02bc . '" />
			<input class="block_param_value' . (strpos($v67db1bd535, "\n") !== false ? ' hidden' : '') . '" type="text" value="' . str_replace('"', "&quot;", $v956913c90f) . '" onBlur="onBlurRegionBlockParam(this)" />
			<select onChange="onChangeRegionBlockParamType(this)">
				<option value="">default</option>
				<option' . (strpos($v67db1bd535, "\n") === false && (substr($v67db1bd535, 0, 1) == '"' || !strlen($v67db1bd535)) ? ' selected' : '') . '>string</option>
				<option' . (strpos($v67db1bd535, "\n") !== false ? ' selected' : '') . '>text</option>
				<option' . (strpos($v67db1bd535, "\n") === false && substr($v67db1bd535, 0, 1) == '$' && strpos($v67db1bd535, "->") === false ? ' selected' : '') . '>variable</option>
			</select>
			<span class="icon search search_page" onclick="onPresentationIncludePageUrlTaskChooseFile(this)" title="Choose a page url">Search Page</span>
			<span class="icon search search_image" onclick="onPresentationIncludeImageUrlTaskChooseFile(this)" title="Choose an image url">Search Image</span>
			<span class="icon add_variable search_variable" onclick="onPresentationProgrammingTaskChooseCreatedVariable(this)" title="Choose a variable">Search Variable</span>
			<div class="block_param_text' . (strpos($v67db1bd535, "\n") === false ? ' hidden' : '') . '"><textarea onBlur="onBlurRegionBlockParam(this)">' . htmlspecialchars($v956913c90f, ENT_NOQUOTES) . '</textarea></div>
		</div>'; } public static function getIncludeHtml($v154d33eec4, $v311012acc5) { $v52679883a6 = substr($v154d33eec4, 0, 1) == '"' ? str_replace('\\"', '"', substr($v154d33eec4, 1, -1)) : $v154d33eec4; return '<div class="item">
			<input class="path" type="text" value="' . str_replace('"', "&quot;", $v52679883a6) . '" onBlur="onBlurInclude(this)" />
			<select onchange="onChangeIncludeType(this)">
				<option value="">default</option>
				<option' . (substr($v154d33eec4, 0, 1) == '"' || !strlen($v154d33eec4) ? ' selected' : '') . '>string</option>
				<option' . (substr($v154d33eec4, 0, 1) == '$' && strpos($v154d33eec4, "->") === false ? ' selected' : '') . '>variable</option>
			</select>
			<input class="once" type="checkbox" value="1"' . ($v311012acc5 ? ' checked' : '') . ' title="Check here to active the include ONCE feature" onchange="onChangeIncludeOnce(this)" />
			<span class="icon search" onClick="onPresentationIncludeTaskChoosePage(this)" title="Choose a file to include">Search</span>
			<span class="icon delete" onClick="removeInclude(this)">Remove</span>
		</div>'; } public static function getDefinedTemplateParamHtml($v58b61e02bc) { $v9acf40c110 = substr($v58b61e02bc, 0, 1) == '"' ? str_replace('\\"', '"', substr($v58b61e02bc, 1, -1)) : $v58b61e02bc; return '<div class="item">' . $v9acf40c110 . '</div>'; } public static function getTemplateParamHtml($v58b61e02bc, $v67db1bd535, $pd6bf10d2 = null) { $v89d55da87e = !isset($v67db1bd535); $v6f9f8bfc01 = isset($pd6bf10d2); $v9acf40c110 = substr($v58b61e02bc, 0, 1) == '"' ? str_replace('\\"', '"', substr($v58b61e02bc, 1, -1)) : $v58b61e02bc; $v956913c90f = substr($v67db1bd535, 0, 1) == '"' ? str_replace('\\"', '"', substr($v67db1bd535, 1, -1)) : $v67db1bd535; $v8e481289c2 = preg_match("/^(is_|are_)/", $v9acf40c110); if ($v67db1bd535 === '"1"' || $v67db1bd535 === "1" || $v67db1bd535 === 1 || $v67db1bd535 === "true") $v67db1bd535 = true; else if ($v67db1bd535 === '"0"' || $v67db1bd535 === "0" || $v67db1bd535 === 0 || $v67db1bd535 === "false" || (!strlen($v956913c90f) && $v8e481289c2)) $v67db1bd535 = false; $v9a4276cc0c = is_bool($v67db1bd535); $peb283674 = !$v9a4276cc0c && strpos($v67db1bd535, "\n") !== false; $v58b61e02bc = str_replace('"', "&quot;", $v58b61e02bc); if ($v9a4276cc0c) $v956913c90f = $v67db1bd535 ? 1 : 0; if ($v8e481289c2) $v9acf40c110 = ucwords(str_replace("_", " ", $v9acf40c110)); return '<div class="item' . ($v89d55da87e ? " inactive" : "") . ($v9a4276cc0c ? " boolean" : "") . '">
			<input class="template_param_active" type="checkbox" onChange="onActivateTemplateParam(this)" ' . ($v89d55da87e ? "" : "checked") . '/>
			<label title="' . str_replace('"', "&quot;", $v9acf40c110) . '">' . $v9acf40c110 . ($v8e481289c2 ? "?" : ":") . '</label>
			<input class="template_param_name" type="hidden" value="' . $v58b61e02bc . '"' . ($v89d55da87e ? " disabled" : "") . ' />
			<input class="template_param_value' . ($peb283674 ? ' hidden' : '') . '" type="' . ($v9a4276cc0c ? "checkbox" : "text") . '" value="' . str_replace('"', "&quot;", $v956913c90f) . '" ' . ($v9a4276cc0c ? "onChange" : "onBlur") . '="onBlurTemplateParam(this)"' . ($v89d55da87e ? " disabled" : "") . ($v9a4276cc0c && $v67db1bd535 ? " checked" : "") . ($v6f9f8bfc01 ? ' placeHolder="' . str_replace('"', "&quot;", $pd6bf10d2) . '"' : "") . ' />
			<select onChange="onChangeTemplateParamType(this)"' . ($v89d55da87e ? " disabled" : "") . '>
				<option value="">default</option>
				<option' . (!$peb283674 && (substr($v67db1bd535, 0, 1) == '"' || !strlen($v956913c90f)) ? ' selected' : '') . '>string</option>
				<option' . ($peb283674 ? ' selected' : '') . '>text</option>
				<option' . (!$peb283674 && substr($v67db1bd535, 0, 1) == '$' && strpos($v67db1bd535, "->") === false ? ' selected' : '') . '>variable</option>
				<option' . ($v9a4276cc0c ? ' selected' : '') . '>boolean</option>
			</select>
			<span class="icon search search_page" onclick="onPresentationIncludePageUrlTaskChooseFile(this)" title="Choose a page url">Search Page</span>
			<span class="icon search search_image" onclick="onPresentationIncludeImageUrlTaskChooseFile(this)" title="Choose an image url">Search Image</span>
			<span class="icon add_variable search_variable" onclick="onPresentationProgrammingTaskChooseCreatedVariable(this)" title="Choose a variable">Search Variable</span>
			<input type="color" class="color-selector" title="Choose a color" onInput="onPresentationChooseColor(this)">
			<span class="icon delete" onClick="removeTemplateParam(this);">Remove</span>
			<div class="template_param_text' . (!$peb283674 ? ' hidden' : '') . '"><textarea onBlur="onBlurTemplateParam(this)"' . ($v89d55da87e ? " disabled" : "") . '>' . htmlspecialchars($v956913c90f, ENT_NOQUOTES) . '</textarea></div>
		</div>'; } public static function getTabContentTemplateLayoutIframeToolbarContentsHtml() { $pf8ed4912 = '
			<i class="icon desktop active" data-title="Show in Desktop" onClick="onChangeTemplateLayoutScreenToDesktop(this)"></i>
			<i class="icon mobile" data-title="Show in Mobile" onClick="onChangeTemplateLayoutScreenToMobile(this)"></i>
			<input class="width" title="Screen Width" value="300" maxlength="4" onKeyUp="onChangeTemplateLayoutScreenSize(this)">
			<span class="px">px</span>
			<span class="x"> x </span>
			<input class="height" title="Screen Height" value="300" maxlength="4" onKeyUp="onChangeTemplateLayoutScreenSize(this)">
			<span class="px">px</span>
			<input type="checkbox" class="fit_to_screen" data-title="Fit dimensions to screen" onChange="onChangeTemplateLayoutScreenSize(this)" checked />'; return $pf8ed4912; } } ?>
