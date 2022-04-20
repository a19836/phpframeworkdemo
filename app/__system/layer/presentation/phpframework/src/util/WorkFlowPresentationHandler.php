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

include_once $EVC->getUtilPath("LayoutTypeProjectHandler"); class WorkFlowPresentationHandler { public static function getHeader($peb014cfd, $v37d269c4fa, $pcfdeae4e = false, $v8555f2f905 = false, $pbd963c11 = false, $v8c5b4fe0d4 = false) { $pf8ed4912 = '
			<!-- Add MyTree main JS and CSS files -->
			<link rel="stylesheet" href="' . $v37d269c4fa . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
			<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerymytree/js/mytree.js"></script>

			<!-- Add FileManager JS file -->
			<link rel="stylesheet" href="' . $peb014cfd . 'css/file_manager.css" type="text/css" charset="utf-8" />
			<script language="javascript" type="text/javascript" src="' . $peb014cfd . 'js/file_manager.js"></script>
		'; $pf8ed4912 .= $pcfdeae4e ? $pcfdeae4e->getHeader(array("tasks_css_and_js" => true, "icons_and_edit_code_already_included" => $v8c5b4fe0d4)) : ""; if (strpos($pf8ed4912, 'vendor/jsbeautify/js/lib/beautify.js') === false) $pf8ed4912 = '
				<!-- Add Html/CSS/JS Beautify code -->
				<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jsbeautify/js/lib/beautify.js"></script>
				<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jsbeautify/js/lib/beautify-css.js"></script>
				<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/myhtmlbeautify/MyHtmlBeautify.js"></script>
			' . $pf8ed4912; if (strpos($pf8ed4912, 'vendor/mycodebeautifier/js/codebeautifier.js') === false) $pf8ed4912 = '
				<!-- Add Code Beautifier -->
				<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/mycodebeautifier/js/codebeautifier.js"></script>' . $pf8ed4912; if (strpos($pf8ed4912, 'vendor/acecodeeditor/src-min-noconflict/ace.js') === false) $pf8ed4912 = '		
				<!-- Add Code Editor JS files -->
				<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
				<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>' . $pf8ed4912; if (strpos($pf8ed4912, 'vendor/jquery/js/jquery.md5.js') === false) $pf8ed4912 = '
				<!-- Add MD5 JS Files -->
				<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquery/js/jquery.md5.js"></script>' . $pf8ed4912; if ($v8555f2f905) { $pf8ed4912 .= '<script>
				jsPlumbWorkFlow.jsPlumbTaskFile.set_tasks_file_url = \'' . $v8555f2f905 . '\';
				</script>'; } if (!$v8c5b4fe0d4) { if (!$pcfdeae4e) $pf8ed4912 .= '
				<!-- Add Fontawsome Icons CSS -->
				<link rel="stylesheet" href="' . $v37d269c4fa . 'vendor/fontawesome/css/all.min.css">
				
				<!-- Add Icons CSS file -->
				<link rel="stylesheet" href="' . $peb014cfd . 'css/icons.css" type="text/css" charset="utf-8" />
				
				<!-- Add Layout JS file -->
				<script language="javascript" type="text/javascript" src="' . $peb014cfd . 'js/layout.js"></script>
				'; $pf8ed4912 .= '
			<!-- Add Edit PHP Code JS and CSS files -->
			<link rel="stylesheet" href="' . $peb014cfd . 'css/edit_php_code.css" type="text/css" charset="utf-8" />
			<script language="javascript" type="text/javascript" src="' . $peb014cfd . 'js/edit_php_code.js"></script>
			'; } if ($pcfdeae4e) { $pf8ed4912 .= '
			<!-- add default function to reset the top positon of the tasksflow panels, if with_top_bar class exists -->
			<script>
				jsPlumbWorkFlow.setjsPlumbWorkFlowObjOption("on_resize_panels_function", onResizeTaskFlowChartPanels);
			</script>
			'; $pf8ed4912 .= $pcfdeae4e->getJS(false, false, array("is_droppable_connection" => true, "add_default_start_task" => true, "selected_task_properties_resizable" => true, "selected_connection_properties_resizable" => true)); } if ($pbd963c11) $pf8ed4912 .= '
			<!-- Add CKEditor JS Files  -->
			<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/ckeditor/ckeditor.js"></script>'; return $pf8ed4912; } public static function getUIEditorWidgetsHtml($v0345b66144, $v37d269c4fa, $v4bf8d90f04, $pfce4d1b3, $v5d3813882f = null) { $v60968209dd = $v0345b66144 . "vendor/jquerylayoutuieditor/"; include_once $v60968209dd . "util.php"; $pae9f35af = "jquerylayoutuieditor/widget/"; $pcb902903 = $v4bf8d90f04 . $pae9f35af; $pf235b497 = $pfce4d1b3 . $pae9f35af; $v0e41af17ee = $v60968209dd . "widget/"; $pce3003cf = $v37d269c4fa . "vendor/jquerylayoutuieditor/widget/"; $pee3480c4 = scanWidgets($v0e41af17ee); $pee3480c4 = filterWidgets($pee3480c4, $v0e41af17ee, $pce3003cf, $v5d3813882f); $pd3938a46 .= getMenuWidgetsHTML($pee3480c4, $v0e41af17ee, $pce3003cf, $pcb902903, $pf235b497); return $pd3938a46; } public static function getExtraUIEditorWidgetsHtml($v0345b66144, $v19b26a58a8, $v4cb34c109a, $v4bf8d90f04, $pfce4d1b3, $v5d3813882f = null) { $v60968209dd = $v0345b66144 . "vendor/jquerylayoutuieditor/"; include_once $v60968209dd . "util.php"; $pae9f35af = "jquerylayoutuieditor/widget/"; $pcb902903 = $v4bf8d90f04 . $pae9f35af; $pf235b497 = $pfce4d1b3 . $pae9f35af; $pee3480c4 = scanWidgets($v19b26a58a8); $pee3480c4 = filterWidgets($pee3480c4, $v19b26a58a8, $v4cb34c109a, $v5d3813882f); $pd3938a46 = getMenuWidgetsHTML($pee3480c4, $v19b26a58a8, $v4cb34c109a, $pcb902903, $pf235b497); return $pd3938a46; } public static function getUserUIEditorWidgetsHtml($v0345b66144, $v0e41af17ee, $v4bf8d90f04, $pfce4d1b3, $v5d3813882f = null) { $pd3938a46 = ""; if ($v0e41af17ee) { $v60968209dd = $v0345b66144 . "vendor/jquerylayoutuieditor/"; include_once $v60968209dd . "util.php"; $pae9f35af = "jquerylayoutuieditor/widget/"; $pcb902903 = $v4bf8d90f04 . $pae9f35af; $pf235b497 = $pfce4d1b3 . $pae9f35af; $v4c778e26ea = $v4bf8d90f04 . $pae9f35af; $v0e41af17ee = is_array($v0e41af17ee) ? $v0e41af17ee : array($v0e41af17ee); foreach ($v0e41af17ee as $pe4619dcd) if (file_exists($pe4619dcd)) { $pe4619dcd = str_replace("//", "/", trim( realpath($pe4619dcd) )); $pe4619dcd .= substr($pe4619dcd, strlen($pe4619dcd) - 1) == "/" ? "" : "/"; $pd0c2309c = hash("crc32b", $pe4619dcd); $pce3003cf = "$pfce4d1b3$pae9f35af$pd0c2309c/"; $pee3480c4 = scanWidgets($pe4619dcd); $pee3480c4 = filterWidgets($pee3480c4, $pe4619dcd, $pce3003cf, $v5d3813882f); self::f22be97f31f($pee3480c4, "$v4c778e26ea$pd0c2309c/"); $pd3938a46 = getMenuWidgetsHTML($pee3480c4, $pe4619dcd, $pce3003cf, $pcb902903, $pf235b497); } } return $pd3938a46; } private static function f22be97f31f($pee3480c4, $pd520d615) { $v5c1c342594 = true; if ($pee3480c4 && !is_dir($pd520d615)) foreach ($pee3480c4 as $v5e813b295b => $v2cd5d67337) { if (is_array($v2cd5d67337)) self::f22be97f31f($v2cd5d67337, $pd520d615 . "$v5e813b295b/"); else { $pcec8b7cc = dirname($v2cd5d67337) . "/webroot/"; if (file_exists($pcec8b7cc) && !self::me1bfc9cf0775($pcec8b7cc, $pd520d615)) $v5c1c342594 = false; } } return $v5c1c342594; } private static function me1bfc9cf0775($v92dcc541a8, $pa5b0817e) { if ($v92dcc541a8 && $pa5b0817e && is_dir($v92dcc541a8)) { if (!is_dir($pa5b0817e)) @mkdir($pa5b0817e, 0755, true); if (is_dir($pa5b0817e)) { $v5c1c342594 = true; $v6ee393d9fb = scandir($v92dcc541a8); if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if ($v7dffdb5a5b != '.' && $v7dffdb5a5b != '..') { if (is_dir("$v92dcc541a8/$v7dffdb5a5b")) { if (!self::me1bfc9cf0775("$v92dcc541a8/$v7dffdb5a5b", "$pa5b0817e/$v7dffdb5a5b")) $v5c1c342594 = false; } else if (!copy("$v92dcc541a8/$v7dffdb5a5b", "$pa5b0817e/$v7dffdb5a5b")) $v5c1c342594 = false; } return $v5c1c342594; } } } public static function getPresentationBrokersHtml($pb0e92e25, $pf7b73b3a, $v46a478e94c) { $pf8ed4912 = ''; if ($pb0e92e25) { $pc37695cb = count($pb0e92e25); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7aeaf992f5 = $pb0e92e25[$v43dd7d0051]; if ($v7aeaf992f5[2]) { $v94a9c171e3 = str_replace("#bean_file_name#", $v7aeaf992f5[1], str_replace("#bean_name#", $v7aeaf992f5[2], $pf7b73b3a)) . '&item_type=presentation&folder_type=#folder_type#'; $pf8ed4912 .= 'main_layers_properties.' . $v7aeaf992f5[2] . ' = {ui: {
						folder: {
							get_sub_files_url: "' . $v94a9c171e3 . '",
							attributes: {
								folder_path: "#path#",
							},
						},
						file: {
							attributes: {
								file_path: "#path#",
								bean_name: "' . $v7aeaf992f5[2] . '",
								get_file_properties_url: "' . str_replace("#bean_file_name#", $v7aeaf992f5[1], str_replace("#bean_name#", $v7aeaf992f5[2], $v46a478e94c)) . '"
							},
						},
					}};
			
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["project_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["entities_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["views_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["templates_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["utils_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["configs_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["blocks_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["cms_common"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"]; //used in deployment/index.php
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["cms_module"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["cms_program"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["cms_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["wordpress_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["wordpress_installation_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["module_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["controllers_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["webroot_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["caches_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["routers_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["dispatchers_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["entity_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["view_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["template_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["template_folder"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["folder"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["util_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["config_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["block_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["module_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["controller_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["js_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["css_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["img_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["undefined_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["cache_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["router_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["dispatcher_file"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["class"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["file"];
					
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["project"] = {
						attributes: {
							project_path: "#path#",
						}
					};
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["project_common"] = main_layers_properties.' . $v7aeaf992f5[2] . '.ui["project"];
					
					main_layers_properties.' . $v7aeaf992f5[2] . '.ui["referenced_folder"] = {
						get_sub_files_url: "' . $v94a9c171e3 . '",
					};
					'; } } } return $pf8ed4912; } public static function getBusinessLogicBrokersHtml($v6e9af47944, $pf7b73b3a, $v46a478e94c) { $pf8ed4912 = ''; if ($v6e9af47944) { $pc37695cb = count($v6e9af47944); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7aeaf992f5 = $v6e9af47944[$v43dd7d0051]; if ($v7aeaf992f5[2]) { $v94a9c171e3 = str_replace("#bean_file_name#", $v7aeaf992f5[1], str_replace("#bean_name#", $v7aeaf992f5[2], $pf7b73b3a)); $pf8ed4912 .= 'main_layers_properties.' . $v7aeaf992f5[2] . ' = {ui: {
						folder: {
							get_sub_files_url: "' . $v94a9c171e3 . '",
						},
						cms_common: {
							get_sub_files_url: "' . $v94a9c171e3 . '",
						},
						cms_module: {
							get_sub_files_url: "' . $v94a9c171e3 . '",
						},
						cms_program: {
							get_sub_files_url: "' . $v94a9c171e3 . '",
						},
						file: {
							attributes: {
								file_path: "#path#",
								bean_name: "' . $v7aeaf992f5[2] . '",
								get_file_properties_url: "' . str_replace("#bean_file_name#", $v7aeaf992f5[1], str_replace("#bean_name#", $v7aeaf992f5[2], $v46a478e94c)) . '"
							}
						},
						service: {
							attributes: {
								file_path: "#path#",
								bean_name: "' . $v7aeaf992f5[2] . '",
								get_file_properties_url: "' . str_replace("#bean_file_name#", $v7aeaf992f5[1], str_replace("#bean_name#", $v7aeaf992f5[2], $v46a478e94c)) . '"
							}
						},
						referenced_folder: {
							get_sub_files_url: "' . $v94a9c171e3 . '",
						},
					}};'; } } } return $pf8ed4912; } public static function getDaoLibAndVendorBrokersHtml($v54c4a1fbb7, $pe2f0f7f1, $v828ff69e5c, $v46a478e94c) { $v5cc2ea124b = str_replace("=dao", "=test_unit", $v54c4a1fbb7); $pc7a85dd5 = str_replace("#bean_file_name#", "", str_replace("#bean_name#", "dao", $v46a478e94c)); return '
			main_layers_properties.dao = {ui: {
				folder: {
					get_sub_files_url: "' . $v54c4a1fbb7 . '",
				},
				cms_common: {
					get_sub_files_url: "' . $v54c4a1fbb7 . '",
				},
				cms_module: {
					get_sub_files_url: "' . $v54c4a1fbb7 . '",
				},
				cms_program: {
					get_sub_files_url: "' . $v54c4a1fbb7 . '",
				},
				file: {
					attributes: {
						file_path: "#path#",
						bean_name: "dao",
						get_file_properties_url: "' . $pc7a85dd5 . '"
					}
				},
				objtype: {
					attributes: {
						file_path: "#path#",
						bean_name: "dao",
						get_file_properties_url: "' . $pc7a85dd5 . '"
					}
				},
				hibernatemodel: {
					attributes: {
						file_path: "#path#",
						bean_name: "dao",
						get_file_properties_url: "' . $pc7a85dd5 . '"
					}
				},
			}};
			main_layers_properties.lib = {ui: {
				folder: {
					get_sub_files_url: "' . $pe2f0f7f1 . '",
				},
				file: {
					attributes: {
						file_path: "#path#",
						bean_name: "lib",
						get_file_properties_url: "' . str_replace("#bean_file_name#", "", str_replace("#bean_name#", "lib", $v46a478e94c)) . '"
					}
				},
			}};
			main_layers_properties.vendor = {ui: {
				folder: {
					get_sub_files_url: "' . $v828ff69e5c . '",
				},
				file: {
					attributes: {
						file_path: "#path#",
						bean_name: "vendor",
						get_file_properties_url: "' . str_replace("#bean_file_name#", "", str_replace("#bean_name#", "vendor", $v46a478e94c)) . '"
					}
				},
				code_workflow_editor: {
					get_sub_files_url: "' . $v828ff69e5c . '",
				},
				code_workflow_editor_task: {
					get_sub_files_url: "' . $v828ff69e5c . '",
				},
				layout_ui_editor: {
					get_sub_files_url: "' . $v828ff69e5c . '",
				},
				layout_ui_editor_widget: {
					get_sub_files_url: "' . $v828ff69e5c . '",
				},
				dao: {
					get_sub_files_url: "' . $v54c4a1fbb7 . '",
				},
				test_unit: {
					get_sub_files_url: "' . $v5cc2ea124b . '",
				},
			}};
			main_layers_properties.test_unit = {ui: {
				folder: {
					get_sub_files_url: "' . $v5cc2ea124b . '",
					attributes: {
						file_path: "#path#", //used in src/testunit/index.php
					}
				},
				file: {
					attributes: {
						file_path: "#path#",
						bean_name: "test_unit",
						get_file_properties_url: "' . str_replace("#bean_file_name#", "", str_replace("#bean_name#", "test_unit", $v46a478e94c)) . '"
					}
				},
				test_unit_obj: {
					attributes: {
						file_path: "#path#",
						bean_name: "test_unit",
						get_file_properties_url: "' . str_replace("#bean_file_name#", "", str_replace("#bean_name#", "test_unit", $v46a478e94c)) . '"
					}
				},
			}};
		'; } public static function getDataAccessBrokersHtml($v9fda9fad47, $pf7b73b3a) { $pf8ed4912 = ''; if ($v9fda9fad47) { $pc37695cb = count($v9fda9fad47); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7aeaf992f5 = $v9fda9fad47[$v43dd7d0051]; if ($v7aeaf992f5[2]) { $v94a9c171e3 = str_replace("#bean_file_name#", $v7aeaf992f5[1], str_replace("#bean_name#", $v7aeaf992f5[2], $pf7b73b3a)); $pf8ed4912 .= 'main_layers_properties.' . $v7aeaf992f5[2] . ' = {ui: {
						folder: {
							get_sub_files_url: "' . $v94a9c171e3 . '",
						},
						cms_common: {
							get_sub_files_url: "' . $v94a9c171e3 . '",
						},
						cms_module: {
							get_sub_files_url: "' . $v94a9c171e3 . '",
						},
						cms_program: {
							get_sub_files_url: "' . $v94a9c171e3 . '",
						},
						file: {
							attributes: {
								file_path: "#path#"
							}
						},
						obj: {
							attributes: {
								file_path: "#path#"
							}
						},
						query: {
							attributes: {
								file_path: "#path#",
								query_type: "#query_type#",
								relationship_type: "#relationship_type#",
								hbn_obj_id: "#hbn_obj_id#",
							}
						},
						relationship: {
							attributes: {
								file_path: "#path#",
								query_type: "#query_type#",
								relationship_type: "#relationship_type#",
								hbn_obj_id: "#hbn_obj_id#",
							}
						},
						hbn_native: {
							attributes: {
								file_path: "#path#",
								query_type: "#query_type#",
								relationship_type: "#relationship_type#",
								hbn_obj_id: "#hbn_obj_id#",
							}
						},
						referenced_folder: {
							get_sub_files_url: "' . $v94a9c171e3 . '",
						},
					}};'; } } } return $pf8ed4912; } public static function getChooseFromFileManagerPopupHtml($v8ffce2a791, $pa0462a8e, $pf7b73b3a, $v54c4a1fbb7, $pe2f0f7f1, $v828ff69e5c, $v5483bfa973, $v9fda9fad47, $pf864769c, $paf75a67c, $v6e9af47944, $pb0e92e25) { $pf8ed4912 = ""; if ($v8ffce2a791 && $pa0462a8e) $pf8ed4912 = self::me4f1b76bfe10($v8ffce2a791, $pa0462a8e, $pf7b73b3a, $v54c4a1fbb7, $pe2f0f7f1, $v828ff69e5c, $v5483bfa973, $v9fda9fad47, $pf864769c, $paf75a67c, $v6e9af47944, $pb0e92e25); else $pf8ed4912 = self::f2ad93f0276($v8ffce2a791, $pa0462a8e, $pf7b73b3a, $v54c4a1fbb7, $pe2f0f7f1, $v828ff69e5c, $v5483bfa973, $v9fda9fad47, $pf864769c, $paf75a67c, $v6e9af47944, $pb0e92e25); if (isset($v5483bfa973) || isset($v9fda9fad47)) { $pf8ed4912 .='<div id="choose_db_driver_table" class="myfancypopup">
				<div class="broker">
					<label>Broker:</label>
					<select onChange="updateDBDriverOnBrokerNameChange(this)">'; $pfb662071 = $v5483bfa973 ? $v5483bfa973 : $v9fda9fad47; $pc37695cb = count($pfb662071); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7aeaf992f5 = $pfb662071[$v43dd7d0051]; $pf8ed4912 .= '<option bean_file_name="' . $v7aeaf992f5[1] . '" bean_name="' . $v7aeaf992f5[2] . '" value="' . $v7aeaf992f5[0] . '">' . $v7aeaf992f5[0] . ($v7aeaf992f5[2] ? '' : ' (Rest)') . '</option>'; } $pf8ed4912 .= '
					</select>
				</div>
				<div class="db_driver">
					<label>DB Driver:</label>
					<select onChange="updateDBTablesOnBrokerDBDriverChange(this)"></select>
				</div>
				<div class="type">
					<label>Type:</label>
					<select onChange="updateDBTablesOnBrokerDBDriverChange(this)">
						<option value="db">From DB Server</option>
						<option value="diagram">From DB Diagram</option>
					</select>
				</div>
				<div class="db_table">
					<label>DB Table:</label>
					<select></select>
				</div>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>'; } if (isset($v9fda9fad47)) { $pf8ed4912 .='<div id="choose_db_driver" class="myfancypopup">
				<div class="broker">
					<label>Data Access Broker:</label>
					<select onChange="updateDBDriverOnBrokerNameChange(this)">'; $pc37695cb = count($v9fda9fad47); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7aeaf992f5 = $v9fda9fad47[$v43dd7d0051]; $pf8ed4912 .= '<option bean_file_name="' . $v7aeaf992f5[1] . '" bean_name="' . $v7aeaf992f5[2] . '" value="' . $v7aeaf992f5[0] . '">' . $v7aeaf992f5[0] . ($v7aeaf992f5[2] ? '' : ' (Rest)') . '</option>'; } $pf8ed4912 .= '
					</select>
				</div>
				<div class="db_driver">
					<label>DB Driver:</label>
					<select></select>
				</div>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>'; } if (isset($pf864769c)) { $pf8ed4912 .='<div id="choose_query_from_file_manager" class="myfancypopup choose_from_file_manager">
				<div class="broker">
					<label>Broker:</label>
					<select onChange="updateDBDriverOnBrokerNameChange(this);updateLayerUrlFileManager(this)">'; $pc37695cb = count($pf864769c); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7aeaf992f5 = $pf864769c[$v43dd7d0051]; $pf8ed4912 .= '<option bean_file_name="' . $v7aeaf992f5[1] . '" bean_name="' . $v7aeaf992f5[2] . '" value="' . $v7aeaf992f5[0] . '">' . $v7aeaf992f5[0] . ($v7aeaf992f5[2] ? '' : ' (Rest)') . '</option>'; } $pf8ed4912 .= '
					</select>
				</div>
				<div class="db_driver">
					<label>DB Driver:</label>
					<select></select>
				</div>
				<div class="type hidden"> <!-- Aparentely any choice we made will get the same result, so the type does not need to be here! -->
					<label>Type:</label>
					<select name="type">
						<option value="db">From DB Server</option>
						<option value="diagram">From DB Diagram</option>
					</select>
					<span class="icon info" title="When we get the parameters for a query we need to know if the attribtutes/parameters will have quotes or no-quotes, this is, if are \'string\' or \'code\'! In order to do this we need to get the db attributes type for each parameter and then check if the attributes type are quotes or non-quotes, this is, bigint is a no-quotes, but varchar is a quotes type. So we can get the db attributes type from the \'DB Server\' or from the \'DB Diagram\'" onClick="alert(this.getAttribute(\'title\'))"></span>
				</div>
				<ul class="mytree">
					<li>
						<label>Root</label>
						<ul layer_url="' . $pf7b73b3a . '"></ul>
					</li>
				</ul>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>'; } if (isset($paf75a67c)) { $pc37695cb = count($paf75a67c); $pf8ed4912 .='<div id="choose_hibernate_object_from_file_manager" class="myfancypopup choose_from_file_manager">
				<div class="broker">
					<label>Broker:</label>
					<select onChange="updateDBDriverOnBrokerNameChange(this);updateLayerUrlFileManager(this)">'; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7aeaf992f5 = $paf75a67c[$v43dd7d0051]; $pf8ed4912 .= '<option bean_file_name="' . $v7aeaf992f5[1] . '" bean_name="' . $v7aeaf992f5[2] . '" value="' . $v7aeaf992f5[0] . '">' . $v7aeaf992f5[0] . ($v7aeaf992f5[2] ? '' : ' (Rest)') . '</option>'; } $pf8ed4912 .= '
					</select>
				</div>
				<div class="db_driver">
					<label>DB Driver:</label>
					<select></select>
				</div>
				<ul class="mytree">
					<li>
						<label>Root</label>
						<ul layer_url="' . $pf7b73b3a . '"></ul>
					</li>
				</ul>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>
			
			<div id="choose_hibernate_object_method_from_file_manager" class="myfancypopup choose_from_file_manager">
				<div class="broker">
					<label>Broker:</label>
					<select onChange="updateDBDriverOnBrokerNameChange(this);updateLayerUrlFileManager(this)">'; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7aeaf992f5 = $paf75a67c[$v43dd7d0051]; $pf8ed4912 .= '<option bean_file_name="' . $v7aeaf992f5[1] . '" bean_name="' . $v7aeaf992f5[2] . '" value="' . $v7aeaf992f5[0] . '">' . $v7aeaf992f5[0] . ($v7aeaf992f5[2] ? '' : ' (Rest)') . '</option>'; } $pf8ed4912 .= '
					</select>
				</div>
				<div class="db_driver">
					<label>DB Driver:</label>
					<select></select>
				</div>
				<div class="type hidden"> <!-- Aparentely any choice we made will get the same result, so the type does not need to be here! -->
					<label>Type:</label>
					<select name="type">
						<option value="db">From DB Server</option>
						<option value="diagram">From DB Diagram</option>
					</select>
					<span class="icon info" title="When we get the parameters for a method or query we need to know if the attribtutes/parameters will have quotes or no-quotes, this is, if are \'string\' or \'code\'! In order to do this we need to get the db attributes type for each parameter and then check if the attributes type are quotes or non-quotes, this is, bigint is a no-quotes, but varchar is a quotes type. So we can get the db attributes type from the \'DB Server\' or from the \'DB Diagram\'" onClick="alert(this.getAttribute(\'title\'))"></span>
				</div>
				<ul class="mytree">
					<li>
						<label>Root</label>
						<ul layer_url="' . $pf7b73b3a . '"></ul>
					</li>
				</ul>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>'; } if (isset($v6e9af47944)) { $pf8ed4912 .='<div id="choose_business_logic_from_file_manager" class="myfancypopup choose_from_file_manager">
				<div class="broker">
					<label>Broker:</label>
					<select onChange="updateLayerUrlFileManager(this)">'; $pc37695cb = count($v6e9af47944); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7aeaf992f5 = $v6e9af47944[$v43dd7d0051]; $pf8ed4912 .= '<option bean_file_name="' . $v7aeaf992f5[1] . '" bean_name="' . $v7aeaf992f5[2] . '" value="' . $v7aeaf992f5[0] . '">' . $v7aeaf992f5[0] . ($v7aeaf992f5[2] ? '' : ' (Rest)') . '</option>'; } $pf8ed4912 .= '
					</select>
				</div>
				<ul class="mytree">
					<li>
						<label>Root</label>
						<ul layer_url="' . $pf7b73b3a . '"></ul>
					</li>
				</ul>
				<div class="businesslogic">
					<label>Business Logic Service:</label>
					<select></select>
				</div>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>'; } if (isset($pb0e92e25)) { $pf8ed4912 .='<div id="choose_presentation_from_file_manager" class="myfancypopup choose_from_file_manager">
				<div class="broker">
					<label>Broker:</label>
					<select onChange="updateLayerUrlFileManager(this)">'; $pc37695cb = count($pb0e92e25); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7aeaf992f5 = $pb0e92e25[$v43dd7d0051]; $pf8ed4912 .= '<option bean_file_name="' . $v7aeaf992f5[1] . '" bean_name="' . $v7aeaf992f5[2] . '" value="' . $v7aeaf992f5[0] . '">' . $v7aeaf992f5[0] . ($v7aeaf992f5[2] ? '' : ' (Rest)') . '</option>'; } $pf8ed4912 .= '
					</select>
				</div>
				<ul class="mytree">
					<li>
						<label>Root</label>
						<ul layer_url="' . $pf7b73b3a . '"></ul>
					</li>
				</ul>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>'; $pf8ed4912 .= self::f15bc1b79f6($pf7b73b3a, $pb0e92e25); } return $pf8ed4912; } private static function me4f1b76bfe10($v8ffce2a791, $pa0462a8e, $pf7b73b3a, $v54c4a1fbb7, $pe2f0f7f1, $v828ff69e5c, $v5483bfa973, $v9fda9fad47, $pf864769c, $paf75a67c, $v6e9af47944, $pb0e92e25) { $pdf10c8d2 = self::f7b6f77050d($v8ffce2a791, $pa0462a8e, $v6e9af47944, $v9fda9fad47, $pf864769c, $paf75a67c, $pb0e92e25); $v195d5f6856 = '
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
			</ul>'; $pf8ed4912 = '
			<div id="choose_property_variable_from_file_manager" class="myfancypopup choose_from_file_manager">
				<div class="type">
					<label>Variable Type:</label>
					<select onChange="onChangePropertyVariableType(this)">
						<option value="new_var">New Variable</option>
						<option value="class_prop_var">Class Property Variable</option>
						<option value="existent_var">Existent Variable</option>
					</select>
				</div>
				<div class="variable_type new_var">
					<div class="group">
						<label>Group:</label>
						<select>
							<option value="">Local variable</option>
							<option value="_GET">Variable from URL</option>
							<option value="_POST">Variable from POST form</option>
							<option value="_COOKIE">Variable from Cookies</option>
							<option value="GLOBALS">Global variable</option>
							<option value="_ENV">Environment variable</option>
						</select>
					</div>
					<div class="name">
						<label>Name:</label>
						<input />
						<span class="icon add" onClick="addNewVarSubGroupToProgrammingTaskChooseCreatedVariablePopup(this)" title="Add a sub group">Add</span>
						<ul class="sub_group"></ul>
					</div>
				</div>
				<div class="variable_type class_prop_var" style="display:none">
					' . $v195d5f6856 . '
					<div class="property">
						<label>Property:</label>
						<select></select>
					</div>
				</div>
				<div class="variable_type existent_var" style="display:none">
					<div class="variable">
						<label>Variable:</label>
						<select></select>
					</div>
				</div>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>
	
			<div id="choose_method_from_file_manager" class="myfancypopup choose_from_file_manager">
				' . $v195d5f6856 . '
				<div class="method">
					<label>Method:</label>
					<select></select>
				</div>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>
	
			<div id="choose_function_from_file_manager" class="myfancypopup choose_from_file_manager">
				' . $v195d5f6856 . '
				<div class="function">
					<label>Function:</label>
					<select></select>
				</div>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>
	
			<div id="choose_file_from_file_manager" class="myfancypopup choose_from_file_manager">
				' . $v195d5f6856 . '
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>
	
			<div id="choose_block_from_file_manager" class="myfancypopup choose_from_file_manager">
				<ul class="mytree">
					<li>
						<label>' . $pdf10c8d2 . '</label>
						<ul url="' . str_replace("#bean_file_name#", $pa0462a8e, str_replace("#bean_name#", $v8ffce2a791, $pf7b73b3a)) . '"></ul>
					</li>
				</ul>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>'; return $pf8ed4912; } private static function f2ad93f0276($v8ffce2a791, $pa0462a8e, $pf7b73b3a, $v54c4a1fbb7, $pe2f0f7f1, $v828ff69e5c, $v5483bfa973, $v9fda9fad47, $pf864769c, $paf75a67c, $v6e9af47944, $pb0e92e25) { $v195d5f6856 = '
			<ul class="mytree">'; if (isset($v6e9af47944)) foreach ($v6e9af47944 as $v7aeaf992f5) { $v195d5f6856 .= '
				<li>
					<label>' . $v7aeaf992f5[0] . ($v7aeaf992f5[2] ? '' : ' (Rest)') . '</label>
					<ul url="' . str_replace("#bean_file_name#", $v7aeaf992f5[1], str_replace("#bean_name#", $v7aeaf992f5[2], $pf7b73b3a)) . '"></ul>
				</li>'; } if (isset($pb0e92e25)) foreach ($pb0e92e25 as $v7aeaf992f5) { $v195d5f6856 .= '
				<li>
					<label>' . $v7aeaf992f5[0] . ($v7aeaf992f5[2] ? '' : ' (Rest)') . '</label>
					<ul url="' . str_replace("#bean_file_name#", $v7aeaf992f5[1], str_replace("#bean_name#", $v7aeaf992f5[2], $pf7b73b3a)) . '"></ul>
				</li>'; } $v195d5f6856 .= '
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
			</ul>'; $pf8ed4912 = '
			<div id="choose_property_variable_from_file_manager" class="myfancypopup choose_from_file_manager">
				<div class="type">
					<label>Variable Type:</label>
					<select onChange="onChangePropertyVariableType(this)">
						<option value="new_var">New Variable</option>
						<option value="class_prop_var">Class Property Variable</option>
						<option value="existent_var">Existent Variable</option>
					</select>
				</div>
				<div class="variable_type new_var">
					<div class="scope">
						<label>Scope:</label>
						<select>
							<option value="">Local variable</option>
							<option value="_GET">Variable from URL</option>
							<option value="_POST">Variable from POST form</option>
							<option value="_COOKIE">Variable from Cookies</option>
							<option value="GLOBALS">Global variable</option>
							<option value="_ENV">Environment variable</option>
						</select>
					</div>
					<div class="name">
						<label>Name:</label>
						<input />
						<span class="icon add" onClick="addNewVarSubGroupToProgrammingTaskChooseCreatedVariablePopup(this)" title="Add a sub group">Add</span>
						<ul class="sub_group"></ul>
					</div>
				</div>
				<div class="variable_type class_prop_var" style="display:none">
					' . $v195d5f6856 . '
					<div class="property">
						<label>Property:</label>
						<select></select>
					</div>
				</div>
				<div class="variable_type existent_var" style="display:none">
					<div class="variable">
						<label>Variable:</label>
						<select></select>
					</div>
				</div>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>
	
			<div id="choose_method_from_file_manager" class="myfancypopup choose_from_file_manager">
				' . $v195d5f6856 . '
				<div class="method">
					<label>Method:</label>
					<select></select>
				</div>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>
	
			<div id="choose_function_from_file_manager" class="myfancypopup choose_from_file_manager">
				' . $v195d5f6856 . '
				<div class="function">
					<label>Function:</label>
					<select></select>
				</div>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>
	
			<div id="choose_file_from_file_manager" class="myfancypopup choose_from_file_manager">
				' . $v195d5f6856 . '
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>
	
			<div id="choose_block_from_file_manager" class="myfancypopup choose_from_file_manager">
				<ul class="mytree">'; if (isset($pb0e92e25)) foreach ($pb0e92e25 as $v7aeaf992f5) { $pf8ed4912 .= '
					<li>
						<label>' . $v7aeaf992f5[0] . ($v7aeaf992f5[2] ? '' : ' (Rest)') . '</label>
						<ul url="' . str_replace("#bean_file_name#", $v7aeaf992f5[1], str_replace("#bean_name#", $v7aeaf992f5[2], $pf7b73b3a)) . '"></ul>
					</li>'; } $pf8ed4912 .= '
				</ul>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>'; return $pf8ed4912; } private static function f7b6f77050d($v8ffce2a791, $pa0462a8e, $v6e9af47944, $v9fda9fad47, $pf864769c, $paf75a67c, $pb0e92e25) { $pec746181 = array($v6e9af47944, $v9fda9fad47, $pf864769c, $paf75a67c, $pb0e92e25); foreach ($pec746181 as $pa8b0de28) if ($pa8b0de28) foreach ($pa8b0de28 as $v1e79db4422) { $v2b2cf4c0eb = $v1e79db4422[0]; $pf2073d1f = $v1e79db4422[1]; $pef1b7ad7 = $v1e79db4422[2]; if ($pf2073d1f == $pa0462a8e && $pef1b7ad7 == $v8ffce2a791) return $v2b2cf4c0eb; } return $v8ffce2a791; } private static function f15bc1b79f6($pf7b73b3a, $pb0e92e25) { $pf8ed4912 = ''; if (isset($pb0e92e25)) { $pf8ed4912 .='<div id="choose_page_url_from_file_manager" class="myfancypopup choose_from_file_manager">
				<div class="broker">
					<label>Broker:</label>
					<select onChange="updateLayerUrlFileManager(this)">'; $pc37695cb = count($pb0e92e25); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7aeaf992f5 = $pb0e92e25[$v43dd7d0051]; $pf8ed4912 .= '<option bean_file_name="' . $v7aeaf992f5[1] . '" bean_name="' . $v7aeaf992f5[2] . '" value="' . $v7aeaf992f5[0] . '">' . $v7aeaf992f5[0] . ($v7aeaf992f5[2] ? '' : ' (Rest)') . '</option>'; } $pf8ed4912 .= '
					</select>
				</div>
				<ul class="mytree">
					<li>
						<label>Root</label>
						<ul layer_url="' . $pf7b73b3a . '"></ul>
					</li>
				</ul>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>
			
			<div id="choose_image_url_from_file_manager" class="myfancypopup choose_from_file_manager">
				<div class="broker">
					<label>Broker:</label>
					<select onChange="updateLayerUrlFileManager(this)">'; $pc37695cb = count($pb0e92e25); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v7aeaf992f5 = $pb0e92e25[$v43dd7d0051]; $pf8ed4912 .= '<option bean_file_name="' . $v7aeaf992f5[1] . '" bean_name="' . $v7aeaf992f5[2] . '" value="' . $v7aeaf992f5[0] . '">' . $v7aeaf992f5[0] . ($v7aeaf992f5[2] ? '' : ' (Rest)') . '</option>'; } $pf8ed4912 .= '
					</select>
				</div>
				<ul class="mytree">
					<li>
						<label>Root</label>
						<ul layer_url="' . $pf7b73b3a . '"></ul>
					</li>
				</ul>
				<div class="button">
					<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>'; } return $pf8ed4912; } public static function getCodeEditorMenuHtml($v5d3813882f) { $v2101769c38 = $v5d3813882f["show_pretty_print"] ? $v5d3813882f["show_pretty_print"] : true; $pe7b2a888 = $v5d3813882f["generate_tasks_flow_from_code_label"] ? $v5d3813882f["generate_tasks_flow_from_code_label"] : "Generate Diagram from Code"; $pd880cf7e = $v5d3813882f["generate_tasks_flow_from_code_func"] ? $v5d3813882f["generate_tasks_flow_from_code_func"] : "generateTasksFlowFromCode"; $v13b5585fac = $v5d3813882f["generate_code_from_tasks_flow_label"] ? $v5d3813882f["generate_code_from_tasks_flow_label"] : "Generate Code From Diagram"; $v0fa2a905e0 = $v5d3813882f["generate_code_from_tasks_flow_func"] ? $v5d3813882f["generate_code_from_tasks_flow_func"] : "generateCodeFromTasksFlow"; return '<ul>
			<li class="editor_settings" title="Open Editor Setings"><a onClick="openEditorSettings()"><i class="icon settings"></i> Open Editor Setings</a></li>
			' . ($v2101769c38 ? '<li class="pretty_print" title="Pretty Print Code"><a onClick="prettyPrintCode()"><i class="icon pretty_print"></i> Pretty Print Code</a></li>' : '') . '
			<li class="set_word_wrap" title="Set Word Wrap"><a onClick="setWordWrap(this)" wrap="0"><i class="icon word_wrap"></i> Word Wrap</i></a></li>
			<li class="separator"></li>
			<li class="generate_tasks_flow_from_code" title="' . $pe7b2a888 . '"><a onClick="' . $pd880cf7e . '(true, {force: true});return false;"><i class="icon generate_tasks_flow_from_code"></i> ' . $pe7b2a888 . '</a></li>
			<li class="generate_code_from_tasks_flow" title="' . $v13b5585fac . '"><a onClick="' . $v0fa2a905e0 . '(true, {force: true});return false;"><i class="icon generate_code_from_tasks_flow"></i> ' . $v13b5585fac . '</a></li>
			<li class="separator"></li>
			<li class="editor_full_screen" title="Maximize/Minimize Editor Screen"><a onClick="toggleCodeEditorFullScreen(this)"><i class="icon full_screen"></i> Maximize Editor Screen</a></li>
			<li class="separator"></li>
			<li class="auto_save_activation" title="Is Auto Save Active" onClick="toggleAutoSaveCheckbox(this, onTogglePHPCodeAutoSave)"><i class="icon auto_save_activation"></i> <span>Enable Auto Save</span> <input type="checkbox" value="1" /></li>
			<li class="auto_convert_activation" title="Is Auto Convert Active" onClick="toggleAutoConvertCheckbox(this, onTogglePHPCodeAutoConvert)"><i class="icon auto_convert_activation"></i> <span>Enable Auto Convert</span> <input type="checkbox" value="1" /></li>
			<li class="save" title="Save"><a onClick="' . $v5d3813882f["save_func"] . '()"><i class="icon save"></i> Save</a></li>
		</ul>'; } public static function getCodeEditorHtml($v067674f4e4, $v48e4e778a5, $pefdd2109, $v3d55458bcd, $v5039a77f9d, $v188b4f5fa6, $pdf77ee66, $v8ffce2a791, $pa0462a8e, $pf7b73b3a, $pe6619ae3, $pd0054995, $v9106c07f80, $v5988353a93 = false) { $pf8ed4912 = '
			<div class="code_menu">
				' . self::getCodeEditorMenuHtml($v48e4e778a5) . '
			</div>
			<div class="layout_ui_editor reverse fixed_side_properties">
				<textarea' . ($v5988353a93 ? ' class="full-source"' : '') . '>' . htmlspecialchars($v067674f4e4, ENT_NOQUOTES) . '</textarea>
				
				<div class="layout-ui-menu-widgets-backup hidden">
					' . $pefdd2109 . '
				</div>
			</div>
			<div id="layout_ui_editor_right_container" class="layout_ui_editor_right_container">
				' . self::getTabContentTemplateLayoutTreeHtml($v3d55458bcd, $v5039a77f9d, $v188b4f5fa6, $pdf77ee66, $v8ffce2a791, $pa0462a8e, $pf7b73b3a, $pe6619ae3, $v9106c07f80) . '
			</div>
			
			<div id="choose_layout_ui_editor_module_block_from_file_manager" class="myfancypopup choose_from_file_manager">
				' . self::getTabContentTemplateLayoutTreeHtml($v3d55458bcd, $v5039a77f9d, $v188b4f5fa6, $pdf77ee66, $v8ffce2a791, $pa0462a8e, $pf7b73b3a, $pe6619ae3, "chooseCodeLayoutUIEditorModuleBlockFromFileManagerTree") . '
				
				<div class="button">
					<input type="button" value="Update" onClick="MyCodeLayoutUIEditorFancyPopup.settings.updateFunction(this)" />
				</div>
			</div>
			
			<div class="myfancypopup db_table_uis_diagram_block" create_page_presentation_uis_diagram_block_url="' . $pd0054995 . '">
				<iframe></iframe>
			</div>'; return $pf8ed4912; } public static function getTabContentTemplateLayoutTreeHtml($v3d55458bcd, $v5039a77f9d, $v08d9602741, $pdf77ee66, $v8ffce2a791, $pa0462a8e, $pf7b73b3a, $pe6619ae3, $v9106c07f80) { $v9ab35f1f0d = $v08d9602741->getPresentationLayer(); $pe61ee068 = $v08d9602741->getCommonProjectName() . "/" . $v9ab35f1f0d->settings["presentation_modules_path"]; $v9b98e0e818 = WorkFlowBeansFileHandler::getLayerDBDrivers($v3d55458bcd, $v5039a77f9d, $v9ab35f1f0d, true); $v1eb9193558 = new LayoutTypeProjectHandler($pdf77ee66, $v3d55458bcd, $v5039a77f9d, $pa0462a8e, $v8ffce2a791); $v1eb9193558->filterLayerBrokersDBDriversPropsBasedInUrl($v9b98e0e818, $pf7b73b3a); $pf8ed4912 = '<script>
			//clones module_folder properties, bc is the same object that the ui["folder"] properties, this is, is the reference object of the ui["folder"] object.
			main_layers_properties.' . $v8ffce2a791 . '.ui["module_folder"] = JSON.parse(JSON.stringify(main_layers_properties.' . $v8ffce2a791 . '.ui["module_folder"])); 
			
			//adds the new changes only to the ui["module_folder"] properties
			main_layers_properties.' . $v8ffce2a791 . '.ui["module_folder"]["attributes"] = {
				folder_path: "#path#",
				module_info_func_name: "showModuleInfoWithSmartPosition",
			};'; if ($v9b98e0e818) foreach ($v9b98e0e818 as $v5ba36af525 => $pa9b2090f) if ($pa9b2090f) $pf8ed4912 .= '
			main_layers_properties["' . $pa9b2090f[2] . '"] = {
				ui: {
					table: {
						attributes: {
							table: "#name#",
						},
					},
				},
			};'; $pf8ed4912 .= '</script>
		<ul class="mytree">
			<li data-jstree="{\'icon\':\'cms_module\'}">
				<label>Modules <i class="icon refresh" onClick="refreshTreeBlocksOrModulesFolder(event, this, ' . $v9106c07f80 . ')">Refresh</i></label>
				<ul url="' . str_replace("#path#", $pe61ee068, str_replace("#bean_file_name#", $pa0462a8e, str_replace("#bean_name#", $v8ffce2a791, $pf7b73b3a))) . '&item_type=presentation&folder_type=module"></ul>
			</li>
			<li data-jstree="{\'icon\':\'blocks_folder\'}">
				<label>Blocks <i class="icon refresh" onClick="refreshTreeBlocksOrModulesFolder(event, this, ' . $v9106c07f80 . ')">Refresh</i></label>
				<ul url="' . str_replace("#bean_file_name#", $pa0462a8e, str_replace("#bean_name#", $v8ffce2a791, $pf7b73b3a)) . '"></ul>
			</li>
			<li data-jstree="{\'icon\':\'main_node_db\'}">
				<label>DB Drivers</label>
				<ul>'; if ($v9b98e0e818) foreach ($v9b98e0e818 as $v5ba36af525 => $pa9b2090f) { if ($pa9b2090f) $pf8ed4912 .= '
					<li data-jstree="{\'icon\':\'db_driver\'}" db_driver_name="' . $v5ba36af525 . '" db_driver_bean_name="' . $pa9b2090f[2] . '" db_driver_bean_file_name="' . $pa9b2090f[1] . '">
						<label>' . ucwords(str_replace("_", " ", $v5ba36af525)) . '</label>
						<ul>
							<li data-jstree="{\'icon\':\'db_management\'}" db_type="db">
								<label>DB Server Tables <i class="icon refresh" onClick="refreshTreeBlocksOrModulesFolder(event, this, ' . $v9106c07f80 . ')">Refresh</i></label>
								<ul url="' . str_replace("#type#", "", str_replace("#bean_file_name#", $pa9b2090f[1], str_replace("#bean_name#", $pa9b2090f[2], $pe6619ae3))) . '"></ul>
							</li>
							<li data-jstree="{\'icon\':\'db_diagram\'}" db_type="diagram">
								<label>DB Diagram Tables <i class="icon refresh" onClick="refreshTreeBlocksOrModulesFolder(event, this, ' . $v9106c07f80 . ')">Refresh</i></label>
								<ul url="' . str_replace("#type#", "diagram", str_replace("#bean_file_name#", $pa9b2090f[1], str_replace("#bean_name#", $pa9b2090f[2], $pe6619ae3))) . '"></ul>
							</li>
						</ul>
					</li>'; else $pf8ed4912 .= '
					<li data-jstree="{\'icon\':\'db_driver\'}" db_driver_name="' . $v5ba36af525 . '">
						<label>' . ucwords(str_replace("_", " ", $v5ba36af525)) . ' (Rest)</label>
					</li>'; } $pf8ed4912 .= '</ul>
			</li>
		</ul>'; return $pf8ed4912; } public static function getTaskFlowContentHtml($pcfdeae4e, $v5d3813882f) { $pe7b2a888 = $v5d3813882f["generate_tasks_flow_from_code_label"] ? $v5d3813882f["generate_tasks_flow_from_code_label"] : "Generate Diagram from Code"; $pd880cf7e = $v5d3813882f["generate_tasks_flow_from_code_func"] ? $v5d3813882f["generate_tasks_flow_from_code_func"] : "generateTasksFlowFromCode"; $v13b5585fac = $v5d3813882f["generate_code_from_tasks_flow_label"] ? $v5d3813882f["generate_code_from_tasks_flow_label"] : "Generate Code From Diagram"; $v0fa2a905e0 = $v5d3813882f["generate_code_from_tasks_flow_func"] ? $v5d3813882f["generate_code_from_tasks_flow_func"] : "generateCodeFromTasksFlow"; $v243e50bc1d = array( "Sort Tasks" => array( "class" => "sort_tasks", "html" => '<a onClick="sortWorkflowTask();return false;"><i class="icon sort"></i> Sort Tasks</a>', "childs" => array( "Sort Type 1" => array( "class" => "sort_tasks", "click" => "sortWorkflowTask(1);return false;" ), "Sort Type 2" => array( "class" => "sort_tasks", "click" => "sortWorkflowTask(2);return false;" ), "Sort Type 3" => array( "class" => "sort_tasks", "click" => "sortWorkflowTask(3);return false;" ), "Sort Type 4" => array( "class" => "sort_tasks", "click" => "sortWorkflowTask(4);return false;" ), ) ), 1 => array( "class" => "separator", "title" => " ", "html" => " ", ), "Flush Cache" => array( "class" => "flush_cache", "html" => '<a onClick="flushCache();return false;"><i class="icon flush_cache"></i> Flush Cache</a>', ), "Empty Diagram" => array( "class" => "empty_diagram", "html" => '<a onClick="emptyDiagam();return false;"><i class="icon empty_diagram"></i> Empty Diagram</a>', ), 2 => array( "class" => "separator", "title" => " ", "html" => " ", ), $pe7b2a888 => array( "class" => "generate_tasks_flow_from_code", "html" => '<a onClick="' . $pd880cf7e . '(true, {force: true});return false;"><i class="icon generate_tasks_flow_from_code"></i> ' . $pe7b2a888 . '</a>', ), $v13b5585fac => array( "class" => "generate_code_from_tasks_flow", "html" => '<a onClick="' . $v0fa2a905e0 . '(true, {force: true});return false;"><i class="icon generate_code_from_tasks_flow"></i> ' . $v13b5585fac . '</a>', ), 3 => array( "class" => "separator", "title" => " ", "html" => " ", ), "Maximize/Minimize Editor Screen" => array( "class" => "tasks_flow_full_screen", "html" => '<a onClick="toggleTaskFlowFullScreen(this);return false;"><i class="icon full_screen"></i> Maximize Editor Screen</a>', ), 4 => array( "class" => "separator", "title" => " ", "html" => " ", ), "Auto Save On" => array( "class" => "auto_save_activation", "title" => "Is Auto Save Active", "html" => '<a onClick="toggleAutoSaveCheckbox(this, onTogglePHPCodeAutoSave)"><i class="icon auto_save_activation"></i> <span>Enable Auto Save</span> <input type="checkbox" value="1" /></a>' ), "Auto Convert On" => array( "class" => "auto_convert_activation", "title" => "Is Auto Convert Active", "html" => '<a onClick="toggleAutoConvertCheckbox(this, onTogglePHPCodeAutoConvert)"><i class="icon auto_convert_activation"></i> <span>Enable Auto Convert</span> <input type="checkbox" value="1" /></a>' ), "Save" => array( "class" => "save", "html" => '<a onClick="' . $v5d3813882f["save_func"] . '();return false;"><i class="icon save"></i> Save</a>', ), ); $pcfdeae4e->setMenus($v243e50bc1d); return $pcfdeae4e->getContent(); } public static function validateHtmlTagsBeforeConvertingToCodeTags($v067674f4e4) { $v446afd1219 = true; preg_match_all("/(<style|<\/style|<script|<\/script|<\?|\?>)/i", $v067674f4e4, $pbae7526c); $pbae7526c = $pbae7526c[0]; $v664387d23c = $v7f8da6644e = $v226207be64 = false; $pc37695cb = count($pbae7526c); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v6107abf109 = $pbae7526c[$v43dd7d0051]; if (strpos($v6107abf109, "<?") !== false) { $v664387d23c = true; if ($v7f8da6644e || $v226207be64) { $v446afd1219 = false; break; } } else if (strpos($v6107abf109, "?>") !== false) { $v664387d23c = false; } else if (strpos($v6107abf109, "<style") !== false) { $v7f8da6644e = true; if ($v664387d23c || $v226207be64) { $v446afd1219 = false; break; } } else if (strpos($v6107abf109, "</style") !== false) { $v7f8da6644e = false; } else if (strpos($v6107abf109, "<script") !== false) { $v226207be64 = true; if ($v7f8da6644e || $v664387d23c) { $v446afd1219 = false; break; } } else if (strpos($v6107abf109, "</script") !== false) { $v226207be64 = false; } } if ($v446afd1219) { preg_match("/<([\w]+)([^>]*)(<\?)(.+)(\?>)([^>]*)>/u", $v067674f4e4, $pbae7526c); $v446afd1219 = count($pbae7526c) == 0; } return $v446afd1219; } public static function convertHtmlTagsToCodeTags($v067674f4e4) { $v067674f4e4 = preg_replace("/<([\w]+)([^>]*)(<\?)(.+)(\?>)([^>]*)>/u", "<$1$2&lt; ?$4? &gt;$6>", $v067674f4e4); $v067674f4e4 = preg_replace("/<script\s+([^>]*)src=(\"|')([^\"']+)(\"|')([^>]*)>(.*)<\/script>/iu", '<pre><code class="language-html">&lt; script $1src=$2$3$4$5&gt;$6&lt; /script&gt;</code></pre>', $v067674f4e4); $v067674f4e4 = str_replace("?>", '</code></pre>', str_replace(array("<? ", "<?php "), '<pre><code class="language-php">', str_replace("<?=", '<pre><code class="language-php">echo ', $v067674f4e4))); $v067674f4e4 = preg_replace("/<\/style>/i", '</code></pre>', preg_replace("/<style([^>]*)>/i", '<pre><code class="language-css">', $v067674f4e4)); $v067674f4e4 = preg_replace("/<\/script>/i", '</code></pre>', preg_replace("/<script([^>]*)>/i", '<pre><code class="language-javascript">', $v067674f4e4)); return $v067674f4e4; } public static function convertCodeTagsToHtmlTags($pf8ed4912) { $pf8ed4912 = preg_replace("/<pre>\s+<code\s+/i", "<pre><code ", $pf8ed4912); $pf8ed4912 = preg_replace('/<pre><code\s+class="language-html">(&lt;|<)(\s*)script\s+([^>]*)src=("|\')([^"\']+)("|\')([^>]*)(&gt;|>)(.*)(&lt;|<)(\s*)\/script(\s*)(&gt;|>)<\/code><\/pre>/iu', '<script $3src=$4$5$6$7>$9</script>', $pf8ed4912); $pf8ed4912 = preg_replace("/<([\w]+)([^>]*)((&lt;|<)(\s*)\?)(.+)(\?(\s*)(&gt;|>))([^>]*)>/u", "<$1$2<?$6?>$10>", $pf8ed4912); $pf8ed4912 = preg_replace('/<pre><code\s+class="language-php">(.*)<\/code><\/pre>/iu', "<? $1 ?>", $pf8ed4912); $pf8ed4912 = preg_replace('/<pre><code\s+class="language-css">(.*)<\/code><\/pre>/iu', "<style>\n$1\n</style>", $pf8ed4912); $pf8ed4912 = preg_replace('/<pre><code\s+class="language-javascript">(.*)<\/code><\/pre>/iu', "<script>\n$1\n</script>", $pf8ed4912); $pf8ed4912 = preg_replace('/<pre><code\s+class="language-php">([^<]+)/iu', "<? $1 ?>", $pf8ed4912); $pf8ed4912 = preg_replace('/<pre><code\s+class="language-css">([^<]+)/iu', "<style>\n$1\n</style>", $pf8ed4912); $pf8ed4912 = preg_replace('/<pre><code\s+class="language-javascript">([^<]+)/iu', "<script>\n$1\n</script>", $pf8ed4912); $pf8ed4912 = str_replace('</code></pre>', "", $pf8ed4912); $pf8ed4912 = str_replace('&gt;', ">", $pf8ed4912); $pf8ed4912 = str_replace('&lt;', "<", $pf8ed4912); return $pf8ed4912; } public static function getHtmlTagProps($v067674f4e4, $pf4b9d8e6, $v5d3813882f = false) { $v9073377656 = array( "html_attributes" => "", "inline_code" => "", ); $v4430104888 = stripos($v067674f4e4, "<$pf4b9d8e6>"); if ($v4430104888 !== false) { $v7db54bb786 = ""; $pbaeb17fb = $v4430104888 + strlen($pf4b9d8e6) + 1; } else { $v4430104888 = stripos($v067674f4e4, "<$pf4b9d8e6 "); if ($v4430104888 === false) { $v7db54bb786 = ""; $pbaeb17fb = false; } else { $v619a1c0905 = $v4430104888 + strlen($pf4b9d8e6) + 2; $pbaeb17fb = strpos($v067674f4e4, ">", $v619a1c0905); $v95a4985a1d = strpos($v067674f4e4, "<?", $v619a1c0905); if ($v95a4985a1d !== false && $v95a4985a1d < $pbaeb17fb) { $pf33edf1c = strpos($v067674f4e4, "?>", $v95a4985a1d + 2); $pbaeb17fb = $pf33edf1c !== false ? strpos($v067674f4e4, ">", $pf33edf1c + 2) : false; } $v7db54bb786 = $pbaeb17fb !== false && $pbaeb17fb > $v619a1c0905 ? substr($v067674f4e4, $v619a1c0905, $pbaeb17fb - $v619a1c0905) : ""; $v9073377656["html_attributes"] = $v7db54bb786; } } if ($v5d3813882f["get_inline_code"]) { if ($v4430104888 !== false && $pbaeb17fb !== false) { $pacc959a1 = strripos($v067674f4e4, "</$pf4b9d8e6>", $pbaeb17fb + 1); if ($pacc959a1 !== false) { $pb757efe0 = substr($v067674f4e4, $pbaeb17fb + 1, $pacc959a1 - $pbaeb17fb - 1); $v9073377656["inline_code"] = $pb757efe0; } } } return $v9073377656; } } ?>
