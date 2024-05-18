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
include_once get_lib("org.phpframework.compression.ZipHandler"); include_once get_lib("org.phpframework.util.web.SSHHandler"); include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler"); include_once get_lib("org.phpframework.cms.wordpress.WordPressUrlsParser"); include_once get_lib("org.phpframework.db.DBDumperHandler"); include_once $EVC->getUtilPath("WorkFlowBeansConverter"); include_once $EVC->getUtilPath("WorkFlowDBHandler"); include_once $EVC->getUtilPath("WorkFlowTestUnitHandler"); include_once $EVC->getUtilPath("CMSObfuscatePHPFilesHandler"); include_once $EVC->getUtilPath("CMSObfuscateJSFilesHandler"); include_once $EVC->getUtilPath("CMSDeploymentSecurityHandler"); class CMSDeploymentHandler { private $pdb9e96e6; private $v28d8163063; private $v5039a77f9d; private $v3d55458bcd; private $pc0fc7d17; private $pf665220f; private $v4d8ea562f0; private $pe9c62c08; private $v32892bba0c; private $v1d2a79cac4; private $pb4703992; private $v76822a17cd; private $pcf87c229; private $v121d0ed499; private static $v79bc342305 = "strip_comments=1&strip_eol=1"; private static $v37b6496be7 = "encoding=Normal&fast_decode=1&special_chars=0&remove_semi_colons=1&allowed_domains=#allowed_domains#&check_allowed_domains_port=#check_allowed_domains_port#"; private static $pa2a1daed = array(".", "..", ".git", ".gitignore", ".htpasswd", ".svn"); public function __construct($pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v28d8163063, $v5039a77f9d, $v3d55458bcd, $pc0fc7d17, $pf665220f) { $this->pdb9e96e6 = $pdb9e96e6; $this->v28d8163063 = $v28d8163063; $this->v5039a77f9d = $v5039a77f9d; $this->v3d55458bcd = $v3d55458bcd; $this->pc0fc7d17 = $pc0fc7d17; $this->pf665220f = $pf665220f; $this->v1d2a79cac4 = array(); $pb4201a4b = WorkFlowTasksFileHandler::getTaskFilePathByPath($this->pdb9e96e6, "deployment"); $this->pcf87c229 = new WorkFlowTasksFileHandler($pb4201a4b); $this->pcf87c229->init(); $this->v4d8ea562f0 = $this->pcf87c229->getWorkflowData(); $pb4201a4b = WorkFlowTasksFileHandler::getTaskFilePathByPath($this->pdb9e96e6, "layer"); $this->v121d0ed499 = new WorkFlowTasksFileHandler($pb4201a4b); $this->v121d0ed499->init(); $this->pe9c62c08 = $this->v121d0ed499->getWorkflowData(); $this->v32892bba0c = self::getTasksByLabel($this->pe9c62c08); $pecad7cca = new WorkFlowTaskHandler($v4bf8d90f04, $pfce4d1b3); $pecad7cca->setCacheRootPath(LAYER_CACHE_PATH); $pecad7cca->setAllowedTaskFolders(array("layer/")); $pecad7cca->initWorkFlowTasks(); $v9becf46403 = $pecad7cca->getTasksByTag("dbdriver"); $v7f3b80e443 = $pecad7cca->getTasksByTag("db"); $pa4a33082 = $pecad7cca->getTasksByTag("dataaccess"); $v79988b7e32 = $pecad7cca->getTasksByTag("businesslogic"); $v808e567c37 = $pecad7cca->getTasksByTag("presentation"); $this->pb4703992 = array( "dbdriver" => isset($v9becf46403[0]["type"]) ? $v9becf46403[0]["type"] : null, "db" => isset($v7f3b80e443[0]["type"]) ? $v7f3b80e443[0]["type"] : null, "dataaccess" => isset($pa4a33082[0]["type"]) ? $pa4a33082[0]["type"] : null, "businesslogic" => isset($v79988b7e32[0]["type"]) ? $v79988b7e32[0]["type"] : null, "presentation" => isset($v808e567c37[0]["type"]) ? $v808e567c37[0]["type"] : null, ); $this->v76822a17cd = fileowner(__FILE__); } public function __destruct() { $this->f18cdcce536(); } public function executeServerAction($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $v1b5ae9c139) { $v50890f6f30 = array(); $pddc51a8e = array(); if ($v8a4ed461b2 && is_numeric($v1495c93fca) && is_numeric($pee4ccbfa) && $v1b5ae9c139) { $paf0ee1a7 = self::getServerTaskTemplate($this->v4d8ea562f0, $v8a4ed461b2, $v1495c93fca); if ($paf0ee1a7) { $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; if (!$v4f2e308cee) $pddc51a8e[] = "Error: server_installation_folder_path cannot be undefined!"; else if (self::validateServerTemplateLicenceData($paf0ee1a7, $this->pf665220f, $pddc51a8e)) { $pb399d8f4 = self::getServerTask($this->v4d8ea562f0, $v8a4ed461b2); $peccb5caa = isset($pb399d8f4["properties"]) ? $pb399d8f4["properties"] : null; $v8c3308965d = new SSHHandler(); $v8c3308965d->setSSHAuthKeyTmpFolderPath($this->v28d8163063); $v49ddb047e0 = array( "host" => isset($peccb5caa["host"]) ? $peccb5caa["host"] : null, "port" => isset($peccb5caa["port"]) ? $peccb5caa["port"] : null, "username" => isset($peccb5caa["username"]) ? $peccb5caa["username"] : null, "fingerprint" => isset($peccb5caa["server_fingerprint"]) ? $peccb5caa["server_fingerprint"] : null, ); $pe17b9f1e = isset($peccb5caa["authentication_type"]) ? $peccb5caa["authentication_type"] : null; switch ($pe17b9f1e) { case "key_files": $v49ddb047e0["ssh_auth_pub_file"] = isset($peccb5caa["ssh_auth_pub_file"]) ? $peccb5caa["ssh_auth_pub_file"] : null; $v49ddb047e0["ssh_auth_priv_file"] = isset($peccb5caa["ssh_auth_pri_file"]) ? $peccb5caa["ssh_auth_pri_file"] : null; $v49ddb047e0["ssh_auth_passphrase"] = isset($peccb5caa["ssh_auth_passphrase"]) ? $peccb5caa["ssh_auth_passphrase"] : null; break; case "key_strings": $v49ddb047e0["ssh_auth_pub_string"] = isset($peccb5caa["ssh_auth_pub"]) ? $peccb5caa["ssh_auth_pub"] : null; $v49ddb047e0["ssh_auth_priv_string"] = isset($peccb5caa["ssh_auth_pri"]) ? $peccb5caa["ssh_auth_pri"] : null; $v49ddb047e0["ssh_auth_passphrase"] = isset($peccb5caa["ssh_auth_passphrase"]) ? $peccb5caa["ssh_auth_passphrase"] : null; break; default: $v49ddb047e0["password"] = isset($peccb5caa["password"]) ? $peccb5caa["password"] : null; } $pc35af75f = $v8c3308965d->connect($v49ddb047e0); if (!$pc35af75f) $pddc51a8e[] = "Error: Server not connected!"; else { switch($v1b5ae9c139) { case "deploy": $pa31acaa4 = false; $this->deploy($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e, $pa31acaa4); $v50890f6f30["deployment_created"] = $pa31acaa4; break; case "redeploy": $v4006cac5f2 = null; $this->redeploy($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e, $pa31acaa4, $v4006cac5f2); $v50890f6f30["deployment_created"] = $pa31acaa4; $v50890f6f30["redeployed_deployment_id"] = $v4006cac5f2; break; case "rollback": $pbd9b32bd = null; $this->rollback($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e, $pbd9b32bd); $v50890f6f30["rollbacked_deployment_id"] = $pbd9b32bd; break; case "clean": case "cleantemps": case "clean_temps": $this->cleanTemps($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); break; case "delete": $this->delete($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); break; default: $pddc51a8e[] = "Error: Invalid deployment action: '$v1b5ae9c139'!"; } } $v8c3308965d->disconnect(); } } else $pddc51a8e[] = "Error: Template '$v1495c93fca' in '$v8a4ed461b2' server does not exists!"; } else $pddc51a8e[] = "Wrong inputs. Please check your request and confirm that server_name, template_id, deployment_id and action are not blank fields."; $v50890f6f30["status"] = empty($pddc51a8e); $v50890f6f30["error_message"] = $pddc51a8e ? implode("\n", $pddc51a8e) : ""; return $v50890f6f30; } public function delete($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { $this->cleanTemps($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $v21c3221d67 = "$v4f2e308cee/.backups/backup_{$v1495c93fca}_$pee4ccbfa/"; if (!$v8c3308965d->removeRemoteFile($v21c3221d67)) $pddc51a8e[] = "Error: '$v21c3221d67' could not be removed in the remote server."; } public function cleanTemps($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { $this->mde110f874a53($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $pddc51a8e); $this->mafb911d03063($v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); } public function rollback($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null, &$pbd9b32bd = null) { $v08a367fe04 = $this->f154d53d339("rollback", $v8a4ed461b2, $v1495c93fca, $pee4ccbfa); $this->v1d2a79cac4[] = $v08a367fe04; $this->f5c516423df($v08a367fe04, $pddc51a8e); $this->f8b85a83380($v08a367fe04, $v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e, $pbd9b32bd); $this->me202360dafc0($v08a367fe04); } public function redeploy($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null, &$pa31acaa4 = null, &$v4006cac5f2 = null) { $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $v21c3221d67 = "$v4f2e308cee/.backups/backup_{$v1495c93fca}_$pee4ccbfa/"; $v55bd236ac1 = isset($paf0ee1a7["properties"]["actions"]) ? $paf0ee1a7["properties"]["actions"] : null; if ($v55bd236ac1) { $this->f7821c1018c($v55bd236ac1); foreach ($v55bd236ac1 as $v342a134247) foreach ($v342a134247 as $v256e3a39a7 => $v1b5ae9c139) if (!empty($v1b5ae9c139["active"]) && !$pa2507f6c) switch($v256e3a39a7) { case "run_test_units": break; case "migrate_dbs": $pa31acaa4 = true; $pd93e9b1d = "$v21c3221d67/migrate_dbs.php"; if (!$v8c3308965d->getFileInfo($pd93e9b1d)) $pddc51a8e[] = "Error: Cannot execute '$pd93e9b1d' file because does not exists in server!"; else { $v7bd5d88a74 = $v8c3308965d->exec("php '$pd93e9b1d'"); if (trim($v7bd5d88a74) !== "1") $pddc51a8e[] = "Error: '$pd93e9b1d' script not executed in remote server!" . ($v7bd5d88a74 ? "\n" . $v7bd5d88a74 : ""); } break; case "copy_layers": $pa31acaa4 = true; $pd93e9b1d = "$v21c3221d67/copy_layers.php"; if (!$v8c3308965d->getFileInfo($pd93e9b1d)) $pddc51a8e[] = "Error: Cannot execute '$pd93e9b1d' file because does not exists in server!"; else { $v32eab72720 = array( 'tmp/cache/', 'tmp/workflow/', 'tmp/program/', 'tmp/deployment/', 'app/__system/layer/presentation/phpframework/webroot/__system/cache/', 'app/__system/layer/presentation/test/webroot/__system/cache/', 'app_old/', 'other_old/', 'vendor_old/', ); $this->f9585076d09($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $v32eab72720, $pddc51a8e); $v7bd5d88a74 = $v8c3308965d->exec("php '$pd93e9b1d'"); if (trim($v7bd5d88a74) !== "1") $pddc51a8e[] = "Error: '$pd93e9b1d' script not executed in remote server!" . ($v7bd5d88a74 ? "\n" . $v7bd5d88a74 : ""); $this->f9585076d09($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $v32eab72720, $pddc51a8e); } break; case "copy_files": $pa31acaa4 = true; $this->f498d659433($v1b5ae9c139, $paf0ee1a7, $v8c3308965d, $pddc51a8e); break; case "execute_shell_cmds": $pa31acaa4 = true; $this->f168f9c60ae($v1b5ae9c139, $v8c3308965d, $pddc51a8e); break; } } if ($pa31acaa4) { $this->md0001dcd79dc($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); $this->f3de97a601f($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); } $v4006cac5f2 = $this->f959e6574c3($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); } public function deploy($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null, &$pa31acaa4 = null) { $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $v55bd236ac1 = isset($paf0ee1a7["properties"]["actions"]) ? $paf0ee1a7["properties"]["actions"] : null; $pa2507f6c = false; if ($v55bd236ac1) { $this->f7821c1018c($v55bd236ac1); foreach ($v55bd236ac1 as $v342a134247) foreach ($v342a134247 as $v256e3a39a7 => $v1b5ae9c139) if ($v1b5ae9c139 && !empty($v1b5ae9c139["active"]) && !$pa2507f6c) switch($v256e3a39a7) { case "run_test_units": $this->f00b34663a8($v1b5ae9c139, $pddc51a8e, $pa2507f6c); break; case "migrate_dbs": $pa31acaa4 = true; $this->f6777bb62b7($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); $this->f2e8ab59eec($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); break; case "copy_layers": $pa31acaa4 = true; $this->f6777bb62b7($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); $this->mb54b1001772c($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $v1b5ae9c139, $paf0ee1a7, $v8c3308965d, $pddc51a8e); break; case "copy_files": $pa31acaa4 = true; $this->f6777bb62b7($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); $this->f498d659433($v1b5ae9c139, $paf0ee1a7, $v8c3308965d, $pddc51a8e); break; case "execute_shell_cmds": $pa31acaa4 = true; $this->f6777bb62b7($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); $this->f168f9c60ae($v1b5ae9c139, $v8c3308965d, $pddc51a8e); break; } } if ($pa31acaa4) { $this->md0001dcd79dc($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); $this->f3de97a601f($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); } } private function f9585076d09($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $v32eab72720, &$pddc51a8e = null) { $v08a367fe04 = $this->f154d53d339("flush_cache", $v8a4ed461b2, $v1495c93fca, $pee4ccbfa); $this->v1d2a79cac4[] = $v08a367fe04; $this->f813ad9e10e($v08a367fe04, $pddc51a8e); $this->f239c4e4ce5($v08a367fe04, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $v32eab72720, $pddc51a8e); $this->me202360dafc0($v08a367fe04); } private function f813ad9e10e($v08a367fe04, &$pddc51a8e = null) { if (!$this->mfb9a66a40371($v08a367fe04)) { $pddc51a8e[] = "Error: Could not create '" . $v08a367fe04 . "' folder!"; return false; } } private function f239c4e4ce5($v08a367fe04, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $v32eab72720, &$pddc51a8e = null) { $pb375ad0f = isset($paf0ee1a7["properties"]["server_installation_url"]) ? $paf0ee1a7["properties"]["server_installation_url"] : null; $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $pffbb2f92 = isset($v1b5ae9c139["server_relative_folder_path"]) ? $v1b5ae9c139["server_relative_folder_path"] : null; if ($v4f2e308cee && substr($v4f2e308cee, -1) != "/") $v4f2e308cee .= "/"; $pf976bc80 = "remove_cache_on_this_server_{$v1495c93fca}_{$pee4ccbfa}_" . rand(1000000, 9999999) . ".php"; $v0b42f762a1 = $v08a367fe04 . $pf976bc80; $pbfe2cc61 = $v4f2e308cee . $pf976bc80; $v515f8d44e8 = "set_cache_permissions_on_this_server_{$v1495c93fca}_{$pee4ccbfa}_" . rand(1000000, 9999999) . ".php"; $pc7c0727a = $v08a367fe04 . $v515f8d44e8; $v90d61578a5 = $v4f2e308cee . $v515f8d44e8; if (!$this->me729921d3313($v4f2e308cee, $v0b42f762a1, $v32eab72720, $pddc51a8e)) $pddc51a8e[] = "Error: Could not create flush cache php file!"; else if (!$this->f9f81305eb7($v4f2e308cee, $pc7c0727a, $v32eab72720, $pddc51a8e)) $pddc51a8e[] = "Error: Could not create flush cache php file!"; else if (!$v8c3308965d->copyLocalToRemoteFile($v0b42f762a1, $pbfe2cc61, true)) $pddc51a8e[] = "Error: Could not scp '$pf976bc80' to remote server folder: '$pffbb2f92'!"; else if (!$v8c3308965d->copyLocalToRemoteFile($pc7c0727a, $v90d61578a5, true)) $pddc51a8e[] = "Error: Could not scp '$v515f8d44e8' to remote server folder: '$pffbb2f92'!"; else { $pb375ad0f = (strpos($pb375ad0f, "://") === false ? "http://" : "") . $pb375ad0f; $v1ae34537ad = parse_url($pb375ad0f); $pd97bc935 = isset($v1ae34537ad["user"]) ? $v1ae34537ad["user"] : null; $v8a9d082c74 = isset($v1ae34537ad["pass"]) ? $v1ae34537ad["pass"] : null; unset($v1ae34537ad["user"]); unset($v1ae34537ad["pass"]); $v1ae34537ad["path"] = isset($v1ae34537ad["path"]) ? $v1ae34537ad["path"] : null; $v1ae34537ad["path"] .= !empty($v1ae34537ad["path"]) && substr($v1ae34537ad["path"], -1) != "/" ? "/" : ""; $v1ae34537ad["path"] .= $v515f8d44e8; $v6f3a2700dd = $this->me9618f011deb($v1ae34537ad); $v30857f7eca = array( "url" => $v6f3a2700dd, "settings" => array( "follow_location" => 1, "connection_timeout" => 60, ) ); if ($pd97bc935 || $v8a9d082c74) { $v30857f7eca["settings"]["http_auth"] = !empty($_SERVER["AUTH_TYPE"]) ? $_SERVER["AUTH_TYPE"] : "basic"; $v30857f7eca["settings"]["user_pwd"] = $pd97bc935 . ":" . $v8a9d082c74; } $v56a64ecb97 = new MyCurl(); $v56a64ecb97->initSingle($v30857f7eca); $v56a64ecb97->get_contents(); $pae77d38c = $v56a64ecb97->getData(); $v516dc3ab74 = isset($pae77d38c[0]["info"]["http_code"]) ? $pae77d38c[0]["info"]["http_code"] : null; if (substr($v516dc3ab74, 0, 1) != 2) $pddc51a8e[] = "Error: Could not flush remote cache with url: '$v6f3a2700dd'"; $v7bd5d88a74 = $v8c3308965d->exec("php '$pbfe2cc61'"); if (trim($v7bd5d88a74) !== "1") $pddc51a8e[] = "Error: '$pf976bc80' script not executed in remote server!" . ($v7bd5d88a74 ? "\n" . $v7bd5d88a74 : ""); } $v89d33f4133 = dirname($pbfe2cc61) . "/"; $v2cd5d67337 = $v8c3308965d->scanRemoteDir($v89d33f4133); $v2cd5d67337 = $v2cd5d67337 ? array_diff($v2cd5d67337, self::$pa2a1daed) : null; $v8675f09557 = explode("_", pathinfo($pf976bc80, PATHINFO_FILENAME)); array_pop($v8675f09557); $v8675f09557 = implode("_", $v8675f09557) . "_"; $v8139e37105 = explode("_", pathinfo($v515f8d44e8, PATHINFO_FILENAME)); array_pop($v8139e37105); $v8139e37105 = implode("_", $v8139e37105) . "_"; if ($v2cd5d67337) foreach ($v2cd5d67337 as $v3a3060fe4b) if ($v3a3060fe4b && (substr($v3a3060fe4b, 0, strlen($v8675f09557)) == $v8675f09557 || substr($v3a3060fe4b, 0, strlen($v8139e37105)) == $v8139e37105)) $v8c3308965d->removeRemoteFile($v89d33f4133 . $v3a3060fe4b); } private function f00b34663a8($v1b5ae9c139, &$pddc51a8e = null, &$pa2507f6c = null) { $pabc9cf6e = array(); $v6ee393d9fb = isset($v1b5ae9c139["files"]) ? $v1b5ae9c139["files"] : null; $v6ee393d9fb = $v6ee393d9fb && !is_array($v6ee393d9fb) ? array($v6ee393d9fb) : $v6ee393d9fb; $v4a542af67c = new WorkFlowTestUnitHandler($this->v3d55458bcd, $this->v5039a77f9d); $v4a542af67c->initBeanObjects(); if (!$v6ee393d9fb) $v4a542af67c->executeTest("", $pabc9cf6e); else foreach ($v6ee393d9fb as $v7dffdb5a5b) $v4a542af67c->executeTest($v7dffdb5a5b, $pabc9cf6e); $v8a29987473 = ""; foreach ($pabc9cf6e as $v7dffdb5a5b => $v7bd5d88a74) if (empty($v7bd5d88a74["status"])) $v8a29987473 .= "\n- " . ($v7dffdb5a5b ? $v7dffdb5a5b . ": " : "") . (isset($v7bd5d88a74["error"]) ? $v7bd5d88a74["error"] : null); if ($v8a29987473) { $pddc51a8e[] = "Error executing the following Test-Units: " . $v8a29987473; $pa2507f6c = true; } } private function f168f9c60ae($v1b5ae9c139, $v8c3308965d, &$pddc51a8e = null) { $v3614b7d285 = isset($v1b5ae9c139["cmds"]) ? $v1b5ae9c139["cmds"] : null; $v7bd5d88a74 = trim( $v8c3308965d->exec($v3614b7d285) ); if ($v7bd5d88a74) $pddc51a8e[] = "Error: executing shell script: " . $v7bd5d88a74; } private function f498d659433($v1b5ae9c139, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $pffbb2f92 = isset($v1b5ae9c139["server_relative_folder_path"]) ? $v1b5ae9c139["server_relative_folder_path"] : null; $v9aca49c8f1 = "$v4f2e308cee/$pffbb2f92/"; $v6ee393d9fb = isset($v1b5ae9c139["files"]) ? $v1b5ae9c139["files"] : null; $v6ee393d9fb = $v6ee393d9fb && !is_array($v6ee393d9fb) ? array($v6ee393d9fb) : $v6ee393d9fb; if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if ($v7dffdb5a5b && !$v8c3308965d->copyLocalToRemoteFile(CMS_PATH . $v7dffdb5a5b, $v9aca49c8f1 . basename($v7dffdb5a5b), true)) $pddc51a8e[] = "Error: Could not scp '$v7dffdb5a5b' to remote server folder: '$pffbb2f92'!"; } private function mde110f874a53($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, &$pddc51a8e = null) { $v84e8cdb0b6 = $this->f154d53d339("rollback", $v8a4ed461b2, $v1495c93fca, $pee4ccbfa); $pdd3572ed = $this->f154d53d339("backup", $v8a4ed461b2, $v1495c93fca, $pee4ccbfa); $v1dcfa21e81 = $this->f154d53d339("copy_layers", $v8a4ed461b2, $v1495c93fca, $pee4ccbfa); $v85ab4b0950 = $this->f154d53d339("migrate_dbs", $v8a4ed461b2, $v1495c93fca, $pee4ccbfa); $v1b9e8ad6f0 = $this->f154d53d339("version", $v8a4ed461b2, $v1495c93fca, $pee4ccbfa); $v8400bb9d4d = $this->v28d8163063 . "/" . basename($v84e8cdb0b6) . ".zip"; $v034f9864e1 = $this->v28d8163063 . "/" . basename($pdd3572ed) . ".zip"; $pad481f86 = $this->v28d8163063 . "/" . basename($v1dcfa21e81) . ".zip"; $v34b214fb5d = $this->v28d8163063 . "/" . basename($v85ab4b0950) . ".zip"; $v57a9807e67 = array($v84e8cdb0b6, $pdd3572ed, $v1dcfa21e81, $v85ab4b0950, $v1b9e8ad6f0, $v8400bb9d4d, $v034f9864e1, $pad481f86, $v34b214fb5d); $v615214f2fd = array(); foreach ($v57a9807e67 as $pa32be502) if (!$this->me202360dafc0($pa32be502)) $v615214f2fd[] = $pa32be502; if ($v615214f2fd) $pddc51a8e[] = "Error: There were some files that could not be removed. Files: \n- " . implode("\n- ", $v615214f2fd); } private function mafb911d03063($v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $v21c3221d67 = "$v4f2e308cee/.backups/backup_{$v1495c93fca}_$pee4ccbfa/"; if ($v8c3308965d->isDir($v21c3221d67)) { $v57a9807e67 = array($v21c3221d67 . "rollbacking/", $v21c3221d67 . "deploying/"); $v615214f2fd = array(); foreach ($v57a9807e67 as $pa32be502) if (!$v8c3308965d->removeRemoteFile($pa32be502)) $v615214f2fd[] = $pa32be502; if ($v615214f2fd) $pddc51a8e[] = "Error: There were some files that could not be removed in the remote server. Files: " . implode("\n- ", $v615214f2fd); } } private function f5c516423df($v08a367fe04, &$pddc51a8e = null) { $v8326538c64 = basename($v08a367fe04); if (!$this->mfb9a66a40371($v08a367fe04)) { $pddc51a8e[] = "Error: Could not create '$v8326538c64' folder!"; return false; } if (!$this->me1bfc9cf0775(CMS_PATH . "app/lib/org/phpframework/db", $v08a367fe04 . "lib/org/phpframework/db/")) { $pddc51a8e[] = "Error: Could not copy app/lib/org/phpframework/db/ folder!"; return false; } if (!$this->mb2ac558f4dad(CMS_PATH . "app/lib/org/phpframework/util/text/TextSanitizer.php", $v08a367fe04 . "lib/org/phpframework/util/text/TextSanitizer.php")) { $pddc51a8e[] = "Error: Could not copy app/lib/org/phpframework/util/text/TextSanitizer.php file!"; return false; } if (!$this->me1bfc9cf0775(CMS_PATH . "app/lib/org/phpframework/compression/", $v08a367fe04 . "lib/org/phpframework/compression/")) { $pddc51a8e[] = "Error: Could not copy app/lib/org/phpframework/compression/ folder!"; return false; } if (!$this->me1bfc9cf0775(CMS_PATH . "app/lib/vendor/sqlparser/", $v08a367fe04 . "lib/vendor/sqlparser/")) { $pddc51a8e[] = "Error: Could not copy app/lib/vendor/sqlparser/ folder!"; return false; } $v5f0471d66c = $this->v28d8163063 . "/$v8326538c64.zip"; $this->v1d2a79cac4[] = $v5f0471d66c; if (!ZipHandler::zip($v08a367fe04, $v5f0471d66c)) { $pddc51a8e[] = "Error: Could not create zip '$v8326538c64.zip' file!"; return false; } if (!ZipHandler::renameFileInZip($v5f0471d66c, $v8326538c64, "rollback")) { $pddc51a8e[] = "Error: Could not rename '$v8326538c64' to 'rollback' in zip file: '$v8326538c64.zip'!"; return false; } $v02a69d4e0f = $v5f0471d66c; $v5f0471d66c = $v08a367fe04 . "/rollback.zip"; if (!rename($v02a69d4e0f, $v5f0471d66c)) { $pddc51a8e[] = "Error: Could not move '$v8326538c64.zip' file to $v8326538c64/rollback.zip!"; return false; } } private function f8b85a83380($v08a367fe04, $v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null, &$pbd9b32bd = null) { if ($pddc51a8e) return false; $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $v1d696dbd12 = isset($paf0ee1a7["properties"]["task"]) ? $paf0ee1a7["properties"]["task"] : null; $v80c5ba5219 = self::getTasksPropsByLabel($v1d696dbd12); $v21c3221d67 = "$v4f2e308cee/.backups/backup_{$v1495c93fca}_$pee4ccbfa/"; $v5f0471d66c = $v08a367fe04 . "/rollback.zip"; if (!$v8c3308965d->isDir($v21c3221d67)) $pddc51a8e[] = "Error: '$v21c3221d67' folder does NOT exist in remote server!"; else { $pa6116ee8 = "$v08a367fe04/rollback.php"; if (!$this->me7b57319d49d($v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v4f2e308cee, $v80c5ba5219, $pa6116ee8, $pddc51a8e)) $pddc51a8e[] = "Error: Could not create rollback deployment php file!"; else { if (!$v8c3308965d->copyLocalToRemoteFile($pa6116ee8, $v21c3221d67 . basename($pa6116ee8), true, 0640)) $pddc51a8e[] = "Error: Could not scp rollback deployment php file to remote server!"; else { if (!$v8c3308965d->copyLocalToRemoteFile($v5f0471d66c, $v21c3221d67 . basename($v5f0471d66c), true, 0640)) $pddc51a8e[] = "Error: Could not scp rollback.zip file to remote server!"; else { $v2cd5d67337 = $v8c3308965d->scanRemoteDir($v4f2e308cee); $v2cd5d67337 = $v2cd5d67337 ? array_diff($v2cd5d67337, self::$pa2a1daed) : array(); $v32eab72720 = array(); foreach ($v2cd5d67337 as $v3a3060fe4b) if (substr(pathinfo($v3a3060fe4b, PATHINFO_FILENAME), -4) == "_old") $v32eab72720[] = $v3a3060fe4b; $this->f9585076d09($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $v32eab72720, $pddc51a8e); $pd93e9b1d = "$v21c3221d67/rollback.php"; $v7bd5d88a74 = $v8c3308965d->exec("php '$pd93e9b1d'"); $v4ba78dbf79 = true; if (trim($v7bd5d88a74) !== "1") $pddc51a8e[] = "Error: '$pd93e9b1d' script not executed in remote server!" . ($v7bd5d88a74 ? "\n" . $v7bd5d88a74 : ""); else { $v4ba78dbf79 = !$this->f804c35fa1c($v8c3308965d, $v4f2e308cee); $pbd9b32bd = 0; } $v2cd5d67337 = $v8c3308965d->scanRemoteDir($v4f2e308cee); $v2cd5d67337 = $v2cd5d67337 ? array_diff($v2cd5d67337, self::$pa2a1daed) : array(); $v32eab72720 = array("tmp/cache/"); foreach ($v2cd5d67337 as $v3a3060fe4b) if (substr(pathinfo($v3a3060fe4b, PATHINFO_FILENAME), -4) == "_old") $v32eab72720[] = $v3a3060fe4b; $this->f9585076d09($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $v32eab72720, $pddc51a8e); if ($v4ba78dbf79) $this->f433419f921($v08a367fe04, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pbd9b32bd, $pddc51a8e); } } } } } private function f6777bb62b7($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { $v08a367fe04 = $this->f154d53d339("backup", $v8a4ed461b2, $v1495c93fca, $pee4ccbfa); $this->v1d2a79cac4[] = $v08a367fe04; $this->f371eb5d05d($v08a367fe04, $paf0ee1a7, $pddc51a8e); $this->f4dc1b9714a($v08a367fe04, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); $this->me202360dafc0($v08a367fe04); } private function f371eb5d05d($v08a367fe04, &$pddc51a8e = null) { $v8326538c64 = basename($v08a367fe04); if (!$this->mfb9a66a40371($v08a367fe04)) { $pddc51a8e[] = "Error: Could not create '$v8326538c64' folder!"; return false; } if (!$this->mb2ac558f4dad(CMS_PATH . "app/lib/org/phpframework/compression/ZipHandler.php", $v08a367fe04 . "lib/org/phpframework/compression/ZipHandler.php")) { $pddc51a8e[] = "Error: Could not copy app/lib/org/phpframework/compression/ZipHandler.php file!"; return false; } $v5f0471d66c = $this->v28d8163063 . "/$v8326538c64.zip"; $this->v1d2a79cac4[] = $v5f0471d66c; if (!ZipHandler::zip($v08a367fe04, $v5f0471d66c)) { $pddc51a8e[] = "Error: Could not create zip '$v8326538c64.zip' file!"; return false; } if (!ZipHandler::renameFileInZip($v5f0471d66c, $v8326538c64, "backup")) { $pddc51a8e[] = "Error: Could not rename '$v8326538c64' to 'backup' in zip file: '$v8326538c64.zip'!"; return false; } $v02a69d4e0f = $v5f0471d66c; $v5f0471d66c = $v08a367fe04 . "/backup.zip"; if (!rename($v02a69d4e0f, $v5f0471d66c)) { $pddc51a8e[] = "Error: Could not move '$v8326538c64.zip' file to $v8326538c64/backup.zip!"; return false; } } private function f4dc1b9714a($v08a367fe04, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { if ($pddc51a8e) return false; $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $v21c3221d67 = "$v4f2e308cee/.backups/backup_{$v1495c93fca}_$pee4ccbfa/"; $v5f0471d66c = $v08a367fe04 . "/backup.zip"; if (!$v8c3308965d->createRemoteFolder($v21c3221d67, 0755, true)) $pddc51a8e[] = "Error: Could not create '$v21c3221d67' folder in remote server!"; else { $pa6116ee8 = "$v08a367fe04/backup.php"; if (!$this->f1d59576218($v1495c93fca, $pee4ccbfa, $v4f2e308cee, $pa6116ee8, $pddc51a8e)) $pddc51a8e[] = "Error: Could not create backup deployment php file!"; else { if (!$v8c3308965d->copyLocalToRemoteFile($pa6116ee8, $v21c3221d67 . basename($pa6116ee8), true, 0640)) $pddc51a8e[] = "Error: Could not scp backup deployment php file to remote server!"; else { if (!$v8c3308965d->copyLocalToRemoteFile($v5f0471d66c, $v21c3221d67 . basename($v5f0471d66c), true, 0640)) $pddc51a8e[] = "Error: Could not scp backup.zip file to remote server!"; else { $pd93e9b1d = "$v21c3221d67/backup.php"; $v7bd5d88a74 = $v8c3308965d->exec("php '$pd93e9b1d'"); if (trim($v7bd5d88a74) !== "1") $pddc51a8e[] = "Error: '$pd93e9b1d' script not executed in remote server!" . ($v7bd5d88a74 ? "\n" . $v7bd5d88a74 : ""); } } } } if (file_exists($v5f0471d66c)) unlink($v5f0471d66c); } private function mb54b1001772c($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $v1b5ae9c139, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { $v08a367fe04 = $this->f154d53d339("copy_layers", $v8a4ed461b2, $v1495c93fca, $pee4ccbfa); $this->v1d2a79cac4[] = $v08a367fe04; $this->mecb847e8130c($v08a367fe04, $v1b5ae9c139, $paf0ee1a7, $pddc51a8e); $this->f87141b87d3($v08a367fe04, $v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); $this->me202360dafc0($v08a367fe04); } private function mecb847e8130c($v08a367fe04, $v1b5ae9c139, $paf0ee1a7, &$pddc51a8e = null) { $v8326538c64 = basename($v08a367fe04); $v8ad572cc50 = isset($paf0ee1a7["properties"]["connection"]) ? $paf0ee1a7["properties"]["connection"] : null; $v44ff379d4c = self::getConnectionsPropsByTaskLabels($v8ad572cc50); $v80c5ba5219 = self::getTasksPropsByLabel($paf0ee1a7["properties"]["task"]); $v5296fbf21d = isset($paf0ee1a7["properties"]["global_settings"]) ? $paf0ee1a7["properties"]["global_settings"] : null; $v8625b218a4 = isset($paf0ee1a7["properties"]["global_vars"]) ? $paf0ee1a7["properties"]["global_vars"] : null; $pd95c1d81 = isset($v1b5ae9c139["sysadmin"]) ? $v1b5ae9c139["sysadmin"] : null; $pee07b646 = isset($v1b5ae9c139["vendor"]) ? $v1b5ae9c139["vendor"] : null; $pbe55fdb5 = isset($v1b5ae9c139["dao"]) ? $v1b5ae9c139["dao"] : null; $pfd932dcd = isset($v1b5ae9c139["modules"]) ? $v1b5ae9c139["modules"] : null; $v342eb0949e = isset($v1b5ae9c139["obfuscate_proprietary_php_files"]) ? $v1b5ae9c139["obfuscate_proprietary_php_files"] : null; $v0a3ddb91ac = isset($v1b5ae9c139["obfuscate_proprietary_js_files"]) ? $v1b5ae9c139["obfuscate_proprietary_js_files"] : null; $v0cba298be6 = isset($v1b5ae9c139["allowed_domains"]) ? $v1b5ae9c139["allowed_domains"] : null; $pd0a05d6a = isset($v1b5ae9c139["check_allowed_domains_port"]) ? $v1b5ae9c139["check_allowed_domains_port"] : null; $pdc56407c = isset($v1b5ae9c139["create_licence"]) ? $v1b5ae9c139["create_licence"] : null; if (!$this->pf665220f["asm"] && $pd95c1d81) $pd95c1d81 = false; if (!$this->mfb9a66a40371($v08a367fe04)) { $pddc51a8e[] = "Error: Could not create '$v8326538c64' folder!"; return false; } if (!$this->f9a54b53aad(CMS_PATH, $v08a367fe04)) { $pddc51a8e[] = "Error: Could not copy hidden files for root folder!"; return false; } $this->mb2ac558f4dad(CMS_PATH . "README.txt", $v08a367fe04 . "README.txt"); $this->mb2ac558f4dad(CMS_PATH . "LICENSE.txt", $v08a367fe04 . "LICENSE.txt"); $this->mb2ac558f4dad(CMS_PATH . "LICENCE.txt", $v08a367fe04 . "LICENCE.txt"); if (!$this->mfb9a66a40371($v08a367fe04 . "app")) { $pddc51a8e[] = "Error: Could not create '{$v8326538c64}/app' folder!"; return false; } if (!$this->f29c1bff20e(CMS_PATH . "app", $v08a367fe04 . "app")) { $pddc51a8e[] = "Error: Could not set same permissions for app folder!"; return false; } if (!$this->f9a54b53aad(CMS_PATH . "app", $v08a367fe04 . "app")) { $pddc51a8e[] = "Error: Could not copy hidden files for root folder!"; return false; } if (!$this->mb2ac558f4dad(CMS_PATH . "app/app.php", $v08a367fe04 . "app/app.php")) { $pddc51a8e[] = "Error: Could not copy app/app.php file!"; return false; } if (!$this->me1bfc9cf0775(CMS_PATH . "app/lib", $v08a367fe04 . "app/lib")) { $pddc51a8e[] = "Error: Could not copy app/lib folder!"; return false; } $v2bdab1bf22 = $v08a367fe04 . "layers.xml"; if (!$this->f7211ccf19b($v2bdab1bf22, $v80c5ba5219, $v44ff379d4c)) { $pddc51a8e[] = "Error: Could not create layers diagram file in: other/workflow/layer/layers.xml!"; return false; } if ($pd95c1d81) { if (!$this->me1bfc9cf0775(CMS_PATH . "app/__system", $v08a367fe04 . "app/__system")) { $pddc51a8e[] = "Error: Could not copy app/__system folder!"; return false; } if (!$this->mfb9a66a40371($v08a367fe04 . "other")) { $pddc51a8e[] = "Error: Could not create other folder!"; return false; } if (!$this->f29c1bff20e(CMS_PATH . "other", $v08a367fe04 . "other")) { $pddc51a8e[] = "Error: Could not set same permissions for other folder!"; return false; } if (!$this->f9a54b53aad(CMS_PATH . "other", $v08a367fe04 . "other")) { $pddc51a8e[] = "Error: Could not copy hidden files for other folder!"; return false; } if (!$this->me1bfc9cf0775(CMS_PATH . "other/authdb", $v08a367fe04 . "other/authdb")) { $pddc51a8e[] = "Error: Could not copy other/authdb folder!"; return false; } if (!$this->me1bfc9cf0775(CMS_PATH . "other/workflow", $v08a367fe04 . "other/workflow")) { $pddc51a8e[] = "Error: Could not copy other/workflow folder!"; return false; } $this->mb2ac558f4dad($v2bdab1bf22, $v08a367fe04 . "other/workflow/layer/layers.xml"); $this->me202360dafc0($v08a367fe04 . "app/__system/layer/presentation/phpframework/webroot/__system/cache/"); $this->me202360dafc0($v08a367fe04 . "app/__system/layer/presentation/test/webroot/__system/cache/"); if (!$pfd932dcd) { if (!$this->mff5674a370ff($v08a367fe04 . "app/__system/layer/presentation/common/src/module/")) $pddc51a8e[] = "Error: Could not delete 'app/__system/layer/presentation/common/src/module/' sub-folders!"; if (!$this->mff5674a370ff($v08a367fe04 . "app/__system/layer/presentation/common/webroot/module/")) $pddc51a8e[] = "Error: Could not delete 'app/__system/layer/presentation/common/webroot/module/' sub-folders!"; } } else { if (!$this->me202360dafc0($v08a367fe04 . "app/lib/org/phpframework/workflow/task/")) $pddc51a8e[] = "Error: Could not delete 'app/lib/org/phpframework/workflow/task/' folder!"; } if ($pee07b646) { if (!$this->me1bfc9cf0775(CMS_PATH . "vendor", $v08a367fe04 . "vendor")) { $pddc51a8e[] = "Error: Could not copy vendor folder!"; return false; } if (!$pd95c1d81) $this->me202360dafc0($v08a367fe04 . "vendor/testunit"); } if ($pbe55fdb5) { if (!$this->me1bfc9cf0775(CMS_PATH . "vendor/dao", $v08a367fe04 . "vendor/dao")) { $pddc51a8e[] = "Error: Could not copy vendor folder!"; return false; } if (!$pee07b646 && !$this->f7940b0ef34(CMS_PATH . "vendor", $v08a367fe04 . "vendor", CMS_PATH . "vendor")) $pddc51a8e[] = "Error: Could not copy hidden files for vendor/dao parent folder, this is, for vendor folder!"; } else if (!$this->me202360dafc0($v08a367fe04 . "vendor/dao")) $pddc51a8e[] = "Error: Could not delete vendor/dao folder!"; if (!$this->mfb9a66a40371($v08a367fe04 . "tmp")) { $pddc51a8e[] = "Error: Could not create tmp folder!"; return false; } if (!$this->f29c1bff20e(CMS_PATH . "tmp", $v08a367fe04 . "tmp")) { $pddc51a8e[] = "Error: Could not set same permissions for tmp folder!"; return false; } if (!$this->f9a54b53aad(CMS_PATH . "tmp", $v08a367fe04 . "tmp")) { $pddc51a8e[] = "Error: Could not copy hidden files for tmp folder!"; return false; } if (!$this->mfb9a66a40371($v08a367fe04 . "app/layer")) { $pddc51a8e[] = "Error: Could not create '{$v8326538c64}/app/layer' folder!"; return false; } if (!$this->f29c1bff20e(CMS_PATH . "app/layer", $v08a367fe04 . "app/layer")) { $pddc51a8e[] = "Error: Could not set same permissions for app/layer folder!"; return false; } if (!$this->f9a54b53aad(CMS_PATH . "app/layer", $v08a367fe04 . "app/layer")) { $pddc51a8e[] = "Error: Could not copy hidden files for app/layer folder!"; return false; } if (!$this->mfb9a66a40371($v08a367fe04 . "app/config")) { $pddc51a8e[] = "Error: Could not create app/config folder!"; return false; } if (!$this->f29c1bff20e(CMS_PATH . "app/config", $v08a367fe04 . "app/config")) { $pddc51a8e[] = "Error: Could not set same permissions for app/config folder!"; return false; } if (!$this->f9a54b53aad(CMS_PATH . "app/config", $v08a367fe04 . "app/config")) { $pddc51a8e[] = "Error: Could not copy hidden files for app/config folder!"; return false; } $v33cf0e9504 = $v08a367fe04 . "app/config/" . pathinfo($this->pc0fc7d17, PATHINFO_BASENAME); if (!$this->f866b8c7922($v33cf0e9504, $v5296fbf21d)) { $pddc51a8e[] = "Error: Could not set global settings file!"; return false; } $pf74eddf7 = $v08a367fe04 . "app/config/" . pathinfo($this->v3d55458bcd, PATHINFO_BASENAME); if (!$this->f038f5b9c57($pf74eddf7, $v8625b218a4)) { $pddc51a8e[] = "Error: Could not set global variables file!"; return false; } if (!$this->mfb9a66a40371($v08a367fe04 . "app/config/bean")) { $pddc51a8e[] = "Error: Could not create app/config/bean folder!"; return false; } if (!$this->f29c1bff20e(CMS_PATH . "app/config/bean", $v08a367fe04 . "app/config/bean")) { $pddc51a8e[] = "Error: Could not set same permissions for app/config/bean folder!"; return false; } if (!$this->f9a54b53aad(CMS_PATH . "app/config/bean", $v08a367fe04 . "app/config/bean")) { $pddc51a8e[] = "Error: Could not copy hidden files for app/config/bean folder!"; return false; } if (!$this->f277a2a7c8c($v08a367fe04, $pf74eddf7, $v33cf0e9504, $v2bdab1bf22)) { $pddc51a8e[] = "Error: Could not create config-beans files!"; return false; } if ($v80c5ba5219) { $v21323a157b = isset($this->pb4703992["dbdriver"]) ? $this->pb4703992["dbdriver"] : null; $pc9570d63 = isset($this->pb4703992["presentation"]) ? $this->pb4703992["presentation"] : null; foreach ($v80c5ba5219 as $v0a5deb92d8 => $pd747dfc1) { if ($pd747dfc1 && !empty($pd747dfc1["active"])) { $v7f5911d32d = $this->v32892bba0c[$v0a5deb92d8]; $pc8421459 = isset($v7f5911d32d["type"]) ? $v7f5911d32d["type"] : null; if (in_array($pc8421459, $this->pb4703992) && $pc8421459 != $v21323a157b) { $v8fc05368c0 = WorkFlowBeansConverter::getFileNameFromRawLabel($v0a5deb92d8); $v7d0332245c = $v08a367fe04 . "app/layer/$v8fc05368c0/"; $v6ee393d9fb = isset($pd747dfc1["files"]) ? $pd747dfc1["files"] : null; $v6ee393d9fb = $v6ee393d9fb ? (is_array($v6ee393d9fb) ? $v6ee393d9fb : array($v6ee393d9fb)) : null; $v051bc0b931 = CMS_PATH . "app/layer/$v8fc05368c0"; $this->md3ac2febb8c4($pc8421459, $v051bc0b931, $v6ee393d9fb); $v6a8648f409 = empty($v6ee393d9fb); if ($v6ee393d9fb) { $v138097d609 = false; foreach ($v6ee393d9fb as $v7dffdb5a5b) if (!empty(trim($v7dffdb5a5b))) { $v138097d609 = true; break; } if (!$v138097d609) $v6a8648f409 = true; } if (!$v6a8648f409) { if ($this->mfb9a66a40371($v7d0332245c)) { if (!$this->f29c1bff20e($v051bc0b931, $v7d0332245c)) $pddc51a8e[] = "Error: Could not set same permissions for app/layer/$v8fc05368c0 folder!"; if (!$this->f9a54b53aad($v051bc0b931, $v7d0332245c)) $pddc51a8e[] = "Error: Could not copy hidden files for app/layer/$v8fc05368c0 folder!"; foreach ($v6ee393d9fb as $v7dffdb5a5b) if (!empty(trim($v7dffdb5a5b))) { $v9078ce28d1 = CMS_PATH . "app/layer/$v8fc05368c0/$v7dffdb5a5b"; $v143a3d53ba = $v08a367fe04 . "app/layer/$v8fc05368c0/$v7dffdb5a5b"; if (!$this->mb2ac558f4dad($v9078ce28d1, $v143a3d53ba)) $pddc51a8e[] = "Error: Could not copy app/layer/$v8fc05368c0/$v7dffdb5a5b!"; if (!$this->f7940b0ef34($v9078ce28d1, $v143a3d53ba, $v051bc0b931)) $pddc51a8e[] = "Error: Could not copy hidden files for app/layer/$v8fc05368c0/$v7dffdb5a5b parent folders!"; } } else $pddc51a8e[] = "Error: Could not create '{$v8326538c64}/app/layer/$v8fc05368c0' folder!"; } else if (!$this->me1bfc9cf0775($v051bc0b931, $v7d0332245c)) $pddc51a8e[] = "Error: Could not copy app/layer/$v8fc05368c0 folder!"; if (!$pfd932dcd && !$this->mff5674a370ff($v7d0332245c . "module/")) $pddc51a8e[] = "Error: Could not delete 'app/layer/$v8fc05368c0/module/' sub-folders!"; if ($pc8421459 == $pc9570d63) { $v9cf63182c4 = isset($pd747dfc1["default_project"]) ? trim($pd747dfc1["default_project"]) : ""; if ($v9cf63182c4) { $pbfb7ee27 = $v7d0332245c . ".htaccess"; if (!file_exists($pbfb7ee27)) $pddc51a8e[] = "Error: presentation .htaccess file does not exists in '$pbfb7ee27'"; else { $v6490ea3a15 = file_get_contents($pbfb7ee27); $v6490ea3a15 = preg_replace("/(RewriteRule\s*\\^\\$\s*)([\w\-\+]+)(\\/webroot\\/)/u", "$1" . $v9cf63182c4 . "$3", $v6490ea3a15); $v6490ea3a15 = preg_replace("/(RewriteRule\s*\\(\\.\\*\\)\s*)([\w\-\+]+)(\\/webroot\\/\\$1)/u", "$1" . $v9cf63182c4 . "$3", $v6490ea3a15); if (file_put_contents($pbfb7ee27, $v6490ea3a15) === false) $pddc51a8e[] = "Error: trying to save default project in presentation layer: '$v0a5deb92d8' into file '$pbfb7ee27'!"; } } $v74e48a8ea2 = isset($pd747dfc1["wordpress_installations"]) ? $pd747dfc1["wordpress_installations"] : null; $v74e48a8ea2 = $v74e48a8ea2 ? (is_array($v74e48a8ea2) ? $v74e48a8ea2 : array($v74e48a8ea2)) : array(); $pf1cf82ed = "$v7d0332245c/common/webroot/" . WordPressUrlsParser::WORDPRESS_FOLDER_PREFIX . "/"; $v13c7cddea3 = array_diff(scandir($pf1cf82ed), self::$pa2a1daed); foreach ($v13c7cddea3 as $v7dffdb5a5b) if (is_dir("$pf1cf82ed$v7dffdb5a5b") && !in_array($v7dffdb5a5b, $v74e48a8ea2)) if (!CacheHandlerUtil::deleteFolder("$pf1cf82ed$v7dffdb5a5b", true)) $pddc51a8e[] = "Error: trying to delete wordpress installation folder: 'app/layer/$v8fc05368c0/common/webroot/" . WordPressUrlsParser::WORDPRESS_FOLDER_PREFIX . "/$v7dffdb5a5b/'!"; $this->f62aed3ff0e($v80c5ba5219, $pf1cf82ed, $v74e48a8ea2, $pddc51a8e); } } } } } CMSDeploymentSecurityHandler::setSecureFiles($v08a367fe04, $pddc51a8e); if ($pdc56407c) $this->md8b226df25ce($v08a367fe04, $v1b5ae9c139, $pddc51a8e); if ($v342eb0949e || $v0a3ddb91ac) { $pdb6e9632 = $this->f63246789f0($v08a367fe04, $pddc51a8e); $v65ab9b7278 = $this->f824a7af873($v08a367fe04, $pddc51a8e); if ($v342eb0949e) $this->med626f690275($v08a367fe04, $pdb6e9632, $pddc51a8e); if ($v0a3ddb91ac) $this->f23d64f20df($v08a367fe04, $v0cba298be6, $pd0a05d6a, $v65ab9b7278, $pddc51a8e); } if (!$this->f0df21bab0b(CMS_PATH, $v08a367fe04)) $pddc51a8e[] = "Error: Could not set folder files perms for '$v8326538c64' folder!!"; if ($pddc51a8e) return false; $this->me202360dafc0($v2bdab1bf22); $v5f0471d66c = $this->v28d8163063 . "/$v8326538c64.zip"; $this->v1d2a79cac4[] = $v5f0471d66c; if (!ZipHandler::zip($v08a367fe04, $v5f0471d66c)) { $pddc51a8e[] = "Error: Could not create zip '$v8326538c64.zip' file!"; return false; } if (!ZipHandler::renameFileInZip($v5f0471d66c, $v8326538c64, "phpframework")) { $pddc51a8e[] = "Error: Could not rename '$v8326538c64' to 'phpframework' in zip file: '$v8326538c64.zip'!"; return false; } $v02a69d4e0f = $v5f0471d66c; $v5f0471d66c = $v08a367fe04 . "/phpframework.zip"; if (!rename($v02a69d4e0f, $v5f0471d66c)) { $pddc51a8e[] = "Error: Could not move '$v8326538c64.zip' file to $v8326538c64/phpframework.zip!"; return false; } } private function f62aed3ff0e($v80c5ba5219, $pf1cf82ed, $v74e48a8ea2, &$pddc51a8e) { $v5c1c342594 = true; if ($v74e48a8ea2) { $v21323a157b = isset($this->pb4703992["dbdriver"]) ? $this->pb4703992["dbdriver"] : null; foreach ($v74e48a8ea2 as $v7ca94f1428) { foreach ($v80c5ba5219 as $v0a5deb92d8 => $pd747dfc1) if ($v0a5deb92d8 == $v7ca94f1428 && $pd747dfc1 && !empty($pd747dfc1["active"])) { $v7f5911d32d = isset($this->v32892bba0c[$v0a5deb92d8]) ? $this->v32892bba0c[$v0a5deb92d8] : null; $pc8421459 = isset($v7f5911d32d["type"]) ? $v7f5911d32d["type"] : null; if ($pc8421459 == $v21323a157b && !empty($v7f5911d32d["properties"])) { $pd5b162f3 = $pf1cf82ed . "$v7ca94f1428/wp-config.php"; if (file_exists($pd5b162f3)) { $v6490ea3a15 = file_get_contents($pd5b162f3); $pb67a2609 = $pcbf9a3e6 = ""; if (preg_match("/define\s*\(\s*('|\")DB_NAME('|\")\s*,\s*'([^']*)'\s*\)\s*;/", $v6490ea3a15, $v87ae7286da, PREG_OFFSET_CAPTURE)) $pb67a2609 = $v87ae7286da[3][0]; else if (preg_match("/define\s*\(\s*('|\")DB_NAME('|\")\s*,\s*\"([^\"]*)\"\s*\)\s*;/", $v6490ea3a15, $v87ae7286da, PREG_OFFSET_CAPTURE)) $pb67a2609 = $v87ae7286da[3][0]; if (preg_match("/define\s*\(\s*('|\")DB_HOST('|\")\s*,\s*'([^']*)'\s*\)\s*;/", $v6490ea3a15, $v87ae7286da, PREG_OFFSET_CAPTURE)) $pcbf9a3e6 = $v87ae7286da[3][0]; else if (preg_match("/define\s*\(\s*('|\")DB_HOST('|\")\s*,\s*\"([^\"]*)\"\s*\)\s*;/", $v6490ea3a15, $v87ae7286da, PREG_OFFSET_CAPTURE)) $pcbf9a3e6 = $v87ae7286da[3][0]; $v9cd205cadb = explode(":", $pcbf9a3e6); $v771b145261 = $v9cd205cadb[0]; $pd5cc6f73 = isset($v9cd205cadb[1]) ? $v9cd205cadb[1] : null; $pf7f20240 = isset($v7f5911d32d["properties"]["host"]) ? $v7f5911d32d["properties"]["host"] : null; $pd3566259 = isset($v7f5911d32d["properties"]["port"]) ? $v7f5911d32d["properties"]["port"] : null; $pdb430e2e = isset($v7f5911d32d["properties"]["db_name"]) ? $v7f5911d32d["properties"]["db_name"] : null; if ($pf7f20240 == $v771b145261 && $pd3566259 == $pd5cc6f73 && $pdb430e2e == $pb67a2609) { $pec7c82fd = isset($pd747dfc1["db_name"]) ? $pd747dfc1["db_name"] : null; if ($pb67a2609 != $pec7c82fd) $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")DB_NAME('|\")\s*,\s*('|\")$pb67a2609('|\")\s*\)\s*;/", "define('DB_NAME', '" . $pec7c82fd . "');", $v6490ea3a15); $v45455f41a5 = isset($pd747dfc1["host"]) ? $pd747dfc1["host"] : null; $v9ab5695d8e = isset($pd747dfc1["port"]) ? $pd747dfc1["port"] : null; if ($v771b145261 != $v45455f41a5 || $pd5cc6f73 != $v9ab5695d8e) $v6490ea3a15 = preg_replace("/define\s*\(\s*('|\")DB_HOST('|\")\s*,\s*('|\")$pcbf9a3e6('|\")\s*\)\s*;/", "define('DB_HOST', '" . $v45455f41a5 . (is_numeric($v9ab5695d8e) ? ":" . $v9ab5695d8e : "") . "');", $v6490ea3a15); if (file_put_contents($pd5b162f3, $v6490ea3a15) === false) { $pddc51a8e[] = "Could not update credentials to wordpress installation '$v7ca94f1428'."; $v5c1c342594 = false; } } } break; } } } } return $v5c1c342594; } private function f87141b87d3($v08a367fe04, $v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { if ($pddc51a8e) return false; $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $v21c3221d67 = "$v4f2e308cee/.backups/backup_{$v1495c93fca}_$pee4ccbfa/"; $v5f0471d66c = $v08a367fe04 . "/phpframework.zip"; if (!$v8c3308965d->createRemoteFolder($v21c3221d67, 0755, true)) $pddc51a8e[] = "Error: Could not create '$v21c3221d67' folder in remote server!"; else { $pa6116ee8 = "$v08a367fe04/copy_layers.php"; if (!$this->f8fc6027f55($v1495c93fca, $pee4ccbfa, $v4f2e308cee, $pa6116ee8, $pddc51a8e)) $pddc51a8e[] = "Error: Could not create layers deployment php file!"; else { if (!$v8c3308965d->copyLocalToRemoteFile($pa6116ee8, $v21c3221d67 . basename($pa6116ee8), true, 0640)) $pddc51a8e[] = "Error: Could not scp layers deployment php file to remote server!"; else { if (!$v8c3308965d->copyLocalToRemoteFile($v5f0471d66c, $v21c3221d67 . basename($v5f0471d66c), true, 0640)) $pddc51a8e[] = "Error: Could not scp phpframework.zip file to remote server!"; else { $v32eab72720 = array( 'tmp/cache/', 'tmp/workflow/', 'tmp/program/', 'tmp/deployment/', 'app/__system/layer/presentation/phpframework/webroot/__system/cache/', 'app/__system/layer/presentation/test/webroot/__system/cache/', 'app_old/', 'other_old/', 'vendor_old/', ); $this->f9585076d09($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $v32eab72720, $pddc51a8e); $pd93e9b1d = "$v21c3221d67/copy_layers.php"; $v7bd5d88a74 = $v8c3308965d->exec("php '$pd93e9b1d'"); if (trim($v7bd5d88a74) !== "1") $pddc51a8e[] = "Error: '$pd93e9b1d' script not executed in remote server!" . ($v7bd5d88a74 ? "\n" . $v7bd5d88a74 : ""); $this->f9585076d09($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $v32eab72720, $pddc51a8e); } } } } if (file_exists($v5f0471d66c)) unlink($v5f0471d66c); } private function f2e8ab59eec($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { $v08a367fe04 = $this->f154d53d339("migrate_dbs", $v8a4ed461b2, $v1495c93fca, $pee4ccbfa); $this->v1d2a79cac4[] = $v08a367fe04; $this->f7ed1027623($v08a367fe04, $paf0ee1a7, $pddc51a8e); $this->f280027e344($v08a367fe04, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pddc51a8e); $this->me202360dafc0($v08a367fe04); } private function f7ed1027623($v08a367fe04, $paf0ee1a7, &$pddc51a8e = null) { $v8326538c64 = basename($v08a367fe04); $v1d696dbd12 = isset($paf0ee1a7["properties"]["task"]) ? $paf0ee1a7["properties"]["task"] : null; $v80c5ba5219 = self::getTasksPropsByLabel($v1d696dbd12); if (!$this->mfb9a66a40371($v08a367fe04)) { $pddc51a8e[] = "Error: Could not create '$v8326538c64' folder!"; return false; } if (!$this->me1bfc9cf0775(CMS_PATH . "app/lib/org/phpframework/db", $v08a367fe04 . "lib/org/phpframework/db/")) { $pddc51a8e[] = "Error: Could not copy app/lib/org/phpframework/db/ folder!"; return false; } if (!$this->mb2ac558f4dad(CMS_PATH . "app/lib/org/phpframework/util/text/TextSanitizer.php", $v08a367fe04 . "lib/org/phpframework/util/text/TextSanitizer.php")) { $pddc51a8e[] = "Error: Could not copy app/lib/org/phpframework/util/text/TextSanitizer.php file!"; return false; } if (!$this->me1bfc9cf0775(CMS_PATH . "app/lib/org/phpframework/compression/", $v08a367fe04 . "lib/org/phpframework/compression/")) { $pddc51a8e[] = "Error: Could not copy app/lib/org/phpframework/compression/ folder!"; return false; } if (!$this->me1bfc9cf0775(CMS_PATH . "app/lib/vendor/sqlparser/", $v08a367fe04 . "lib/vendor/sqlparser/")) { $pddc51a8e[] = "Error: Could not copy app/lib/vendor/sqlparser/ folder!"; return false; } if (!$this->mb8b562d1df0b($v08a367fe04 . "dbsbackup/", $v80c5ba5219, $pddc51a8e)) { $pddc51a8e[] = "Error: Could not create DBs backups!"; return false; } $v5f0471d66c = $this->v28d8163063 . "/$v8326538c64.zip"; $this->v1d2a79cac4[] = $v5f0471d66c; if (!ZipHandler::zip($v08a367fe04, $v5f0471d66c)) { $pddc51a8e[] = "Error: Could not create zip '$v8326538c64.zip' file!"; return false; } if (!ZipHandler::renameFileInZip($v5f0471d66c, $v8326538c64, "migrate_dbs")) { $pddc51a8e[] = "Error: Could not rename '$v8326538c64' to 'migrate_dbs' in zip file: '$v8326538c64.zip'!"; return false; } $v02a69d4e0f = $v5f0471d66c; $v5f0471d66c = $v08a367fe04 . "/migrate_dbs.zip"; if (!rename($v02a69d4e0f, $v5f0471d66c)) { $pddc51a8e[] = "Error: Could not move '$v8326538c64.zip' file to $v8326538c64/migrate_dbs.zip!"; return false; } } private function f280027e344($v08a367fe04, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { if ($pddc51a8e) return false; $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $v1d696dbd12 = isset($paf0ee1a7["properties"]["task"]) ? $paf0ee1a7["properties"]["task"] : null; $v80c5ba5219 = self::getTasksPropsByLabel($v1d696dbd12); $v21c3221d67 = "$v4f2e308cee/.backups/backup_{$v1495c93fca}_$pee4ccbfa/"; $v5f0471d66c = $v08a367fe04 . "/migrate_dbs.zip"; if (!$v8c3308965d->createRemoteFolder($v21c3221d67, 0755, true)) $pddc51a8e[] = "Error: Could not create '$v21c3221d67' folder in remote server!"; else { $pa6116ee8 = "$v08a367fe04/migrate_dbs.php"; if (!$this->mff21fca9344e($v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v4f2e308cee, $v80c5ba5219, $pa6116ee8, $pddc51a8e)) $pddc51a8e[] = "Error: Could not create dbs deployment php file!"; else { if (!$v8c3308965d->copyLocalToRemoteFile($pa6116ee8, $v21c3221d67 . basename($pa6116ee8), true, 0640)) $pddc51a8e[] = "Error: Could not scp dbs deployment php file to remote server!"; else { if (!$v8c3308965d->copyLocalToRemoteFile($v5f0471d66c, $v21c3221d67 . basename($v5f0471d66c), true, 0640)) $pddc51a8e[] = "Error: Could not scp phpframework.zip file to remote server!"; else { $pd93e9b1d = "$v21c3221d67/migrate_dbs.php"; $v7bd5d88a74 = $v8c3308965d->exec("php '$pd93e9b1d'"); if (trim($v7bd5d88a74) !== "1") $pddc51a8e[] = "Error: '$pd93e9b1d' script not executed in remote server!" . ($v7bd5d88a74 ? "\n" . $v7bd5d88a74 : ""); } } } } if (file_exists($v5f0471d66c)) unlink($v5f0471d66c); } private function f3de97a601f($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { $v08a367fe04 = $this->f154d53d339("wordpress", $v8a4ed461b2, $v1495c93fca, $pee4ccbfa); $this->v1d2a79cac4[] = $v08a367fe04; $v668a7c4d3f = array(); $this->mc4bf88d29d4d($v08a367fe04, $paf0ee1a7, $v668a7c4d3f); $this->maaa45ef02335($v08a367fe04, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $v668a7c4d3f); $pddc51a8e = array_merge($pddc51a8e, $v668a7c4d3f); $this->me202360dafc0($v08a367fe04); } private function mc4bf88d29d4d($v08a367fe04, $paf0ee1a7, &$pddc51a8e = null) { if (!$this->mfb9a66a40371($v08a367fe04)) { $pddc51a8e[] = "Error: Could not create '" . basename($v08a367fe04) . "' folder!"; return false; } $pa6116ee8 = $v08a367fe04 . "update_wordpress_settings.php"; if (!$this->f70d9207fac($paf0ee1a7, $pa6116ee8, $pddc51a8e)) $pddc51a8e[] = "Error: Could not create 'update_wordpress_settings.php' file!"; } private function maaa45ef02335($v08a367fe04, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { if ($pddc51a8e) return false; $pb375ad0f = isset($paf0ee1a7["properties"]["server_installation_url"]) ? $paf0ee1a7["properties"]["server_installation_url"] : null; if ($pb375ad0f) { $v1d696dbd12 = isset($paf0ee1a7["properties"]["task"]) ? $paf0ee1a7["properties"]["task"] : null; $v80c5ba5219 = self::getTasksPropsByLabel($v1d696dbd12); $pacd0ce06 = array(); if ($v80c5ba5219) { $pc9570d63 = isset($this->pb4703992["presentation"]) ? $this->pb4703992["presentation"] : null; foreach ($v80c5ba5219 as $v0a5deb92d8 => $pd747dfc1) if ($pd747dfc1 && !empty($pd747dfc1["active"])) { $v7f5911d32d = isset($this->v32892bba0c[$v0a5deb92d8]) ? $this->v32892bba0c[$v0a5deb92d8] : null; $pc8421459 = isset($v7f5911d32d["type"]) ? $v7f5911d32d["type"] : null; if ($pc8421459 == $pc9570d63 && !empty($pd747dfc1["wordpress_installations"])) { $v74e48a8ea2 = $pd747dfc1["wordpress_installations"]; $v74e48a8ea2 = $v74e48a8ea2 ? (is_array($v74e48a8ea2) ? $v74e48a8ea2 : array($v74e48a8ea2)) : array(); foreach ($v74e48a8ea2 as $pc3ef9f9c) if ($pc3ef9f9c) { $pacd0ce06[] = $v0a5deb92d8; break; } } } } if ($pacd0ce06) { $pa6116ee8 = $v08a367fe04 . "update_wordpress_settings.php"; $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $v21c3221d67 = "$v4f2e308cee/.backups/backup_{$v1495c93fca}_$pee4ccbfa/"; $pd93e9b1d = $v21c3221d67 . "update_wordpress_settings.php"; if (!file_exists($pa6116ee8)) $pddc51a8e[] = "Error: update_wordpress_settings.php file does not exist in local server!"; else if (!$v8c3308965d->copyLocalToRemoteFile($pa6116ee8, $pd93e9b1d, true, 0640)) $pddc51a8e[] = "Error: Could not upload update_wordpress_settings.php file to remote server!"; else { foreach ($pacd0ce06 as $v0a5deb92d8) { $v8fc05368c0 = WorkFlowBeansConverter::getFileNameFromRawLabel($v0a5deb92d8); $pf1cf82ed = "$v4f2e308cee/app/layer/$v8fc05368c0/common/webroot/" . WordPressUrlsParser::WORDPRESS_FOLDER_PREFIX . "/"; $v74e48a8ea2 = $v8c3308965d->scanRemoteDir($pf1cf82ed); $v74e48a8ea2 = is_array($v74e48a8ea2) ? array_diff($v74e48a8ea2, self::$pa2a1daed) : array(); foreach ($v74e48a8ea2 as $pc3ef9f9c) if ($v8c3308965d->isDir($pf1cf82ed . $pc3ef9f9c)) { $v7bd5d88a74 = $v8c3308965d->exec("php '$pd93e9b1d' '$v8fc05368c0' '$pc3ef9f9c'"); if (trim($v7bd5d88a74) !== "1") $pddc51a8e[] = "Error: '$pd93e9b1d' script not executed in remote server!" . ($v7bd5d88a74 ? "\n" . $v7bd5d88a74 : ""); } } } } } } private function md0001dcd79dc($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { $v08a367fe04 = $this->f154d53d339("version", $v8a4ed461b2, $v1495c93fca, $pee4ccbfa); $this->v1d2a79cac4[] = $v08a367fe04; $v668a7c4d3f = array(); $this->f4c8f214f63($v08a367fe04, $v668a7c4d3f); $this->mfdfd0fa672e8($v08a367fe04, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $v668a7c4d3f); $pddc51a8e = array_merge($pddc51a8e, $v668a7c4d3f); $this->me202360dafc0($v08a367fe04); } private function f4c8f214f63($v08a367fe04, &$pddc51a8e = null) { $v8326538c64 = basename($v08a367fe04); if (!$this->mfb9a66a40371($v08a367fe04)) { $pddc51a8e[] = "Error: Could not create '$v8326538c64' folder!"; return false; } } private function mfdfd0fa672e8($v08a367fe04, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { if ($pddc51a8e) return false; $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $v0cd96e7d65 = "$v08a367fe04/version.txt"; $v39e1347c93 = "template_id:$v1495c93fca\ndeployment_id:$pee4ccbfa\ntime:" . time() . "\ndate:" . date("Y-m-d H:i:s"); if (file_put_contents($v0cd96e7d65, $v39e1347c93) === false) $pddc51a8e[] = "Error: Could not create deployment version txt file!"; else if (!$v8c3308965d->copyLocalToRemoteFile($v0cd96e7d65, $v4f2e308cee . "/" . basename($v0cd96e7d65), true, 0640)) $pddc51a8e[] = "Error: Could not scp backup deployment version txt file to remote server!"; } private function f959e6574c3($v8a4ed461b2, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pddc51a8e = null) { $v08a367fe04 = $this->f154d53d339("version", $v8a4ed461b2, $v1495c93fca, $pee4ccbfa); $this->v1d2a79cac4[] = $v08a367fe04; $this->f837728e9df($v08a367fe04, $pddc51a8e); $this->f433419f921($v08a367fe04, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, $pdc770089, $pddc51a8e); $this->me202360dafc0($v08a367fe04); return $pee4ccbfa; } private function f837728e9df($v08a367fe04, &$pddc51a8e = null) { $v8326538c64 = basename($v08a367fe04); if (!$this->mfb9a66a40371($v08a367fe04)) { $pddc51a8e[] = "Error: Could not create '$v8326538c64' folder!"; return false; } } private function f433419f921($v08a367fe04, $v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v8c3308965d, &$pdc770089, &$pddc51a8e = null) { $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $v0cd96e7d65 = "$v08a367fe04/version.txt"; $pdc770089 = null; if (!$v8c3308965d->copyRemoteToLocalFile($v4f2e308cee . "/version.txt", $v0cd96e7d65) || !file_exists($v0cd96e7d65)) $pddc51a8e[] = "Error: Could not get version file from server!"; else { $v6490ea3a15 = file_get_contents($v0cd96e7d65); preg_match("/deployment_id:([0-9]+)/", $v6490ea3a15, $pbae7526c, PREG_OFFSET_CAPTURE); $pdc770089 = $pbae7526c ? $pbae7526c[1][0] : -1; } } private function f804c35fa1c($v8c3308965d, $v4f2e308cee) { $pd4d55af9 = $v8c3308965d->exists($v4f2e308cee . "/version.txt"); if ($pd4d55af9) return false; $v2cd5d67337 = $v8c3308965d->scanRemoteDir($v4f2e308cee); $v2cd5d67337 = $v2cd5d67337 ? array_diff($v2cd5d67337, self::$pa2a1daed) : null; $v2cd5d67337 = $v2cd5d67337 ? array_diff($v2cd5d67337, array(".backups")) : null; if ($v2cd5d67337) foreach ($v2cd5d67337 as $v3a3060fe4b) if ($v3a3060fe4b && substr($v3a3060fe4b, 0, 1) != ".") return false; return true; } private function f154d53d339($pdcf670f6, $v8a4ed461b2, $v1495c93fca, $pee4ccbfa) { $v8326538c64 = $pdcf670f6 . "_" . $v8a4ed461b2 . "_" . $v1495c93fca . "_" . $pee4ccbfa; return $this->v28d8163063 . "/$v8326538c64/"; } private function me729921d3313($v4f2e308cee, $pa6116ee8, $v32eab72720, $pddc51a8e) { $v067674f4e4 = "<?php
\$installation_folder_path = \"" . addcslashes($v4f2e308cee, '"') . "/\";

