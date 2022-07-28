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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); include_once $EVC->getUtilPath("CMSPresentationLayerUIHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $no_php_erros = $_GET["no_php_erros"]; $include_jquery = $_GET["include_jquery"]; $is_edit_template = $_GET["is_edit_template"]; $data = json_decode( htmlspecialchars_decode( file_get_contents("php://input"), ENT_NOQUOTES), true); $html_to_parse = $data["html_to_parse"]; $template = $data["template"] ? $data["template"] : $_GET["template"]; $path = str_replace("../", "", $path); if ($path && $template) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { unset($data["html_to_parse"]); $json_data = json_encode($data); $get_page_block_simulated_html_url = "{$project_url_prefix}phpframework/presentation/get_page_block_simulated_html?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&project=#project#&block=#block#"; $save_page_block_simulated_html_setting_url = "{$project_url_prefix}phpframework/presentation/save_page_block_simulated_html_setting?bean_name=$bean_name&bean_file_name=$bean_file_name&project=#project#&block=#block#"; $html = f1f204a3d16($PEVC, $user_global_variables_file_path, $template, $data, $html_to_parse, $no_php_erros, $include_jquery, $is_edit_template, $project_url_prefix, $project_common_url_prefix, $get_page_block_simulated_html_url, $save_page_block_simulated_html_setting_url); } else { launch_exception(new Exception("PEVC doesn't exists!")); die(); } } else if (!$path) { launch_exception(new Exception("Undefined path!")); die(); } else { launch_exception(new Exception("Undefined template!")); die(); } function f1f204a3d16($EVC, $v3d55458bcd, $pe7333513, $v539082ff30, $v708ac23d1c, $v17cd69faa1, $v5cd161b785, $v5bfcf1f35d, $pfde6442c, $v5f6585d5af, $v6d68dcb64c, $v511197c8e2) { $v651d63364b = $v539082ff30["template_regions"]; $v9e3513bc0e = $v539082ff30["template_params"]; $v66a6023e1e = $v539082ff30["template_includes"]; $v0febf893c5 = $v539082ff30["is_external_template"]; $pb34c57d8 = $v539082ff30["external_template_params"]; $pfaf08f23 = new PHPVariablesFileHandler(array($v3d55458bcd, $EVC->getConfigPath("pre_init_config"))); $pfaf08f23->startUserGlobalVariables(); if ($v708ac23d1c) $pf8ed4912 = $v708ac23d1c; else { $pf8ed4912 = CMSPresentationLayerHandler::getSetTemplateCode($EVC, $v0febf893c5, $pe7333513, $pb34c57d8, $v66a6023e1e); if (!$pf8ed4912 && !$v0febf893c5) { $v8ea5ee2b30 = $EVC->getTemplatePath($pe7333513); if (!file_exists($v8ea5ee2b30)) { launch_exception(new Exception("Template doesn't exists!")); die(); } else if ($v5bfcf1f35d) $pf8ed4912 = '<!DOCTYPE html><html><head></head><body></body></html>'; } } if ($pf8ed4912) { include $EVC->getConfigPath("config"); @include_once $EVC->getModulePath("translator/include_text_translator_handler", $EVC->getCommonProjectName()); $pf8ed4912 = PHPCodePrintingHandler::getCodeWithoutComments($pf8ed4912); $pf8ed4912 = removeSequentialLogicalActivitiesPHPCode($pf8ed4912); if ($v66a6023e1e) { $pd6dd936f = "<?"; foreach ($v66a6023e1e as $pc24afc88) if ($pc24afc88["path"]) $pd6dd936f .= "\n" . "include" . ($pc24afc88["once"] ? "_once" : "") . " " . $pc24afc88["path"] . ";"; $pd6dd936f .= "\n?>\n"; $pf8ed4912 = $pd6dd936f . $pf8ed4912; } $v90717bbaed = WorkFlowPresentationHandler::getHtmlTagProps($pf8ed4912, "body", array("get_inline_code" => true)); $v222bf3e7cb = $v90717bbaed["inline_code"]; $v49843a0815 = CMSFileHandler::getHardCodedRegionsBlocks($v222bf3e7cb); $v384ae1b367 = "_xxx_" . rand(0, 10000); $v1e709b8e88 = prepareEditableTemplate($EVC, $v222bf3e7cb, $v651d63364b, $v9e3513bc0e, $v384ae1b367, $v5bfcf1f35d); $pe11b912a = WorkFlowPresentationHandler::getHtmlTagProps($pf8ed4912, "head", array("get_inline_code" => true)); $pe898d4ab = $pe11b912a["inline_code"]; $v323e3a6fd5 = $v708ac23d1c ? getHeadHtmlPHPCode($EVC, $pe898d4ab, $v651d63364b, $v9e3513bc0e) : ""; if ($v5bfcf1f35d) { $v0ef25d9f08 = replacePHPWithComments($pe898d4ab, $v384ae1b367, true); $v0ef25d9f08 = $v323e3a6fd5 ? $v323e3a6fd5 . $v0ef25d9f08 : $v0ef25d9f08; } else if ($v323e3a6fd5) $v0ef25d9f08 = $v323e3a6fd5 . $pe898d4ab; else $v0ef25d9f08 = $pe898d4ab; $pf8ed4912 = str_replace($pe898d4ab, $v0ef25d9f08, $pf8ed4912); if ($v5bfcf1f35d) $v1e709b8e88 = replacePHPWithComments($v1e709b8e88, $v384ae1b367, false, $v49843a0815); $pf8ed4912 = str_replace($v222bf3e7cb, $v1e709b8e88, $pf8ed4912); $v37f1176ca4 = tmpfile(); $v32449e14b2 = stream_get_meta_data($v37f1176ca4); $v4e03b5e19e = $v32449e14b2['uri']; $pc3772d0d = str_split($pf8ed4912, 1024 * 4); foreach ($pc3772d0d as $v306839072f) fwrite($v37f1176ca4, $v306839072f, strlen($v306839072f)); $pf0f58138 = error_reporting(); ob_start(); if ($v17cd69faa1) error_reporting(0); include $v4e03b5e19e; if ($v17cd69faa1) error_reporting($pf0f58138); $pf8ed4912 = ob_get_contents(); ob_end_clean(); fclose($v37f1176ca4); if ($v5bfcf1f35d) $pf8ed4912 = replaceCommentsWithPHP($pf8ed4912, $v384ae1b367); $v31199c28eb = ''; if (stripos($pf8ed4912, "jquery") === false || $v5cd161b785) $v31199c28eb .= '<script class="layout-ui-editor-reserved" type="text/javascript" src="' . $v5f6585d5af . 'vendor/jquery/js/jquery-1.8.1.min.js"></script>'; else $v31199c28eb .= '
		<script class="layout-ui-editor-reserved">
		if(!window.jQuery) {
		   var win = this;
		   var doc = win.document;
		   doc.write(\'<\' + \'script class="layout-ui-editor-reserved" type="text/javascript" src="' . $v5f6585d5af . 'vendor/jquery/js/jquery-1.8.1.min.js"><\' + \'/script>\');
		   
		   //cannot use the location anymore, bc the document.location is now the same than the parent.location because we are using an ajax request to get this file.
		   /*if (win.location != win.parent.location) {
		     var url = "" + doc.location;
		     url += (url.indexOf("?") != -1 ? "&" : "?") + "include_jquery=1";
		     document.location = url;
		   }
		   else
		   	doc.write(\'<\' + \'script class="layout-ui-editor-reserved" type="text/javascript" src="' . $v5f6585d5af . 'vendor/jquery/js/jquery-1.8.1.min.js"><\' + \'/script>\');*/
		}
		</script>'; $v31199c28eb .= '
		<!--layout-ui-editor-reserved: Global script with some native javascript functions -->
		<script class="layout-ui-editor-reserved" src="' . $v5f6585d5af . 'js/global.js"></script>
		
		<!--layout-ui-editor-reserved: Layout UI Editor - Add ACE-Editor -->
		<script class="layout-ui-editor-reserved">
	   		var head = document.querySelector("head");
	   		var head_old_nodes = Array.from(head.childNodes);
	   	</script>
		<script class="layout-ui-editor-reserved" type="text/javascript" src="' . $v5f6585d5af . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
		<script class="layout-ui-editor-reserved" type="text/javascript" src="' . $v5f6585d5af . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>
	   	<script class="layout-ui-editor-reserved">
	   		//Bc the ace editor adds some styles to the head, we need to have this code here
	   		var head_new_nodes = Array.from(head.childNodes);
	   		var length = head_new_nodes.length;
	   		
	   		for (var i = 0; i < length; i++) {
	   			var node = head_new_nodes[i];
	   			
	   			if (node.nodeType == Node.ELEMENT_NODE && head_old_nodes.indexOf(node) == -1)
	   				node.classList.add("layout-ui-editor-reserved");
	   		}
	   	</script>
	   	
		<!--layout-ui-editor-reserved: Add Fontawsome Icons CSS -->
		<link class="layout-ui-editor-reserved" rel="stylesheet" href="' . $v5f6585d5af . 'vendor/fontawesome/css/all.min.css">
		
		<!--layout-ui-editor-reserved: Add Icons CSS -->
		<link class="layout-ui-editor-reserved" rel="stylesheet" href="' . $pfde6442c . 'css/icons.css">
		
		<!--layout-ui-editor-reserved: Add Simple Template Layout CSS and JS Files  -->
		<link class="layout-ui-editor-reserved" rel="stylesheet" href="' . $pfde6442c . 'css/presentation/edit_simple_template_layout.css" type="text/css" charset="utf-8" />
		<script class="layout-ui-editor-reserved" language="javascript" type="text/javascript" src="' . $pfde6442c . 'js/presentation/edit_simple_template_layout.js"></script>
		
		<script class="layout-ui-editor-reserved">
			var selected_project_id = \'' . $EVC->getpresentationLayer()->getSelectedPresentationId() . '\';
			var system_get_page_block_simulated_html_data = ' . json_encode($v539082ff30) . ';
			var system_get_page_block_simulated_html_url = \'' . $v6d68dcb64c . '\';
			var system_save_page_block_simulated_html_setting_url = \'' . $v511197c8e2 . '\';
		</script>'; if ($v5bfcf1f35d && preg_match("/<body([^>]*)>/i", $pf8ed4912, $v6107abf109, PREG_OFFSET_CAPTURE) !== false) { $v327f72fb62 = $v6107abf109[0][0]; $v8fa21f7d4d = null; if (preg_match("/(\s)class(\s*)=(\s*)\"([^\"]*)\"/i", $v327f72fb62, $pabe9f1e0, PREG_OFFSET_CAPTURE)) $v8fa21f7d4d = '"'; else if (preg_match("/(\s)class(\s*)=(\s*)'([^']*)'/i", $v327f72fb62, $pabe9f1e0, PREG_OFFSET_CAPTURE)) $v8fa21f7d4d = "'"; else if (preg_match("/(\s)class(\s*)=(\s*)([0-9a-z_\-]+)/i", $v327f72fb62, $pabe9f1e0, PREG_OFFSET_CAPTURE)) $v8fa21f7d4d = ""; if ($pabe9f1e0 && $pabe9f1e0[0]) { $v68141e6400 = $pabe9f1e0[0][0]; $v060d772180 = substr($v68141e6400, 0, -1) . " droppable main-droppable" . $v8fa21f7d4d; if (!$v8fa21f7d4d) $v060d772180 = preg_replace("/(\s)class(\s*)=(\s*)([0-9a-z_\-]+)/i", '$1class$2=$3"$4 droppable main-droppable"', $v68141e6400); $v91a962d917 = str_replace($v68141e6400, $v060d772180, $v327f72fb62); } else $v91a962d917 = substr($v327f72fb62, 0, -1) . ' class="droppable main-droppable">'; $pf8ed4912 = str_replace($v327f72fb62, $v91a962d917, $pf8ed4912); } if (preg_match("/<\/body([^>]*)>/i", $pf8ed4912, $v6107abf109, PREG_OFFSET_CAPTURE) !== false) $pf8ed4912 = str_ireplace($v6107abf109[0][0], $v31199c28eb. $v6107abf109[0][0], $pf8ed4912); else $pf8ed4912 .= $v31199c28eb; $v28f58fc66d = '<script class="layout-ui-editor-reserved">
