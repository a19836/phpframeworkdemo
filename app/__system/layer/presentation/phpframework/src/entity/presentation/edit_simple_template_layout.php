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

include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); include_once $EVC->getUtilPath("CMSPresentationLayerUIHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $no_php_erros = $_GET["no_php_erros"]; $include_jquery = $_GET["include_jquery"]; $json_data = $_GET["data"]; $data = json_decode($json_data, true); $template = $data["template"] ? $data["template"] : $_GET["template"]; $path = str_replace("../", "", $path); if ($path && $template) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $html_to_parse = htmlspecialchars_decode( file_get_contents("php://input"), ENT_NOQUOTES); $get_page_block_simulated_html_url = "{$project_url_prefix}phpframework/presentation/get_page_block_simulated_html?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&project=#project#&block=#block#&page_region_block_params=#page_region_block_params#&page_region_block_join_points=#page_region_block_join_points#&data=" . urlencode($json_data); $save_page_block_simulated_html_setting_url = "{$project_url_prefix}phpframework/presentation/save_page_block_simulated_html_setting?bean_name=$bean_name&bean_file_name=$bean_file_name&project=#project#&block=#block#"; $html = f1f204a3d16($PEVC, $user_global_variables_file_path, $template, $data, $html_to_parse, $no_php_erros, $include_jquery, $project_url_prefix, $project_common_url_prefix, $get_page_block_simulated_html_url, $save_page_block_simulated_html_setting_url); } else { launch_exception(new Exception("PEVC doesn't exists!")); die(); } } else if (!$path) { launch_exception(new Exception("Undefined path!")); die(); } else { launch_exception(new Exception("Undefined template!")); die(); } function f1f204a3d16($EVC, $v3d55458bcd, $pe7333513, $v539082ff30, $v708ac23d1c, $v17cd69faa1, $v5cd161b785, $pfde6442c, $v5f6585d5af, $v6d68dcb64c, $v511197c8e2) { $v651d63364b = $v539082ff30["template_regions"]; $v9e3513bc0e = $v539082ff30["template_params"]; $v66a6023e1e = $v539082ff30["template_includes"]; $v0febf893c5 = $v539082ff30["is_external_template"]; $pb34c57d8 = $v539082ff30["external_template_params"]; $pfaf08f23 = new PHPVariablesFileHandler(array($v3d55458bcd, $EVC->getConfigPath("pre_init_config"))); $pfaf08f23->startUserGlobalVariables(); if ($v708ac23d1c) $pf8ed4912 = $v708ac23d1c; else { $pf8ed4912 = CMSPresentationLayerHandler::getSetTemplateCode($EVC, $v0febf893c5, $pe7333513, $pb34c57d8, $v66a6023e1e); if (!$pf8ed4912 && !$v0febf893c5) { $v8ea5ee2b30 = $EVC->getTemplatePath($pe7333513); if (!file_exists($v8ea5ee2b30)) { launch_exception(new Exception("Template doesn't exists!")); die(); } } } if ($pf8ed4912) { include $EVC->getConfigPath("config"); @include_once $EVC->getModulePath("translator/include_text_translator_handler", $EVC->getCommonProjectName()); if ($v66a6023e1e) { $pd6dd936f = "<?"; foreach ($v66a6023e1e as $pc24afc88) if ($pc24afc88["path"]) $pd6dd936f .= "\n" . "include" . ($pc24afc88["once"] ? "_once" : "") . " " . $pc24afc88["path"] . ";"; $pd6dd936f .= "\n?>\n"; $pf8ed4912 = $pd6dd936f . $pf8ed4912; } $pf8ed4912 = prepareEditableTemplate($EVC, $pf8ed4912, $v651d63364b, $v9e3513bc0e); $v37f1176ca4 = tmpfile(); $v32449e14b2 = stream_get_meta_data($v37f1176ca4); $v4e03b5e19e = $v32449e14b2['uri']; $pc3772d0d = str_split($pf8ed4912, 1024 * 4); foreach ($pc3772d0d as $v306839072f) fwrite($v37f1176ca4, $v306839072f, strlen($v306839072f)); $pf0f58138 = error_reporting(); ob_start(); if ($v17cd69faa1) error_reporting(0); include $v4e03b5e19e; if ($v17cd69faa1) error_reporting($pf0f58138); $pf8ed4912 = ob_get_contents(); ob_end_clean(); fclose($v37f1176ca4); $v31199c28eb = ''; if (stripos($pf8ed4912, "jquery") === false || $v5cd161b785) $v31199c28eb .= '<script type="text/javascript" src="' . $v5f6585d5af . 'vendor/jquery/js/jquery-1.8.1.min.js"></script>'; else $v31199c28eb .= '
		<script>
		if(!window.jQuery) {
		   var url = "" + document.location;
		   url += (url.indexOf("?") != -1 ? "&" : "?") + "include_jquery=1";
		   document.location = url;
		}
		</script>'; $v31199c28eb .= '
		<!-- Global script with some native javascript functions -->
		<script src="' . $v5f6585d5af . 'js/global.js"></script>
		  
		<!-- Add TinyMCE JS Files  -->
		<script type="text/javascript" src="' . $v5f6585d5af . 'vendor/tinymce/js/tinymce/tinymce.min.js"></script>
		<script type="text/javascript" src="' . $v5f6585d5af . 'vendor/tinymce/js/tinymce/jquery.tinymce.min.js"></script>	
	   	
		<!-- Add Simple Template Layout CSS and JS Files  -->
		<link rel="stylesheet" href="' . $pfde6442c . 'css/presentation/edit_simple_template_layout.css" type="text/css" charset="utf-8" />
		<script language="javascript" type="text/javascript" src="' . $pfde6442c . 'js/presentation/edit_simple_template_layout.js"></script>
		
		<script>
			var selected_project_id = \'' . $EVC->getpresentationLayer()->getSelectedPresentationId() . '\';
			var system_get_page_block_simulated_html_url = \'' . $v6d68dcb64c . '\';
			var system_save_page_block_simulated_html_setting_url = \'' . $v511197c8e2 . '\';
		</script>'; if (strpos($pf8ed4912, "</body>") !== false) $pf8ed4912 = str_replace('</body>', $v31199c28eb. '</body>', $pf8ed4912); else $pf8ed4912 .= $v31199c28eb; header_remove(); if (substr("" . http_response_code(), 0, 1) != "2") http_response_code(200); } $pfaf08f23->endUserGlobalVariables(); return $pf8ed4912; } function prepareEditableTemplate($v08d9602741, $pf8ed4912, $v651d63364b, $v9e3513bc0e) { $v2b1e634696 = array(); if ($v651d63364b) foreach ($v651d63364b as $v9bc6a691fa => $pfff36d74) if ($pfff36d74) $v2b1e634696 = array_merge($v2b1e634696, $pfff36d74); $v2508589a4c = $v08d9602741->getPresentationLayer()->getSelectedPresentationId(); $v5aaf0d3496 = CMSPresentationLayerHandler::initBlocksListThroughRegionBlocks($v188b4f5fa6, $v2b1e634696, $v2508589a4c); $pf07e7d8a = "<?"; $pf8ed4912 = preg_replace('/\$block_local_variables\s*=\s*\$region_block_local_variables([^;]+);/', '', $pf8ed4912); $pf8ed4912 = preg_replace('/\$region_block_local_variables\[[^\]]+\]\[[^\]]+\]\[[^\]]+\]\s*=([^;]+);/', '', $pf8ed4912); $pf8ed4912 = preg_replace('/\$region_block_local_variables([^;]+);/', '', $pf8ed4912); $pf8ed4912 = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSJoinPointLayer\(\)->resetRegionBlockJoinPoints\([^\)]*\);/', '', $pf8ed4912); $pf8ed4912 = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->addRegionBlock\([^\)]*\);/', '', $pf8ed4912); $pf8ed4912 = preg_replace('/include \$EVC->getBlockPath\([^\)]*\);/', '', $pf8ed4912); $pf8ed4912 = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->setParam\([^\)]*\);/', '', $pf8ed4912); if ($v651d63364b) foreach ($v651d63364b as $v9bc6a691fa => $pfff36d74) { $v7bfbcea0f1 = getProjectTemplateRegionBlocksHtml($v9bc6a691fa, $pfff36d74, $v5aaf0d3496); $pe1aee0c8 = $v9bc6a691fa . ' . "_xxx"'; $pf07e7d8a .= '$EVC->getCMSLayer()->getCMSTemplateLayer()->addRegionHtml(' . $pe1aee0c8 . ', \'' . addcslashes($v7bfbcea0f1, "'") . '\');'; $v02a69d4e0f = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->renderRegion\(' . $v9bc6a691fa . '\)/u', '$EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion(' . $pe1aee0c8 . ')', $pf8ed4912); if ($v02a69d4e0f !== null) $pf8ed4912 = $v02a69d4e0f; } if ($v9e3513bc0e) foreach ($v9e3513bc0e as $v67ccb03f4c => $v72eb975550) { $pf795164c = $v67ccb03f4c . ' . "_xxx"'; $v72eb975550 = strlen($v72eb975550) ? $v72eb975550 : "''"; $pf07e7d8a .= "\n" . '$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam(' . $pf795164c . ', ' . $v72eb975550 . ');'; $v02a69d4e0f = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->getParam\(' . $v67ccb03f4c . '\)/u', '$EVC->getCMSLayer()->getCMSTemplateLayer()->getParam(' . $pf795164c . ')', $pf8ed4912); if ($v02a69d4e0f !== null) $pf8ed4912 = $v02a69d4e0f; } $pf07e7d8a .= "\n?>"; return $pf07e7d8a . $pf8ed4912; } function prepareEditableTemplate2($v08d9602741, $pf8ed4912, $v651d63364b, $v9e3513bc0e) { $v2508589a4c = $v08d9602741->getPresentationLayer()->getSelectedPresentationId(); $v5aaf0d3496 = CMSPresentationLayerHandler::getAvailableBlocksList($v08d9602741, $v2508589a4c); if ($v651d63364b) foreach ($v651d63364b as $pe5c5e2fe => $v956913c90f) $pf8ed4912 = prepareProjectTemplateRegionHtml($pe5c5e2fe, $v956913c90f, $pf8ed4912, $v5aaf0d3496); if ($v9e3513bc0e) foreach ($v9e3513bc0e as $pe5c5e2fe => $v956913c90f) $pf8ed4912 = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->getParam\(' . $pe5c5e2fe . '\)/u', '"Param: ' . addcslashes($pe5c5e2fe, '"') . '"', $pf8ed4912); return $pf8ed4912; } function prepareProjectTemplateRegionHtml($v9bc6a691fa, $pfff36d74, $pf8ed4912, $v5aaf0d3496) { $v7bfbcea0f1 = getProjectTemplateRegionBlocksHtml($v9bc6a691fa, $pfff36d74, $v5aaf0d3496); $pbd1bc7b0 = strpos($pf8ed4912, '$EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion(' . $v9bc6a691fa . ')'); $pbd1bc7b0 = strpos($pf8ed4912, '?>', $pbd1bc7b0) + 2; $pf8ed4912 = substr($pf8ed4912, 0, $pbd1bc7b0) . $v7bfbcea0f1 . substr($pf8ed4912, $pbd1bc7b0); $pf8ed4912 = preg_replace('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->renderRegion\(' . $v9bc6a691fa . '\)/u', '""', $pf8ed4912); return $pf8ed4912; } function getProjectTemplateRegionBlocksHtml($v9bc6a691fa, $pfff36d74, $v5aaf0d3496) { $v0ebcb16521 = ""; $v5bd013bcfe = array(); if ($pfff36d74 && is_array($pfff36d74)) { foreach ($pfff36d74 as $pd06bdde3) { $peebaaf55 = $pd06bdde3[1]; if ($peebaaf55) { $pd6ec966e = $pd06bdde3[2]; $pcbe60070 = $pd06bdde3[3]; $pe603f3eb = $pd06bdde3[4]; $v8eaa4de79a = $pcbe60070 ? md5($peebaaf55) : $peebaaf55; if (is_numeric($pe603f3eb) && $pe603f3eb > $v5bd013bcfe["$v9bc6a691fa-$v8eaa4de79a-$pd6ec966e"]) $v5bd013bcfe["$v9bc6a691fa-$v8eaa4de79a-$pd6ec966e"] = $pe603f3eb; } } foreach ($pfff36d74 as $pd06bdde3) { $peebaaf55 = $pd06bdde3[1]; $v7959970a41 = false; if ($peebaaf55) { $pd6ec966e = $pd06bdde3[2]; $pcbe60070 = $pd06bdde3[3]; $pe603f3eb = $pd06bdde3[4]; $v8eaa4de79a = $pcbe60070 ? md5($peebaaf55) : $peebaaf55; if (!is_numeric($pe603f3eb)) { if (isset($v5bd013bcfe["$v9bc6a691fa-$v8eaa4de79a-$pd6ec966e"])) $v5bd013bcfe["$v9bc6a691fa-$v8eaa4de79a-$pd6ec966e"]++; else $v5bd013bcfe["$v9bc6a691fa-$v8eaa4de79a-$pd6ec966e"] = 0; } $v14c6c41074 = CMSPresentationLayerUIHandler::getRegionBlockHtml($v9bc6a691fa, $peebaaf55, $pd6ec966e, $pcbe60070, $v5aaf0d3496, array(), array(), is_numeric($pe603f3eb) ? $pe603f3eb : $v5bd013bcfe["$v9bc6a691fa-$v8eaa4de79a-$pd6ec966e"]); if (!$pcbe60070) { $v56b1e1a2b7 = substr($peebaaf55, 0, 1) == '"' ? str_replace('"', '', $peebaaf55) : $peebaaf55; $pc611e727 = substr($pd6ec966e, 0, 1) == '"' ? str_replace('"', '', $pd6ec966e) : $pd6ec966e; $pd45d0d0d = $v5aaf0d3496[$pc611e727]; $v7959970a41 = empty($peebaaf55) || ($pd45d0d0d && in_array($v56b1e1a2b7, $pd45d0d0d)); $v3ae55a9a2e = $v7959970a41 ? " active" : ($v56b1e1a2b7 ? " invalid" : ""); if ($v3ae55a9a2e) $v14c6c41074 = str_replace('<div class="item', '<div class="item' . $v3ae55a9a2e, $v14c6c41074); } $v0ebcb16521 .= $v14c6c41074; } else $v0ebcb16521 .= CMSPresentationLayerUIHandler::getRegionBlockHtml($v9bc6a691fa, null, null, false, $v5aaf0d3496); } } else $v0ebcb16521 = CMSPresentationLayerUIHandler::getRegionBlockHtml($v9bc6a691fa, null, null, false, $v5aaf0d3496); $v0ebcb16521 = str_replace('<div class="block_html editor', '<div class="block_html', $v0ebcb16521); return '<div class="template_region" region="' . str_replace('"', '&quot;', $v9bc6a691fa) . '"><div class="template_region_name">Region ' . $v9bc6a691fa . ':</div><div class="items">' . $v0ebcb16521 . '</div></div>'; } ?>