\$files_to_remove = array("; if ($v32eab72720) foreach ($v32eab72720 as $v1b08a89324) $v067674f4e4 .= "
	\"" . addcslashes($v1b08a89324, '"') . "\","; $v067674f4e4 .= "
);

\$status = true;

foreach (\$files_to_remove as \$file) {
	\$fp = \$installation_folder_path . \$file;
	
	if (!removeFile(\$fp))
		\$status = false;
}

if (\$status)
	exitScript(); //terminate script without errors
else 
	exitScript('Error: trying to flush cache by removing files');

" . $this->mef56ed7c3970() . "
" . $this->f202ae761dc() . "
?>"; return file_put_contents($pa6116ee8, $v067674f4e4) !== false; } private function f9f81305eb7($v4f2e308cee, $pa6116ee8, $v32eab72720, $pddc51a8e) { $v067674f4e4 = "<?php
\$installation_folder_path = \"" . addcslashes($v4f2e308cee, '"') . "/\";

\$files_to_remove = array("; if ($v32eab72720) foreach ($v32eab72720 as $v1b08a89324) $v067674f4e4 .= "
	\"" . addcslashes($v1b08a89324, '"') . "\","; $v067674f4e4 .= "
);

\$status = true;

foreach (\$files_to_remove as \$file) {
	\$fp = \$installation_folder_path . \$file;
	
	if (!setFilePermissions(\$fp))
		\$status = false;
}