window.onerror = function(msg, url, line, col, error) {
	' . ($v5bfcf1f35d ? 'if (window.parent && window.parent.$) window.parent.$(".template_loaded_with_errors").removeClass("hidden");' : '') . '
	
	if (console && console.log)
		console.log("[edit_page_and_template.js:reloadLayoutIframeFromSettings()] Layout Iframe error:" + "\n- message: " + msg + "\n- line " + line + "\n- column " + col + "\n- url: " + url + "\n- error: " + error);
	
	return true; //return true, avoids the error to be shown and other scripts to stop.
};
</script>'; if (preg_match("/<head([^>]*)>/i", $pf8ed4912, $v6107abf109, PREG_OFFSET_CAPTURE) !== false) $pf8ed4912 = str_ireplace($v6107abf109[0][0], $v6107abf109[0][0] . $v28f58fc66d, $pf8ed4912); else $pf8ed4912 = $v28f58fc66d . $pf8ed4912; header_remove(); if (substr("" . http_response_code(), 0, 1) != "2") http_response_code(200); } $pfaf08f23->endUserGlobalVariables(); return $pf8ed4912; } function replaceCommentsWithPHP($pf8ed4912, $v384ae1b367) { return str_replace('<!--?' . $v384ae1b367, '<?', str_replace($v384ae1b367 . '?-->', '?>', $pf8ed4912)); } function replacePHPWithComments($pf8ed4912, $v384ae1b367, $v5b903b30bd = false, $v49843a0815 = false) { $pdda5eb2d = TextSanitizer::mbStrSplit($pf8ed4912); $pe2ae3be9 = count($pdda5eb2d); $v058209df1f = ""; $v280cab9580 = ""; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051++) { $v9a8b7dc209 = $pdda5eb2d[$v43dd7d0051]; if ($v9a8b7dc209 == "<") { $pbdb99a55 = $pdda5eb2d[$v43dd7d0051 + 1]; if ($pbdb99a55 == "?") { $v1ba9437a9c = $pd5cb1082 = false; $v89ecd5a9ff = $v9a8b7dc209 . $pbdb99a55; for ($v9d27441e80 = $v43dd7d0051 + 2; $v9d27441e80 < $pe2ae3be9; $v9d27441e80++) { $v0eb4668863 = $pdda5eb2d[$v9d27441e80]; if ($v0eb4668863 == '"' && !$pd5cb1082 && !TextSanitizer::isMBCharEscaped($pf8ed4912, $v9d27441e80, $pdda5eb2d)) $v1ba9437a9c = !$v1ba9437a9c; else if ($v0eb4668863 == "'" && !$v1ba9437a9c && !TextSanitizer::isMBCharEscaped($pf8ed4912, $v9d27441e80, $pdda5eb2d)) $pd5cb1082 = !$pd5cb1082; else if ($v0eb4668863 == "?" && $pdda5eb2d[$v9d27441e80 + 1] == ">" && !$v1ba9437a9c && !$pd5cb1082) { $v89ecd5a9ff .= $v0eb4668863 . $pdda5eb2d[$v9d27441e80 + 1]; break; } $v89ecd5a9ff .= $v0eb4668863; } $v43dd7d0051 = $v9d27441e80 + 1; $v929618aaf7 = strpos($v89ecd5a9ff, '<div class="template_region"') !== false; $v541d633194 = preg_match('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->renderRegion\(/u', $v89ecd5a9ff); $v1d0761458b = preg_match('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->getParam\(/u', $v89ecd5a9ff); $pbf9e82d3 = strpos($v89ecd5a9ff, ' . "' . $v384ae1b367 . '"') !== false; if (!$v929618aaf7 && !$pbf9e82d3 && !$v541d633194 && !$v1d0761458b ) { if ($v5b903b30bd) $v9a8b7dc209 = $v89ecd5a9ff; else { $v89ecd5a9ff = replacePHPWithCommentsWithHardCodedBlocks($v89ecd5a9ff, $v384ae1b367, $v49843a0815); $v9a8b7dc209 = str_replace('<?', '<!--?' . $v384ae1b367, str_replace('?>', $v384ae1b367 . '?-->', $v89ecd5a9ff)); } } else if (!$pbf9e82d3 && ($v541d633194 || $v1d0761458b)) { if ($v5b903b30bd) { $v9a8b7dc209 = $v89ecd5a9ff; $v280cab9580 .= "\n\t" . str_replace('<?', '<!--?', str_replace('?>', '?-->', $v89ecd5a9ff)); } else $v9a8b7dc209 = str_replace('<?', '<!--?' . $v384ae1b367, str_replace('?>', $v384ae1b367 . '?-->', $v89ecd5a9ff)); } else $v9a8b7dc209 = $v89ecd5a9ff; } } $v058209df1f .= $v9a8b7dc209; } return $v058209df1f . $v280cab9580; } function replacePHPWithCommentsWithHardCodedBlocks($v89ecd5a9ff, $v384ae1b367, $v49843a0815) { if ($v49843a0815) foreach ($v49843a0815 as $v7aeaf992f5) { $v87ae7286da = $v7aeaf992f5["match"]; if ($v87ae7286da && strpos($v89ecd5a9ff, $v87ae7286da) !== false) { $peebaaf55 = $v7aeaf992f5["block"]; $pa7fd620a = $v7aeaf992f5["block_type"]; $v175e16d850 = $v7aeaf992f5["block_project"]; $v76425220d5 = $v7aeaf992f5["block_project_type"]; $pc427c92a = PHPUICodeExpressionHandler::getArgumentCode($peebaaf55, $pa7fd620a); $v68439d9732 = PHPUICodeExpressionHandler::getArgumentCode($v175e16d850, $v76425220d5); $v89ecd5a9ff = preg_replace('/\$block_local_variables\s*=\s*array\s*\(\s*\)\s*;/', '', $v89ecd5a9ff); $v89ecd5a9ff = preg_replace('/include \$EVC->getBlockPath\(\s*' . preg_quote($pc427c92a, "/") . ($v68439d9732 ? "\s*,\s*" . preg_quote($v68439d9732, "/") : "") . '\s*\);/', '', $v89ecd5a9ff); if (!$v175e16d850) $v89ecd5a9ff = preg_replace('/include \$EVC->getBlockPath\(\s*' . preg_quote($pc427c92a, "/") . '\s*\);/', '', $v89ecd5a9ff); $v91a962d917 = "#hard_coded_html_$v384ae1b367#"; $pbdc517d2 = '""; ?>'; $v4d79853eb7 = '<? echo ""'; $v89ecd5a9ff = str_replace($v87ae7286da, $pbdc517d2 . $v91a962d917 . $v4d79853eb7, $v89ecd5a9ff); $v89ecd5a9ff = preg_replace('/\s*echo\s*""\s*;/', "", $v89ecd5a9ff); $v89ecd5a9ff = preg_replace('/<?\s*echo\s*""\s*\?>/', "", $v89ecd5a9ff); $v89ecd5a9ff = preg_replace('/\s*\?>/', " ?>", $v89ecd5a9ff); $v89ecd5a9ff = preg_replace('/<\?(php|=|)\s*\?>/', "", $v89ecd5a9ff); $pd01e30e3 = '
<div class="template_block_item">
	<div class="template_block_item_header">
		Call block <span class="block_name">' . $pc427c92a . '</span><span class="block_project"> in "<span>' . ($v175e16d850 ? $v68439d9732 : "") . '</span>" project.</span>
	</div>
	
	<input class="block hidden" type="text" value="' . $peebaaf55 . '" />
	<select class="region_block_type hidden">
		<option value>default</option>
		<option' . ($pa7fd620a == "string" ? " selected" : "") . '>string</option>
		<option' . ($pa7fd620a == "variable" ? " selected" : "") . '>variable</option>
	</select>
	<div class="block_simulated_html"></div>
</div>'; $v89ecd5a9ff = str_replace($v91a962d917, $pd01e30e3, $v89ecd5a9ff); } } return $v89ecd5a9ff; } function removeSequentialLogicalActivitiesPHPCode($pf8ed4912) { $pdda5eb2d = TextSanitizer::mbStrSplit($pf8ed4912); $pe2ae3be9 = count($pdda5eb2d); $v3ff757a876 = $pf8ed4912; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051++) { $v9a8b7dc209 = $pdda5eb2d[$v43dd7d0051]; if ($v9a8b7dc209 == "<") { $pbdb99a55 = $pdda5eb2d[$v43dd7d0051 + 1]; if ($pbdb99a55 == "?") { $v1ba9437a9c = $pd5cb1082 = false; $pf07e7d8a = $v9a8b7dc209 . $pbdb99a55; for ($v9d27441e80 = $v43dd7d0051 + 2; $v9d27441e80 < $pe2ae3be9; $v9d27441e80++) { $v0eb4668863 = $pdda5eb2d[$v9d27441e80]; if ($v0eb4668863 == '"' && !$pd5cb1082 && !TextSanitizer::isMBCharEscaped($pf8ed4912, $v9d27441e80, $pdda5eb2d)) $v1ba9437a9c = !$v1ba9437a9c; else if ($v0eb4668863 == "'" && !$v1ba9437a9c && !TextSanitizer::isMBCharEscaped($pf8ed4912, $v9d27441e80, $pdda5eb2d)) $pd5cb1082 = !$pd5cb1082; else if ($v0eb4668863 == "?" && $pdda5eb2d[$v9d27441e80 + 1] == ">" && !$v1ba9437a9c && !$pd5cb1082) { $pf07e7d8a .= $v0eb4668863 . $pdda5eb2d[$v9d27441e80 + 1]; break; } $pf07e7d8a .= $v0eb4668863; } $v6b912e5f4e = preg_replace("/^<\?(=|php|)/i", "", $pf07e7d8a); $v6b912e5f4e = preg_replace("/\?>$/i", "", $v6b912e5f4e); $pc3cc7ced = $v6b912e5f4e; while (preg_match("/\s*\->\s*setSequentialLogicalActivities\s*\(/", $v6b912e5f4e, $pbae7526c, PREG_OFFSET_CAPTURE) && $pbae7526c && $pbae7526c[0]) { $v327f72fb62 = $pbae7526c[0][0]; $pac65f06f =$pbae7526c[0][1]; $v5c74807c6a = $pd2282eb2 = null; $pc186baae = TextSanitizer::mbStrSplit($v6b912e5f4e); $v13a1b63010 = count($pc186baae); $v1ba9437a9c = $pd5cb1082 = false; for ($v9d27441e80 = $pac65f06f - 1; $v9d27441e80 >= 0; $v9d27441e80--) { $v9a8b7dc209 = $pc186baae[$v9d27441e80]; if ($v9a8b7dc209 == '"' && !$pd5cb1082 && !TextSanitizer::isMBCharEscaped($v6b912e5f4e, $v9d27441e80, $pc186baae)) $v1ba9437a9c = !$v1ba9437a9c; else if ($v9a8b7dc209 == "'" && !$v1ba9437a9c && !TextSanitizer::isMBCharEscaped($v6b912e5f4e, $v9d27441e80, $pc186baae)) $pd5cb1082 = !$pd5cb1082; else if ($v9a8b7dc209 == ";" && !$v1ba9437a9c && !$pd5cb1082) { $v5c74807c6a = $v9d27441e80 + 1; break; } } if (!$v5c74807c6a) $v5c74807c6a = 0; $v1ba9437a9c = $pd5cb1082 = false; for ($v9d27441e80 = $pac65f06f + 1; $v9d27441e80 < $v13a1b63010; $v9d27441e80++) { $v9a8b7dc209 = $pc186baae[$v9d27441e80]; if ($v9a8b7dc209 == '"' && !$pd5cb1082 && !TextSanitizer::isMBCharEscaped($v6b912e5f4e, $v9d27441e80, $pc186baae)) $v1ba9437a9c = !$v1ba9437a9c; else if ($v9a8b7dc209 == "'" && !$v1ba9437a9c && !TextSanitizer::isMBCharEscaped($v6b912e5f4e, $v9d27441e80, $pc186baae)) $pd5cb1082 = !$pd5cb1082; else if ($v9a8b7dc209 == ";" && !$v1ba9437a9c && !$pd5cb1082) { $pd2282eb2 = $v9d27441e80 + 1; break; } } if (!$pd2282eb2) $pd2282eb2 = $v13a1b63010; $v6b912e5f4e = substr($v6b912e5f4e, 0, $v5c74807c6a) . substr($v6b912e5f4e, $pd2282eb2); } if (trim($v6b912e5f4e)) $v4568d94dd1 = str_replace($pc3cc7ced, $v6b912e5f4e, $pf07e7d8a); else $v4568d94dd1 = ""; $v3ff757a876 = str_replace($pf07e7d8a, $v4568d94dd1, $v3ff757a876); } } } return $v3ff757a876; } function getHeadHtmlPHPCode($v08d9602741, $pe898d4ab, $v651d63364b, $v9e3513bc0e) { $v9ab35f1f0d = $v08d9602741->getpresentationLayer(); $v2508589a4c = $v9ab35f1f0d->getSelectedPresentationId(); $pee3fb492 = $v9ab35f1f0d->getPresentationFileExtension(); $v86f703e78b = array(); $v9b391a5b1f = array(); if ($v651d63364b) { $v3f8f1acfdb = CMSPresentationLayerHandler::getAvailableRegionsListFromCode($pe898d4ab, $v2508589a4c); $pc37695cb = count($v3f8f1acfdb); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v9b9b8653bc = $v3f8f1acfdb[$v43dd7d0051]; $pfff36d74 = $v651d63364b[$v9b9b8653bc]; if ($pfff36d74) { $v9f714b9298 = count($pfff36d74); for ($v9d27441e80 = 0; $v9d27441e80 < $v9f714b9298; $v9d27441e80++) { $pd06bdde3 = $pfff36d74[$v9d27441e80]; $v9b9b8653bc = $pd06bdde3[0]; $peebaaf55 = $pd06bdde3[1]; $pd6ec966e = $pd06bdde3[2]; $pcbe60070 = $pd06bdde3[3]; $v86f703e78b[] = array( "region" => $v9b9b8653bc, "region_type" => "", "block" => $peebaaf55, "block_type" => "", "block_project" => $pd6ec966e, "block_project_type" => "", "is_html" => $pcbe60070, ); } } } } if ($v9e3513bc0e) { $pf101a896 = CMSPresentationLayerHandler::getAvailableTemplateParamsListFromCode($pe898d4ab); $v3fd37663c7 = $pf101a896[0]; $pc37695cb = count($v3fd37663c7); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v67ccb03f4c = $v3fd37663c7[$v43dd7d0051]; if (array_key_exists($v67ccb03f4c, $v9e3513bc0e)) { $v72eb975550 = $v9e3513bc0e[$v67ccb03f4c]; $v9b391a5b1f[] = array( "param" => $v67ccb03f4c, "param_type" => "", "value" => $v72eb975550, "value_type" => "", ); } } } $pdc3a0007 = CMSPresentationLayerHandler::createCMSLayerCodeForTemplateParams($v9b391a5b1f); $v71ed39ebcc = CMSPresentationLayerHandler::createCMSLayerCodeForRegionsBLocks($v2508589a4c, $pee3fb492, $v86f703e78b); if ($pdc3a0007 || $v71ed39ebcc) { $pf07e7d8a = "<?"; $pf07e7d8a .= trim($pdc3a0007) ? "\n" . $pdc3a0007 : ""; $pf07e7d8a .= trim($v71ed39ebcc) ? "\n" . $v71ed39ebcc : ""; $pf07e7d8a .= "?>"; return $pf07e7d8a; } return ""; } function prepareEditableTemplate($v08d9602741, $pf8ed4912, $v651d63364b, $v9e3513bc0e, $v384ae1b367, $v5bfcf1f35d) { $v2b1e634696 = array(); if ($v651d63364b) foreach ($v651d63364b as $v9bc6a691fa => $pfff36d74) if ($pfff36d74) $v2b1e634696 = array_merge($v2b1e634696, $pfff36d74); $v2508589a4c = $v08d9602741->getPresentationLayer()->getSelectedPresentationId(); $v5aaf0d3496 = CMSPresentationLayerHandler::initBlocksListThroughRegionBlocks($v188b4f5fa6, $v2b1e634696, $v2508589a4c); $pf07e7d8a = "<?"; $pf8ed4912 = preg_replace('/\$block_local_variables\s*=\s*\$region_block_local_variables([^;]+);/', '', $pf8ed4912); $pf8ed4912 = preg_replace('/\$region_block_local_variables\[[^\]]+\]\[[^\]]+\]\[[^\]]+\]\s*=([^;]+);/', '', $pf8ed4912); $pf8ed4912 = preg_replace('/\$region_block_local_variables([^;]+);/', '', $pf8ed4912); $pf8ed4912 = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSJoinPointLayer\(\)->resetRegionBlockJoinPoints\([^\)]*\);/', '', $pf8ed4912); $pf8ed4912 = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->addRegionBlock\([^\)]*\);/', '', $pf8ed4912); $pf8ed4912 = preg_replace('/include \$EVC->getBlockPath\([^\)]*\);/', '', $pf8ed4912); $pf8ed4912 = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->setParam\([^\)]*\);/', '', $pf8ed4912); if ($v651d63364b) foreach ($v651d63364b as $v9bc6a691fa => $pfff36d74) { $v7bfbcea0f1 = getProjectTemplateRegionBlocksHtml($v9bc6a691fa, $pfff36d74, $v5aaf0d3496, $v5bfcf1f35d); $pe1aee0c8 = $v9bc6a691fa . ' . "' . $v384ae1b367 . '"'; $pf07e7d8a .= "\n" . '$EVC->getCMSLayer()->getCMSTemplateLayer()->addRegionHtml(' . $pe1aee0c8 . ', \'' . addcslashes($v7bfbcea0f1, "'") . '\');'; $v02a69d4e0f = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->renderRegion\(' . preg_quote($v9bc6a691fa, "/") . '\)/u', '$EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion(' . $pe1aee0c8 . ')', $pf8ed4912); if ($v02a69d4e0f !== null) $pf8ed4912 = $v02a69d4e0f; } if (!$v5bfcf1f35d && $v9e3513bc0e) foreach ($v9e3513bc0e as $v67ccb03f4c => $v72eb975550) { $pf795164c = $v67ccb03f4c . ' . "' . $v384ae1b367 . '"'; $v72eb975550 = strlen($v72eb975550) ? $v72eb975550 : "''"; $pf07e7d8a .= "\n" . '$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam(' . $pf795164c . ', ' . $v72eb975550 . ');'; $v02a69d4e0f = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->getParam\(' . preg_quote($v67ccb03f4c, "/") . '\)/u', '$EVC->getCMSLayer()->getCMSTemplateLayer()->getParam(' . $pf795164c . ')', $pf8ed4912); if ($v02a69d4e0f !== null) $pf8ed4912 = $v02a69d4e0f; } $pf07e7d8a .= "\n?>"; return $pf07e7d8a . $pf8ed4912; } function prepareEditableTemplate2($v08d9602741, $pf8ed4912, $v651d63364b, $v9e3513bc0e, $v384ae1b367, $v5bfcf1f35d) { $v2508589a4c = $v08d9602741->getPresentationLayer()->getSelectedPresentationId(); $v5aaf0d3496 = CMSPresentationLayerHandler::getAvailableBlocksList($v08d9602741, $v2508589a4c); if ($v651d63364b) foreach ($v651d63364b as $pe5c5e2fe => $v956913c90f) $pf8ed4912 = prepareProjectTemplateRegionHtml($pe5c5e2fe, $v956913c90f, $pf8ed4912, $v5aaf0d3496, $v5bfcf1f35d); if (!$v5bfcf1f35d && $v9e3513bc0e) foreach ($v9e3513bc0e as $pe5c5e2fe => $v956913c90f) $pf8ed4912 = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->getParam\(' . preg_quote($pe5c5e2fe, "/") . '\)/u', '"Param: ' . addcslashes($pe5c5e2fe, '"') . '"', $pf8ed4912); return $pf8ed4912; } function prepareProjectTemplateRegionHtml($v9bc6a691fa, $pfff36d74, $pf8ed4912, $v5aaf0d3496, $v5bfcf1f35d) { $v7bfbcea0f1 = getProjectTemplateRegionBlocksHtml($v9bc6a691fa, $pfff36d74, $v5aaf0d3496, $v5bfcf1f35d); $pbd1bc7b0 = strpos($pf8ed4912, '$EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion(' . $v9bc6a691fa . ')'); $pbd1bc7b0 = strpos($pf8ed4912, '?>', $pbd1bc7b0) + 2; $pf8ed4912 = substr($pf8ed4912, 0, $pbd1bc7b0) . $v7bfbcea0f1 . substr($pf8ed4912, $pbd1bc7b0); $pf8ed4912 = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->renderRegion\(' . preg_quote($v9bc6a691fa, "/") . '\)/u', '""', $pf8ed4912); return $pf8ed4912; } function getProjectTemplateRegionBlocksHtml($v9bc6a691fa, $pfff36d74, $v5aaf0d3496, $v5bfcf1f35d) { $v0ebcb16521 = ""; $v5bd013bcfe = array(); if ($pfff36d74 && is_array($pfff36d74)) { foreach ($pfff36d74 as $pd06bdde3) { $peebaaf55 = $pd06bdde3[1]; if ($peebaaf55) { $pd6ec966e = $pd06bdde3[2]; $pcbe60070 = $pd06bdde3[3]; $pe603f3eb = $pd06bdde3[4]; $v8eaa4de79a = $pcbe60070 ? md5($peebaaf55) : $peebaaf55; if (is_numeric($pe603f3eb) && $pe603f3eb > $v5bd013bcfe["$v9bc6a691fa-$v8eaa4de79a-$pd6ec966e"]) $v5bd013bcfe["$v9bc6a691fa-$v8eaa4de79a-$pd6ec966e"] = $pe603f3eb; } } foreach ($pfff36d74 as $pd06bdde3) { $peebaaf55 = $pd06bdde3[1]; $v7959970a41 = false; if ($peebaaf55) { $pd6ec966e = $pd06bdde3[2]; $pcbe60070 = $pd06bdde3[3]; $pe603f3eb = $pd06bdde3[4]; $v8eaa4de79a = $pcbe60070 ? md5($peebaaf55) : $peebaaf55; if (!is_numeric($pe603f3eb)) { if (isset($v5bd013bcfe["$v9bc6a691fa-$v8eaa4de79a-$pd6ec966e"])) $v5bd013bcfe["$v9bc6a691fa-$v8eaa4de79a-$pd6ec966e"]++; else $v5bd013bcfe["$v9bc6a691fa-$v8eaa4de79a-$pd6ec966e"] = 0; $pe603f3eb = $v5bd013bcfe["$v9bc6a691fa-$v8eaa4de79a-$pd6ec966e"]; } $v14c6c41074 = CMSPresentationLayerUIHandler::getRegionBlockHtml($v9bc6a691fa, $peebaaf55, $pd6ec966e, $pcbe60070, $v5aaf0d3496, array(), array(), $pe603f3eb); if (!$pcbe60070) { $v56b1e1a2b7 = substr($peebaaf55, 0, 1) == '"' ? str_replace('"', '', $peebaaf55) : $peebaaf55; $pc611e727 = substr($pd6ec966e, 0, 1) == '"' ? str_replace('"', '', $pd6ec966e) : $pd6ec966e; $pd45d0d0d = $v5aaf0d3496[$pc611e727]; $v7959970a41 = empty($peebaaf55) || ($pd45d0d0d && in_array($v56b1e1a2b7, $pd45d0d0d)); $v3ae55a9a2e = $v7959970a41 ? " active" : ($v56b1e1a2b7 ? " invalid" : ""); if ($v3ae55a9a2e) $v14c6c41074 = str_replace('<div class="template_region_item', '<div class="template_region_item' . $v3ae55a9a2e, $v14c6c41074); } $v0ebcb16521 .= $v14c6c41074; } } } $v0ebcb16521 = str_replace('<div class="block_html editor', '<div class="block_html', $v0ebcb16521); $v7a1b9c07b3 = str_replace('"', '&quot;', $v9bc6a691fa); return '<div class="template_region" region="' . $v7a1b9c07b3 . '">
			<div class="template_region_name">
				<span class="icon info template_region_name_icon" onClick="openTemplateRegionInfoPopup(this)" title="View region samples">View region samples</span>
				<span class="template_region_name_label">Region ' . $v9bc6a691fa . '.</span>
				<a class="template_region_name_link" href="javascript:void(0)" onClick="addFirstRegionBlock(this)" title="Add a new box so you can choose an existent block file"><i class="icon add"></i> Add Block File</a>
			</div>
			<div class="template_region_items droppable' . ($v5bfcf1f35d ? '' : ' main-droppable" data-main-droppable-name="' . $v7a1b9c07b3) . '">' . $v0ebcb16521 . '</div>
			<div class="template_region_intro">
				<div class="template_region_intro_title">Drag&Drop Widgets Here!</div>
				<div class="template_region_intro_text">Or click in the "Add" button above to add a block file.<br/>Otherwise click me, to edit text...</div>
			</div>
		</div>'; } ?>
