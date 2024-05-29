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
include_once get_lib("org.phpframework.bean.BeanFactory"); include_once $EVC->getUtilPath("PHPVariablesFileHandler"); class WorkFlowBeansFolderHandler { private $pddfc29cd; private $v69bff92632; private $v5039a77f9d; private $v3d55458bcd; private $pc0fc7d17; private $v73fec76b27; private $v39c8cc4726 = array(); public function __construct($v5039a77f9d, $v3d55458bcd, $pc0fc7d17, $v73fec76b27 = array()) { $this->pddfc29cd = new BeanFactory(); $this->v5039a77f9d = $v5039a77f9d; $this->v3d55458bcd = $v3d55458bcd; $this->pc0fc7d17 = $pc0fc7d17; $this->v73fec76b27 = $v73fec76b27; $this->v73fec76b27 = $this->v73fec76b27 ? $this->v73fec76b27 : array(); $this->v73fec76b27["LAYER_CACHE_PATH"] = $this->v73fec76b27["LAYER_CACHE_PATH"] ? $this->v73fec76b27["LAYER_CACHE_PATH"] : LAYER_CACHE_PATH; $this->v73fec76b27["LAYER_PATH"] = $this->v73fec76b27["LAYER_PATH"] ? $this->v73fec76b27["LAYER_PATH"] : LAYER_PATH; $this->v73fec76b27["BEAN_PATH"] = $this->v73fec76b27["BEAN_PATH"] ? $this->v73fec76b27["BEAN_PATH"] : BEAN_PATH; $this->v73fec76b27["SYSTEM_LAYER_PATH"] = $this->v73fec76b27["SYSTEM_LAYER_PATH"] ? $this->v73fec76b27["SYSTEM_LAYER_PATH"] : SYSTEM_LAYER_PATH; } public function getGlobalPaths() { return $this->v73fec76b27; } public function createDefaultFiles() { $v5c1c342594 = true; $pe8ba831e = '<?php 
//The contents of these files cannot be "" (empty string), otherwise it will output an empty line and if we set headers in some other files, the headers will not be set, bc it already echoes an empty line. So we must add the open and close php tags.
//DO NOT OUTPUT ANYTHING IN THIS FILE!
?>'; if (!file_exists($this->pc0fc7d17) && file_put_contents($this->pc0fc7d17, $pe8ba831e) === false) $v5c1c342594 = false; if (!file_exists($this->v3d55458bcd) && file_put_contents($this->v3d55458bcd, $pe8ba831e) === false) $v5c1c342594 = false; return $v5c1c342594; } public function removeOldBeansFiles() { if (is_dir($this->v5039a77f9d) && ($v89d33f4133 = opendir($this->v5039a77f9d)) ) { while( ($v7dffdb5a5b = readdir($v89d33f4133)) !== false) { if (substr($v7dffdb5a5b, strlen($v7dffdb5a5b) - 4) == ".xml" && !is_dir($this->v5039a77f9d . $v7dffdb5a5b) && is_writable($this->v5039a77f9d . $v7dffdb5a5b)) { unlink($this->v5039a77f9d . $v7dffdb5a5b); } } closedir($v89d33f4133); } return true; } public function createDefaultLayer($v0494809a75 = null) { $pbfb7ee27 = $this->v73fec76b27["LAYER_PATH"] . ".htaccess"; $v7959970a41 = false; if ($v0494809a75) foreach ($this->v39c8cc4726 as $pfd248cca => $v41103fabe9) foreach ($v41103fabe9 as $v86d8fc0681) if ($v86d8fc0681 == $v0494809a75) { $v7959970a41 = true; break; } if (!$v7959970a41) { $v08bcc377fb = self::getDefaultLayerFolder($pbfb7ee27); if ($v08bcc377fb) foreach ($this->v39c8cc4726 as $pfd248cca => $v41103fabe9) foreach ($v41103fabe9 as $v86d8fc0681) if ($v86d8fc0681 == $v08bcc377fb) return true; } if (!$v7959970a41) { $pf3c022e0 = array("presentation", "business_logic", "data_access", "db_data"); $v0494809a75 = null; foreach ($pf3c022e0 as $v3fb9f41470) if ($this->v39c8cc4726[$v3fb9f41470]) { foreach ($this->v39c8cc4726[$v3fb9f41470] as $v86d8fc0681) if ($v86d8fc0681) { $v0494809a75 = $v86d8fc0681; break; } if ($v0494809a75) break; } } $v5c1c342594 = true; if ($v0494809a75) { if (file_exists($pbfb7ee27)) { $pae77d38c = file_get_contents($pbfb7ee27); $v0494809a75 = preg_replace("/\/+/", "\/", $v0494809a75); $pae77d38c = preg_replace("/(RewriteRule\s*\\^\\$\s*)([\w\-\+\/]+)(\\/)/u", "$1" . $v0494809a75 . "$3", $pae77d38c); $pae77d38c = preg_replace("/(RewriteRule\s*\\(\\.\\*\\)\s*)([\w\-\+\/]+)(\\/\\$1)/u", "$1" . $v0494809a75 . "$3", $pae77d38c); } else $pae77d38c = '<IfModule mod_rewrite.c>
RewriteEngine on

RewriteRule ^$ ' . $v0494809a75 . '/ [L,NC]

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule (.*) ' . $v0494809a75 . '/$1 [L,NC]
</IfModule>'; if (file_put_contents($pbfb7ee27, $pae77d38c) === false) $v5c1c342594 = false; } return $v5c1c342594; } public function setSetupProjectName($v29e5d6d712) { return file_put_contents($this->v73fec76b27["LAYER_CACHE_PATH"] . "default_project_name", $v29e5d6d712) !== false; } public function getSetupProjectName() { return file_exists($this->v73fec76b27["LAYER_CACHE_PATH"] . "default_project_name") ? file_get_contents($this->v73fec76b27["LAYER_CACHE_PATH"] . "default_project_name") : ""; } public function getSetupDefaultProjectName() { return "default"; } public function prepareBeansFolder($pf3dc0762, $v30857f7eca = false) { $v5c1c342594 = true; $pfaf08f23 = new PHPVariablesFileHandler($this->v3d55458bcd); $pfaf08f23->startUserGlobalVariables(); $pc5a892eb = array(); $this->pddfc29cd->init(array("file" => $pf3dc0762, "external_vars" => $pc5a892eb)); $v1e9dc98c41 = $this->pddfc29cd->getBeans(); $v761f4d757f = $this->pddfc29cd->getObjects(); $this->v69bff92632 = $this->getSetupProjectName(); $v161267c5c2 = false; $v0590d88e86 = false; $v31fed95ca7 = false; $pf79443b1 = false; foreach($v1e9dc98c41 as $v5e813b295b => $pdec569fb) { if (file_exists($pdec569fb->path)) { include_once $pdec569fb->path; if (is_subclass_of($pdec569fb->class_name, "ILayer")) { if (is_array($pdec569fb->constructor_args)) { foreach ($pdec569fb->constructor_args as $v4122422558) { $pea70e132 = $v4122422558->value; if (empty($pea70e132)) { $v6da63250f5 = $v4122422558->reference; $pea70e132 = $v761f4d757f[$v6da63250f5]; } if (!empty($pea70e132)) { $pa32be502 = false; if (isset($pea70e132["presentations_path"])) { $v3fb9f41470 = "presentation"; $pa32be502 = $pea70e132["presentations_path"]; if (empty($pf79443b1)) $pf79443b1 = basename($pa32be502); } else if (isset($pea70e132["business_logic_path"])) { $v3fb9f41470 = "business_logic"; $pa32be502 = $pea70e132["business_logic_path"]; if (empty($v31fed95ca7)) $v31fed95ca7 = basename($pa32be502); } else if (isset($pea70e132["dal_path"])) { $v3fb9f41470 = "data_access"; $pa32be502 = $pea70e132["dal_path"]; if (empty($v0590d88e86)) $v0590d88e86 = basename($pa32be502); } else if (isset($pea70e132["dbl_path"])) { $v3fb9f41470 = "db_data"; $pa32be502 = $pea70e132["dbl_path"]; if (empty($v161267c5c2)) $v161267c5c2 = basename($pa32be502); } if (!empty($pa32be502)) { if ($this->v73fec76b27["LAYER_PATH"] != LAYER_PATH && substr($pa32be502, 0, strlen(LAYER_PATH)) == LAYER_PATH) $pa32be502 = $this->v73fec76b27["LAYER_PATH"] . substr($pa32be502, strlen(LAYER_PATH)); if (is_dir($pa32be502) || mkdir($pa32be502, 0755, true)) { $pa32be502 .= substr($pa32be502, -1) != "/" ? "/" : ""; $this->pddfc29cd->initObjects(); switch($v3fb9f41470) { case "presentation": $this->mdb797218f3b5($pf3dc0762, $pa32be502, $v1e9dc98c41, $v30857f7eca); break; case "business_logic": $this->f2ac9322675($pf3dc0762, $pa32be502, $v1e9dc98c41, $v30857f7eca); break; case "data_access": $v972f1a5c2b = $this->pddfc29cd->getObject($v5e813b295b); if (is_a($v972f1a5c2b, "IbatisDataAccessLayer")) $this->md43d13ccf621($pf3dc0762, $pa32be502, $v1e9dc98c41, $v30857f7eca); else $this->mb3141b2d247d($pf3dc0762, $pa32be502, $v1e9dc98c41, $v30857f7eca); break; case "db_data": $this->f020602a19d($pf3dc0762, $pa32be502, $v1e9dc98c41, $v30857f7eca); break; } } else $v5c1c342594 = false; break; } } } } } } } $pfaf08f23->endUserGlobalVariables(); if ($pf79443b1) $this->v39c8cc4726["presentation"][] = $pf79443b1; if ($v31fed95ca7) $this->v39c8cc4726["business_logic"][] = $v31fed95ca7; if ($v0590d88e86) $this->v39c8cc4726["data_access"][] = $v0590d88e86; if ($v161267c5c2) $this->v39c8cc4726["db_data"][] = $v161267c5c2; return $v5c1c342594; } private function mdb797218f3b5($pce995d72, $v7d0332245c, $v1e9dc98c41, $v30857f7eca = false) { $pa47fac06 = ""; foreach($v1e9dc98c41 as $v5e813b295b => $pdec569fb) { if (is_a($v972f1a5c2b, "PresentationLayer")) { $pa47fac06 = $v972f1a5c2b->getCommonProjectName(); break; } } $pa47fac06 = $pa47fac06 ? $pa47fac06 : "common"; $v69bff92632 = $this->v69bff92632; if (!$v69bff92632) { $pbfb7ee27 = $v7d0332245c . ".htaccess"; $v69bff92632 = self::getPresentationLayerDefaultproject($pbfb7ee27); if (!$v69bff92632 && $v7d0332245c && is_dir($v7d0332245c)) { $v6ee393d9fb = scandir($v7d0332245c); if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if ($v7dffdb5a5b != "." && $v7dffdb5a5b != ".." && $v7dffdb5a5b != $pa47fac06 && is_dir("$v7d0332245c/$v7dffdb5a5b") && is_dir("$v7d0332245c/$v7dffdb5a5b/webroot")) { $v69bff92632 = $v7dffdb5a5b; break; } } } if ($v69bff92632 || !file_exists($v7d0332245c . ".htaccess")) { $pae77d38c = '<IfModule mod_rewrite.c>
   RewriteEngine on
  
   RewriteRule ^$ ' . ($v69bff92632 ? $v69bff92632 : $this->getSetupDefaultProjectName()) . '/webroot/ [L,NC]
   
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteRule (.*) ' . ($v69bff92632 ? $v69bff92632 : $this->getSetupDefaultProjectName()) . '/webroot/$1 [L,NC]
</IfModule>'; file_put_contents($v7d0332245c . ".htaccess", $pae77d38c); } $pae77d38c = '<?xml version="1.0" encoding="UTF-8"?>
<modules>
	<module id="COMMON">' . $pa47fac06 . '</module>
	' . ($this->v69bff92632 ? '<module id="' . strtoupper($this->v69bff92632) . '">' . $this->v69bff92632 . '</module>' : '') . '
</modules>'; if (!file_exists($v7d0332245c . "modules.xml")) file_put_contents($v7d0332245c . "modules.xml", $pae77d38c); $pa0462a8e = substr($pce995d72, strlen($this->v73fec76b27["BEAN_PATH"])); $pbb42d802 = false; $v04b031d856 = false; $paff527d3 = false; $pded57d25 = false; foreach($v1e9dc98c41 as $v5e813b295b => $pdec569fb) { $v972f1a5c2b = $this->pddfc29cd->getObject($v5e813b295b); if (is_a($v972f1a5c2b, "DispatcherCacheHandler")) $pbb42d802 = $v5e813b295b; else if (is_a($v972f1a5c2b, "PresentationLayer")) $v04b031d856 = $v5e813b295b; else if (is_a($v972f1a5c2b, "EVCDispatcher") || is_a($v972f1a5c2b, "PresentationDispatcher")) $paff527d3 = $v5e813b295b; else if (is_a($v972f1a5c2b, "EVC")) $pded57d25 = $v5e813b295b; } $pae77d38c = '<?php
try {
	define(\'GLOBAL_SETTINGS_PROPERTIES_FILE_PATH\', dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/config/global_settings.php");
	define(\'GLOBAL_VARIABLES_PROPERTIES_FILE_PATH\', dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/config/global_variables.php");

	include dirname(dirname(__DIR__)) . "/app.php";
	include_once get_lib("org.phpframework.webservice.layer.PresentationLayerWebService");

	define(\'BEANS_FILE_PATH\', BEAN_PATH . \'' . $pa0462a8e . '\');
	define(\'PRESENTATION_DISPATCHER_CACHE_HANDLER_BEAN_NAME\', \'' . $pbb42d802 . '\');
	define(\'PRESENTATION_LAYER_BEAN_NAME\', \'' . $v04b031d856 . '\');
	define(\'EVC_DISPATCHER_BEAN_NAME\', \'' . $paff527d3 . '\');
	define(\'EVC_BEAN_NAME\', \'' . $pded57d25 . '\');

	echo call_presentation_layer_web_service(array("presentation_id" => $presentation_id, "external_vars" => $external_vars, "includes" => $includes, "includes_once" => $includes_once));
}
catch(Exception $e) {
	$GlobalExceptionLogHandler->log($e);
}
?>'; file_put_contents($v7d0332245c . "init.php", $pae77d38c); if (!file_exists($v7d0332245c . "$pa47fac06/")) { self::copyFolder($this->v73fec76b27["SYSTEM_LAYER_PATH"] . "presentation/$pa47fac06/", $v7d0332245c . "$pa47fac06/"); CacheHandlerUtil::deleteFolder($v7d0332245c . "$pa47fac06/src/module/", false); CacheHandlerUtil::deleteFolder($v7d0332245c . "$pa47fac06/webroot/module/", false); } if ($this->v69bff92632 && !file_exists($v7d0332245c . "/" . $this->v69bff92632 . "/")) self::copyFolder($this->v73fec76b27["SYSTEM_LAYER_PATH"] . "presentation/empty/", $v7d0332245c . "/" . $this->v69bff92632 . "/"); } private function f2ac9322675($pce995d72, $v7d0332245c, $v1e9dc98c41, $v30857f7eca = false) { $pae77d38c = '<IfModule mod_rewrite.c>
    RewriteEngine On
   
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ init.php?url=$1 [QSA,L,NC]
</IfModule>'; file_put_contents($v7d0332245c . ".htaccess", $pae77d38c); $pce434ca7 = ""; foreach($v1e9dc98c41 as $v5e813b295b => $pdec569fb) { $v972f1a5c2b = $this->pddfc29cd->getObject($v5e813b295b); if (is_a($v972f1a5c2b, "BusinessLogicLayer")) $pce434ca7 = $v972f1a5c2b->settings["business_logic_modules_common_name"]; } $pce434ca7 = $pce434ca7 ? $pce434ca7 : "common"; $pae77d38c = '<?xml version="1.0" encoding="UTF-8"?>
<modules>
	<module id="COMMON">' . $pce434ca7 . '</module>
	' . ($this->v69bff92632 ? '<module id="' . strtoupper($this->v69bff92632) . '">' . $this->v69bff92632 . '</module>' : '') . '
</modules>'; if (!file_exists($v7d0332245c . "modules.xml")) file_put_contents($v7d0332245c . "modules.xml", $pae77d38c); $pa0462a8e = substr($pce995d72, strlen($this->v73fec76b27["BEAN_PATH"])); $v31199c28eb = $this->f55c5fd3b20($v1e9dc98c41, $v30857f7eca, "BusinessLogicBrokerServer", $pfd44460b, $v1f4bcb3ebf, $pc5c85d51, $v796be992c6); if (!$v1f4bcb3ebf) $pae77d38c = '<?php
//no remote broker server defined!
?>'; else $pae77d38c = '<?php
try {
	define(\'GLOBAL_SETTINGS_PROPERTIES_FILE_PATH\', dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/config/global_settings.php");
	define(\'GLOBAL_VARIABLES_PROPERTIES_FILE_PATH\', dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/config/global_variables.php");

	include dirname(dirname(__DIR__)) . "/app.php";
	include_once get_lib("org.phpframework.webservice.layer.BusinessLogicLayerWebService");

	define(\'BEANS_FILE_PATH\', BEAN_PATH . \'' . $pa0462a8e . '\');
	
	$broker_server_bean_name = \'' . $v1f4bcb3ebf . '\';
	$broker_server_request_encryption_key = \'' . $pc5c85d51 . '\';
	' . $v796be992c6 . '
	' . $v31199c28eb . '
	define(\'BUSINESS_LOGIC_BROKER_SERVER_BEAN_NAME\', $broker_server_bean_name);
	
	echo call_business_logic_layer_web_service(array("global_variables" => $_POST["gv"], "request_encryption_key" => $broker_server_request_encryption_key));
}
catch(Exception $e) {
	$GlobalExceptionLogHandler->log($e);
}
?>'; file_put_contents($v7d0332245c . "init.php", $pae77d38c); if (!file_exists($v7d0332245c . "$pce434ca7/")) self::copyFolder($this->v73fec76b27["SYSTEM_LAYER_PATH"] . "businesslogic/$pce434ca7/", $v7d0332245c . "$pce434ca7/"); $v7a207d11fe = $v7d0332245c . "$pce434ca7/CommonService.php"; if (file_exists($v7a207d11fe)) { $pae77d38c = file_get_contents($v7a207d11fe); $pb77a7e67 = substr($v7d0332245c, strlen($this->v73fec76b27["LAYER_PATH"])); $pb77a7e67 = preg_replace("/\/+/", "/", $pb77a7e67); $pb77a7e67 = substr($pb77a7e67, -1) == "/" ? substr($pb77a7e67, 0, -1) : $pb77a7e67; $v1efaf06c58 = str_replace("/", "\\", $pb77a7e67); $pae77d38c = preg_replace("/namespace\s+([^;]+);/", "namespace $pb77a7e67;", $pae77d38c, 1); $pae77d38c = preg_replace("/if\s*\(\s*!\s*class_exists\s*\(\s*\"([\w\\\\]+)\"\s*\)\s*\)/", "if (!class_exists(\"\\$pb77a7e67\\CommonService\"))", $pae77d38c, 1); file_put_contents($v7a207d11fe, $pae77d38c); } $pdcf670f6 = substr($pa0462a8e, 0, strlen($pa0462a8e) - 4); $pdc0a3686 = $this->v73fec76b27["BEAN_PATH"] . $pdcf670f6 . "_common_services.xml"; if (file_exists($pdc0a3686) && !file_exists("$pce434ca7/services.xml")) copy($pdc0a3686, $v7d0332245c . "$pce434ca7/services.xml"); if ($this->v69bff92632 && !file_exists($v7d0332245c . $this->v69bff92632)) self::copyFolder($this->v73fec76b27["SYSTEM_LAYER_PATH"] . "businesslogic/empty/", $v7d0332245c . $this->v69bff92632 . "/"); if (!is_dir($v7d0332245c . "module")) @mkdir($v7d0332245c . "module", 0775, true); if (!is_dir($v7d0332245c . "program")) @mkdir($v7d0332245c . "program", 0775, true); if (!is_dir($v7d0332245c . "resource")) @mkdir($v7d0332245c . "resource", 0775, true); } private function md43d13ccf621($pce995d72, $v7d0332245c, $v1e9dc98c41, $v30857f7eca = false) { $this->f77b6a67152($pce995d72, $v7d0332245c, $v1e9dc98c41, "ibatis", $v30857f7eca); } private function mb3141b2d247d($pce995d72, $v7d0332245c, $v1e9dc98c41, $v30857f7eca = false) { $this->f77b6a67152($pce995d72, $v7d0332245c, $v1e9dc98c41, "hibernate", $v30857f7eca); } private function f77b6a67152($pce995d72, $v7d0332245c, $v1e9dc98c41, $v3fb9f41470, $v30857f7eca = false) { $pae77d38c = '<IfModule mod_rewrite.c>
    RewriteEngine On
   
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ init.php?url=$1 [QSA,L,NC]
</IfModule>'; file_put_contents($v7d0332245c . ".htaccess", $pae77d38c); $pae77d38c = '<?xml version="1.0" encoding="UTF-8"?>
<modules>
	' . ($this->v69bff92632 ? '<module id="' . strtoupper($this->v69bff92632) . '">' . $this->v69bff92632 . '</module>' : '') . '
</modules>'; if (!file_exists($v7d0332245c . "modules.xml")) file_put_contents($v7d0332245c . "modules.xml", $pae77d38c); $pa0462a8e = substr($pce995d72, strlen($this->v73fec76b27["BEAN_PATH"])); if ($v3fb9f41470 == "ibatis") { $v31199c28eb = $this->f55c5fd3b20($v1e9dc98c41, $v30857f7eca, "IbatisDataAccessBrokerServer", $pfd44460b, $v1f4bcb3ebf, $pc5c85d51, $v796be992c6); if (!$v1f4bcb3ebf) $pae77d38c = '<?php
//no remote broker server defined!
?>'; else $pae77d38c = '<?php
try {
	define(\'GLOBAL_SETTINGS_PROPERTIES_FILE_PATH\', dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/config/global_settings.php");
	define(\'GLOBAL_VARIABLES_PROPERTIES_FILE_PATH\', dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/config/global_variables.php");

	include dirname(dirname(__DIR__)) . "/app.php";
	include_once get_lib("org.phpframework.webservice.layer.IbatisDataAccessLayerWebService");

	define(\'BEANS_FILE_PATH\', BEAN_PATH . \'' . $pa0462a8e . '\');
	
	$broker_server_bean_name = \'' . $v1f4bcb3ebf . '\';
	$broker_server_request_encryption_key = \'' . $pc5c85d51 . '\';
	' . $v796be992c6 . '
	' . $v31199c28eb . '
	define(\'IBATIS_DATA_ACCESS_BROKER_SERVER_BEAN_NAME\', $broker_server_bean_name);

	echo call_ibatis_data_access_layer_web_service(array("global_variables" => $_POST["gv"], "request_encryption_key" => $broker_server_request_encryption_key));
}
catch(Exception $e) {
	$GlobalExceptionLogHandler->log($e);
}
?>'; } else { $v31199c28eb = $this->f55c5fd3b20($v1e9dc98c41, $v30857f7eca, "HibernateDataAccessBrokerServer", $pfd44460b, $v1f4bcb3ebf, $pc5c85d51, $v796be992c6); if (!$v1f4bcb3ebf) $pae77d38c = '<?php
//no remote broker server defined!
?>'; else $pae77d38c = '<?php
try {
	define(\'GLOBAL_SETTINGS_PROPERTIES_FILE_PATH\', dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/config/global_settings.php");
	define(\'GLOBAL_VARIABLES_PROPERTIES_FILE_PATH\', dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/config/global_variables.php");

	include dirname(dirname(__DIR__)) . "/app.php";
	include_once get_lib("org.phpframework.webservice.layer.HibernateDataAccessLayerWebService");

	define(\'BEANS_FILE_PATH\', BEAN_PATH . \'' . $pa0462a8e . '\');
	
	$broker_server_bean_name = \'' . $v1f4bcb3ebf . '\';
	$broker_server_request_encryption_key = \'' . $pc5c85d51 . '\';
	' . $v796be992c6 . '
	' . $v31199c28eb . '
	define(\'HIBERNATE_DATA_ACCESS_BROKER_SERVER_BEAN_NAME\', $broker_server_bean_name);

	echo call_hibernate_data_access_layer_web_service(array("global_variables" => $_POST["gv"], "request_encryption_key" => $broker_server_request_encryption_key));
}
catch(Exception $e) {
	$GlobalExceptionLogHandler->log($e);
}
?>'; } file_put_contents($v7d0332245c . "init.php", $pae77d38c); $pae77d38c = '<?xml version="1.0" encoding="UTF-8"?>
<beans>
	<!-- START FILE SYSTEM HANDLER --> 
	<bean name="ServiceCacheHandler" path="org.phpframework.cache.service.filesystem.FileSystemServiceCacheHandler" path_prefix="<?php echo LIB_PATH;?>">
		<constructor_arg><?php echo $vars["dal_module_cache_maximum_size"]; ?></constructor_arg>
		
		<property name="rootPath"><?php echo $vars["dal_cache_path"] . $vars["current_dal_module_id"]; ?></property>
		<property name="defaultTTL"><?php echo $vars["dal_default_cache_ttl"]; ?></property>
	</bean>
	<!-- END FILE SYSTEM HANDLER --> 
</beans>'; if (!file_exists($v7d0332245c . "cache_handler.xml")) file_put_contents($v7d0332245c . "cache_handler.xml", $pae77d38c); if ($this->v69bff92632 && !is_dir($v7d0332245c . $this->v69bff92632)) @mkdir($v7d0332245c . $this->v69bff92632, 0775, true); if (!is_dir($v7d0332245c . "common")) @mkdir($v7d0332245c . "common", 0775, true); if (!is_dir($v7d0332245c . "module")) @mkdir($v7d0332245c . "module", 0775, true); if (!is_dir($v7d0332245c . "program")) @mkdir($v7d0332245c . "program", 0775, true); if (!is_dir($v7d0332245c . "resource")) @mkdir($v7d0332245c . "resource", 0775, true); $pae77d38c = '<?xml version="1.0" encoding="UTF-8"?>
<beans>
	<services>
		
	</services>
</beans>'; if ($this->v69bff92632 && !file_exists($v7d0332245c . $this->v69bff92632 . "/services.xml")) file_put_contents($v7d0332245c . $this->v69bff92632 . "/services.xml", $pae77d38c); } private function f020602a19d($pce995d72, $v7d0332245c, $v1e9dc98c41, $v30857f7eca = false) { $pae77d38c = '<IfModule mod_rewrite.c>
    RewriteEngine On
   
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ init.php?url=$1 [QSA,L,NC]
</IfModule>'; file_put_contents($v7d0332245c . ".htaccess", $pae77d38c); $pa0462a8e = substr($pce995d72, strlen($this->v73fec76b27["BEAN_PATH"])); $v31199c28eb = $this->f55c5fd3b20($v1e9dc98c41, $v30857f7eca, "DBBrokerServer", $pfd44460b, $v1f4bcb3ebf, $pc5c85d51, $v796be992c6); if (!$v1f4bcb3ebf) $pae77d38c = '<?php
//no remote broker server defined!
?>'; else $pae77d38c = '<?php
try {
	define(\'GLOBAL_SETTINGS_PROPERTIES_FILE_PATH\', dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/config/global_settings.php");
	define(\'GLOBAL_VARIABLES_PROPERTIES_FILE_PATH\', dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/config/global_variables.php");

	include dirname(dirname(__DIR__)) . "/app.php";
	include_once get_lib("org.phpframework.webservice.layer.DBLayerWebService");
	
	define(\'BEANS_FILE_PATH\', BEAN_PATH . \'' . $pa0462a8e . '\');
	
	$broker_server_bean_name = \'' . $v1f4bcb3ebf . '\';
	$broker_server_request_encryption_key = \'' . $pc5c85d51 . '\';
	' . $v796be992c6 . '
	' . $v31199c28eb . '
	define(\'DB_BROKER_SERVER_BEAN_NAME\', $broker_server_bean_name);

	echo call_db_layer_web_service(array("global_variables" => $_POST["gv"], "request_encryption_key" => $broker_server_request_encryption_key));
}
catch(Exception $e) {
	$GlobalExceptionLogHandler->log($e);
}
?>'; file_put_contents($v7d0332245c . "init.php", $pae77d38c); $pae77d38c = '<?xml version="1.0" encoding="UTF-8"?>
<beans>
	<!-- START FILE SYSTEM HANDLER --> 
	<bean name="ServiceCacheHandler" path="org.phpframework.cache.service.filesystem.FileSystemServiceCacheHandler" path_prefix="<?php echo LIB_PATH;?>">
		<constructor_arg><?php echo $vars["dbl_module_cache_maximum_size"]; ?></constructor_arg>
		
		<property name="rootPath"><?php echo $vars["dbl_cache_path"]; ?></property>
		<property name="defaultTTL"><?php echo $vars["dbl_default_cache_ttl"]; ?></property>
	</bean>
	<!-- END FILE SYSTEM HANDLER --> 
</beans>'; if (!file_exists($v7d0332245c . "cache_handler.xml")) file_put_contents($v7d0332245c . "cache_handler.xml", $pae77d38c); } private function f55c5fd3b20($v1e9dc98c41, $v30857f7eca, $pa3e0d5e0, &$pfd44460b, &$v1f4bcb3ebf, &$pc5c85d51, &$v796be992c6) { $pc86aa511 = array(); if (!empty($v30857f7eca["layer_brokers"])) foreach($v1e9dc98c41 as $v5e813b295b => $pdec569fb) { $v972f1a5c2b = $this->pddfc29cd->getObject($v5e813b295b); if (is_a($v972f1a5c2b, "BrokerServer") && !is_a($v972f1a5c2b, "LocalDBBrokerServer")) foreach ($v30857f7eca["layer_brokers"] as $v676be2c810 => $v56db643248) if (is_a($v972f1a5c2b, strtoupper($v676be2c810) . $pa3e0d5e0)) $pc86aa511[$v676be2c810] = $v5e813b295b; } if ($pc86aa511) { $pfd44460b = key($pc86aa511); $v1f4bcb3ebf = $pc86aa511[$pfd44460b]; $pc5c85d51 = $v30857f7eca["layer_brokers"][$pfd44460b]["request_encryption_key"]; $v796be992c6 = $this->f913fad247a($v30857f7eca["layer_brokers"][$pfd44460b]["global_variables"]); $v31199c28eb = ''; if (count($pc86aa511) > 1) { $v31199c28eb .= '
	$headers = getallheaders();

	switch ($headers["layer_broker_server_type"]) {'; foreach ($pc86aa511 as $v676be2c810 => $pb9794db4) if ($v676be2c810 != $pfd44460b) { $v796be992c6 .= $this->f913fad247a($v30857f7eca["layer_brokers"][$v676be2c810]["global_variables"]); $v31199c28eb .= '
		case "' . $v676be2c810 . '":
			$broker_server_bean_name = \'' . $pb9794db4 . '\';
			$broker_server_request_encryption_key = \'' . $v30857f7eca["layer_brokers"][$v676be2c810]["request_encryption_key"] . '\';
			' . str_replace("\n", "\n\t\t", $v796be992c6) . '
			break;'; } $v31199c28eb .= '
	}
	
	unset($headers);
	'; } } return $v31199c28eb; } private function f913fad247a($pd7a36e35) { $v067674f4e4 = ""; if (is_array($pd7a36e35) && !empty($pd7a36e35["vars_name"])) { $v45de354860 = $pd7a36e35["vars_name"]; $v61fc24eb6d = $pd7a36e35["vars_value"]; if (!is_array($v45de354860)) { $v45de354860 = array($v45de354860); $v61fc24eb6d = array($v61fc24eb6d); } foreach ($v45de354860 as $pd69fb7d0 => $v5e813b295b) if ($v5e813b295b) { $v67db1bd535 = $v61fc24eb6d[$pd69fb7d0]; $v067674f4e4 .= ($v067674f4e4 ? "\n\t" : "") . "\$$v5e813b295b = '" . $v67db1bd535 . "';"; } } return $v067674f4e4; } public static function getDefaultLayerFolder($pbfb7ee27) { $v1c28a1be9d = null; if (file_exists($pbfb7ee27)) { $v6490ea3a15 = file_get_contents($pbfb7ee27); preg_match("/RewriteRule\s*\\^\\$\s*([\w\-\+]+)\\//iu", $v6490ea3a15, $pb7f9502b); preg_match("/RewriteRule\s*\\(\\.\\*\\)\s*([\w\-\+]+)\\/\\$1/iu", $v6490ea3a15, $v01929c58f3); if ($pb7f9502b) $v1c28a1be9d = $pb7f9502b[1]; else if ($v01929c58f3) $v1c28a1be9d = $v01929c58f3[1]; } return $v1c28a1be9d; } public static function getPresentationLayerDefaultproject($pbfb7ee27) { $v1bb6c0775d = null; if (file_exists($pbfb7ee27)) { $v6490ea3a15 = file_get_contents($pbfb7ee27); preg_match("/RewriteRule\s*\\^\\$\s*([\w\-\+\/]+)\\/webroot\\//iu", $v6490ea3a15, $pb7f9502b); preg_match("/RewriteRule\s*\\(\\.\\*\\)\s*([\w\-\+\/]+)\\/webroot\\/\\$1/iu", $v6490ea3a15, $v01929c58f3); if ($pb7f9502b) $v1bb6c0775d = $pb7f9502b[1]; else if ($v01929c58f3) $v1bb6c0775d = $v01929c58f3[1]; $v1bb6c0775d = preg_replace("/\/+/", "/", $v1bb6c0775d); } return $v1bb6c0775d; } public static function copyFolder($v92dcc541a8, $pa5b0817e) { $v5c1c342594 = (file_exists($pa5b0817e) && !is_dir($pa5b0817e)) || (!file_exists($pa5b0817e) && !mkdir($pa5b0817e, 0775, true)) ? false : true; if($v5c1c342594) { if (is_dir($v92dcc541a8)) { $v6ee393d9fb = scandir($v92dcc541a8); if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if ($v7dffdb5a5b != "." && $v7dffdb5a5b != "..") { if(is_dir($v92dcc541a8 . $v7dffdb5a5b)) { if (!self::copyFolder($v92dcc541a8 . $v7dffdb5a5b . "/", $pa5b0817e . $v7dffdb5a5b . "/")) $v5c1c342594 = false; } else if (!copy($v92dcc541a8 . $v7dffdb5a5b, $pa5b0817e . $v7dffdb5a5b)) $v5c1c342594 = false; } } else $v5c1c342594 = false; } return $v5c1c342594; } } ?>