clearstatcache();

if (\$status)
	exitScript(); //terminate script without errors
else 
	exitScript('Error: trying to flush cache by setting permissions');

" . $this->mef56ed7c3970() . "

function setFilePermissions(\$path) {
	\$status = true;
	
	if (\$path && file_exists(\$path)) {
		if (is_dir(\$path)) {
			\$files = array_diff(scandir(\$path), " . self::f8edaa78497() . ");
			
			foreach (\$files as \$file)
				if (!setFilePermissions(\"\$path/\$file\"))
					\$status = false;
		}
		
		if (is_writable(\$path) && !chmod(\$path, 0777))
			\$status = false;
	}
	return \$status;
}
?>"; return file_put_contents($pa6116ee8, $v067674f4e4) !== false; } private function me7b57319d49d($v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v4f2e308cee, $v80c5ba5219, $pa6116ee8, $pddc51a8e) { $v067674f4e4 = "<?php
\$installation_folder_path = \"" . addcslashes($v4f2e308cee, '"') . "/\";
\$backups_folder_path = \$installation_folder_path . \".backups/backup_{$v1495c93fca}_$pee4ccbfa/\";
\$rollbacking_folder_path = \$backups_folder_path . \"rollbacking/\";
\$db_backups_folder_path = \$backups_folder_path . \"dbsbackup/\";
\$zip_file_path = \$backups_folder_path . \"rollback.zip\";
\$old_zip_file_path = \$backups_folder_path . \"old_phpframework.zip\";
\$old_perms_file_path = \$backups_folder_path . \"old_perms.json\";
\$lib_folder_path = \$rollbacking_folder_path . \"rollback/lib/\";

//creating rollbacking folder
if (is_dir(\$rollbacking_folder_path))
	removeFile(\$rollbacking_folder_path);

if (!is_dir(\$rollbacking_folder_path))
	mkdir(\$rollbacking_folder_path, 0755, true);

if (!is_dir(\$rollbacking_folder_path)) 
	exitScript(\"Error: Could not create '\$rollbacking_folder_path' folder\");

if (!file_exists(\$old_zip_file_path))
	exitScript(\"Error: '\$old_zip_file_path' file does NOT exist\");

//unzip old_phpframework.zip to rollbacking folder
\$ZipArchive = new ZipArchive();
\$status = \$ZipArchive->open(\$old_zip_file_path) === true;

if (\$status) {
	\$status = \$ZipArchive->extractTo(\$rollbacking_folder_path);
	\$ZipArchive->close();
}

if (!\$status)
	exitScript(\"Error: Could not unzip '\$old_zip_file_path' file\");

//getting permissions from old_perms_file_path and setting them to the new files 
if (!file_exists(\$old_perms_file_path))
	exitScript(\"Error: Old permissions file does not exists. File should be in: '\$old_perms_file_path'!\");

\$perms = json_decode(file_get_contents(\$old_perms_file_path), true);
if (\$perms)
	foreach (\$perms as \$file => \$perm) 
		if (\$perm) {
			\$fp = \$rollbacking_folder_path . \"phpframework/\$file\";
			
			if (file_exists(\$fp)) {
				\$p = octdec(\$perm);
				
				if (!chmod(\$fp, \$p))
					exitScript(\"Error: Could not set permission '\$perm' to '\$file' file!\");
			}
		}

//move files from rollbacking_folder_path to installation_folder_path
\$files = scandir(\$rollbacking_folder_path . \"phpframework\");
\$old_files_to_remove = array();
\$moved = true;

if (\$files)
	foreach (\$files as \$file)
		if (!in_array(\$file, " . self::f8edaa78497() . ") && \$file != \".backups\") {
			\$fp = \$installation_folder_path . \$file;
			
			if (file_exists(\$fp)) {
				\$pathinfo = pathinfo(\$fp);
				\$fp_old = \$pathinfo[\"dirname\"] . \"/\" . \$pathinfo[\"filename\"] . \"_old\" . (!empty(\$pathinfo[\"extension\"]) ? \".\" . \$pathinfo[\"extension\"] : \"\");
				
				if (!rename(\$fp, \$fp_old))
					exitScript(\"Error: Could not rename file: '\$file' to  '\" . basename(\$fp_old) . \"'!\");
				
				\$old_files_to_remove[] = \$fp_old;
			}
			
			if (!rename(\$rollbacking_folder_path . \"phpframework/\$file\", \$fp))
				\$moved = false;
		}

//DO NOT remove old files, bc they still contains files with the apache user owner. These folders will be removed via CMSDeploymentHandler::fushCacheOnRemoteServer method.
//foreach (\$old_files_to_remove as \$file)
//	if (!removeFile(\$file))
//		exitScript(\"Error: Could not remove file: '\$file'!\");

if (!\$moved)
	exitScript(\"Error: Could not move all files from '\$rollbacking_folder_path/phpframework/' folder to '\$installation_folder_path' folder\");

//settings permission to installation_folder_path
if (\$perms && !empty(\$perms[\"/\"])) {
	\$perm = \$perms[\"/\"];
	\$p = octdec(\$perm);
	
	if (!chmod(\$installation_folder_path, \$p))
		exitScript(\"Error: Could not set permission '\$perm' to '\$installation_folder_path' folder!\");
}
"; if ($v80c5ba5219 && $this->pe9c62c08 && !empty($this->pe9c62c08["tasks"])) { $v8625b218a4 = isset($paf0ee1a7["properties"]["global_vars"]) ? $paf0ee1a7["properties"]["global_vars"] : null; $pd7a36e35 = $this->f7bdfa249e6($v8625b218a4); $v923aa1816f = trim( PHPVariablesFileHandler::getVarsCode($pd7a36e35, false) ); $v923aa1816f = trim( substr($v923aa1816f, 5, -2) ); $v067674f4e4 .= "
//unzip zip_file_path to deploying folder
\$ZipArchive = new ZipArchive();
\$status = \$ZipArchive->open(\$zip_file_path) === true;

if (\$status) {
	\$status = \$ZipArchive->extractTo(\$rollbacking_folder_path);
	\$ZipArchive->close();
}

if (!\$status)
	exitScript(\"Error: Could not unzip '\$zip_file_path' file\");

include get_lib(\"org.phpframework.db.DB\");

//setting global variables - bc some of them may be in the DBDrivers props
" . $v923aa1816f . "
"; $v21323a157b = isset($this->pb4703992["dbdriver"]) ? $this->pb4703992["dbdriver"] : null; foreach ($this->pe9c62c08["tasks"] as $v8282c7dd58 => $v7f5911d32d) { $v89cfc6ba9c = isset($v7f5911d32d["label"]) ? $v7f5911d32d["label"] : null; $pd747dfc1 = isset($v80c5ba5219[$v89cfc6ba9c]) ? $v80c5ba5219[$v89cfc6ba9c] : null; $pc8421459 = isset($v7f5911d32d["type"]) ? $v7f5911d32d["type"] : null; $pd82569a2 = $pd747dfc1 && !empty($pd747dfc1["active"]); if ($pd82569a2 && $pc8421459 == $v21323a157b) { $v0eae24f3f2 = isset($pd747dfc1["type"]) ? $pd747dfc1["type"] : null; $v067674f4e4 .= "
\$dump_file_path = \$db_backups_folder_path . \"prev_mysqldump.$v89cfc6ba9c.sql\";

if (file_exists(\$dump_file_path)) {
	//*** DB $v89cfc6ba9c ***
	//connect to " . $v0eae24f3f2 . " DB: '$v89cfc6ba9c' and create DB if not exists yet
	\$db_type = \"" . $v0eae24f3f2 . "\";
	\$DBDriver = DB::createDriverByType(\$db_type);

	\$db_options = array(
		\"extension\" => \"" . (isset($pd747dfc1["extension"]) ? $pd747dfc1["extension"] : "") . "\",
		\"host\" => \"" . (isset($pd747dfc1["host"]) ? $pd747dfc1["host"] : "") . "\",
		\"db_name\" => \"" . (isset($pd747dfc1["db_name"]) ? $pd747dfc1["db_name"] : "") . "\",
		\"username\" => \"" . (isset($pd747dfc1["username"]) ? $pd747dfc1["username"] : "") . "\",
		\"password\" => \"" . (isset($pd747dfc1["password"]) ? $pd747dfc1["password"] : "") . "\",
		\"port\" => \"" . (isset($pd747dfc1["port"]) ? $pd747dfc1["port"] : "") . "\",
		\"persistent\" => \"" . (isset($pd747dfc1["persistent"]) ? $pd747dfc1["persistent"] : "") . "\",
		\"new_link\" => \"" . (isset($pd747dfc1["new_link"]) ? $pd747dfc1["new_link"] : "") . "\",
		\"encoding\" => \"" . (isset($pd747dfc1["encoding"]) ? $pd747dfc1["encoding"] : "") . "\",
		\"schema\" => \"" . (isset($pd747dfc1["schema"]) ? $pd747dfc1["schema"] : "") . "\",
		\"odbc_data_source\" => \"" . (isset($pd747dfc1["odbc_data_source"]) ? $pd747dfc1["odbc_data_source"] : "") . "\",
		\"odbc_driver\" => \"" . (isset($pd747dfc1["odbc_driver"]) ? $pd747dfc1["odbc_driver"] : "") . "\",
		\"extra_dsn\" => \"" . (isset($pd747dfc1["extra_dsn"]) ? $pd747dfc1["extra_dsn"] : "") . "\",
	);
	\$DBDriver->setOptions(\$db_options);

	\$exception = null;

	try {
		\$connected = @\$DBDriver->connect();
	}
	catch (Exception \$e) {
		\$exception = \$e;
	}

	//tryies to create DB if not exists yet
	if (!\$connected || \$exception) {
		\$exception = null;
	
		try {
			\$db_name = isset(\$db_options[\"db_name\"]) ? \$db_options[\"db_name\"] : null;
			\$created = \$DBDriver->createDB(\$db_name);
			\$connected = \$created && \$DBDriver->isDBSelected() && \$DBDriver->getSelectedDB() == \$db_name;
		}
		catch (Exception \$e) {
			\$exception = \$e;
		}
	}

	if (!\$connected || \$exception) {
		\$msg = \$exception ? (\$exception->problem ? \$exception->problem . PHP_EOL : \"\") . \$exception->getMessage() : \"\";
		exitScript(\"Error (1): Could not connect to \$db_type DB Driver: '$v89cfc6ba9c'!\" . (\$msg ? \"\\n\" . \$msg : \"\"));
	}
	
	//import schema
	\$contents = file_get_contents(\$dump_file_path);
	\$imported = true;
	\$msg = \"\";

	try {
		\$imported = \$DBDriver->setData(\$contents, array(\"remove_comments\" => true)); //This must be executed in a batch (this is, all sqls together) bc we may have store procedures or other sql commands that can only take effect if executed together in the same sql session. SO PLEASE DO NOT SPLIT THE SQL STATEMENTS!
	}
	catch(Exception \$e) {
		\$exception = \$e;
	}
	
	if (!\$imported || \$exception) {
		\$msg = \$exception ? (\$exception->problem ? \$exception->problem . PHP_EOL : \"\") . \$exception->getMessage() : \"\";
		exitScript(\"Error: Could not import schema to DB '$v89cfc6ba9c'!\" . (\$msg ? \"\\n\" . \$msg : \"\"));
	}
	
	\$DBDriver->disconnect();
}
"; } } } $v067674f4e4 .= "
//remove rollbacking folder
removeFile(\$rollbacking_folder_path);

exitScript(); //terminate script without errors

" . $this->mef56ed7c3970() . "
" . $this->f8cf63fbbc2() . "
" . $this->f71ceffff49() . "
" . $this->f202ae761dc() . "
?>"; return file_put_contents($pa6116ee8, $v067674f4e4) !== false; } private function f1d59576218($v1495c93fca, $pee4ccbfa, $v4f2e308cee, $pa6116ee8, $pddc51a8e) { $v067674f4e4 = "<?php
\$installation_folder_path = \"" . addcslashes($v4f2e308cee, '"') . "/\";
\$installation_folder_name = basename(\$installation_folder_path);
\$backups_folder_path = \$installation_folder_path . \".backups/backup_{$v1495c93fca}_$pee4ccbfa/\";
\$deploying_folder_path = \$backups_folder_path . \"deploying/\";
\$zip_file_path = \$backups_folder_path . \"backup.zip\";
\$old_zip_file_path = \$backups_folder_path . \"old_phpframework.zip\";
\$old_perms_file_path = \$backups_folder_path . \"old_perms.json\";

//creating deploying folder
if (is_dir(\$deploying_folder_path))
	removeFile(\$deploying_folder_path);

if (!is_dir(\$deploying_folder_path))
	mkdir(\$deploying_folder_path, 0755, true);

if (!is_dir(\$deploying_folder_path))
	exitScript(\"Error: Could not create '\$deploying_folder_path' folder\");

//unzip backup.zip to deploying folder
\$ZipArchive = new ZipArchive();
\$status = \$ZipArchive->open(\$zip_file_path) === true;

if (\$status) {
	\$status = \$ZipArchive->extractTo(\$deploying_folder_path);
	\$ZipArchive->close();
}

if (!\$status)
	exitScript(\"Error: Could not unzip '\$zip_file_path' file\");

//zip installation_folder_path folder (.ignore .backups folder) and put it in .backups with version number
include \$deploying_folder_path . \"backup/lib/org/phpframework/compression/ZipHandler.php\";

if (!file_exists(\$old_zip_file_path)) {
	if (!ZipHandler::zip(\$installation_folder_path, \$old_zip_file_path, array(\"exclude_files\" => \$installation_folder_path . \".backups\")))
		exitScript(\"Error: Could not create backup zip file for '\$installation_folder_path'\");
	
	if (!ZipHandler::renameFileInZip(\$old_zip_file_path, \$installation_folder_name, \"phpframework\"))
		exitScript(\"Error: Could not rename '\$installation_folder_name' to 'phpframework' in '\$installation_folder_path' file\");
}

//getting files permissions and save them to old_perms_file_path
\$perms = getFolderFilesPermissions(\$installation_folder_path);
if (!file_put_contents(\$old_perms_file_path, json_encode(\$perms)))
	exitScript(\"Error: creating '\$old_perms_file_path' file\");

//remove deploying folder 
removeFile(\$deploying_folder_path);

exitScript(); //terminate script without errors

" . $this->mef56ed7c3970() . "
" . $this->f202ae761dc() . "
" . $this->f1f81d050c6() . "
?>"; return file_put_contents($pa6116ee8, $v067674f4e4) !== false; } private function f8fc6027f55($v1495c93fca, $pee4ccbfa, $v4f2e308cee, $pa6116ee8, $pddc51a8e) { $v067674f4e4 = "<?php
\$installation_folder_path = \"" . addcslashes($v4f2e308cee, '"') . "/\";
\$backups_folder_path = \$installation_folder_path . \".backups/backup_{$v1495c93fca}_$pee4ccbfa/\";
\$deploying_folder_path = \$backups_folder_path . \"deploying/\";
\$zip_file_path = \$backups_folder_path . \"phpframework.zip\";
\$old_zip_file_path = \$backups_folder_path . \"old_phpframework.zip\";

//creating deploying folder
if (is_dir(\$deploying_folder_path))
	removeFile(\$deploying_folder_path);

if (!is_dir(\$deploying_folder_path))
	mkdir(\$deploying_folder_path, 0755, true);

if (!is_dir(\$deploying_folder_path))
	exitScript(\"Error: Could not create '\$deploying_folder_path' folder\");

//unzip zip_file_path to deploying folder
\$ZipArchive = new ZipArchive();
\$status = \$ZipArchive->open(\$zip_file_path) === true;

if (\$status) {
	\$status = \$ZipArchive->extractTo(\$deploying_folder_path);
	\$ZipArchive->close();
}

if (!\$status)
	exitScript(\"Error: Could not unzip '\$zip_file_path' file\");

//set phpframework files perms
\$perms_file_path = \$deploying_folder_path . \"phpframework/perms.json\";

if (!file_exists(\$perms_file_path))
	exitScript(\"Error: Permissions file does not exists. File should be in: '\$perms_file_path'!\");

\$perms = json_decode(file_get_contents(\$perms_file_path), true);
if (\$perms)
	foreach (\$perms as \$file => \$perm) 
		if (\$perm) {
			\$fp = \$deploying_folder_path . \"phpframework/\$file\";
			
			if (file_exists(\$fp)) {
				\$p = octdec(\$perm);
				
				if (!chmod(\$fp, \$p))
					exitScript(\"Error: Could not set permission '\$perm' to '\$file' file!\");
			}
		}

//add \$installation_folder_path/.htaccess if not exists
if (!file_exists(\$installation_folder_path . \".htaccess\") && !rename(\$deploying_folder_path . \"phpframework/.htaccess\", \$installation_folder_path . \".htaccess\"))
	exitScript(\"Error: Could not move '\$deploying_folder_path/phpframework/.htaccess' to '\$installation_folder_path/.htaccess'!\");

//add \$installation_folder_path/LICENSE.txt if not exists
if (!file_exists(\$installation_folder_path . \"LICENSE.txt\") && !rename(\$deploying_folder_path . \"phpframework/LICENSE.txt\", \$installation_folder_path . \"LICENSE.txt\"))
	exitScript(\"Error: Could not move '\$deploying_folder_path/phpframework/LICENSE.txt' to '\$installation_folder_path/LICENSE.txt'!\");

//remove old folder in case it exists. It should already have been removed via the CMSDeploymentHandler::fushCacheOnRemoteServer method, but just in case do it again
removeFile(\$installation_folder_path . \"app_old\"); 

//mv old app folder to app_old
if (file_exists(\$installation_folder_path . \"app\") && !rename(\$installation_folder_path . \"app\", \$installation_folder_path . \"app_old\"))
	exitScript(\"Error: Could not move '\$installation_folder_path/app' to '\$installation_folder_path/app_old'!\");

//mv \$deploying_folder_path/phpframework/app to app
if (!file_exists(\$deploying_folder_path . \"phpframework/app\"))
	exitScript(\"Error: '\$deploying_folder_path/phpframework/app' folder does not exists!\");

if (!rename(\$deploying_folder_path . \"phpframework/app\", \$installation_folder_path . \"app\"))
	exitScript(\"Error: Could not move '\$deploying_folder_path/phpframework/app' to '\$installation_folder_path/app'!\");

//copy app_old/.htaccess to app/.htaccess and others hidden files
if (file_exists(\$installation_folder_path . \"app_old/.htaccess\")) {
	removeFile(\$installation_folder_path . \"app/.htaccess\");
	
	if (!rename(\$installation_folder_path . \"app_old/.htaccess\", \$installation_folder_path . \"app/.htaccess\"))
		exitScript(\"Error: Could not move '\$installation_folder_path/app_old/.htaccess' to '\$installation_folder_path/app/.htaccess'!\");
}

//remove old folder in case it exists. It should already have been removed via the CMSDeploymentHandler::fushCacheOnRemoteServer method, but just in case do it again
removeFile(\$installation_folder_path . \"vendor_old\"); //remove old folder in case it exists

//mv old vendor folder to vendor_old
if (file_exists(\$installation_folder_path . \"vendor\") && !rename(\$installation_folder_path . \"vendor\", \$installation_folder_path . \"vendor_old\"))
	exitScript(\"Error: Could not move '\$installation_folder_path/vendor' to '\$installation_folder_path/vendor_old'!\");

//prepare new vendor folder
if (file_exists(\$deploying_folder_path . \"phpframework/vendor\")) {
	//mv \$deploying_folder_path/phpframework/vendor to vendor
	if (!rename(\$deploying_folder_path . \"phpframework/vendor\", \$installation_folder_path . \"vendor\"))
		exitScript(\"Error: Could not move '\$deploying_folder_path/phpframework/vendor' to '\$installation_folder_path/vendor'!\");

	//copy vendor_old/.htaccess to vendor/.htaccess and others hidden files
	if (file_exists(\$installation_folder_path . \"vendor_old/.htaccess\")) {
		removeFile(\$installation_folder_path . \"vendor/.htaccess\");
		
		if (!rename(\$installation_folder_path . \"vendor_old/.htaccess\", \$installation_folder_path . \"vendor/.htaccess\"))
			exitScript(\"Error: Could not move '\$installation_folder_path/vendor_old/.htaccess' to '\$installation_folder_path/vendor/.htaccess'!\");
	}
}

//remove old folder in case it exists. It should already have been removed via the CMSDeploymentHandler::fushCacheOnRemoteServer method, but just in case do it again
removeFile(\$installation_folder_path . \"other_old\"); //remove old folder in case it exists

//mv old other folder to other_old
if (file_exists(\$installation_folder_path . \"other\") && !rename(\$installation_folder_path . \"other\", \$installation_folder_path . \"other_old\"))
	exitScript(\"Error: Could not move '\$installation_folder_path/other' to '\$installation_folder_path/other_old'!\");

//prepare new other folder
if (file_exists(\$deploying_folder_path . \"phpframework/other\")) {
	//mv \$deploying_folder_path/phpframework/other to other
	if (!rename(\$deploying_folder_path . \"phpframework/other\", \$installation_folder_path . \"other\"))
		exitScript(\"Error: Could not move '\$deploying_folder_path/phpframework/other' to '\$installation_folder_path/other'!\");

	//copy other_old/.htaccess to other/.htaccess and others hidden files
	if (file_exists(\$installation_folder_path . \"other_old/.htaccess\")) {
		removeFile(\$installation_folder_path . \"other/.htaccess\");
		
		if (!rename(\$installation_folder_path . \"other_old/.htaccess\", \$installation_folder_path . \"other/.htaccess\"))
			exitScript(\"Error: Could not move '\$installation_folder_path/other_old/.htaccess' to '\$installation_folder_path/other/.htaccess'!\");
	}
}

//add \$installation_folder_path/tmp folder if not exists
if (!file_exists(\$installation_folder_path . \"tmp\") && !rename(\$deploying_folder_path . \"phpframework/tmp\", \$installation_folder_path . \"tmp\"))
	exitScript(\"Error: Could not move '\$deploying_folder_path/phpframework/tmp' to '\$installation_folder_path/tmp'!\");

//remove deploying folder 
removeFile(\$deploying_folder_path);

//DO NOT remove old folder in case it exists, bc they still contains files with the apache user owner. These folders will be removed via CMSDeploymentHandler::fushCacheOnRemoteServer method.
//removeFile(\$installation_folder_path . \"tmp/cache\");
//removeFile(\$installation_folder_path . \"app_old\");
//removeFile(\$installation_folder_path . \"vendor_old\");
//removeFile(\$installation_folder_path . \"other_old\");

exitScript(); //terminate script without errors

" . $this->mef56ed7c3970() . "
" . $this->f202ae761dc() . "
?>"; return file_put_contents($pa6116ee8, $v067674f4e4) !== false; } private function mff21fca9344e($v1495c93fca, $pee4ccbfa, $paf0ee1a7, $v4f2e308cee, $v80c5ba5219, $pa6116ee8, $pddc51a8e) { $v067674f4e4 = "<?php"; if ($v80c5ba5219 && $this->pe9c62c08 && !empty($this->pe9c62c08["tasks"])) { $v8625b218a4 = isset($paf0ee1a7["properties"]["global_vars"]) ? $paf0ee1a7["properties"]["global_vars"] : null; $pd7a36e35 = $this->f7bdfa249e6($v8625b218a4); $v923aa1816f = trim( PHPVariablesFileHandler::getVarsCode($pd7a36e35, false) ); $v923aa1816f = trim( substr($v923aa1816f, 5, -2) ); $v067674f4e4 .= "
\$installation_folder_path = \"" . addcslashes($v4f2e308cee, '"') . "/\";
\$backups_folder_path = \$installation_folder_path . \".backups/backup_{$v1495c93fca}_$pee4ccbfa/\";
\$db_backups_folder_path = \$backups_folder_path . \"dbsbackup/\";
\$deploying_folder_path = \$backups_folder_path . \"deploying/\";
\$zip_file_path = \$backups_folder_path . \"migrate_dbs.zip\";
\$lib_folder_path = \$deploying_folder_path . \"migrate_dbs/lib/\";

//creating deploying folder
if (is_dir(\$deploying_folder_path))
	removeFile(\$deploying_folder_path);

if (!is_dir(\$deploying_folder_path))
	mkdir(\$deploying_folder_path, 0755, true);

if (!is_dir(\$deploying_folder_path))
	exitScript(\"Error: Could not create '\$deploying_folder_path' folder\");

//creating dbs backup folder
if (!is_dir(\$db_backups_folder_path))
	mkdir(\$db_backups_folder_path, 0755, true);

if (!is_dir(\$db_backups_folder_path))
	exitScript(\"Error: Could not create '\$db_backups_folder_path' folder\");

//unzip zip_file_path to deploying folder
\$ZipArchive = new ZipArchive();
\$status = \$ZipArchive->open(\$zip_file_path) === true;

if (\$status) {
	\$status = \$ZipArchive->extractTo(\$deploying_folder_path);
	\$ZipArchive->close();
}

if (!\$status)
	exitScript(\"Error: Could not unzip '\$zip_file_path' file\");

include get_lib(\"org.phpframework.db.DB\");
include get_lib(\"org.phpframework.db.DBDumperHandler\");

//setting global variables - bc some of them may be in the DBDrivers props
" . $v923aa1816f . "
"; $v21323a157b = isset($this->pb4703992["dbdriver"]) ? $this->pb4703992["dbdriver"] : null; foreach ($this->pe9c62c08["tasks"] as $v8282c7dd58 => $v7f5911d32d) { $v89cfc6ba9c = isset($v7f5911d32d["label"]) ? $v7f5911d32d["label"] : null; $pd747dfc1 = isset($v80c5ba5219[$v89cfc6ba9c]) ? $v80c5ba5219[$v89cfc6ba9c] : null; $pd82569a2 = $pd747dfc1 && !empty($pd747dfc1["active"]); $pf2d9ff95 = $pd747dfc1 && !empty($pd747dfc1["migrate_db_schema"]); $v71af0b0882 = $pd747dfc1 && !empty($pd747dfc1["migrate_db_data"]); $v644df8cd23 = $pd747dfc1 && !empty($pd747dfc1["remove_deprecated_tables_and_attributes"]); $pc8421459 = isset($v7f5911d32d["type"]) ? $v7f5911d32d["type"] : null; if ($pd82569a2 && $pc8421459 == $v21323a157b && ($pf2d9ff95 || $v71af0b0882)) { $v0eae24f3f2 = isset($pd747dfc1["type"]) ? $pd747dfc1["type"] : null; $v56c929bbd8 = DB::getDSNByType($v0eae24f3f2, $pd747dfc1); $v067674f4e4 .= "
//*** DB $v89cfc6ba9c ***
//connect to " . $v0eae24f3f2 . " DB: '$v89cfc6ba9c' and create DB if not exists yet
\$db_type = \"" . $v0eae24f3f2 . "\";
\$DBDriver = DB::createDriverByType(\$db_type);

\$db_options = array(
	\"extension\" => \"" . (isset($pd747dfc1["extension"]) ? $pd747dfc1["extension"] : "") . "\",
	\"host\" => \"" . (isset($pd747dfc1["host"]) ? $pd747dfc1["host"] : "") . "\",
	\"db_name\" => \"" . (isset($pd747dfc1["db_name"]) ? $pd747dfc1["db_name"] : "") . "\",
	\"username\" => \"" . (isset($pd747dfc1["username"]) ? $pd747dfc1["username"] : "") . "\",
	\"password\" => \"" . (isset($pd747dfc1["password"]) ? $pd747dfc1["password"] : "") . "\",
	\"port\" => \"" . (isset($pd747dfc1["port"]) ? $pd747dfc1["port"] : "") . "\",
	\"persistent\" => \"" . (isset($pd747dfc1["persistent"]) ? $pd747dfc1["persistent"] : "") . "\",
	\"new_link\" => \"" . (isset($pd747dfc1["new_link"]) ? $pd747dfc1["new_link"] : "") . "\",
	\"encoding\" => \"" . (isset($pd747dfc1["encoding"]) ? $pd747dfc1["encoding"] : "") . "\",
	\"schema\" => \"" . (isset($pd747dfc1["schema"]) ? $pd747dfc1["schema"] : "") . "\",
	\"odbc_data_source\" => \"" . (isset($pd747dfc1["odbc_data_source"]) ? $pd747dfc1["odbc_data_source"] : "") . "\",
	\"odbc_driver\" => \"" . (isset($pd747dfc1["odbc_driver"]) ? $pd747dfc1["odbc_driver"] : "") . "\",
	\"extra_dsn\" => \"" . (isset($pd747dfc1["extra_dsn"]) ? $pd747dfc1["extra_dsn"] : "") . "\",
);
\$DBDriver->setOptions(\$db_options);

\$sql_options = array(\"schema\" => \$DBDriver->getOption(\"schema\"));
\$exception = null;
\$is_db_previously_created = true;

try {
	\$connected = @\$DBDriver->connect();
}
catch (Exception \$e) {
	\$exception = \$e;
}

//tryies to create DB if not exists yet
if (!\$connected || \$exception) {
	\$is_db_previously_created = false;
	\$exception = null;
	
	try {
		\$db_name = isset(\$db_options[\"db_name\"]) ? \$db_options[\"db_name\"] : null;
		\$created = \$DBDriver->createDB(\$db_name);
		\$connected = \$created && \$DBDriver->isDBSelected() && \$DBDriver->getSelectedDB() == \$db_name;
	}
	catch (Exception \$e) {
		\$exception = \$e;
	}
}

if (!\$connected || \$exception) {
	\$msg = \$exception ? (\$exception->problem ? \$exception->problem . PHP_EOL : \"\") . \$exception->getMessage() : \"\";
	exitScript(\"Error (2): Could not connect to \$db_type DB Driver: '$v89cfc6ba9c'!\" . (\$msg ? \"\\n\" . \$msg : \"\"));
}

\$DBDriver->disconnect();

//backup $v89cfc6ba9c DB to \$db_backups_folder_path folder
\$pdo_settings = " . (!empty($pd747dfc1["persistent"]) && empty($pd747dfc1["new_link"]) ? "array(PDO::ATTR_PERSISTENT => true)" : "array()") . ";

if (\$is_db_previously_created) {
	//backup DB
	\$dump_file_path = \$db_backups_folder_path . \"prev_mysqldump.$v89cfc6ba9c.sql\";

	if (!file_exists(\$dump_file_path)) { //if file exists it means that the backup already happend previously and we don't want to overwrite it, bc we need the first backup with the original changes!
		\$dump_settings = array(
			'include-tables' => array(),
			'exclude-tables' => array(),
			'include-views' => array(),
			'compress' => DBDumperHandler::NONE,
			'no-data' => false,
			'reset-auto-increment' => false,
			'add-drop-database' => false,
			'add-drop-table' => true,
			'add-drop-trigger' => false,
			'add-drop-routine' => true,
			'add-drop-event' => false,
			'add-locks' => true,
			'complete-insert' => true, //must be complete-insert bc postgres gives an error when dumping insert queries without column names.
			'databases' => false,
			'default-character-set' => \"" . (!empty($pd747dfc1["encoding"]) ? $pd747dfc1["encoding"] : DBDumperHandler::UTF8) . "\",
			'disable-keys' => true,
			'extended-insert' => false,
			'events' => false,
			'hex-blob' => false, //faster than escaped content
			'insert-ignore' => false, 
			'net_buffer_length' => DBDumperHandler::MAX_LINE_SIZE,
			'no-autocommit' => false,
			'no-create-info' => false,
			'lock-tables' => true,
			'routines' => true, //for store procedure
			'single-transaction' => false,
			'skip-triggers' => false,
			'skip-tz-utc' => true,
			'skip-comments' => true,
			'skip-dump-date' => false,
			'skip-definer' => false,
			'where' => '',
		);

		\$DBDumperHandler = new DBDumperHandler(\$DBDriver, \$dump_settings, \$pdo_settings);
		\$DBDumperHandler->connect();
		\$DBDumperHandler->run(\$dump_file_path);

		if (!file_exists(\$dump_file_path))
			exitScript(\"Error: Could not create '\$dump_file_path' file\");
		
		\$DBDumperHandler->disconnect();
	}
}

//connects to DB
try {
	\$connected = \$DBDriver->connect();
}
catch (Exception \$e) {
	\$exception = \$e;
}

if (!\$connected || \$exception) {
	\$msg = \$exception ? (\$exception->problem ? \$exception->problem . PHP_EOL : \"\") . \$exception->getMessage() : \"\";
	exitScript(\"Error (3): Could not connect to \$db_type DB Driver: '$v89cfc6ba9c'!\" . (\$msg ? \"\\n\" . \$msg : \"\"));
}
"; if ($pf2d9ff95 && !$v71af0b0882) { $v067674f4e4 .= "
//get the DB schema from server
\$old_tables = \$DBDriver->listTables();

if (\$old_tables) {
	//save tables structure
	foreach (\$old_tables as \$idx => \$table) {
		\$table_name = isset(\$table[\"name\"]) ? \$table[\"name\"] : null;
		\$old_tables[\$idx][\"attributes\"] = \$DBDriver->listTableFields(\$table_name);
	}
	
	//TODO: get views, indexes, triggers, procedures and save them too...
}

//get the DB schema from the local computer
\$new_tables_path = \$deploying_folder_path . \"migrate_dbs/dbsbackup/db_structure.$v89cfc6ba9c.json\";
\$new_tables = file_exists(\$new_tables_path) ? json_decode(file_get_contents(\$new_tables_path), true) : null;

//compare both tables arrays, get the differences and add and remove differences
\$new_tables_name = array();
\$dump_backup = false;

if (\$new_tables) {
	\$new_tables_fks = array();
	
	foreach (\$new_tables as \$new_table) {
		\$tn = isset(\$new_table[\"name\"]) ? \$new_table[\"name\"] : null;
		\$fks = isset(\$new_table[\"foreign_keys\"]) ? \$new_table[\"foreign_keys\"] : null;
		\$new_tables_name[] = \$tn;
		\$old_table_detected = null;
		
		foreach (\$old_tables as \$old_table) {
			\$old_table_name = isset(\$old_table[\"name\"]) ? \$old_table[\"name\"] : null;
			
			if (\$DBDriver->isTheSameTableName(\$old_table_name, \$tn)) {
				\$old_table_detected = \$old_table;
				break;
			}
		}
		
		//create new table
		if (!\$old_table_detected) { 
			\$msg = null;
			
			try {
				\$sql = \$DBDriver->getDropTableForeignKeysStatement(\$tn, \$sql_options);
				\$DBDriver->setData(\$sql, array(\"remove_comments\" => true));
				
				\$sql = \$DBDriver->getDropTableStatement(\$tn, \$sql_options);
				\$DBDriver->setData(\$sql, array(\"remove_comments\" => true));
				
				//remove foreign_keys, bc they will be added later.
				unset(\$new_table[\"foreign_keys\"]);
				\$sql = \$DBDriver->getCreateTableStatement(\$new_table, \$sql_options);
				\$created = \$DBDriver->setData(\$sql, array(\"remove_comments\" => true));
				
				//prepare foreign keys to be added later
				if (\$created && \$fks) 
					\$new_tables_fks[\$tn] = \$fks;
			}
			catch(Exception \$e) {
				\$msg = \$e->getMessage();
			}
			
			if (!\$created)
				echo \"\\nError: Could not create table '\$tn'!\" . (\$msg ? \"\\n\" . \$msg : \"\");
		}
		else {
			//compare table attributes
			\$new_attributes = isset(\$new_table[\"attributes\"]) ? \$new_table[\"attributes\"] : null;
			\$old_attributes = isset(\$old_table_detected[\"attributes\"]) ? \$old_table_detected[\"attributes\"] : null;
			\$new_table_attributes_name = array();
			\$attributes_to_add = array();
			\$attributes_to_modify = array();
			\$change_pks = false;
			\$pks = \$pks_attrs = \$auto_increment_pks = array();
			
			foreach (\$new_attributes as \$new_attribute) {
				\$tan = isset(\$new_attribute[\"name\"]) ? \$new_attribute[\"name\"] : null;
				\$new_table_attributes_name[] = \$tan;
				\$old_tan_detected = null;
				
				if (!empty(\$new_attribute[\"primary_key\"])) {
					\$pks[] = \$tan;
					\$pks_attrs[] = \$new_attribute;
				}
				
				foreach (\$old_attributes as \$old_attribute) {
					\$old_attribute_name = isset(\$old_attribute[\"name\"]) ? \$old_attribute[\"name\"] : null;
					
					if (\$old_attribute_name == \$tan) {
						\$old_tan_detected = \$old_attribute;
						break;
					}
				}
				
				//create new attribute
				if (!\$old_tan_detected)
					\$attributes_to_add[] = \$new_attribute;
				else {
					\$new_attribute_type = isset(\$new_attribute[\"type\"]) ? \$new_attribute[\"type\"] : null;
					\$new_attribute_length = isset(\$new_attribute[\"length\"]) ? \$new_attribute[\"length\"] : null;
					\$new_attribute_null = isset(\$new_attribute[\"null\"]) ? \$new_attribute[\"null\"] : null;
					\$new_attribute_unique = isset(\$new_attribute[\"unique\"]) ? \$new_attribute[\"unique\"] : null;
					\$new_attribute_unsigned = isset(\$new_attribute[\"unsigned\"]) ? \$new_attribute[\"unsigned\"] : null;
					\$new_attribute_default = isset(\$new_attribute[\"default\"]) ? \$new_attribute[\"default\"] : null;
					\$new_attribute_charset = isset(\$new_attribute[\"charset\"]) ? \$new_attribute[\"charset\"] : null;
					\$new_attribute_collation = isset(\$new_attribute[\"collation\"]) ? \$new_attribute[\"collation\"] : null;
					\$new_attribute_extra = isset(\$new_attribute[\"extra\"]) ? \$new_attribute[\"extra\"] : null;
					\$new_attribute_comment = isset(\$new_attribute[\"comment\"]) ? \$new_attribute[\"comment\"] : null;
					
					\$old_tan_detected_type = isset(\$old_tan_detected[\"type\"]) ? \$old_tan_detected[\"type\"] : null;
					\$old_tan_detected_length = isset(\$old_tan_detected[\"length\"]) ? \$old_tan_detected[\"length\"] : null;
					\$old_tan_detected_null = isset(\$old_tan_detected[\"null\"]) ? \$old_tan_detected[\"null\"] : null;
					\$old_tan_detected_unique = isset(\$old_tan_detected[\"unique\"]) ? \$old_tan_detected[\"unique\"] : null;
					\$old_tan_detected_unsigned = isset(\$old_tan_detected[\"unsigned\"]) ? \$old_tan_detected[\"unsigned\"] : null;
					\$old_tan_detected_default = isset(\$old_tan_detected[\"default\"]) ? \$old_tan_detected[\"default\"] : null;
					\$old_tan_detected_charset = isset(\$old_tan_detected[\"charset\"]) ? \$old_tan_detected[\"charset\"] : null;
					\$old_tan_detected_collation = isset(\$old_tan_detected[\"collation\"]) ? \$old_tan_detected[\"collation\"] : null;
					\$old_tan_detected_extra = isset(\$old_tan_detected[\"extra\"]) ? \$old_tan_detected[\"extra\"] : null;
					\$old_tan_detected_comment = isset(\$old_tan_detected[\"comment\"]) ? \$old_tan_detected[\"comment\"] : null;
					
					//compare type, length, null, primary_key, unique, unsigned, default, charset, collation, extra, comment
					\$is_at_diff = \$new_attribute_type != \$old_tan_detected_type || \$new_attribute_length != \$old_tan_detected_length || \$new_attribute_null != \$old_tan_detected_null || \$new_attribute_unique != \$old_tan_detected_unique || \$new_attribute_unsigned != \$old_tan_detected_unsigned || \$new_attribute_default != \$old_tan_detected_default || \$new_attribute_charset != \$old_tan_detected_charset || \$new_attribute_collation != \$old_tan_detected_collation || \$new_attribute_extra != \$old_tan_detected_extra || \$new_attribute_comment != \$old_tan_detected_comment;
					
					//add table attribute to modify
					if (\$is_at_diff)
						\$attributes_to_modify[] = \$new_attribute;
					
					//check if PK was changed
					\$new_attribute_primary_key = isset(\$new_attribute[\"primary_key\"]) ? \$new_attribute[\"primary_key\"] : null;
					\$old_tan_detected_primary_key = isset(\$old_tan_detected[\"primary_key\"]) ? \$old_tan_detected[\"primary_key\"] : null;
					
					if (\$new_attribute_primary_key != \$old_tan_detected_primary_key) {
						\$change_pks = true;
						
						//check if old attribute is auto increment pk
						if (\$old_tan_detected_primary_key && (!empty(\$old_tan_detected[\"auto_increment\"]) || stripos(\$old_tan_detected_extra, \"auto_increment\") !== false)) {
							\$old_tan_detected_name = isset(\$old_tan_detected[\"name\"]) ? \$old_tan_detected[\"name\"] : null;
							
							\$auto_increment_pks[\$old_tan_detected_name] = \$old_tan_detected;
						}
					}
				}
			}
			
			\$attrs_with_auto_increment_to_modify = array();
			\$pks_dropped = false;
"; if ($v644df8cd23) $v067674f4e4 .= "
			//remove PKs
			if (\$change_pks) {
				//check if old PKs exists
				\$exists_pks = false;
				
				/*foreach (\$new_attributes as \$new_attribute) {
					\$new_attribute_name = isset(\$new_attribute[\"name\"]) ? \$new_attribute[\"name\"] : null;
					
					foreach (\$old_attributes as \$old_attribute) {
						\$old_attribute_name = isset(\$old_attribute[\"name\"]) ? \$old_attribute[\"name\"] : null;
						\$old_attribute_primary_key = isset(\$old_attribute[\"primary_key\"]) ? \$old_attribute[\"primary_key\"] : null;
						
						if (\$old_attribute_name == \$new_attribute_name && \$old_attribute_primary_key) {
							\$exists_pks = true;
							break;
						}
					}
					
					if (\$exists_pks)
						break;
				}*/
				foreach (\$old_attributes as \$old_attribute) {
					\$old_attribute_primary_key = isset(\$old_attribute[\"primary_key\"]) ? \$old_attribute[\"primary_key\"] : null;
					
					if (\$old_attribute_primary_key) {
						\$exists_pks = true;
						break;
					}
				}
				
				//drop old PKs if exists
				if (\$exists_pks) {
					//Before removing the pks we must first delete all the auto increments keys, bc I cannot remove pk if there are auto_increment keys in Mysql. To remove the auto_increment key, we only need to execute a modify statement. For more info please check: https://www.techbrothersit.com/2019/01/how-to-drop-or-disable-autoincrement.html
					if (\$auto_increment_pks)
						foreach (\$auto_increment_pks as \$attr_name => \$attr) {
							\$attr[\"auto_increment\"] = false;
							\$attr[\"extra\"] = isset(\$attr[\"extra\"]) ? preg_replace(\"/(^|\s)auto_increment($|\s)/i\", \"\", \$attr[\"extra\"]) : null;
							
							\$sql = \$DBDriver->getModifyTableAttributeStatement(\$tn, \$attr);
							
							if (!\$DBDriver->setData(\$sql))
								echo \"\\nError: Could not remove auto_increment prop from primary keys in table: '\$tn'!\";
						}
					
					\$sql = \$DBDriver->getDropTablePrimaryKeysStatement(\$tn, \$sql_options);
					
					if (!\$DBDriver->setData(\$sql))
						echo \"\\nError: Could not drop table primary keys for table: '\$tn'!\";
					
					\$pks_dropped = true;
				}
			}
"; $v067674f4e4 .= "
			//add table attribute
			foreach (\$attributes_to_add as \$new_attribute) {
				\$new_attribute_extra = isset(\$new_attribute[\"extra\"]) ? \$new_attribute[\"extra\"] : null;
				
				//remove auto_increment property bc it can only be added to a KEY (primary key or other key)
				if (!empty(\$new_attribute[\"auto_increment\"]) || stripos(\$new_attribute_extra, \"auto_increment\") !== false) {
					\$attrs_with_auto_increment_to_modify[] = \$new_attribute;
					
					\$new_attribute[\"extra\"] = \$new_attribute_extra ? preg_replace(\"/(^|\s)auto_increment(\s|$)/i\", \" \", \$new_attribute[\"extra\"]) : null;
					\$new_attribute[\"auto_increment\"] = false;
				}
				
				\$tan = isset(\$new_attribute[\"name\"]) ? \$new_attribute[\"name\"] : null;
				\$sql = \$DBDriver->getAddTableAttributeStatement(\$tn, \$new_attribute, \$sql_options);
				
				if (!\$DBDriver->setData(\$sql))
					echo \"\\nError: Could not add table attribute '\$tn.\$tan'!\";
			}
			
			//modify table attribute
			foreach (\$attributes_to_modify as \$new_attribute) {
				\$new_attribute_extra = isset(\$new_attribute[\"extra\"]) ? \$new_attribute[\"extra\"] : null;
				
				//remove auto_increment property bc it can only be added to a KEY (primary key or other key)
				if (\$pks_dropped && (!empty(\$new_attribute[\"auto_increment\"]) || stripos(\$new_attribute_extra, \"auto_increment\") !== false)) {
					\$attrs_with_auto_increment_to_modify[] = \$new_attribute;
					
					\$new_attribute[\"extra\"] = \$new_attribute_extra ? preg_replace(\"/(^|\s)auto_increment(\s|$)/i\", \" \", \$new_attribute[\"extra\"]) : null;
					\$new_attribute[\"auto_increment\"] = false;
				}
				
				\$tan = isset(\$new_attribute[\"name\"]) ? \$new_attribute[\"name\"] : null;
				\$sql = \$DBDriver->getModifyTableAttributeStatement(\$tn, \$new_attribute, \$sql_options);
				
				if (!\$DBDriver->setData(\$sql))
					echo \"\\nError: Could not modify table attribute '\$tn.\$tan'!\";
			}
			
			//add new PKs if exists
			if (\$change_pks && \$pks) {
				\$sql = \$DBDriver->getAddTablePrimaryKeysStatement(\$tn, \$pks_attrs, \$sql_options);
				
				if (!\$DBDriver->setData(\$sql))
					echo \"\\nError: Could not add table primary keys for table: '\$tn' with attributes: '\" . implode(\"', '\", \$pks) . \"'!\";
			}
			
			//add auto_increment to attrs after the getAddTablePrimaryKeysStatement gets executed
			if (\$attrs_with_auto_increment_to_modify)
				foreach (\$attrs_with_auto_increment_to_modify as \$attr) {
					\$sql = \$DBDriver->getModifyTableAttributeStatement(\$tn, \$attr, \$sql_options);
					
					if (!\$DBDriver->setData(\$sql))
						echo \"\\nError: Could not modify table attribute '\$tn.\$tan' adding auto_increment property!\";
				}
"; if ($v644df8cd23) $v067674f4e4 .= "			
			//remove old table attributes
			foreach (\$old_attributes as \$old_attribute)
				if (!empty(\$old_attribute[\"name\"]) && !in_array(\$old_attribute[\"name\"], \$new_table_attributes_name)) {
					//remove old attribute
					\$sql = \$DBDriver->getDropTableAttributeStatement(\$tn, \$old_attribute[\"name\"], \$sql_options);
					
					if (!\$DBDriver->setData(\$sql))
						echo \"\\nError: Could not drop table attribute '\$tn.\" . \$old_attribute[\"name\"] . \"'!\";
				}
"; $v067674f4e4 .= "			
		}
			
	}
	
	//add foreign keys if exists any...
	foreach (\$new_tables_fks as \$tn => \$fks) 
		if (\$fks) {
			foreach (\$fks as \$fk) {
				\$msg = null;
				\$created = true;
				
				try {
					\$sql = \$DBDriver->getAddTableForeignKeyStatement(\$tn, \$fk, \$sql_options);
					
					if (!\$DBDriver->setData(\$sql, array(\"remove_comments\" => true)))
						\$created = false;
				}
				catch(Exception \$e) {
					\$msg = \$e->getMessage();
				}
				
				if (!\$created)
					echo \"\\nError: Could not add foreign key to table '\$tn'!\" . (\$msg ? \"\\n\" . \$msg : \"\");
			}
		}
"; if ($v644df8cd23) $v067674f4e4 .= "
	
//remove old tables
foreach (\$old_tables as \$old_table) 
	if (!empty(\$old_table[\"name\"]) && !\$DBDriver->isTableInNamesList(\$new_tables_name, \$old_table[\"name\"])) {
		\$sql_1 = \$DBDriver->getDropTableForeignKeysStatement(\$old_table[\"name\"], \$sql_options);
		\$sql_2 = \$DBDriver->getDropTableStatement(\$old_table[\"name\"], \$sql_options);
		
		if (!\$DBDriver->setData(\$sql_1) || !\$DBDriver->setData(\$sql_2))
			echo \"\\nError: Table '\" . \$old_table[\"name\"] . \"' in '$v89cfc6ba9c' DB could not be removed!\";
	}

"; } else if ($v71af0b0882) { if ($pf2d9ff95) $v067674f4e4 .= "
//remove old tables since we will dump the new schema
/* DO NOT EXECUTE THIS CODE, bc if a table has a foreign key to another table and we try to remove it, it won't work and we will get a DB error, if that Foreign Key is restrict on delete. The dbsqldump_schema.$v89cfc6ba9c.sql file already contains the proper code to remove this tables, this is, first removes the foreign keys and then remove the table. This means that this code is obsulete and deprecated!
\$old_tables = \$DBDriver->listTables();
if (\$old_tables)
	foreach (\$old_tables as \$old_table) 
		if (!empty(\$old_table[\"name\"])) {
			\$sql_1 = \$DBDriver->getDropTableForeignKeysStatement(\$old_table[\"name\"], \$sql_options);
			\$sql_2 = \$DBDriver->getDropTableStatement(\$old_table[\"name\"], \$sql_options);
			
			if (!\$DBDriver->setData(\$sql_1) || !\$DBDriver->setData(\$sql_2))
				echo \"\\nError: Table '\" . \$old_table[\"name\"] . \"' in '$v89cfc6ba9c' DB could not be removed!\";
		}
*/

//load dbsqldump_schema.$v89cfc6ba9c.sql (to migrate the latest db schema)
\$dbsqldump_schema_path = \$deploying_folder_path . \"migrate_dbs/dbsbackup/dbsqldump_schema.$v89cfc6ba9c.sql\";

if (!file_exists(\$dbsqldump_schema_path))
	exitScript(\"\\nError: File dbsqldump_schema.$v89cfc6ba9c.sql does not exists!\");

\$contents = file_get_contents(\$dbsqldump_schema_path);
\$imported = true;
\$msg = \"\";

try {
	\$imported = \$DBDriver->setData(\$contents, array(\"remove_comments\" => true)); //This must be executed in a batch (this is, all sqls together) bc we may have store procedures or other sql commands that can only take effect if executed together in the same sql session. SO PLEASE DO NOT SPLIT THE SQL STATEMENTS!
}
catch(Exception \$e) {
	\$exception = \$e;
}

if (!\$imported || \$exception) {
	\$msg = \$exception ? PHP_EOL . (\$exception->problem ? \$exception->problem . PHP_EOL : \"\") . \$exception->getMessage() : \"\";
	exitScript(\"\\nError: Could not migrate schema for DB '$v89cfc6ba9c'!\" . (\$msg ? \"\\n\" . \$msg : \"\"));
}
"; $v067674f4e4 .= "
//load the dbsqldump_data.$v89cfc6ba9c.sql (to copy the db data)
//only insert the new values and do not replace the existent values. This file has insert ignore! If we wish to replace all data, we execute first the dbsqldump_schema.$v89cfc6ba9c.sql and then dbsqldump_data.$v89cfc6ba9c.sql.
\$dbsqldump_data_path = \$deploying_folder_path . \"migrate_dbs/dbsbackup/dbsqldump_data.$v89cfc6ba9c.sql\";

if (!file_exists(\$dbsqldump_data_path))
	exitScript(\"\\nError: File dbsqldump_data.$v89cfc6ba9c.sql does not exists!\");

\$contents = file_get_contents(\$dbsqldump_data_path);
\$msg = \"\";

try {
	\$imported = \$DBDriver->setData(\$contents, array(\"remove_comments\" => true)); //This must be executed in a batch (this is, all sqls together) bc we may have store procedures or other sql commands that can only take effect if executed together in the same sql session. SO PLEASE DO NOT SPLIT THE SQL STATEMENTS!
}
catch(Exception \$e) {
	\$exception = \$e;
}

if (!\$imported || \$exception) {
	\$msg = \$exception ? PHP_EOL . (\$exception->problem ? \$exception->problem . PHP_EOL : \"\") . \$exception->getMessage() : \"\";
	exitScript(\"\\nError: Could not migrate data for DB '$v89cfc6ba9c'!\" . (\$msg ? \"\\n\" . \$msg : \"\"));
}
"; } $v067674f4e4 .= "
\$DBDriver->disconnect();
"; } } $v067674f4e4 .= "
//remove deploying folder 
removeFile(\$deploying_folder_path);

" . $this->mef56ed7c3970() . "
" . $this->f8cf63fbbc2() . "
" . $this->f71ceffff49() . "
" . $this->f202ae761dc(); } $v067674f4e4 .= "
exitScript(); //terminate script without errors
?>"; return file_put_contents($pa6116ee8, $v067674f4e4) !== false; } private function f70d9207fac($paf0ee1a7, $pa6116ee8, $pddc51a8e) { $v4f2e308cee = isset($paf0ee1a7["properties"]["server_installation_folder_path"]) ? $paf0ee1a7["properties"]["server_installation_folder_path"] : null; $pb375ad0f = isset($paf0ee1a7["properties"]["server_installation_url"]) ? $paf0ee1a7["properties"]["server_installation_url"] : null; if (substr($v4f2e308cee, -1) != "/") $v4f2e308cee .= "/"; $pb375ad0f = (strpos($pb375ad0f, "://") === false ? "http://" : "") . $pb375ad0f; $pb375ad0f .= substr($pb375ad0f, -1) != "/" ? "/" : ""; $v1ae34537ad = parse_url($pb375ad0f); unset($v1ae34537ad["user"]); unset($v1ae34537ad["pass"]); unset($v1ae34537ad["query"]); unset($v1ae34537ad["fragment"]); $pb375ad0f = $this->me9618f011deb($v1ae34537ad); $v067674f4e4 = '<?php
$layer_folder_name = isset($argv[1]) ? $argv[1] : null;
$wordpress_installation = isset($argv[2]) ? $argv[2] : null;

if ($wordpress_installation) {
	$wordpress_folder_path = "' . $v4f2e308cee . 'app/layer/$layer_folder_name/common/webroot/' . WordPressUrlsParser::WORDPRESS_FOLDER_PREFIX . '/$wordpress_installation/";
	$wordpress_url = "' . ($pb375ad0f ? $pb375ad0f . 'common/' . WordPressUrlsParser::WORDPRESS_FOLDER_PREFIX . '/$wordpress_installation' : '') . '";
	
	if (!file_exists($wordpress_folder_path)) 
		echo "\'$wordpress_folder_path\' folder does not exists!";
	else if (!$wordpress_url) 
		echo "wordpress_url var cannot be empty!";
	else {
		$status = true;
		$error_message = "";
		
		//include wordpress lib
		require $wordpress_folder_path . "wp-load.php";
		
		//check if the WP_HOME and WP_SITEURL are the same than the $wordpress_url. If not, it means that the wordpress was moved through the deployment process and it should be updated before it continues
		$wp_home_url = get_option("home");
		$wp_site_url = get_option("siteurl");
		
		//prepare $wordpress_url with right protocol;
		if (empty(parse_url($wordpress_url, PHP_URL_SCHEME))) {
			$protocol = parse_url($wp_home_url, PHP_URL_SCHEME);
			
			if (!$protocol)
				$protocol = parse_url($wp_site_url, PHP_URL_SCHEME);
			
			if ($protocol)
				$wordpress_url = $protocol . "://" . $wordpress_url;
		}
		
		if ($wordpress_url != $wp_home_url || $wordpress_url != $wp_site_url) {
			//update site_url and home url in the wordpress DB, otherwise will have this file url as default and wordpress will gets reinstalled, loosing all its hacks that the system did on the installation process...
			update_option("siteurl", $wordpress_url);
			update_option("home", $wordpress_url);
			
			//error if update did not run correctly
			if ($wordpress_url != get_option("home") || $wordpress_url != get_option("siteurl")) {
				$status = false;
				
				$error_message = "Could not automatically update the new URL in the DB of the WordPress installation: \'$wordpress_installation\'! Please try again or contact the system administrator...";
			}
			
			//check the wordpress/.htaccess file to see if contains the host or uri that do not correspond to the $wordpress_url
			$htaccess_fp = $wordpress_folder_path . ".htaccess";
			
			if (file_exists($htaccess_fp)) {
				$htaccess_contents = file_get_contents($htaccess_fp);
				
				$new_url_parts = parse_url($wordpress_url);
				$old_url_parts = parse_url($wordpress_url != $wp_home_url ? $wp_home_url : $wp_site_url);
				
				//remove last slash so the paths be sanitized
				if (substr($new_url_parts["path"], -1) == "/")
					$new_url_parts["path"] = substr($new_url_parts["path"], 0, -1);
				
				if (substr($old_url_parts["path"], -1) == "/")
					$old_url_parts["path"] = substr($old_url_parts["path"], 0, -1);
				
				//replace htaccess with new host and path
				$new_url_parts_host = isset($new_url_parts["host"]) ? $new_url_parts["host"] : null;
				$old_url_parts_host = isset($old_url_parts["host"]) ? $old_url_parts["host"] : null;
				
				if ($new_url_parts_host != $old_url_parts_host && strpos($htaccess_contents, $old_url_parts_host) !== false)
					$htaccess_contents = str_replace($old_url_parts_host, $new_url_parts_host, $htaccess_contents);
				
				if ($new_url_parts["path"] != $old_url_parts["path"] && strpos($htaccess_contents, $old_url_parts["path"]) !== false)
					$htaccess_contents = str_replace($old_url_parts["path"], $new_url_parts["path"], $htaccess_contents);
				
				if (file_put_contents($htaccess_fp, $htaccess_contents) === false) {
					$status = false;
					
					$error_message = ($error_message ? "\n" : "") . "Could not automatically update the new URL in the .htaccess of the WordPress installation: \'$wordpress_installation\'! Please try again or contact the system administrator...";
				}
			}
			
			//flush wordpress cache
			//flush_rewrite_rules();
			wp_clean_update_cache();
			wp_cache_flush();
		}
		
		echo $status ? 1 : $error_message;
	}
}
?>'; return file_put_contents($pa6116ee8, $v067674f4e4) !== false; } private function mef56ed7c3970() { return "
function exitScript(\$msg = null) {
	if (\$msg) {
		echo \$msg;
		exit(1);
	}
	
	echo 1;
	exit();
}
"; } private function f8cf63fbbc2() { return "
function get_lib(\$str) {
	global \$lib_folder_path;
	\$str = preg_replace(\"/^lib(\\.|\\/)/\", \"\", \$str); //remove first lib prefix, bc all files will be already added from the lib_folder_path
	return \$lib_folder_path . str_replace(\".\", \"/\", \$str) . \".php\";
}
"; } private function f71ceffff49() { return "
function launch_exception(Exception \$exception) {
	throw \$exception;
	return false;
}
"; } private function f202ae761dc() { return "
function removeFile(\$path) {
	\$status = true;
	
	if (\$path) {
		if (is_dir(\$path)) {
			\$files = array_diff(scandir(\$path), array('.', '..'));
			
			foreach (\$files as \$file)
				if (!removeFile(\"\$path/\$file\"))
					\$status = false;
			
			if (\$status)
				\$status = rmdir(\$path);
		}
		else if (file_exists(\$path) && !unlink(\$path))
			\$status = false;
	}
	return \$status;
}
"; } private function f1f81d050c6() { return "
function getFolderFilesPermissions(\$path, \$key = \"\", \$this_file_uid = null) {
	\$perms = array();
		
	if (\$path) {
		\$file_path = \"\$path/\$key\";
		
		if (file_exists(\$file_path)) {
			\$file_perm = fileperms(\$file_path) & 0777;
			\$file_uid = fileowner(\$file_path); //this is the file owner user id
			\$this_file_uid = \$this_file_uid ? \$this_file_uid : fileowner(__FILE__); //this is the ftp user like jplpinto
			
			//if file's owner is different than this_file_uid, we set the perm to 777, bc we cannot change the user owner. Var \$this_file_uid is jplpinto!
			if (\$this_file_uid != \$file_uid) 
				\$file_perm = 0777;
				
			\$perms[ \$key ? \$key : \"/\" ] = \"0\" . decoct(\$file_perm);
			
			if (is_dir(\$file_path)) {
				\$files = scandir(\$file_path);
				
				if (\$files)
					foreach (\$files as \$file)
						if (!in_array(\$file, " . self::f8edaa78497() . ")) 
							\$perms = array_merge(\$perms, getFolderFilesPermissions(\$path, \"\$key/\$file\", \$this_file_uid));
			}
		}
	}
	
	return \$perms;
}
"; } private function f866b8c7922($pc0fc7d17, $v5296fbf21d) { return PHPVariablesFileHandler::saveVarsToFile($pc0fc7d17, $v5296fbf21d); } private function f7bdfa249e6($v8625b218a4) { $pd7a36e35 = array(); $v45de354860 = isset($v8625b218a4["vars_name"]) ? $v8625b218a4["vars_name"] : null; $v61fc24eb6d = isset($v8625b218a4["vars_value"]) ? $v8625b218a4["vars_value"] : null; if ($v45de354860) { if (!is_array($v45de354860)) { $v45de354860 = array($v45de354860); $v61fc24eb6d = array($v61fc24eb6d); } $pc37695cb = count($v45de354860); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $pd7a36e35[ $v45de354860[$v43dd7d0051] ] = isset($v61fc24eb6d[$v43dd7d0051]) ? $v61fc24eb6d[$v43dd7d0051] : null; } return $pd7a36e35; } private function f038f5b9c57($v3d55458bcd, $v8625b218a4) { $pd7a36e35 = $this->f7bdfa249e6($v8625b218a4); return PHPVariablesFileHandler::saveVarsToFile($v3d55458bcd, $pd7a36e35, true); } private function f7211ccf19b($v2bdab1bf22, $v80c5ba5219, $v44ff379d4c) { $pb4b50f9b = $this->pe9c62c08; if ($pb4b50f9b && !empty($pb4b50f9b["tasks"])) { $pd794563c = array(); $v21323a157b = isset($this->pb4703992["dbdriver"]) ? $this->pb4703992["dbdriver"] : null; foreach ($pb4b50f9b["tasks"] as $v8282c7dd58 => $v7f5911d32d) { $v89cfc6ba9c = isset($v7f5911d32d["label"]) ? $v7f5911d32d["label"] : null; $pd747dfc1 = isset($v80c5ba5219[$v89cfc6ba9c]) ? $v80c5ba5219[$v89cfc6ba9c] : null; $pd747dfc1 = $pd747dfc1 ? $pd747dfc1 : array(); $pc8421459 = isset($v7f5911d32d["type"]) ? $v7f5911d32d["type"] : null; if (empty($pd747dfc1["active"]) && $pc8421459 == $v21323a157b) { $pd794563c[] = $v8282c7dd58; unset($pb4b50f9b["tasks"][$v8282c7dd58]); } } foreach ($pb4b50f9b["tasks"] as $v8282c7dd58 => $v7f5911d32d) { $v89cfc6ba9c = isset($v7f5911d32d["label"]) ? $v7f5911d32d["label"] : null; $pd747dfc1 = isset($v80c5ba5219[$v89cfc6ba9c]) ? $v80c5ba5219[$v89cfc6ba9c] : null; $pd747dfc1 = $pd747dfc1 ? $pd747dfc1 : array(); $pc8421459 = isset($v7f5911d32d["type"]) ? $v7f5911d32d["type"] : null; if ($pc8421459 == $v21323a157b && $pd747dfc1) { if (!empty($v7f5911d32d["properties"])) { foreach ($v7f5911d32d["properties"] as $pe5c5e2fe => $v956913c90f) if (array_key_exists($pe5c5e2fe, $pd747dfc1)) $pb4b50f9b["tasks"][$v8282c7dd58]["properties"][$pe5c5e2fe] = $pd747dfc1[$pe5c5e2fe]; } else { unset($pd747dfc1["migrate_db_schema"]); unset($pd747dfc1["remove_deprecated_tables_and_attributes"]); unset($pd747dfc1["migrate_db_data"]); $pb4b50f9b["tasks"][$v8282c7dd58]["properties"] = $pd747dfc1; } } if (array_key_exists("active", $pd747dfc1)) $pb4b50f9b["tasks"][$v8282c7dd58]["properties"]["active"] = $pd747dfc1["active"]; $pb4b50f9b["tasks"][$v8282c7dd58]["start"] = array_key_exists("start", $pd747dfc1) && $pd747dfc1["start"] ? 1 : 0; if (!empty($v7f5911d32d["exits"]["layer_exit"])) { $v6939304e91 = $v7f5911d32d["exits"]["layer_exit"]; if (!empty($v6939304e91["task_id"])) $v6939304e91 = array($v6939304e91); foreach ($v6939304e91 as $v5f3164fb81 => $paec2c009) { $v0b70a1f3bc = isset($paec2c009["task_id"]) ? $paec2c009["task_id"] : null; if (in_array($v0b70a1f3bc, $pd794563c)) unset($v6939304e91[$v5f3164fb81]); else { $pc37695cb = isset($this->pe9c62c08["tasks"][$v0b70a1f3bc]) ? $this->pe9c62c08["tasks"][$v0b70a1f3bc] : null; $v489cba0859 = isset($pc37695cb["label"]) ? $pc37695cb["label"] : null; if (!empty($v44ff379d4c[$v89cfc6ba9c]) && array_key_exists($v489cba0859, $v44ff379d4c[$v89cfc6ba9c])) $v6939304e91[$v5f3164fb81]["properties"] = $v44ff379d4c[$v89cfc6ba9c][$v489cba0859]; } } $pb4b50f9b["tasks"][$v8282c7dd58]["exits"]["layer_exit"] = $v6939304e91; } } } return WorkFlowTasksFileHandler::createTasksFile($v2bdab1bf22, $pb4b50f9b); } private function f277a2a7c8c($v08a367fe04, $v3d55458bcd, $pc0fc7d17, $v2bdab1bf22) { $v5039a77f9d = $v08a367fe04 . "app/config/bean/"; $v73fec76b27 = array( "LAYER_CACHE_PATH" => $v08a367fe04 . "tmp/cache/layer/", "LAYER_PATH" => $v08a367fe04 . "app/layer/", "SYSTEM_LAYER_PATH" => $v08a367fe04 . "app/__system/layer/", "BEAN_PATH" => $v5039a77f9d, ); $v7935389d61 = new WorkFlowBeansConverter($v2bdab1bf22, $v5039a77f9d, $v3d55458bcd, $pc0fc7d17, $v73fec76b27); $v7935389d61->init(); $v5c1c342594 = $v7935389d61->createBeans(); return $v5c1c342594; } private function md3ac2febb8c4($pc8421459, $v051bc0b931, &$v6ee393d9fb) { if ($v6ee393d9fb) { $pd622504e = is_dir($v051bc0b931) ? scandir($v051bc0b931) : null; if ($pd622504e) foreach ($pd622504e as $v1b08a89324) if (!in_array($v1b08a89324, self::$pa2a1daed) && !is_dir("$v051bc0b931/$v1b08a89324")) $this->f9efad03639($v1b08a89324, $v6ee393d9fb); } } private function f9efad03639($v7dffdb5a5b, &$v6ee393d9fb) { $v7959970a41 = in_array($v7dffdb5a5b, $v6ee393d9fb); if (!$v7959970a41) foreach ($v6ee393d9fb as $v1b08a89324) { $v1b08a89324 = trim($v1b08a89324); $v1b08a89324 = substr($v1b08a89324, 0, 1) == "/" ? substr($v1b08a89324, 1) : $v1b08a89324; $v1b08a89324 = substr($v1b08a89324, -1) == "/" ? substr($v1b08a89324, 0, -1) : $v1b08a89324; if ($v1b08a89324 == $v7dffdb5a5b) { $v7959970a41 = true; break; } } if (!$v7959970a41) $v6ee393d9fb[] = $v7dffdb5a5b; } private function mb8b562d1df0b($v130fd1f9ce, $v80c5ba5219, &$pddc51a8e) { $v5c1c342594 = true; $v130fd1f9ce .= substr($v130fd1f9ce, -1) != "/" ? "/" : ""; $v1d696dbd12 = $this->v121d0ed499->getTasksByLayerTag("dbdriver"); $v660b5296f8 = new WorkFlowDBHandler($this->v5039a77f9d, $this->v3d55458bcd); if (!is_dir($v130fd1f9ce)) mkdir($v130fd1f9ce, 0755, true); if (!is_dir($v130fd1f9ce)) { $v5c1c342594 = false; $pddc51a8e[] = "Error: Could not create dbs_backup_folder_path: .dbsbackup"; } else foreach ($v1d696dbd12 as $v7f5911d32d) { $v89cfc6ba9c = isset($v7f5911d32d["label"]) ? $v7f5911d32d["label"] : null; $pd747dfc1 = isset($v80c5ba5219[$v89cfc6ba9c]) ? $v80c5ba5219[$v89cfc6ba9c] : null; $pd82569a2 = $pd747dfc1 && !empty($pd747dfc1["active"]); $pf2d9ff95 = isset($pd747dfc1["migrate_db_schema"]) ? $pd747dfc1["migrate_db_schema"] : null; $v71af0b0882 = isset($pd747dfc1["migrate_db_data"]) ? $pd747dfc1["migrate_db_data"] : null; if ($pd82569a2 && ($pf2d9ff95 || $v71af0b0882)) { $v6491a7e70b = WorkFlowBeansConverter::getFileNameFromRawLabel($v89cfc6ba9c) . "_dbdriver.xml"; $v4948cc5869 = $v660b5296f8->getBeanObject($v6491a7e70b, $v89cfc6ba9c); if ($v4948cc5869) { $v5c1c342594 = $v4948cc5869->connect(); if ($v5c1c342594) { $pac4bc40a = $v4948cc5869->listTables(); if ($pac4bc40a) { if (!DBDumper::isValid($v4948cc5869)) { $v5c1c342594 = false; $v4948cc5869->disconnect(); } else { $v968ce26ec3 = $v4948cc5869->getOptions(); $pc1d077f0 = !empty($v968ce26ec3["persistent"]) && empty($v968ce26ec3["new_link"]) ? array(PDO::ATTR_PERSISTENT => true) : array(); $v834e515e94 = null; if ($pf2d9ff95 || $v71af0b0882) { foreach ($pac4bc40a as $pd69fb7d0 => $pc661dc6b) if (!empty($pc661dc6b["name"])) { $pac4bc40a[$pd69fb7d0]["attributes"] = $v4948cc5869->listTableFields($pc661dc6b["name"]); $pac4bc40a[$pd69fb7d0]["foreign_keys"] = $v4948cc5869->listForeignKeys($pc661dc6b["name"]); } $v4948cc5869->disconnect(); $v720883e45a = "$v130fd1f9ce/db_structure.$v89cfc6ba9c.json"; if (file_put_contents($v720883e45a, json_encode($pac4bc40a)) === false) $v5c1c342594 = false; $v60b36de38a = array( 'include-tables' => array(), 'exclude-tables' => array(), 'include-views' => array(), 'compress' => DBDumperHandler::NONE, 'no-data' => true, 'reset-auto-increment' => false, 'add-drop-database' => false, 'add-drop-table' => true, 'add-drop-trigger' => false, 'add-drop-routine' => true, 'add-drop-event' => false, 'add-locks' => true, 'complete-insert' => false, 'databases' => false, 'default-character-set' => !empty($v968ce26ec3["encoding"]) ? $v968ce26ec3["encoding"] : DBDumperHandler::UTF8, 'disable-keys' => true, 'extended-insert' => false, 'events' => false, 'hex-blob' => false, 'insert-ignore' => false, 'net_buffer_length' => DBDumperHandler::MAX_LINE_SIZE, 'no-autocommit' => false, 'no-create-info' => false, 'lock-tables' => true, 'routines' => true, 'single-transaction' => false, 'skip-triggers' => false, 'skip-tz-utc' => true, 'skip-comments' => true, 'skip-dump-date' => false, 'skip-definer' => false, 'where' => '', ); $v2aba126d60 = "$v130fd1f9ce/dbsqldump_schema.$v89cfc6ba9c.sql"; $v834e515e94 = new DBDumperHandler($v4948cc5869, $v60b36de38a, $pc1d077f0); $v834e515e94->connect(); $v834e515e94->run($v2aba126d60); if (!file_exists($v2aba126d60)) $v5c1c342594 = false; } else $v4948cc5869->disconnect(); if ($v71af0b0882) { $v60b36de38a = array( 'include-tables' => array(), 'exclude-tables' => array(), 'include-views' => array(), 'compress' => DBDumperHandler::NONE, 'no-data' => false, 'reset-auto-increment' => false, 'add-drop-database' => false, 'add-drop-table' => false, 'add-drop-trigger' => false, 'add-drop-routine' => false, 'add-drop-event' => false, 'add-locks' => false, 'complete-insert' => true, 'databases' => false, 'default-character-set' => !empty($v968ce26ec3["encoding"]) ? $v968ce26ec3["encoding"] : DBDumperHandler::UTF8, 'disable-keys' => true, 'extended-insert' => false, 'events' => false, 'hex-blob' => false, 'insert-ignore' => true, 'net_buffer_length' => DBDumperHandler::MAX_LINE_SIZE, 'no-autocommit' => false, 'no-create-info' => true, 'lock-tables' => true, 'routines' => false, 'single-transaction' => false, 'skip-triggers' => true, 'skip-tz-utc' => true, 'skip-comments' => true, 'skip-dump-date' => false, 'skip-definer' => false, 'where' => '', ); $v2aba126d60 = "$v130fd1f9ce/dbsqldump_data.$v89cfc6ba9c.sql"; if ($v834e515e94) $v834e515e94->setDBDumperSettings($v60b36de38a); else { $v834e515e94 = new DBDumperHandler($v4948cc5869, $v60b36de38a, $pc1d077f0); $v834e515e94->connect(); } $v834e515e94->run($v2aba126d60); if (!file_exists($v2aba126d60)) $v5c1c342594 = false; } if ($v834e515e94) $v834e515e94->disconnect(); } } else $v4948cc5869->disconnect(); } else $pddc51a8e[] = "Error: Could not connect to DB Driver $v89cfc6ba9c!"; } } } return $v5c1c342594; } private function md8b226df25ce($v08a367fe04, $pbd6307e7, &$pddc51a8e) { $v5b39797b22 = isset($pbd6307e7["keys_file"]) ? $pbd6307e7["keys_file"] : null; $v74e98f0dc8 = isset($pbd6307e7["private_key"]) ? $pbd6307e7["private_key"] : null; $v069d1b4ae6 = isset($pbd6307e7["public_key"]) ? $pbd6307e7["public_key"] : null; $pb89c0438 = isset($pbd6307e7["private_key_file"]) ? $pbd6307e7["private_key_file"] : null; $v5e59c1bb9a = isset($pbd6307e7["public_key_file"]) ? $pbd6307e7["public_key_file"] : null; $pfcd66519 = isset($pbd6307e7["passphrase"]) ? $pbd6307e7["passphrase"] : null; $v105fddb79a = isset($pbd6307e7["pro" . 'jects' . "_expira" . 'tion_d' . "ate"]) ? $pbd6307e7["pro" . 'jects' . "_expira" . 'tion_d' . "ate"] : null; $v1f5fcad24e = isset($pbd6307e7["sy" . 'sad' . "min_" . "expi" . 'ration' . "_date"]) ? $pbd6307e7["sy" . 'sad' . "min_" . "expi" . 'ration' . "_date"] : null; $v1426427798 = isset($pbd6307e7["pr" . 'oject' . "s_maxi" . 'mum_nu' . "mber"]) ? $pbd6307e7["pr" . 'oject' . "s_maxi" . 'mum_nu' . "mber"] : null; $v2a25aacb5b = isset($pbd6307e7["us" . 'er' . "s_maxi" . 'mum_nu' . "mber"]) ? $pbd6307e7["us" . 'er' . "s_maxi" . 'mum_nu' . "mber"] : null; $pc9ad6087 = isset($pbd6307e7["e" . "nd" . "_us" . 'er' . "s_maxi" . 'mum_nu' . "mber"]) ? $pbd6307e7["e" . "nd" . "_us" . 'er' . "s_maxi" . 'mum_nu' . "mber"] : null; $pec8209d0 = isset($pbd6307e7["ac" . 'tion' . "s_maxi" . 'mum_nu' . "mber"]) ? $pbd6307e7["ac" . 'tion' . "s_maxi" . 'mum_nu' . "mber"] : null; $pf4c40197 = isset($pbd6307e7["al" . "low" . "ed_pa" . "ths"]) ? $pbd6307e7["al" . "low" . "ed_pa" . "ths"] : null; $v0cba298be6 = isset($pbd6307e7["al" . "low" . "ed_do" . "mains"]) ? $pbd6307e7["al" . "low" . "ed_do" . "mains"] : null; $pd0a05d6a = isset($pbd6307e7["chec" . "k_al" . "low" . "ed_do" . "mai" . "ns_p" . "ort"]) ? $pbd6307e7["chec" . "k_al" . "low" . "ed_do" . "mai" . "ns_p" . "ort"] : null; $pfca4e2ea = isset($pbd6307e7["sy" . "sadmi" . "n"]) ? $pbd6307e7["sy" . "sadmi" . "n"] : null; $pabf565d2 = isset($this->pf665220f["ped"]) ? $this->pf665220f["ped"] : null; $v0432229340 = isset($this->pf665220f["sed"]) ? $this->pf665220f["sed"] : null; $v860cb5afeb = isset($this->pf665220f["pmn"]) ? $this->pf665220f["pmn"] : null; $pd6c8b95f = isset($this->pf665220f["umn"]) ? $this->pf665220f["umn"] : null; $pfce96b03 = isset($this->pf665220f["eumn"]) ? $this->pf665220f["eumn"] : null; $pca56312f = isset($this->pf665220f["amn"]) ? $this->pf665220f["amn"] : null; $v6ce03a1442 = isset($this->pf665220f["ap"]) ? $this->pf665220f["ap"] : null; $pd6e5e954 = isset($this->pf665220f["asm"]) ? $this->pf665220f["asm"] : null; if ($pabf565d2 != -1 && (!trim($v105fddb79a) || $v105fddb79a < 0 || strtotime($v105fddb79a) > strtotime($pabf565d2))) $v105fddb79a = $pabf565d2; $v67131a2f26 = $v1f5fcad24e ? strtotime($v1f5fcad24e) : time() + (60 * 60 * 24 * 30); if ($v67131a2f26 > strtotime($v0432229340)) $v1f5fcad24e = $v0432229340; if ($v860cb5afeb > 0) $v860cb5afeb--; if ($v860cb5afeb != -1 && (!is_numeric($v1426427798) || $v1426427798 < 0 || $v1426427798 > $v860cb5afeb)) $v1426427798 = $v860cb5afeb; if ($pd6c8b95f != -1 && (!is_numeric($v2a25aacb5b) || $v2a25aacb5b < 0 || $v2a25aacb5b > $pd6c8b95f)) $v2a25aacb5b = $pd6c8b95f; if ($pfce96b03 != -1 && (!is_numeric($pc9ad6087) || $pc9ad6087 < 0 || $pc9ad6087 > $pfce96b03)) $pc9ad6087 = $pfce96b03; if ($pca56312f != -1 && (!is_numeric($pec8209d0) || $pec8209d0 < 0 || $pec8209d0 > $pca56312f)) $pec8209d0 = $pca56312f; if (!$pd6e5e954 && $pfca4e2ea) $pfca4e2ea = $pd6e5e954; if ($v6ce03a1442) { $pf4c40197 = trim($pf4c40197); if ($pf4c40197) { $pf4c40197 = str_replace(";", ",", $pf4c40197); $v4655b7d7e0 = "," . trim($v6ce03a1442) . ","; $pf29a9800 = ""; $v9cd205cadb = explode(",", $pf4c40197); foreach ($v9cd205cadb as $v1d2d80ed32) { $v1d2d80ed32 = trim($v1d2d80ed32); if ($v1d2d80ed32) { $v1d2d80ed32 = preg_replace("/\/+/", "/", $v1d2d80ed32); $v1d2d80ed32 = preg_replace("/\/+$/", "", $v1d2d80ed32); if (preg_match("/,\s*" . str_replace("/", "\\/", str_replace(".", "\\.", $v1d2d80ed32)) . "\s*\/?,/i", $v4655b7d7e0)) $pf29a9800 .= ($pf29a9800 ? "," : "") . $v1d2d80ed32; } } $pf4c40197 = $pf29a9800; } if (!$pf4c40197) $pf4c40197 = $v6ce03a1442; } $this->f1d44fd8063($v0cba298be6, $pd0a05d6a); if ($v5b39797b22 == "key_strings") { $v74e98f0dc8 = trim($v74e98f0dc8); $v069d1b4ae6 = trim($v069d1b4ae6); if (!$v74e98f0dc8 || !$v069d1b4ae6) { $pddc51a8e[] = "Error: Private and Public Key strings cannot be empty! You must enter the text from your private/public .pem file!"; return false; } $pb89c0438 = $v08a367fe04 . "deployment_app_priv_key.pem"; $v5e59c1bb9a = $v08a367fe04 . "deployment_app_pub_key.pem"; if (file_put_contents($pb89c0438, $v74e98f0dc8) === false || file_put_contents($v5e59c1bb9a, $v069d1b4ae6) === false) { $this->me202360dafc0($pb89c0438); $this->me202360dafc0($v5e59c1bb9a); $pddc51a8e[] = "Error: Private or Public Key strings could not be saved in temporary files to be used to create licence!"; return false; } } else { if (!$pb89c0438 || !$v5e59c1bb9a) { $pddc51a8e[] = "Error: Private and Public Key files cannot be empty! You must enter the CMS relative url for your priv.pem/pub.pem files!"; return false; } $pb89c0438 = CMS_PATH . $pb89c0438; $v5e59c1bb9a = CMS_PATH . $v5e59c1bb9a; } CMSDeploymentSecurityHandler::createAppLicence($v08a367fe04 . "app", $pb89c0438, $v5e59c1bb9a, $pfcd66519, $v105fddb79a, $v1f5fcad24e, $v1426427798, $v2a25aacb5b, $pc9ad6087, $pec8209d0, $pf4c40197, $v0cba298be6, $pd0a05d6a, $pfca4e2ea, $pddc51a8e); if ($v5b39797b22 == "key_strings") { $this->me202360dafc0($pb89c0438); $this->me202360dafc0($v5e59c1bb9a); } } private function f1d44fd8063(&$v0cba298be6, &$pd0a05d6a) { $v7daf22b9f7 = isset($this->pf665220f["ad"]) ? $this->pf665220f["ad"] : null; $paa0a2b5a = isset($this->pf665220f["cadp"]) ? $this->pf665220f["cadp"] : null; $pd0a05d6a = $paa0a2b5a ? true : $pd0a05d6a; if ($v7daf22b9f7) { $v95d8eb5b00 = trim(str_replace(";", ",", $v7daf22b9f7)) . ","; $v95d8eb5b00 = preg_replace("/:80,/", ",", $v95d8eb5b00); $v95d8eb5b00 = strtolower($paa0a2b5a ? $v95d8eb5b00 : preg_replace("/:[0-9]+,/", ",", $v95d8eb5b00)); $pe6af403d = explode(",", $v95d8eb5b00); $v6f1a751aba = array(); foreach ($pe6af403d as $v2b41c4f28c) { $v2b41c4f28c = trim($v2b41c4f28c); if ($v2b41c4f28c) $v6f1a751aba[] = $v2b41c4f28c; } if (!empty($v6f1a751aba)) { $v0cba298be6 = trim($v0cba298be6); if ($v0cba298be6) { $v0cba298be6 = trim(str_replace(";", ",", $v0cba298be6)) . ","; $v0cba298be6 = preg_replace("/:80,/", ",", $v0cba298be6); $peaf76eae = explode(",", $v0cba298be6); $v16a181c823 = ""; foreach ($peaf76eae as $pac19db45) { $pac19db45 = trim($pac19db45); if ($pac19db45) { $v3d07ac58bb = strtolower($paa0a2b5a ? $pac19db45 : preg_replace("/:[0-9]+,/", ",", $pac19db45)); $v0ff021f094 = array_search($v3d07ac58bb, $v6f1a751aba) !== false; if (!$v0ff021f094) foreach ($v6f1a751aba as $v2b41c4f28c) if (strpos("$v3d07ac58bb,", ".$v2b41c4f28c,") !== false) { $v0ff021f094 = true; break; } if ($v0ff021f094) $v16a181c823 .= ($v16a181c823 ? "," : "") . $pac19db45; } } $v0cba298be6 = $v16a181c823; } if (!$v0cba298be6) $v0cba298be6 = implode(",", $v6f1a751aba); } } } private function med626f690275($v08a367fe04, $paad98334, &$pddc51a8e) { $v5d3813882f = self::$v79bc342305; $v90f13d1b02 = new CMSObfuscatePHPFilesHandler($v08a367fe04); $pd5dfe038 = $v90f13d1b02->getDefaultSerializedFiles(); $pebb3f429 = $v90f13d1b02->getConfiguredOptions($v5d3813882f); $v63c9ec8e00 = $v90f13d1b02->getDefaultFilesToAvoidWarnings(); $v50890f6f30 = $v90f13d1b02->obfuscate($pebb3f429, $paad98334, $pd5dfe038, $v63c9ec8e00); $v1db8fcc7cd = !empty($v50890f6f30["errors"]) ? "PHP obfuscation error files: [" . implode(", ", $v50890f6f30["errors"]) . "]" : ""; $v1db8fcc7cd .= ($v1db8fcc7cd ? "\n" : "") . (isset($v50890f6f30["warning_msg"]) ? $v50890f6f30["warning_msg"] : ""); if (empty($v50890f6f30["status"]) || $v1db8fcc7cd) $pddc51a8e[] = "Error: trying to obfuscate php files!" . ($v1db8fcc7cd ? "\n" . $v1db8fcc7cd : ""); } private function f63246789f0($v08a367fe04, &$pddc51a8e) { $v90f13d1b02 = new CMSObfuscatePHPFilesHandler($v08a367fe04); $paad98334 = $v90f13d1b02->getDefaultFilesSettings($v08a367fe04); CMSDeploymentSecurityHandler::emptyFileClassMethod("$v08a367fe04/app/__system/layer/presentation/phpframework/src/util/CMSObfuscatePHPFilesHandler.php", "CMSObfuscatePHPFilesHandler", "getDefaultFilesSettings", $pddc51a8e); return $paad98334; } private function f23d64f20df($v08a367fe04, $v0cba298be6, $pd0a05d6a, $paad98334, &$pddc51a8e) { $this->f1d44fd8063($v0cba298be6, $pd0a05d6a); $v5d3813882f = str_replace("#check_allowed_domains_port#", $pd0a05d6a, str_replace("#allowed_domains#", $v0cba298be6, self::$v37b6496be7)); $v883e018d2b = new CMSObfuscateJSFilesHandler($v08a367fe04); $pebb3f429 = $v883e018d2b->getConfiguredOptions($v5d3813882f); $v50890f6f30 = $v883e018d2b->obfuscate($pebb3f429, $paad98334); $v1db8fcc7cd = !empty($v50890f6f30["errors"]) ? "JS obfuscation error files: [" . implode(", ", $v50890f6f30["errors"]) . "]" : ""; if (empty($v50890f6f30["status"]) || $v1db8fcc7cd) $pddc51a8e[] = "Error: trying to obfuscate js files!" . ($v1db8fcc7cd ? "\n" . $v1db8fcc7cd : ""); } private function f824a7af873($v08a367fe04, &$pddc51a8e) { $v644c3a506b = "/app/layer/presentation/common/webroot/"; $v0b69598e4a = "/app/__system/layer/presentation/common/webroot/"; $v858a6ae395 = "/app/__system/layer/presentation/phpframework/webroot/"; $v883e018d2b = new CMSObfuscateJSFilesHandler($v08a367fe04); $paad98334 = $v883e018d2b->getDefaultFilesSettings($v08a367fe04, $v644c3a506b, $v0b69598e4a, $v858a6ae395); CMSDeploymentSecurityHandler::emptyFileClassMethod("$v08a367fe04/app/__system/layer/presentation/phpframework/src/util/CMSObfuscateJSFilesHandler.php", "CMSObfuscateJSFilesHandler", "getDefaultFilesSettings", $pddc51a8e); return $paad98334; } private function mfb9a66a40371($pa32be502) { $this->me202360dafc0($pa32be502); if (!is_dir($pa32be502)) @mkdir($pa32be502, 0755, true); return is_dir($pa32be502); } private function f9a54b53aad($v92dcc541a8, $pa5b0817e) { $v5c1c342594 = true; $v6ee393d9fb = file_exists($v92dcc541a8) ? scandir($v92dcc541a8) : null; if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if (!in_array($v7dffdb5a5b, self::$pa2a1daed) && substr($v7dffdb5a5b, 0, 1) == ".") if (!$this->mb2ac558f4dad("$v92dcc541a8/$v7dffdb5a5b", "$pa5b0817e/$v7dffdb5a5b")) $v5c1c342594 = false; return $v5c1c342594; } private function f7940b0ef34($v92dcc541a8, $pa5b0817e, $v292065fd45 = null) { $v5c1c342594 = true; if ($v92dcc541a8 && $pa5b0817e) { $v3a0afd3885 = dirname($v92dcc541a8); $v8cf6c660a2 = dirname($pa5b0817e); $v3a0afd3885 = $v3a0afd3885 == "." ? $v3a0afd3885 : ""; $v8cf6c660a2 = $v8cf6c660a2 == "." ? $v8cf6c660a2 : ""; $v5c1c342594 = false; if (!$v3a0afd3885 || !$v8cf6c660a2) $v5c1c342594 = true; else if (is_dir($v3a0afd3885) && is_dir($v8cf6c660a2)) { $v5c1c342594 = true; $v3a0afd3885 .= "/"; $v292065fd45 .= substr($v292065fd45, -1) != "/" ? "/" : ""; $v28c1cd997a = !empty( trim(str_replace($v292065fd45, "", $v3a0afd3885)) ); if (!$this->f9a54b53aad($v3a0afd3885, $v8cf6c660a2)) $v5c1c342594 = false; if ($v28c1cd997a && !$this->f7940b0ef34($v3a0afd3885, $v8cf6c660a2, $v292065fd45)) $v5c1c342594 = false; } } return $v5c1c342594; } private function me1bfc9cf0775($v92dcc541a8, $pa5b0817e) { if ($v92dcc541a8 && $pa5b0817e && is_dir($v92dcc541a8)) { if (!is_dir($pa5b0817e)) @mkdir($pa5b0817e, 0755, true); if (is_dir($pa5b0817e)) { $v5c1c342594 = true; $v6ee393d9fb = scandir($v92dcc541a8); if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if (!in_array($v7dffdb5a5b, self::$pa2a1daed) && !$this->mb2ac558f4dad("$v92dcc541a8/$v7dffdb5a5b", "$pa5b0817e/$v7dffdb5a5b")) $v5c1c342594 = false; if (!$this->f29c1bff20e($v92dcc541a8, $pa5b0817e)) $v5c1c342594 = false; return $v5c1c342594; } } } private function mb2ac558f4dad($v92dcc541a8, $pa5b0817e) { if ($v92dcc541a8 && $pa5b0817e && file_exists($v92dcc541a8)) { if (is_dir($v92dcc541a8)) $v5c1c342594 = $this->me1bfc9cf0775($v92dcc541a8, $pa5b0817e); else { $v8cf6c660a2 = dirname($pa5b0817e); if ($v8cf6c660a2 && !is_dir($v8cf6c660a2)) mkdir($v8cf6c660a2, 0755, true); $v5c1c342594 = is_dir($v8cf6c660a2) && copy($v92dcc541a8, $pa5b0817e); } if (!$this->f29c1bff20e($v92dcc541a8, $pa5b0817e)) $v5c1c342594 = false; return $v5c1c342594; } } private function f29c1bff20e($v92dcc541a8, $pa5b0817e) { $v5c1c342594 = true; if ($v92dcc541a8 && $pa5b0817e && file_exists($v92dcc541a8) && file_exists($pa5b0817e)) { $v9509165848 = function_exists("posix_getuid") ? posix_getuid() : null; $pe14342ba = fileowner($v92dcc541a8); $pf6e3d638 = fileperms($v92dcc541a8) & 0777; $paa940150 = fileperms($pa5b0817e) & 0777; if (!$v9509165848 || ($v9509165848 == $pe14342ba && $this->v76822a17cd != $v9509165848)) $pf6e3d638 = 0777; if ($pf6e3d638 != $paa940150 && !chmod($pa5b0817e, $pf6e3d638)) $v5c1c342594 = false; } return $v5c1c342594; } private function f0df21bab0b($v92dcc541a8, $pa5b0817e) { if ($v92dcc541a8 && $pa5b0817e && is_dir($v92dcc541a8) && is_dir($pa5b0817e)) { $v2d2046720b = $this->f98621ce336($v92dcc541a8, $pa5b0817e); return file_put_contents($pa5b0817e . "/perms.json", json_encode($v2d2046720b)); } } private function f98621ce336($v92dcc541a8, $pa5b0817e, $v77cb07b555 = "") { $v2d2046720b = array(); if ($pa5b0817e) { $v1a74c80ef8 = "$pa5b0817e/$v77cb07b555"; $v6b146f3e75 = "$v92dcc541a8/$v77cb07b555"; if (is_dir($v1a74c80ef8)) { $v6ee393d9fb = scandir($v1a74c80ef8); if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if (!in_array($v7dffdb5a5b, self::$pa2a1daed)) { $v7eb8f95833 = "$v1a74c80ef8$v7dffdb5a5b"; $pf1574b73 = "$v6b146f3e75$v7dffdb5a5b"; $pedcc3a33 = is_dir($v7eb8f95833); $pbfa01ed1 = "$v77cb07b555$v7dffdb5a5b" . ($pedcc3a33 ? "/" : ""); $v3f3a368530 = fileperms($v7eb8f95833) & 0777; if (file_exists($pf1574b73)) { $v2ff93070fe = fileowner($pf1574b73); if ($this->v76822a17cd != $v2ff93070fe) $v3f3a368530 = 0777; } $v2d2046720b[$pbfa01ed1] = "0" . decoct($v3f3a368530); if ($pedcc3a33) $v2d2046720b = array_merge($v2d2046720b, $this->f98621ce336($v92dcc541a8, $pa5b0817e, $pbfa01ed1)); } } } return $v2d2046720b; } private function f18cdcce536() { if ($this->v1d2a79cac4) foreach ($this->v1d2a79cac4 as $pa32be502) $this->me202360dafc0($pa32be502); } private function me202360dafc0($pa32be502) { if ($pa32be502 && file_exists($pa32be502)) { if (is_dir($pa32be502)) return CacheHandlerUtil::deleteFolder($pa32be502); else return unlink($pa32be502); } return true; } private function mff5674a370ff($pa32be502) { return CacheHandlerUtil::deleteFolder($pa32be502, false); } public static function validateServerTemplateLicenceData($paf0ee1a7, $pf665220f, &$pddc51a8e) { $v5c1c342594 = true; if ($paf0ee1a7 && !empty($paf0ee1a7["properties"]["actions"])) { $v2639aa6a16 = $paf0ee1a7["properties"]["actions"]; $v651d593e1f = array_keys($v2639aa6a16) !== range(0, count($v2639aa6a16) - 1); if ($v651d593e1f) $v2639aa6a16 = array($v2639aa6a16); $v0432229340 = isset($pf665220f["sed"]) ? $pf665220f["sed"] : null; $v860cb5afeb = isset($pf665220f["pmn"]) ? $pf665220f["pmn"] : null; $pabf565d2 = isset($pf665220f["ped"]) ? $pf665220f["ped"] : null; if ($v860cb5afeb > 0) $v860cb5afeb--; foreach ($v2639aa6a16 as $pd69fb7d0 => $v98e8b259aa) foreach ($v98e8b259aa as $v256e3a39a7 => $v1b5ae9c139) if ($v256e3a39a7 == "copy_layers" && (!isset($v1b5ae9c139["active"]) || $v1b5ae9c139["active"])) { $v1426427798 = isset($v1b5ae9c139["proj" . 'ects_m' . "aximum_nu" . 'mber']) ? $v1b5ae9c139["proj" . 'ects_m' . "aximum_nu" . 'mber'] : null; $v105fddb79a = isset($v1b5ae9c139['proj' . "ects_expi" . 'ration' . '_date']) ? $v1b5ae9c139['proj' . "ects_expi" . 'ration' . '_date'] : null; $v1f5fcad24e = isset($v1b5ae9c139["sy" . 'sad' . "min_ex" . 'pirati' . "on_date"]) ? $v1b5ae9c139["sy" . 'sad' . "min_ex" . 'pirati' . "on_date"] : null; if ($v1426427798 && $v860cb5afeb != -1 && ($v1426427798 == -1 || $v1426427798 > $v860cb5afeb)) { $pddc51a8e[] = "Error: Maximum number of projects cannot be -1 or bigger than $v860cb5afeb."; $v5c1c342594 = false; } if ($v105fddb79a && $pabf565d2 != -1 && ($v105fddb79a == -1 || strtotime($v105fddb79a) > strtotime($pabf565d2))) { $pddc51a8e[] = "Error: Projects expiration date cannot be -1 and bigger than '$pabf565d2'."; $v5c1c342594 = false; } if ($v1f5fcad24e && strtotime($v1f5fcad24e) > strtotime($v0432229340)) { $pddc51a8e[] = "Error: SysAdmin expiration date cannot be bigger than '$v0432229340'."; $v5c1c342594 = false; } } } return $v5c1c342594; } public static function getServerTask($v1d696dbd12, $v8a4ed461b2) { if (!empty($v1d696dbd12["tasks"])) foreach ($v1d696dbd12["tasks"] as $v8282c7dd58 => $v7f5911d32d) { $v89cfc6ba9c = isset($v7f5911d32d["label"]) ? $v7f5911d32d["label"] : null; if ($v89cfc6ba9c == $v8a4ed461b2) return $v7f5911d32d; } return null; } public static function getServerTaskTemplate($v1d696dbd12, $v8a4ed461b2, $v1495c93fca) { $v7f5911d32d = self::getServerTask($v1d696dbd12, $v8a4ed461b2); if ($v7f5911d32d && !empty($v7f5911d32d["properties"]) && !empty($v7f5911d32d["properties"]["templates"])) { $v94d48fb72f = $v7f5911d32d["properties"]["templates"]; if (isset($v94d48fb72f["name"]) || isset($v94d48fb72f["created_date"]) || isset($v94d48fb72f["modified_date"]) || isset($v94d48fb72f["template_id"])) $v94d48fb72f = array($v94d48fb72f); foreach ($v94d48fb72f as $pd69fb7d0 => $pe7333513) { $v147dfb9ee5 = isset($pe7333513["template_id"]) ? $pe7333513["template_id"] : null; if ($v147dfb9ee5 == $v1495c93fca) return $pe7333513; } } return null; } public static function getTasksByLabel($v1d696dbd12) { $pc810e9c4 = array(); if ($v1d696dbd12 && !empty($v1d696dbd12["tasks"])) { foreach ($v1d696dbd12["tasks"] as $v8282c7dd58 => $v7f5911d32d) { $v89cfc6ba9c = isset($v7f5911d32d["label"]) ? $v7f5911d32d["label"] : null; $pc810e9c4[$v89cfc6ba9c] = $v7f5911d32d; } } return $pc810e9c4; } public static function getTasksPropsByLabel($v80c5ba5219) { $v819a8209f0 = array(); if ($v80c5ba5219) foreach ($v80c5ba5219 as $v7f5911d32d) { $v89cfc6ba9c = isset($v7f5911d32d["label"]) ? $v7f5911d32d["label"] : null; $v819a8209f0[$v89cfc6ba9c] = isset($v7f5911d32d["properties"]) ? $v7f5911d32d["properties"] : null; } return $v819a8209f0; } public static function getConnectionsPropsByTaskLabels($v44ff379d4c) { $v1f6f4697a6 = array(); if ($v44ff379d4c) foreach ($v44ff379d4c as $v296f8e88e6) { $v9777079fca = isset($v296f8e88e6["source_label"]) ? $v296f8e88e6["source_label"] : null; $v46a48d85c6 = isset($v296f8e88e6["target_label"]) ? $v296f8e88e6["target_label"] : null; $v1f6f4697a6[$v9777079fca][$v46a48d85c6] = isset($v296f8e88e6["properties"]) ? $v296f8e88e6["properties"] : null; } return $v1f6f4697a6; } private function f7821c1018c(&$pfb662071) { $pa632b390 = !is_array($pfb662071); if (!$pa632b390) foreach ($pfb662071 as $pd69fb7d0 => $v02a69d4e0f) if (!is_numeric($pd69fb7d0)) { $pa632b390 = true; break; } if ($pa632b390) $pfb662071 = array($pfb662071); } private function me9618f011deb($v1ae34537ad) { $v9bf2f1652d = isset($v1ae34537ad['scheme']) ? $v1ae34537ad['scheme'] . '://' : ''; $v244067a7fe = isset($v1ae34537ad['host']) ? $v1ae34537ad['host'] : ''; $v7e782022ec = isset($v1ae34537ad['port']) ? ':' . $v1ae34537ad['port'] : ''; $pd5141703 = isset($v1ae34537ad['user']) ? $v1ae34537ad['user'] : ''; $v52e9ea5509 = isset($v1ae34537ad['pass']) ? ':' . $v1ae34537ad['pass'] : ''; $v52e9ea5509 = ($pd5141703 || $v52e9ea5509) ? "$v52e9ea5509@" : ''; $pa32be502 = isset($v1ae34537ad['path']) ? (substr($v1ae34537ad['path'], 0, 1) != "/" ? "/" : "") . $v1ae34537ad['path'] : ''; $v9d1744e29c = isset($v1ae34537ad['query']) ? '?' . $v1ae34537ad['query'] : ''; $v73d5506af0 = isset($v1ae34537ad['fragment']) ? '#' . $v1ae34537ad['fragment'] : ''; return "$v9bf2f1652d$pd5141703$v52e9ea5509$v244067a7fe$v7e782022ec$pa32be502$v9d1744e29c$v73d5506af0"; } private static function f8edaa78497() { return str_replace("\n", "", var_export(self::$pa2a1daed, true)); } } ?>
