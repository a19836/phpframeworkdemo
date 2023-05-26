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

include $EVC->getUtilPath("CMSPresentationUIAutomaticFilesHandler"); include $EVC->getUtilPath("CMSPresentationFormSettingsUIHandler"); class CMSPresentationUIDiagramFilesHandler { private static $v6a5af55cf8 = null; private static $v9ddaebbf03 = null; private static $v4a8e028ce8 = null; private static $v4a8c422aa6 = null; private static $pec316626 = null; private static $pa82053f1 = null; private static $v35727ff744 = null; private static $v058a242493 = null; private static $v79b4f08546 = null; private static $v86098bc5f1 = null; private static $v3b4cb56c97 = null; private static $v7444124b34 = null; private static $v6b8ab5a551 = null; private static $v552676fb3d = null; private static $v2b9248d393 = null; private static $v298bc3731e = null; private static $v5f60005f1d = null; private static $v50e29b88ec = null; private static $v1d5dafbccf = null; private static $pff4c8506 = null; private static $pf9a2cdcb = null; public static function getAuthPageAndBlockIds() { return array( "auth_validation_block_id" => self::$v6a5af55cf8, "access_id" => self::$v9ddaebbf03, "object_type_page_id" => self::$v4a8e028ce8, "logout_block_id" => self::$v4a8c422aa6, "logout_page_id" => self::$pec316626, "login_block_id" => self::$pa82053f1, "login_page_id" => self::$v35727ff744, "register_block_id" => self::$v058a242493, "register_page_id" => self::$v79b4f08546, "forgot_credentials_block_id" => self::$v86098bc5f1, "forgot_credentials_page_id" => self::$v3b4cb56c97, "edit_profile_block_id" => self::$v7444124b34, "edit_profile_page_id" => self::$v6b8ab5a551, "list_and_edit_users_block_id" => self::$v552676fb3d, "list_and_edit_users_page_id" => self::$v2b9248d393, "list_users_block_id" => self::$v298bc3731e, "list_users_page_id" => self::$v5f60005f1d, "edit_user_block_id" => self::$v50e29b88ec, "edit_user_page_id" => self::$v1d5dafbccf, "add_user_block_id" => self::$pff4c8506, "add_user_page_id" => self::$pf9a2cdcb, ); } public static function addUserAccessControlToVarsFile($v188b4f5fa6, $pd763cc84, $pfe8f3da7, $v761f4d757f) { $pf3dc0762 = $v188b4f5fa6->getConfigPath($pd763cc84); $v17be587282 = dirname($pf3dc0762); $v444c0a588f = ""; if (is_array($pfe8f3da7)) { $v327f72fb62 = ""; foreach ($pfe8f3da7 as $pcb71a4d9) { $pcb71a4d9 = trim($pcb71a4d9); if ($pcb71a4d9 && is_numeric($pcb71a4d9)) $v327f72fb62 .= ($v327f72fb62 ? "," : "") . $pcb71a4d9; } $v444c0a588f = $v327f72fb62; } else $v444c0a588f = trim($pfe8f3da7); $pfe5fee27 = '<?php
$allowed_list_and_edit_users_types = array(' . $v444c0a588f . ');'; if ($v761f4d757f) $pfe5fee27 .= '

if (!$GLOBALS["UserSessionActivitiesHandler"]) {
    include_once $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());
	initUserSessionActivitiesHandler($EVC);
}

if ($GLOBALS["UserSessionActivitiesHandler"] && $allowed_list_and_edit_users_types) {
	$logged_user_data = $GLOBALS["UserSessionActivitiesHandler"]->getUserData();
	$logged_user_type_ids = $logged_user_data ? $logged_user_data["user_type_ids"] : null;
	$allowed = false;
	
	if (is_array($logged_user_type_ids))
		foreach ($logged_user_type_ids as $ut_id)
			if (in_array($ut_id, $allowed_list_and_edit_users_types)) {
				$allowed = true;
				break;
			}
	
	if (!$allowed) 
		$' . implode(' = $', $v761f4d757f) . ' = null;
}'; $pfe5fee27 .= '
?>'; if (file_exists($pf3dc0762)) { $v6490ea3a15 = trim(file_get_contents($pf3dc0762)); $v6490ea3a15 = preg_replace('/\$allowed_list_and_edit_users_types(\s*)=(\s*)([^;]*)(\s*);/i', '', $v6490ea3a15); $v6490ea3a15 = preg_replace('/if\s*\(\s*!\s*\$GLOBALS\s*\[\s*("|\')UserSessionActivitiesHandler("|\')\s*\]\s*\)\s*\{\s*include_once\s*\$EVC\s*\->\s*getUtilPath\s*\(\s*("|\')user_session_activities_handler("|\')\s*,\s*\$EVC\s*\->\s*getCommonProjectName\s*\(\s*\)\s*\)\s*;\s*initUserSessionActivitiesHandler\s*\(\s*\$EVC\s*\)\s*;\s*\}/i', '', $v6490ea3a15); $v6490ea3a15 = preg_replace('/if\s*\(\s*\$GLOBALS\[\s*("|\')UserSessionActivitiesHandler("|\')\s*\]\s*&&\s*\$allowed_list_and_edit_users_types\s*\)\s*\n*\s*\{\s*\n*\s*\$logged_user_data\s*=\s*\$GLOBALS\[\s*("|\')UserSessionActivitiesHandler("|\')\s*\]\-\>getUserData\(\)\s*;\s*\n*\s*\$logged_user_type_ids\s*=\s*\$logged_user_data\s*\?\s*\$logged_user_data\s*\[\s*("|\')user_type_ids("|\')\s*\]\s*\:\s*null\s*;\s*\n*\s*\$allowed\s*=\s*false\s*;\s*\n*if\s*\(\s*is_array\s*\(\s*\$logged_user_type_ids\s*\)\s*\)\s*\n*\s*foreach\s*\(\s*\$logged_user_type_ids\s*as\s*\$ut_id\s*\)\s*\n*\s*if\s*\(\s*in_array\s*\(\s*\$ut_id\s*,\s*\$allowed_list_and_edit_users_types\s*\)\s*\)\s*\{\s*\n*\$allowed\s*=\s*true\s*;\s*\n*\s*break\s*;\s*\n*\s*\}\s*\n*\s*if\s*\(\s*\!\s*\$allowed\s*\)\s*\n*\s*\}\s*\n*/i', '', $v6490ea3a15); $v6490ea3a15 = preg_replace('/\$[\w\$=\s]+\s*=\s*null\s*;\s*/iu', '', $v6490ea3a15); $v6490ea3a15 .= $pfe5fee27; $v6490ea3a15 = str_replace(array("", "", "\r"), "", $v6490ea3a15); $v6490ea3a15 = preg_replace("/\?>\s*<\?php/", "", $v6490ea3a15); $v6490ea3a15 = preg_replace("/\?>\s*<\?/", "", $v6490ea3a15); $v6490ea3a15 = preg_replace("/\n+/", "\n", preg_replace("/\s*\n\s*/", "\n", $v6490ea3a15)); } else { $v6490ea3a15 = $pfe5fee27; if (!is_dir($v17be587282)) mkdir($v17be587282, 0755, true); } return is_dir($v17be587282) && file_put_contents($pf3dc0762, $v6490ea3a15) !== false; } public static function addVarsFile($v188b4f5fa6, $pd763cc84, $v761f4d757f) { $v067674f4e4 = ''; if ($v761f4d757f) foreach ($v761f4d757f as $v1cfba8c105 => $pa6209df1) $v067674f4e4 .= '$' . $v1cfba8c105 . ' = "' . addcslashes($pa6209df1, '"') . '";' . "\n"; $v067674f4e4 = '<?php
' . trim($v067674f4e4) . '
?>'; $pf3dc0762 = $v188b4f5fa6->getConfigPath($pd763cc84); $v17be587282 = dirname($pf3dc0762); if (file_exists($pf3dc0762)) { $v6490ea3a15 = trim(file_get_contents($pf3dc0762)); if ($v761f4d757f) foreach ($v761f4d757f as $v1cfba8c105 => $pa6209df1) { do { preg_match('/(\$' . $v1cfba8c105 . ')(\s*)=(\s*)/iu', $v6490ea3a15, $pbae7526c, PREG_OFFSET_CAPTURE); if ($pbae7526c[0]) { $v6107abf109 = $pbae7526c[0]; $v5c7334c53d = $v6107abf109[0]; $pac65f06f = $v6107abf109[1]; $v79b7cb19d1 = $v66327139f0 = false; $pe2ae3be9 = strlen($v6490ea3a15); $v5c74807c6a = $pac65f06f + strlen($v5c7334c53d); $pd2282eb2 = $pe2ae3be9; for ($v9d27441e80 = $v5c74807c6a; $v9d27441e80 < $pe2ae3be9; $v9d27441e80++) { $pc288256e = $v6490ea3a15[$v9d27441e80]; if ($pc288256e == '"' && !$v66327139f0 && !TextSanitizer::isCharEscaped($v6490ea3a15, $v9d27441e80)) $v79b7cb19d1 = !$v79b7cb19d1; else if ($pc288256e == "'" && !$v79b7cb19d1 && !TextSanitizer::isCharEscaped($v6490ea3a15, $v9d27441e80)) $v66327139f0 = !$v66327139f0; else if ($pc288256e == ";" && !$v66327139f0 && !$v79b7cb19d1) { $pd2282eb2 = $v9d27441e80 + 1; if (substr($v6490ea3a15, $pd2282eb2, 1) == "\n") $pd2282eb2++; break; } } $v391cc249fc = substr($v6490ea3a15, $pac65f06f, $pd2282eb2 - $pac65f06f); $v6490ea3a15 = str_replace($v391cc249fc, "", $v6490ea3a15); } } while ($pbae7526c && $pbae7526c[0]); } $v6490ea3a15 .= $v067674f4e4; $v6490ea3a15 = str_replace(array("", "", "\r"), "", $v6490ea3a15); $v6490ea3a15 = preg_replace("/\?>\s*<\?php/", "", $v6490ea3a15); $v6490ea3a15 = preg_replace("/\?>\s*<\?/", "", $v6490ea3a15); $v6490ea3a15 = preg_replace("/\n+/", "\n", preg_replace("/\s*\n\s*/", "\n", $v6490ea3a15)); } else { $v6490ea3a15 = $v067674f4e4; if (!is_dir($v17be587282)) mkdir($v17be587282, 0755, true); } return is_dir($v17be587282) && file_put_contents($pf3dc0762, $v6490ea3a15) !== false; } public static function createPageFile($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $pe7333513, &$v2e8aa9d64e, &$v1b7f0d0c99, &$v0fd98ea3ab, $v1d696dbd12, $v7f5911d32d, $pccb248b8 = false, $v28bb4bea47 = false, &$pef34936b = false, &$pb39a0a9c = false) { $v250a1176c9 = self::mee7f8e059cfa($v7f5911d32d); $pfbb6ee46 = $v7f5911d32d["tag"]; $v876c18d646 = $v7f5911d32d["properties"]["files_to_create"]; $v53a57f1353 = $v7f5911d32d["properties"]["page_settings"]; $v7943b46e77 = null; $pf6b7bac7 = trim($pf6b7bac7); if ($pf6b7bac7 && substr($pf6b7bac7, -1) != "/") $pf6b7bac7 .= "/"; $v7943b46e77 = $v7f5911d32d["properties"]["authentication_type"] ? array( "authentication_type" => $v7f5911d32d["properties"]["authentication_type"], "authentication_users" => $v7f5911d32d["properties"]["authentication_users"], ) : null; if ($pfbb6ee46 == "page") { $v30d38c7973 = self::md1303a72d528($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $pe7333513, $v2e8aa9d64e, $v1b7f0d0c99, $v0fd98ea3ab, $v1d696dbd12, $v7f5911d32d, $pef34936b, $pb39a0a9c); } else $v30d38c7973 = self::mcfa7aa8fab54($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $pe7333513, $v2e8aa9d64e, $v1b7f0d0c99, $v0fd98ea3ab, $v1d696dbd12, $v7f5911d32d, $pccb248b8, $v28bb4bea47 . $v250a1176c9 . "/", false, $v7943b46e77, $pef34936b, $pb39a0a9c); if (!$v30d38c7973) return false; if ($pccb248b8) { $v0fd98ea3ab .= ($v0fd98ea3ab && $v30d38c7973["js"] ? "\n" : "") . $v30d38c7973["js"]; $v30d38c7973["js"] = ""; } else { $v40b70e70c3 = self::f3eb8f3f6a9($v1b7f0d0c99); if ($v40b70e70c3) $v30d38c7973["js"] = $v40b70e70c3 . "\n" . $v0fd98ea3ab . "\n" . $v30d38c7973["js"]; } $v5c1c342594 = self::f67d92f51be($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v98a8251725 . $v28bb4bea47 . $v250a1176c9, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $pe7333513, $v2e8aa9d64e, $v30d38c7973, $v7f5911d32d["id"], $v876c18d646, "local", $v7943b46e77, $pef34936b, $v53a57f1353, $pb39a0a9c); return $v5c1c342594; } private static function md1303a72d528($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $pe7333513, &$v2e8aa9d64e, &$v1b7f0d0c99, &$v0fd98ea3ab, $v1d696dbd12, $v7f5911d32d, &$pef34936b, &$pb39a0a9c) { $v250a1176c9 = self::mee7f8e059cfa($v7f5911d32d); $pef349725 = $v7f5911d32d["properties"]; $v9df16de072 = $pef349725["join_type"]; $v5ed3bcae90 = $pef349725["links"]; $pa752cc18 = $pef349725["pre_form_settings"]; $v09aa1ddf8f = $pef349725["pos_form_settings"]; $v678baef824 = $v7f5911d32d["exits"]["default_exit"]; $v678baef824 = $v678baef824[0] ? $v678baef824 : array($v678baef824); $v1f377b389c = $v7f5911d32d["tasks"]; $v7943b46e77 = $v7f5911d32d["properties"]["authentication_type"] ? array( "authentication_type" => $v7f5911d32d["properties"]["authentication_type"], "authentication_users" => $v7f5911d32d["properties"]["authentication_users"], ) : null; $v5ed3bcae90 = self::f3429787ad7($v5ed3bcae90); $v370576c033 = $v9df16de072 == "tabs"; $v30d38c7973 = array( "actions" => array(), "css" => "", "js" => "", ); if ($pa752cc18) { if ($pa752cc18["actions"]) { $pa752cc18["actions"] = isset($pa752cc18["actions"]["action_type"]) || isset($pa752cc18["actions"]["action_value"]) ? $pa752cc18["actions"] : array($pa752cc18["actions"]); $v30d38c7973["actions"] = $pa752cc18["actions"]; } if ($pa752cc18["css"]) $v30d38c7973["css"] = $pa752cc18["css"]; if ($pa752cc18["js"]) $v30d38c7973["js"] = $pa752cc18["js"]; } if ($v1f377b389c) { $v516837ee42 = $v370576c033 ? "tabs_tasks_html" : false; foreach ($v1f377b389c as $pd12cba7e => $pa53fc09c) { $v068fa04e3e = self::mcfa7aa8fab54($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $pe7333513, $v2e8aa9d64e, $v1b7f0d0c99, $v0fd98ea3ab, $v1d696dbd12, $pa53fc09c, $v7f5911d32d, $v250a1176c9 . "/", $v516837ee42, $v7943b46e77, $pef34936b, $pb39a0a9c); if (!$v068fa04e3e) return false; $v30d38c7973["actions"] = array_merge($v30d38c7973["actions"], $v068fa04e3e["actions"]); $v30d38c7973["css"] .= $v068fa04e3e["css"] ? "\n\n" . $v068fa04e3e["css"] : ""; $v30d38c7973["js"] .= $v068fa04e3e["js"] ? "\n\n" . $v068fa04e3e["js"] : ""; } if ($v370576c033) { $pf8ed4912 = '<div class="tabs">
	<ul>'; $v151122e7dd = ''; $v43dd7d0051 = 0; foreach ($v1f377b389c as $pd12cba7e => $pa53fc09c) { $v9acc88059e = ucwords(strtolower(str_replace("_", " ", $pa53fc09c["label"]))); $pf8ed4912 .= '
		<li><a href="#tab_content_' . $pd12cba7e . '">' . $v9acc88059e . '</a></li>'; $v151122e7dd .= '
	<div id="tab_content_' . $pd12cba7e . '" class="tab_content">
		<ptl:echo \$' . $v516837ee42 . '[' . $v43dd7d0051 . ']/>
	</div>'; $v43dd7d0051++; } $pf8ed4912 .= '
	</ul>
	' . $v151122e7dd . '
</div>'; $v30d38c7973["actions"][] = array( "result_var_name" => "", "action_type" => "html", "condition_type" => "execute_always", "condition_value" => "", "action_value" => array( "ptl" => array( "code" => $pf8ed4912 ) ) ); } } $v81edb34617 = ''; if ($v5ed3bcae90) foreach ($v5ed3bcae90 as $pdeee6a59) { $v81edb34617 .= '
		<li class="link link-free ' . $pdeee6a59["class"] . '">
			' . $pdeee6a59["previous_html"] . '
			<a href="' . $pdeee6a59["url"] . '" title="' . $pdeee6a59["title"] . '"' . ($pdeee6a59["target"] ? 'target="' . $pdeee6a59["target"] . '"' : '') . '>' . $pdeee6a59["value"] . '</a>
			' . $pdeee6a59["next_html"] . '
		</li>'; } if ($v678baef824) for ($v43dd7d0051 = 0; $v43dd7d0051 < count($v678baef824); $v43dd7d0051++) { $v5b242fbd41 = $v678baef824[$v43dd7d0051]; $v965b5b3a0d = $v5b242fbd41["label"]; $v002556527f = $v5b242fbd41["properties"]["connection_type"]; $pbae0a0a1 = $v5b242fbd41["properties"]["connection_title"]; $pdcf4a554 = $v5b242fbd41["properties"]["connection_class"]; $v3ee652c2b9 = $v5b242fbd41["properties"]["connection_target"]; $pf6db3176 = $v5b242fbd41["task_id"]; $v7b98c1186c = self::f1c3b9b59c1($v1d696dbd12, $pf6db3176); if ($v7b98c1186c && $v7b98c1186c["tag"] != "page") $v7b98c1186c = self::f2fa4395443($v1d696dbd12, $v7b98c1186c); if ($v7b98c1186c) { $pb0067554 = '{$project_url_prefix}' . $v98a8251725 . self::mee7f8e059cfa($v7b98c1186c); $pe0a01068 = ucwords(strtolower(str_replace("_", " ", $v7b98c1186c["label"]))); $v965b5b3a0d = $v965b5b3a0d ? $v965b5b3a0d : $pe0a01068; $pbae0a0a1 = $pbae0a0a1 ? $pbae0a0a1 : $v965b5b3a0d; if ($v002556527f == "popup") { $v1b7f0d0c99["iframe_popup"] = true; $v81edb34617 .= '
		<li class="link link-page-connection ' . $pdcf4a554 . '">
			<a href="javascript:void(0)" onClick="return openIframePopup(this, \'' . $pb0067554 . '\')" title="' . $pbae0a0a1 . '">' . $v965b5b3a0d . '</a>
		</li>'; } else if ($v002556527f == "parent") { $v1b7f0d0c99["parent"] = true; $v81edb34617 .= '
		<li class="link link-page-connection ' . $pdcf4a554 . '">
			<a href="javascript:void(0)" onClick="return openParentLocation(this, \'' . $pb0067554 . '\')" title="' . $pbae0a0a1 . '">' . $v965b5b3a0d . '</a>
		</li>'; } else $v81edb34617 .= '
		<li class="link link-page-connection ' . $pdcf4a554 . '">
			<a href="' . $pb0067554 . '" title="' . $pbae0a0a1 . '"' . ($v3ee652c2b9 ? 'target="' . $v3ee652c2b9 . '"' : '') . '>' . $v965b5b3a0d . '</a>
		</li>'; } } if ($v81edb34617) $v30d38c7973["actions"][] = array( "result_var_name" => "", "action_type" => "html", "condition_type" => "execute_always", "condition_value" => "", "action_value" => array( "ptl" => array( "code" => '<ul class="links">' . $v81edb34617 . "\n</ul>" ) ) ); if ($v09aa1ddf8f) { if ($v09aa1ddf8f["actions"]) { $v09aa1ddf8f["actions"] = isset($v09aa1ddf8f["actions"]["action_type"]) || isset($v09aa1ddf8f["actions"]["action_value"]) ? $v09aa1ddf8f["actions"] : array($v09aa1ddf8f["actions"]); $v30d38c7973["actions"] = array_merge($v30d38c7973["actions"], $v09aa1ddf8f["actions"]); } if ($v09aa1ddf8f["css"]) $v30d38c7973["css"] .= "\n\n" . $v09aa1ddf8f["css"]; if ($v09aa1ddf8f["js"]) $v30d38c7973["js"] .= "\n\n" . $v09aa1ddf8f["js"]; } return $v30d38c7973; } private static function mcfa7aa8fab54($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $pe7333513, &$v2e8aa9d64e, &$v1b7f0d0c99, &$v0fd98ea3ab, $v1d696dbd12, $v7f5911d32d, $pccb248b8, $v28bb4bea47, $v516837ee42 = false, $v7943b46e77 = null, &$pef34936b = false, &$pb39a0a9c = false) { $v8282c7dd58 = $v7f5911d32d["id"]; $pfbb6ee46 = $v7f5911d32d["tag"]; $pef349725 = $v7f5911d32d["properties"]; $pe59b744b = $pef349725["choose_db_table"]; $v9d7547e4d6 = $pe59b744b["db_table"]; $pfdbbc383 = $pef349725["attributes"]; $v55bd236ac1 = $pef349725["action"]; $v5ed3bcae90 = $pef349725["links"]; $pa752cc18 = $pef349725["pre_form_settings"]; $v09aa1ddf8f = $pef349725["pos_form_settings"]; $v487b7d34ae = $pef349725["brokers_services_and_rules"]; $pabde73f0 = $pef349725["pagination"]; $v876c18d646 = $pef349725["files_to_create"]; $v1f377b389c = $v7f5911d32d["tasks"]; $v678baef824 = $v7f5911d32d["exits"]["default_exit"]; $v678baef824 = $v678baef824[0] ? $v678baef824 : array($v678baef824); $pa929f3e8 = $pef349725["users_perms"]; $pef34936b = array(); if (is_array($pa929f3e8)) { if (array_key_exists("user_type_id", $pa929f3e8) || array_key_exists("activity_id", $pa929f3e8)) $pa929f3e8 = array($pa929f3e8); foreach ($pa929f3e8 as $pd69fb7d0 => $v74b37bf978) if (is_numeric($v74b37bf978["user_type_id"]) && is_numeric($v74b37bf978["activity_id"])) $pef34936b[] = $v74b37bf978; } $v5ed3bcae90 = self::f3429787ad7($v5ed3bcae90); $v9a4a47d31e = array($v9d7547e4d6 => $pe59b744b["db_table_alias"]); $v44ffadba90 = $pccb248b8 && ($pccb248b8["tag"] == "listing" || $pccb248b8["tag"] == "form" || $pccb248b8["tag"] == "view"); $pa8f6b97e = self::f57c62455ca($v7f5911d32d, $pccb248b8); if ($pfbb6ee46 == "listing") $pb3f8356a = $pef349725["listing_type"] == "tree" ? "list_form" : ($pef349725["listing_type"] == "multi_form" ? "multiple_form" : "list_table"); else $pb3f8356a = $v44ffadba90 && !$pa8f6b97e ? "multiple_form" : "single_form"; $v28bb4bea47 .= self::mee7f8e059cfa($v7f5911d32d) . "/"; if (($pb3f8356a == "list_table" || $pb3f8356a == "list_form") && $pabde73f0 && $pabde73f0["active"]) { if ($v44ffadba90) { $pabde73f0["on_click_js_func"] = "loadEmbededPageWithNewNavigation"; $v1b7f0d0c99["ajax_navigation"] = true; } else if ($v516837ee42) { $pabde73f0["on_click_js_func"] = "loadTabPageWithNewNavigation"; $v1b7f0d0c99["ajax_tab_navigation"] = true; } } else if ($pb3f8356a == "multiple_form") { if ($v44ffadba90) { $pabde73f0 = array("on_click_js_func" => "loadEmbededPageWithNewNavigation"); $v1b7f0d0c99["ajax_navigation"] = true; } else if ($v516837ee42) { $pabde73f0["on_click_js_func"] = "loadTabPageWithNewNavigation"; $v1b7f0d0c99["ajax_tab_navigation"] = true; } } else $pabde73f0 = array("active" => false); $v108a6b4e42 = array(); if ($pfdbbc383) foreach ($pfdbbc383 as $pe5c5e2fe => $v97915b9670) { $v7162e23723 = $v97915b9670["name"] ? $v97915b9670["name"] : $pe5c5e2fe; $v108a6b4e42[$v7162e23723] = $v97915b9670; } $pfdbbc383 = $v108a6b4e42; $v30857f7eca[$v9d7547e4d6] = array( "panel_type" => $pb3f8356a, "panel_id" => $v7f5911d32d["id"], "panel_class" => "task-panel " . $v7f5911d32d["id"] . ($pef349725["interface_class"] ? " " . $pef349725["interface_class"] : ""), "panel_previous_html" => $pef349725["interface_previous_html"], "panel_next_html" => $pef349725["interface_next_html"], "form_type" => "settings", "attributes" => array_keys($pfdbbc383), "actions" => array( "links" => $v5ed3bcae90, "attributes_settings" => $pfdbbc383, ), "pagination" => $pabde73f0, "conditions" => array(), ); if ($pe59b744b["db_table_conditions"]) { if (isset($pe59b744b["db_table_conditions"]["attribute"]) || isset($pe59b744b["db_table_conditions"]["value"])) $pe59b744b["db_table_conditions"] = array($pe59b744b["db_table_conditions"]); foreach ($pe59b744b["db_table_conditions"] as $pd69fb7d0 => $v2566fc2579) $v30857f7eca[$v9d7547e4d6]["conditions"][] = array( "column" => $v2566fc2579["attribute"], "table" => $pe59b744b["db_table_parent"] ? $pe59b744b["db_table_parent"] : $v9d7547e4d6, "value" => $v2566fc2579["value"], ); } if ($pe59b744b["db_table_parent"]) $v30857f7eca[$v9d7547e4d6]["table_parent"] = $pe59b744b["db_table_parent"]; if ($v487b7d34ae) foreach ($v487b7d34ae as $v15972e4042 => $pa00bfad8) { $pbb76dfac = false; switch ($pa00bfad8["brokers_layer_type"]) { case "callbusinesslogic": case "callibatisquery": case "callhibernatemethod": $pbb76dfac = !$pa00bfad8["service_id"]; break; case "getquerydata": case "setquerydata": $pbb76dfac = !$pa00bfad8["sql"]; break; } if ($pbb76dfac) unset($v487b7d34ae[$v15972e4042]); } $v30857f7eca[$v9d7547e4d6]["brokers"] = $v487b7d34ae; $pf5de42d9 = ($v487b7d34ae["insert"] || $v30857f7eca[$v9d7547e4d6]["actions"]["insert"]) && !$v487b7d34ae["update"] && !$v487b7d34ae["delete"] && !$v30857f7eca[$v9d7547e4d6]["actions"]["update"] && !$v30857f7eca[$v9d7547e4d6]["actions"]["delete"]; if ($pb3f8356a == "single_form" && !$v487b7d34ae["get"] && !$pf5de42d9) $v30857f7eca[$v9d7547e4d6]["actions"]["get"] = array("action" => "get"); if ($pb3f8356a != "single_form" && !$v487b7d34ae["get_all"]) { if ($v44ffadba90) { if ($pccb248b8["properties"]["choose_db_table"]["db_table"] != $v9d7547e4d6) $v30857f7eca[$v9d7547e4d6]["table_parent"] = $pccb248b8["properties"]["choose_db_table"]["db_table"]; $paf1bc6f6 = self::f96b3f1ab58($v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v8ffce2a791, $pa0462a8e, $pe59b744b["db_driver"], $pe59b744b["db_type"], $pccb248b8["properties"]["choose_db_table"]["db_table"], $v9d7547e4d6); $v30857f7eca[$v9d7547e4d6]["conditions"] = array_merge($v30857f7eca[$v9d7547e4d6]["conditions"], $paf1bc6f6); } else if ($v487b7d34ae["parents_get_all"] && $v487b7d34ae["parents_count"]) { $v30857f7eca[$v9d7547e4d6]["brokers"]["get_all"] = $v487b7d34ae["parents_get_all"]; $v30857f7eca[$v9d7547e4d6]["brokers"]["count"] = $v487b7d34ae["parents_count"]; } else if ($pe59b744b["db_table_parent"]) { $paf1bc6f6 = self::f96b3f1ab58($v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v8ffce2a791, $pa0462a8e, $pe59b744b["db_driver"], $pe59b744b["db_type"], $pe59b744b["db_table_parent"], $v9d7547e4d6); $v30857f7eca[$v9d7547e4d6]["conditions"] = array_merge($v30857f7eca[$v9d7547e4d6]["conditions"], $paf1bc6f6); } } self::ma4259f841757($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $v2e8aa9d64e, $v28bb4bea47, $v30857f7eca, $v8282c7dd58, $v876c18d646, $pe59b744b, $v9a4a47d31e, $v9d7547e4d6, $v487b7d34ae, $v55bd236ac1, "single_", "insert", $v44ffadba90, $pb3f8356a, $v516837ee42, $v7943b46e77, $pef34936b); self::ma4259f841757($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $v2e8aa9d64e, $v28bb4bea47, $v30857f7eca, $v8282c7dd58, $v876c18d646, $pe59b744b, $v9a4a47d31e, $v9d7547e4d6, $v487b7d34ae, $v55bd236ac1, "single_", "update", $v44ffadba90, $pb3f8356a, $v516837ee42, $v7943b46e77, $pef34936b); self::ma4259f841757($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $v2e8aa9d64e, $v28bb4bea47, $v30857f7eca, $v8282c7dd58, $v876c18d646, $pe59b744b, $v9a4a47d31e, $v9d7547e4d6, $v487b7d34ae, $v55bd236ac1, "single_", "delete", $v44ffadba90, $pb3f8356a, $v516837ee42, $v7943b46e77, $pef34936b); self::ma4259f841757($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $v2e8aa9d64e, $v28bb4bea47, $v30857f7eca, $v8282c7dd58, $v876c18d646, $pe59b744b, $v9a4a47d31e, $v9d7547e4d6, $v487b7d34ae, $v55bd236ac1, "multiple_", "insert", $v44ffadba90, $pb3f8356a, $v516837ee42, $v7943b46e77, $pef34936b); self::ma4259f841757($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $v2e8aa9d64e, $v28bb4bea47, $v30857f7eca, $v8282c7dd58, $v876c18d646, $pe59b744b, $v9a4a47d31e, $v9d7547e4d6, $v487b7d34ae, $v55bd236ac1, "multiple_", "update", $v44ffadba90, $pb3f8356a, $v516837ee42, $v7943b46e77, $pef34936b); self::ma4259f841757($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $v2e8aa9d64e, $v28bb4bea47, $v30857f7eca, $v8282c7dd58, $v876c18d646, $pe59b744b, $v9a4a47d31e, $v9d7547e4d6, $v487b7d34ae, $v55bd236ac1, "multiple_", "delete", $v44ffadba90, $pb3f8356a, $v516837ee42, $v7943b46e77, $pef34936b); if ($v1f377b389c) { $v5768afccea = "ajax"; foreach ($v1f377b389c as $pd12cba7e => $pa53fc09c) { $pd12cba7e = $pa53fc09c["id"]; $v2e6ac2dd33 = $pa53fc09c["properties"]; $v0ec3afeef5 = $v2e6ac2dd33["parent_link_value"]; $pfc1f8ace = $v2e6ac2dd33["parent_link_title"]; $paab95b02 = $v2e6ac2dd33["interface_type"]; $pb21bb581 = '{$project_url_prefix}' . $v98a8251725 . $v28bb4bea47 . self::mee7f8e059cfa($pa53fc09c); $pfb80a61e = $paab95b02 == "popup" ? "return openPopup(this, '$pb21bb581')" : "return openEmbed(this, '$pb21bb581')"; $v1b7f0d0c99[$paab95b02] = true; $v30857f7eca[$v9d7547e4d6]["actions"]["links"][] = array( "url" => "javascript:void(0)", "value" => strlen($v0ec3afeef5) ? $v0ec3afeef5 : $pa53fc09c["label"], "title" => strlen($pfc1f8ace) ? $pfc1f8ace : $pa53fc09c["label"], "class" => $v2e6ac2dd33["parent_link_class"], "extra_attributes" => array( array("name" => "onClick", "value" => $pfb80a61e) ), "previous_html" => $v2e6ac2dd33["parent_link_previous_html"], "next_html" => $v2e6ac2dd33["parent_link_next_html"], ); if ($v7943b46e77) { $pa53fc09c["properties"]["authentication_type"] = $v7943b46e77["authentication_type"]; $pa53fc09c["properties"]["authentication_users"] = $v7943b46e77["authentication_users"]; } if (!self::createPageFile($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $v5768afccea, $v2e8aa9d64e, $v1b7f0d0c99, $v0fd98ea3ab, $v1d696dbd12, $pa53fc09c, $v7f5911d32d, $v28bb4bea47, $pef34936b, $pb39a0a9c)) return false; } } if ($v678baef824) for ($v43dd7d0051 = 0; $v43dd7d0051 < count($v678baef824); $v43dd7d0051++) { $v5b242fbd41 = $v678baef824[$v43dd7d0051]; $v965b5b3a0d = $v5b242fbd41["label"]; $v002556527f = $v5b242fbd41["properties"]["connection_type"]; $pbae0a0a1 = $v5b242fbd41["properties"]["connection_title"]; $pdcf4a554 = $v5b242fbd41["properties"]["connection_class"]; $v3ee652c2b9 = $v5b242fbd41["properties"]["connection_target"]; $pf6db3176 = $v5b242fbd41["task_id"]; $v7b98c1186c = self::f1c3b9b59c1($v1d696dbd12, $pf6db3176); if ($v7b98c1186c && $v7b98c1186c["tag"] != "page") $v7b98c1186c = self::f2fa4395443($v1d696dbd12, $v7b98c1186c); if ($v7b98c1186c) { $pb0067554 = '{$project_url_prefix}' . $v98a8251725 . self::mee7f8e059cfa($v7b98c1186c); $pe0a01068 = ucwords(strtolower(str_replace("_", " ", $v7b98c1186c["label"]))); $v965b5b3a0d = $v965b5b3a0d ? $v965b5b3a0d : $pe0a01068; $pbae0a0a1 = $pbae0a0a1 ? $pbae0a0a1 : $v965b5b3a0d; if ($v002556527f == "popup") { $v1b7f0d0c99["iframe_popup"] = true; $v30857f7eca[$v9d7547e4d6]["actions"]["links"][] = array( "url" => "javascript:void(0)", "value" => $v965b5b3a0d, "title" => $pbae0a0a1, "class" => $pdcf4a554, "extra_attributes" => array( array("name" => "onClick", "value" => "return openIframePopup(this, '$pb0067554')") ), ); } else if ($v002556527f == "parent") { $v1b7f0d0c99["parent"] = true; $v30857f7eca[$v9d7547e4d6]["actions"]["links"][] = array( "url" => "javascript:void(0)", "value" => $v965b5b3a0d, "title" => $pbae0a0a1, "class" => $pdcf4a554, "extra_attributes" => array( array("name" => "onClick", "value" => "return openParentLocation(this, '$pb0067554')") ), ); } else $v30857f7eca[$v9d7547e4d6]["actions"]["links"][] = array( "url" => $pb0067554, "value" => $v965b5b3a0d, "title" => $pbae0a0a1, "class" => $pdcf4a554, "target" => $v3ee652c2b9, ); } } $v0b166cf50c = self::f4280f7a6c1($v188b4f5fa6, $v3d55458bcd, $v5039a77f9d, $pe59b744b["db_driver"]); $v826d0ddb62 = self::f90bb9f56e7($v188b4f5fa6, $v3d55458bcd, $v5039a77f9d, $pe59b744b["db_driver"]); $pb09c0b5b = self::f8a1714bd98($v188b4f5fa6, $v3d55458bcd, $v5039a77f9d, $pe59b744b["include_db_driver"]); $v30d38c7973 = CMSPresentationFormSettingsUIHandler::getFormSettings($v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, null, $v0b166cf50c, $v826d0ddb62, $pb09c0b5b, $pe59b744b["db_driver"], $pe59b744b["include_db_driver"], $pe59b744b["db_type"], $v9a4a47d31e, $v30857f7eca, false, $v0fd98ea3ab, $v516837ee42, $pef34936b, $pb39a0a9c); if ($pa752cc18) { if ($pa752cc18["actions"]) { $pa752cc18["actions"] = isset($pa752cc18["actions"]["action_type"]) || isset($pa752cc18["actions"]["action_value"]) ? $pa752cc18["actions"] : array($pa752cc18["actions"]); $v30d38c7973["actions"] = array_merge($pa752cc18["actions"], $v30d38c7973["actions"]); } if ($pa752cc18["css"]) $v30d38c7973["css"] = $pa752cc18["css"] . ($v30d38c7973["css"] ? "\n\n" . $v30d38c7973["css"] : ""); if ($pa752cc18["js"]) $v30d38c7973["js"] = $pa752cc18["js"] . ($v30d38c7973["js"] ? "\n\n" . $v30d38c7973["js"] : ""); } if ($v09aa1ddf8f) { if ($v09aa1ddf8f["actions"]) { $v09aa1ddf8f["actions"] = isset($v09aa1ddf8f["actions"]["action_type"]) || isset($v09aa1ddf8f["actions"]["action_value"]) ? $v09aa1ddf8f["actions"] : array($v09aa1ddf8f["actions"]); $v30d38c7973["actions"] = array_merge($v30d38c7973["actions"], $v09aa1ddf8f["actions"]); } if ($v09aa1ddf8f["css"]) $v30d38c7973["css"] .= "\n\n" . $v09aa1ddf8f["css"]; if ($v09aa1ddf8f["js"]) $v30d38c7973["js"] .= "\n\n" . $v09aa1ddf8f["js"]; } $v30d38c7973["actions"] = array( array( "result_var_name" => "", "action_type" => "group", "condition_type" => "execute_always", "condition_value" => "", "action_value" => array( "group_name" => "", "actions" => $v30d38c7973["actions"], ), ) ); return $v30d38c7973; } private static function ma4259f841757($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, &$v2e8aa9d64e, $v28bb4bea47, &$v30857f7eca, $v8282c7dd58, $v876c18d646, $pe59b744b, $v9a4a47d31e, $v9d7547e4d6, $v487b7d34ae, $v55bd236ac1, $v8ff80a1320, $v256e3a39a7, $v44ffadba90, $pb3f8356a, $v516837ee42, $v7943b46e77, $pef34936b) { $v9f2f9d92a3 = $v8ff80a1320 . $v256e3a39a7; if ($v55bd236ac1[$v9f2f9d92a3]) { $v313ffa5f74 = '{$project_url_prefix}' . $v98a8251725 . substr($v28bb4bea47, 0, -1) . "_"; $pa8b4b2c5 = $pb3f8356a == "list_table" || $pb3f8356a == "list_form"; $pbf2a75a9 = $v8ff80a1320 == "multiple_"; $pa0def03f = $v55bd236ac1[$v9f2f9d92a3 . "_confirmation_message"]; $v6ed826eaa0 = $v55bd236ac1[$v9f2f9d92a3 . "_ok_msg_message"]; $v63ab3e3366 = $v55bd236ac1[$v9f2f9d92a3 . "_ok_msg_redirect_url"]; $v19e19f0053 = $v55bd236ac1[$v9f2f9d92a3 . "_error_msg_message"]; $pfc6b8f45 = $v55bd236ac1[$v9f2f9d92a3 . "_error_msg_redirect_url"]; if ($pbf2a75a9 && ($v256e3a39a7 == "insert" || $v256e3a39a7 == "update") && $v55bd236ac1["multiple_insert"] && $v55bd236ac1["multiple_update"]) $v9f2f9d92a3 = "multiple_insert_update"; if ($v44ffadba90 || $v516837ee42 || ($pa8b4b2c5 && $v8ff80a1320 == "single_")) { $v30857f7eca[$v9d7547e4d6]["actions"][$v9f2f9d92a3] = array( "action" => $v9f2f9d92a3, "action_type" => "ajax_on_click", "ajax_url" => $v313ffa5f74 . $v9f2f9d92a3, "confirmation_message" => $pa0def03f, "ok_msg_message" => $v6ed826eaa0, "ok_msg_redirect_url" => $v63ab3e3366, "error_msg_message" => $v19e19f0053, "error_msg_redirect_url" => $pfc6b8f45, ); self::f3bf480b134($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $v2e8aa9d64e, $v28bb4bea47, $v30857f7eca, $v8282c7dd58, $v876c18d646, $pe59b744b, $v9a4a47d31e, $v9d7547e4d6, $v487b7d34ae, $v55bd236ac1, $v8ff80a1320, $v256e3a39a7, $v9f2f9d92a3, $pbf2a75a9, $v7943b46e77, $pef34936b); } else if ($pb3f8356a == "multiple_form" && $v9f2f9d92a3 == "single_insert") $v30857f7eca[$v9d7547e4d6]["actions"][$v9f2f9d92a3] = array( "action" => $v9f2f9d92a3, "action_type" => $v44ffadba90 || $v516837ee42 ? "ajax_on_click" : "", "ajax_url" => $v44ffadba90 || $v516837ee42 ? $v313ffa5f74 . $v9f2f9d92a3 : null, "ok_msg_message" => $v6ed826eaa0, "ok_msg_redirect_url" => $v63ab3e3366, "error_msg_message" => $v19e19f0053, "error_msg_redirect_url" => $pfc6b8f45, ); else $v30857f7eca[$v9d7547e4d6]["actions"][$v9f2f9d92a3] = array( "action" => $v9f2f9d92a3, "action_type" => "", "confirmation_message" => $pa0def03f, "ok_msg_message" => $v6ed826eaa0, "ok_msg_redirect_url" => $v63ab3e3366, "error_msg_message" => $v19e19f0053, "error_msg_redirect_url" => $pfc6b8f45, ); } } private static function f3bf480b134($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, $v98a8251725, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, &$v2e8aa9d64e, $v28bb4bea47, $v30857f7eca, $v8282c7dd58, $v876c18d646, $pe59b744b, $v9a4a47d31e, $v9d7547e4d6, $v487b7d34ae, $v55bd236ac1, $v8ff80a1320, $v256e3a39a7, $v9f2f9d92a3, $pbf2a75a9, $v7943b46e77, $pef34936b) { $pef3a5b70 = array( $v9d7547e4d6 => array( "form_type" => $v30857f7eca["form_type"], "brokers" => array(), "actions" => array(), ) ); if ($v9f2f9d92a3 == "multiple_insert_update") { $v9f2f9d92a3 = "multiple_insert_update"; $pe86870b8 = array($v256e3a39a7, $v256e3a39a7 == "insert" ? "update" : "insert"); } else $pe86870b8 = array($v256e3a39a7); foreach ($pe86870b8 as $pe9daf1fc) { if (!$v487b7d34ae[$pe9daf1fc]) $pef3a5b70[$v9d7547e4d6]["actions"][$v9f2f9d92a3] = array( "action" => $v9f2f9d92a3, "action_type" => "", ); else { $pef3a5b70[$v9d7547e4d6]["brokers"][$pe9daf1fc] = $v487b7d34ae[$pe9daf1fc]; if ($pe9daf1fc == "update") $pef3a5b70[$v9d7547e4d6]["brokers"]["update_pks"] = $v487b7d34ae["update_pks"]; else if ($pe9daf1fc != "insert") $pef3a5b70[$v9d7547e4d6]["brokers"]["get"] = $v487b7d34ae["get"]; } } if ($pbf2a75a9) $pef3a5b70[$v9d7547e4d6]["actions"][$v9f2f9d92a3] = array( "action" => $v9f2f9d92a3, "action_type" => "", ); $v0b166cf50c = self::f4280f7a6c1($v188b4f5fa6, $v3d55458bcd, $v5039a77f9d, $pe59b744b["db_driver"]); $v826d0ddb62 = self::f90bb9f56e7($v188b4f5fa6, $v3d55458bcd, $v5039a77f9d, $pe59b744b["db_driver"]); $pb09c0b5b = self::f8a1714bd98($v188b4f5fa6, $v3d55458bcd, $v5039a77f9d, $pe59b744b["include_db_driver"]); $v6f4044e7e2 = CMSPresentationFormSettingsUIHandler::getFormSettings($v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v4bf8d90f04, $pfce4d1b3, $v8ffce2a791, $pa0462a8e, $pa32be502, null, $v0b166cf50c, $v826d0ddb62, $pb09c0b5b, $pe59b744b["db_driver"], $pe59b744b["include_db_driver"], $pe59b744b["db_type"], $v9a4a47d31e, $pef3a5b70, true, "", false, $pef34936b, $pb39a0a9c); self::f67d92f51be($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v98a8251725 . substr($v28bb4bea47, 0, -1) . "_" . $v9f2f9d92a3, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, "ajax", $v2e8aa9d64e, $v6f4044e7e2, $v8282c7dd58, $v876c18d646, "ajax", $v7943b46e77, $pef34936b, null, $pb39a0a9c); } private static function f90bb9f56e7($v188b4f5fa6, $v3d55458bcd, $v5039a77f9d, $v872f5b4dbb) { $pc4223ce1 = $v188b4f5fa6->getPresentationLayer()->getBrokers(); if ($v872f5b4dbb) { $pab752e34 = WorkFlowBeansFileHandler::getBrokersLocalDBBrokerNameForChildBrokerDBDriver($v3d55458bcd, $v5039a77f9d, $pc4223ce1, $v872f5b4dbb); if ($pab752e34) return $pab752e34; } } private static function f8a1714bd98($v188b4f5fa6, $v3d55458bcd, $v5039a77f9d, $v8b13fa2358) { if ($v8b13fa2358) return true; $pc4223ce1 = $v188b4f5fa6->getPresentationLayer()->getBrokers(); $v7a0994a134 = WorkFlowBeansFileHandler::getLayerBrokersSettings($v3d55458bcd, $v5039a77f9d, $pc4223ce1); return count($v7a0994a134["db_brokers"]) > 1; } private static function f4280f7a6c1($v188b4f5fa6, $v3d55458bcd, $v5039a77f9d, $v872f5b4dbb) { $pc4223ce1 = $v188b4f5fa6->getPresentationLayer()->getBrokers(); if ($pc4223ce1) foreach ($pc4223ce1 as $v2b2cf4c0eb => $pd922c2f7) if (is_a($pd922c2f7, "IDataAccessBrokerClient")) return $v2b2cf4c0eb; return null; } private static function f3eb8f3f6a9($v1b7f0d0c99) { $v40b70e70c3 = ''; if ($v1b7f0d0c99["ajax_navigation"]) $v40b70e70c3 .= '
function loadEmbededPageWithNewNavigation(page_attr_name, page_num, type, panel_id, elm) {
	var p = $(elm).parent().closest(".embeded-inner-task");
	
	if (p[0]) {
		var url = p.attr("data-url");
		
		if (url) {
			eval("url = decodeURI(url).replace(/" + page_attr_name + "=[^&]*/gi, \'\');");
			url += (url.indexOf("?") == -1 ? "?" : "&") + page_attr_name + "=" + page_num;
			url = url.replace(/[&]+/g, "&");
			p.attr("data-url", url);
			
			prepareUrlContent(p, url, function(html) {
				if (p.is("tr"))
					p.children("td").first().html(html);
				else if (p.hasClass("myfancypopup")) {
					p.contents(":not(.popup_close)").remove(); //do not remove close button
					p.append(html);
				}
				else
					p.html(html);
				
				if (typeof onNewHtml == "function")
					onNewHtml(elm, p);
			});
		}
	}
	else
		alert("Error: trying to find .embeded-inner-task element. Please check the function: loadEmbededPageWithNewNavigation.");
	
	return false;
}
'; if ($v1b7f0d0c99["ajax_tab_navigation"]) $v40b70e70c3 .= '
function loadTabPageWithNewNavigation(page_attr_name, page_num, type, panel_id, elm) {
	var p = $(elm).parent().closest(".task-panel");
	
	if (p[0]) {
		var url = document.location.toString();
		eval("url = decodeURI(url).replace(/" + page_attr_name + "=[^&]*/gi, \'\');");
		url += (url.indexOf("?") == -1 ? "?" : "&") + page_attr_name + "=" + page_num;
		url = url.replace(/[&]+/g, "&");
		
		prepareUrlContent(p, url, function(html) {
			var task_id = p.attr("id");
			var task_html = $(html).find("#" + task_id).html();
			p.html(task_html);
			
			if (typeof onNewHtml == "function")
				onNewHtml(elm, p);
		});
	}
	else
		alert("Error: trying to find .embeded-inner-task element. Please check the function: loadTabPageWithNewNavigation.");
	
	return false;
}
'; if ($v1b7f0d0c99["embeded"]) $v40b70e70c3 .= '
function openEmbed(elm, url) {
	elm = $(elm);
	var p = elm.parent().closest("tr, li, form, .task-panel");
	
	if (p.is(".task-panel"))
		p = p.parent();
	
	if (p.is("form")) //check if form even if is the .task-panel parent
		p = p.parent();
	
	if (p[0]) {
		var query_string = elm.attr("data-query-string");
		if (query_string)
			url += (url.indexOf("?") != -1 ? "&" : "?") + query_string;
		
		var previous_loaded_elm = p.is("tr") ? p.next(".embeded-inner-task") : p.children(".embeded-inner-task");
		
		if (!previous_loaded_elm[0]) {
			if (p.is("tr")) {
				previous_loaded_elm = $(\'<tr class="embeded-inner-task"><td colspan="\' + p.children().length + \'"></td></tr>\');
				previous_loaded_elm.insertAfter(p);
			}
			else {
				previous_loaded_elm = $(\'<div class="embeded-inner-task"><div>\');
				p.append(previous_loaded_elm);
			}
		}
		
		//if (previous_loaded_elm[0].hasAttribute("data-url"))
		//	previous_loaded_elm.toggle();
		if (previous_loaded_elm[0].hasAttribute("data-url") && previous_loaded_elm.css("display") != "none")
			previous_loaded_elm.hide();
		else {
			previous_loaded_elm.show().attr("data-url", url);
			
			if (previous_loaded_elm.is("tr"))
				previous_loaded_elm.children("td").first().html("");
			else
				previous_loaded_elm.html("");
			
			prepareUrlContent(elm, url, function(html) {
				if (previous_loaded_elm.is("tr"))
					previous_loaded_elm.children("td").first().html(html);
				else
					previous_loaded_elm.html(html);
				
				if (typeof onNewHtml == "function")
					onNewHtml(elm, previous_loaded_elm);
			});
		}
	}
	else 
		alert("Error: trying to find parent in openEmbed function. Please check the function: openEmbed.");
	
	return false;
}
'; if ($v1b7f0d0c99["popup"]) $v40b70e70c3 .= '
function openPopup(elm, url) {
	elm = $(elm);
	var p = $("body");
	var query_string = elm.attr("data-query-string");
	
	if (query_string)
		url += (url.indexOf("?") != -1 ? "&" : "?") + query_string;
	
	if (typeof MyFancyPopupClass == "function") {
		var popup_elm = p.children(".myfancypopup.embeded-inner-task");
		
		if (!popup_elm[0]) {
			popup_elm = $(\'<div class="myfancypopup embeded-inner-task"><div>\');
			p.append(popup_elm);
		}
		
		//if (popup_elm.attr("data-url") != url) {
			popup_elm.html("");
			popup_elm.attr("data-url", url);
			
			prepareUrlContent(elm, url, function(html) {
				popup_elm.append(html);
				
				if (typeof onNewHtml == "function")
					onNewHtml(elm, popup_elm);
			});
		//}
		
		var popup = new MyFancyPopupClass();
		popup.init({
			elementToShow: popup_elm,
		});
		popup.showPopup();
	}
	else { //bootstrap modal
		var modal = p.find(" > .modal > .modal-dialog > .modal-content.embeded-inner-task").parent().closest(".modal");
		
		if (!modal[0]) {
			modal = \'<div class="modal" tabindex="-1" role="dialog">\'
					+ \'	<div class="modal-dialog modal-lg" role="document">\'
					+ \'		<div class="modal-content embeded-inner-task">\'
	 				+ \'	    </div>\'
					+ \'	  </div>\'
					+ \'	</div>\';
			modal = $(modal);
			p.append(modal);
		}
		
		var modal_content = modal.find(" > .modal-dialog > .modal-content.embeded-inner-task");
		
		//if (modal_content.attr("data-url") != url) {
			modal_content.html("");
			modal_content.attr("data-url", url);
			
			prepareUrlContent(elm, url, function(html) {
				modal_content.html(html);
				
				if (typeof onNewHtml == "function")
					onNewHtml(elm, modal_content);
			});
		//}
		
		if (typeof modal.modal == "function") {
			modal.modal();
			modal.modal("show");
		}
		else
			alert("Please include in your template the bootstrap.js or the jquery.myfancybox.js");
	}
	
	return false;
}
'; if ($v1b7f0d0c99["iframe_popup"]) $v40b70e70c3 .= '
function openIframePopup(elm, url) {
	elm = $(elm);
	var p = $("body");
	var query_string = elm.attr("data-query-string");
	
	if (query_string)
		url += (url.indexOf("?") != -1 ? "&" : "?") + query_string;
	
	if (typeof MyFancyPopupClass == "function") {
		var popup_elm = p.children(".myfancypopup.iframe-inner-task");
		
		if (!popup_elm[0]) {
			popup_elm = $(\'<div class="myfancypopup iframe-inner-task"><div>\');
			p.append(popup_elm);
		}
		
		//if (popup_elm.children("iframe").attr("src") != url)
			popup_elm.html(\'<iframe src="\' + url + \'"></iframe>\');
		
		var popup = new MyFancyPopupClass();
		popup.init({
			elementToShow: popup_elm,
			type: "iframe",
		});
		popup.showPopup();
	}
	else { //bootstrap modal
		var modal = p.find(" > .modal > .modal-dialog > .modal-content.iframe-inner-task").parent().closest(".modal");
		
		if (!modal[0]) {
			modal = \'<div class="modal" tabindex="-1" role="dialog">\'
					+ \'	<div class="modal-dialog modal-lg" role="document">\'
					+ \'		<div class="modal-content iframe-inner-task">\'
	 				+ \'	    </div>\'
					+ \'	  </div>\'
					+ \'	</div>\';
			modal = $(modal);
			p.append(modal);
		}
		
		var modal_content = modal.find(" > .modal-dialog > .modal-content.iframe-inner-task");
		
		//if (modal_content.children("iframe").attr("src") != url)
			modal_content.html(\'<iframe src="\' + url + \'"></iframe>\');
		
		if (typeof modal == "function") {
			modal.modal();
			modal.modal("show");
		}
		else
			alert("Please include in your template the bootstrap.js or the jquery.myfancybox.js");
	}
	
	return false;
}
'; if ($v1b7f0d0c99["parent"]) $v40b70e70c3 .= '
function openParentLocation(elm, url) {
	elm = $(elm);
	var query_string = elm.attr("data-query-string");
	
	if (query_string)
		url += (url.indexOf("?") != -1 ? "&" : "?") + query_string;
	
	if (window.top)
		window.top.location = url;
	else
		window.location = url;
		
	return false;
}
'; if ($v1b7f0d0c99["embeded"] || $v1b7f0d0c99["popup"] || $v1b7f0d0c99["ajax_navigation"] || $v1b7f0d0c99["ajax_tab_navigation"]) $v40b70e70c3 .= '
function prepareUrlContent(elm, url, handler) {
	elm = $(elm);
	
	//execute ajax request to get the html from url and them call handler
	$.ajax({
		type : "get",
		url : url,
		dataType : "text",
		success : function(html, text_status, jqXHR) {
			if (typeof handler == "function")
				handler(html);
			else
				alert("Invalid handler in prepareUrlContent function. Please check your javascript code!");
		},
		error : function() {
			alert("Error: trying to get url.");
		},
	});
}
'; return $v40b70e70c3; } private static function f1c3b9b59c1($v1d696dbd12, $pd7d41506) { if ($v1d696dbd12[$pd7d41506]) return $v1d696dbd12[$pd7d41506]; foreach ($v1d696dbd12 as $v8282c7dd58 => $v7f5911d32d) if ($v7f5911d32d["tasks"]) { $pc37695cb = self::f1c3b9b59c1($v7f5911d32d["tasks"], $pd7d41506); if ($pc37695cb) return $pc37695cb; } return null; } private static function f2fa4395443($v1d696dbd12, $v7b98c1186c, $pbd45713b = array()) { $pf6db3176 = $v7b98c1186c["id"]; if ($v1d696dbd12[$pf6db3176]) { foreach ($pbd45713b as $v9acf40c110) if ($v9acf40c110["tag"] == "page") return $v9acf40c110; return null; } foreach ($v1d696dbd12 as $v8282c7dd58 => $v7f5911d32d) if ($v7f5911d32d["tasks"]) { $v6709ed11d2 = $pbd45713b; $v6709ed11d2[] = $v7f5911d32d; $pc37695cb = self::f2fa4395443($v7f5911d32d["tasks"], $v7b98c1186c, $v6709ed11d2); if ($pc37695cb) return $pc37695cb; } return null; } public static function getLabelFileName($v9acc88059e) { $v5e813b295b = TextSanitizer::normalizeAccents($v9acc88059e); $v5e813b295b = strtolower(str_replace(array(" ", "-"), "_", $v5e813b295b)); return $v5e813b295b; } private static function mee7f8e059cfa($v7f5911d32d) { return self::getLabelFileName($v7f5911d32d["label"]); } private static function f57c62455ca($v80015e8d8a, $pf71bb4d4, $v4932f00f0d = false) { return $v80015e8d8a && $pf71bb4d4 && $v80015e8d8a["properties"] && $pf71bb4d4["properties"] && $v80015e8d8a["properties"]["choose_db_table"] && $pf71bb4d4["properties"]["choose_db_table"] && $v80015e8d8a["properties"]["choose_db_table"]["db_driver"] == $pf71bb4d4["properties"]["choose_db_table"]["db_driver"] && (!$v4932f00f0d || $v80015e8d8a["properties"]["choose_db_table"]["db_type"] == $pf71bb4d4["properties"]["choose_db_table"]["db_type"]) && $v80015e8d8a["properties"]["choose_db_table"]["db_table"] == $pf71bb4d4["properties"]["choose_db_table"]["db_table"]; } private static function f3429787ad7(&$v5ed3bcae90) { if ($v5ed3bcae90 && (isset($v5ed3bcae90["url"]) || isset($v5ed3bcae90["class"]) || isset($v5ed3bcae90["value"]) || isset($v5ed3bcae90["title"]))) $v5ed3bcae90 = array($v5ed3bcae90); return $v5ed3bcae90; } private static function f96b3f1ab58($v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v8ffce2a791, $pa0462a8e, $v872f5b4dbb, $v6cc00e79ac, $v5c0849530a, $v937ff6b99f) { $paf1bc6f6 = array(); $v094d1a2778 = new WorkFlowDataAccessHandler(); if ($v6cc00e79ac == "diagram") { $v5e053dece2 = WorkFlowTasksFileHandler::getDBDiagramTaskFilePath($pdb9e96e6, "db_diagram", $v872f5b4dbb); $v094d1a2778->setTasksFilePath($v5e053dece2); } else { $pb512d021 = new WorkFlowBeansFileHandler($v5039a77f9d . $pa0462a8e, $v3d55458bcd); $v972f1a5c2b = $pb512d021->getBeanObject($v8ffce2a791); $v9ac2ae8b4d = $v972f1a5c2b && is_a($v972f1a5c2b, "Layer") ? WorkFlowBeansFileHandler::getLayerDBDriverProps($v3d55458bcd, $v5039a77f9d, $v972f1a5c2b, $v872f5b4dbb) : null; $v9ac2ae8b4d = $v9ac2ae8b4d ? $v9ac2ae8b4d[1] : null; if ($v9ac2ae8b4d) { $v660b5296f8 = new WorkFlowDBHandler($v5039a77f9d, $v3d55458bcd); $v1d696dbd12 = $v660b5296f8->getUpdateTaskDBDiagram($v9ac2ae8b4d, $v872f5b4dbb); $v094d1a2778->setTasks($v1d696dbd12); } } $pac4bc40a = $v094d1a2778->getTasksAsTables(); $pe81664f7 = WorkFlowDBHandler::getTableFromTables($pac4bc40a, $v5c0849530a); $pf5795cb2 = WorkFlowDBHandler::getTableFromTables($pac4bc40a, $v937ff6b99f); if ($pe81664f7 && $pf5795cb2) { foreach ($pe81664f7 as $v1b0cfa478b) if ($v1b0cfa478b["primary_key"]) { $v5e45ec9bb9 = $v1b0cfa478b["name"]; $paf1bc6f6[] = array( "column" => $v5e45ec9bb9, "table" => $v5c0849530a, "value" => "{\$_GET[\"$v5e45ec9bb9\"]}", ); } } return $paf1bc6f6; } private static function f67d92f51be($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pf3dc0762, $pc4aa460d, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $pe7333513, &$v2e8aa9d64e, $v30d38c7973, $v8282c7dd58, $v876c18d646, $v982d6fe381, $v7943b46e77 = null, $pef34936b = null, $v53a57f1353 = null, $pb39a0a9c = null) { $v9ab35f1f0d = $v188b4f5fa6->getPresentationLayer(); $pa2bba2ac = $v9ab35f1f0d->getLayerPathSetting(); $v2508589a4c = $v9ab35f1f0d->getSelectedPresentationId(); $v6bfcc44e7b = $v9ab35f1f0d->getPresentationFileExtension(); $v657ab10c9c = $pf3dc0762; $v29fec2ceaa = $v657ab10c9c; if (!$pc4aa460d) { CMSPresentationLayerHandler::configureUniqueFileId($v657ab10c9c, $v188b4f5fa6->getEntitiesPath(), "." . $v6bfcc44e7b); CMSPresentationLayerHandler::configureUniqueFileId($v29fec2ceaa, $v188b4f5fa6->getBlocksPath(), "." . $v6bfcc44e7b); } $pb0f85b2f = $v188b4f5fa6->getEntityPath($v657ab10c9c); $v303bc2287d = $v188b4f5fa6->getBlockPath($v29fec2ceaa); $pf838ce5a = str_replace($pa2bba2ac, "", $pb0f85b2f); $pf838ce5a = substr($pf838ce5a, 0, strlen($pf838ce5a) - (strlen($v6bfcc44e7b) + 1)); $pc349ec81 = str_replace($pa2bba2ac, "", $v303bc2287d); $pc349ec81 = substr($pc349ec81, 0, strlen($pc349ec81) - (strlen($v6bfcc44e7b) + 1)); $v57a4d641c3 = array( "includes" => array(), "regions_blocks" => array(), "template_params" => array( "Page Title" => CMSPresentationFormSettingsUIHandler::getName(basename($pf3dc0762)) ), "template" => $pe7333513, ); if ($v53a57f1353 && $v53a57f1353["regions_blocks"]) { if (isset($v53a57f1353["regions_blocks"]["region"]) || isset($v53a57f1353["regions_blocks"]["block"]) || isset($v53a57f1353["regions_blocks"]["project"])) $v53a57f1353["regions_blocks"] = array($v53a57f1353["regions_blocks"]); foreach ($v53a57f1353["regions_blocks"] as $v1758c645b6) { $v9b9b8653bc = $v1758c645b6["region"]; $peebaaf55 = $v1758c645b6["block"]; $v93756c94b3 = $v1758c645b6["project"]; $v93756c94b3 = $v93756c94b3 == $v2508589a4c ? null : $v93756c94b3; if ($v9b9b8653bc && $peebaaf55) $v57a4d641c3["regions_blocks"][] = array("region" => $v9b9b8653bc, "block" => $peebaaf55, "project" => $v93756c94b3); } } if ($v53a57f1353 && $v53a57f1353["includes"]) { if (isset($v53a57f1353["includes"]["path"]) || isset($v53a57f1353["includes"]["once"])) $v53a57f1353["includes"] = array($v53a57f1353["includes"]); $v57a4d641c3["includes"] = $v53a57f1353["includes"]; } if ($pa9e9a096) { $v7959970a41 = false; foreach ($v57a4d641c3["includes"] as $v2d22d85b1f) if (strpos($v2d22d85b1f["path"], $pa9e9a096) !== false) { $v7959970a41 = true; break; } if (!$v7959970a41) $v57a4d641c3["includes"][] = array("path" => $pa9e9a096, "once" => 1); } if ($pef34936b || $v7943b46e77) { $v7959970a41 = false; foreach ($v57a4d641c3["includes"] as $v2d22d85b1f) if (strpos($v2d22d85b1f["path"], "user/include_user_session_activities_handler") !== false) { $v7959970a41 = true; break; } if (!$v7959970a41) $v57a4d641c3["includes"][] = array("path" => '$EVC->getModulePath("user/include_user_session_activities_handler", "' . $v188b4f5fa6->getCommonProjectName() . '")'); } if ($v53a57f1353 && $v53a57f1353["template_params"]) { if (isset($v53a57f1353["template_params"]["name"]) || isset($v53a57f1353["template_params"]["value"])) $v53a57f1353["template_params"] = array($v53a57f1353["template_params"]); foreach ($v53a57f1353["template_params"] as $v01644b5abd) if ($v01644b5abd["name"]) $v57a4d641c3["template_params"][ $v01644b5abd["name"] ] = $v01644b5abd["value"]; } if ($v7943b46e77 && $v7943b46e77["authentication_type"] == "authenticated") { $v53c35d2106 = $v7943b46e77["authentication_users"]; if (is_array($v53c35d2106) && array_key_exists("user_type_id", $v53c35d2106)) $v53c35d2106 = array($v53c35d2106); if (!self::mf9fe3791ee77($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v53c35d2106, $v57a4d641c3, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e, $v657ab10c9c, $v29fec2ceaa)) return false; } switch ($pffbf7c43) { case 2: $v2e8aa9d64e[$v8282c7dd58][$pb0f85b2f] = array( "file_id" => CMSPresentationLayerHandler::getFilePathId($v188b4f5fa6, $pb0f85b2f), "type" => $v982d6fe381, ); if (file_exists($pb0f85b2f)) { $v2e8aa9d64e[$v8282c7dd58][$pb0f85b2f]["created_time"] = filectime($pb0f85b2f); $v2e8aa9d64e[$v8282c7dd58][$pb0f85b2f]["modified_time"] = filemtime($pb0f85b2f); $v2e8aa9d64e[$v8282c7dd58][$pb0f85b2f]["hard_coded"] = CMSPresentationLayerHandler::isEntityFileHardCoded($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pb0f85b2f); } $v2e8aa9d64e[$v8282c7dd58][$v303bc2287d] = array( "file_id" => CMSPresentationLayerHandler::getFilePathId($v188b4f5fa6, $v303bc2287d), "type" => $v982d6fe381, ); if (file_exists($v303bc2287d)) { $v2e8aa9d64e[$v8282c7dd58][$v303bc2287d]["created_time"] = filectime($v303bc2287d); $v2e8aa9d64e[$v8282c7dd58][$v303bc2287d]["modified_time"] = filemtime($v303bc2287d); } break; case 3: $pb8473876 = !$v876c18d646 || !isset($v876c18d646[$pf838ce5a]) || $v876c18d646[$pf838ce5a]; $v43bf6fd181 = !$v876c18d646 || !isset($v876c18d646[$pc349ec81]) || $v876c18d646[$pc349ec81]; if ($v43bf6fd181) { $v6d17a5248e = CMSPresentationUIAutomaticFilesHandler::getFormSettingsBlockCode($v30d38c7973, array("form_settings_php_codes_list" => $pb39a0a9c)); $v2e8aa9d64e[$v8282c7dd58][$v303bc2287d] = array( "old_code" => file_exists($v303bc2287d) ? file_get_contents($v303bc2287d) : "", "new_code" => $v6d17a5248e, ); } if ($pb8473876) { $v7959970a41 = false; foreach ($v57a4d641c3["regions_blocks"] as $v1758c645b6) if ($v1758c645b6["block"] == $v29fec2ceaa && !$v1758c645b6["project"]) { $v7959970a41 = true; break; } if (!$v7959970a41) $v57a4d641c3["regions_blocks"][] = array("region" => "Content", "block" => $v29fec2ceaa); $pfefc55de = CMSPresentationUIAutomaticFilesHandler::getEntityCode($v57a4d641c3); $v2e8aa9d64e[$v8282c7dd58][$pb0f85b2f] = array( "old_code" => file_exists($pb0f85b2f) ? file_get_contents($pb0f85b2f) : "", "new_code" => $pfefc55de, ); } break; default: $pb025a7be = array(); $pb8473876 = !$v876c18d646 || !isset($v876c18d646[$pf838ce5a]) || $v876c18d646[$pf838ce5a]; $v43bf6fd181 = !$v876c18d646 || !isset($v876c18d646[$pc349ec81]) || $v876c18d646[$pc349ec81]; if ($v43bf6fd181) { $v6d17a5248e = CMSPresentationUIAutomaticFilesHandler::getFormSettingsBlockCode($v30d38c7973, array("form_settings_php_codes_list" => $pb39a0a9c)); CMSPresentationUIAutomaticFilesHandler::saveBlockCode($v188b4f5fa6, $v29fec2ceaa, $v6d17a5248e, $pc4aa460d, $pb025a7be); $v303bc2287d = $v188b4f5fa6->getBlockPath($v29fec2ceaa); } if ($pb8473876) { $v7959970a41 = false; foreach ($v57a4d641c3["regions_blocks"] as $v1758c645b6) if ($v1758c645b6["block"] == $v29fec2ceaa && !$v1758c645b6["project"]) { $v7959970a41 = true; break; } if (!$v7959970a41) $v57a4d641c3["regions_blocks"][] = array("region" => "Content", "block" => $v29fec2ceaa); CMSPresentationUIAutomaticFilesHandler::createAndSaveEntityCode($v188b4f5fa6, $v657ab10c9c, $v57a4d641c3, $pc4aa460d, $pb025a7be); $pb0f85b2f = $v188b4f5fa6->getEntityPath($v657ab10c9c); self::mfb3060dfd640($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v657ab10c9c, $pb025a7be); if ($v53c35d2106) self::f379796b4dc($v188b4f5fa6, $v53c35d2106, $v657ab10c9c); if ($pef34936b) self::f65372eab6c($v188b4f5fa6, $pef34936b, $v657ab10c9c); } $v2e8aa9d64e[$v8282c7dd58][$pb0f85b2f] = (!isset($pb025a7be[$pb0f85b2f]) && file_exists($pb0f85b2f)) || $pb025a7be[$pb0f85b2f] ? array( "file_id" => CMSPresentationLayerHandler::getFilePathId($v188b4f5fa6, $pb0f85b2f), "created_time" => filectime($pb0f85b2f), "modified_time" => filemtime($pb0f85b2f), "status" => true, ) : false; $v2e8aa9d64e[$v8282c7dd58][$v303bc2287d] = (!isset($pb025a7be[$v303bc2287d]) && file_exists($v303bc2287d)) || $pb025a7be[$v303bc2287d] ? array( "file_id" => CMSPresentationLayerHandler::getFilePathId($v188b4f5fa6, $v303bc2287d), "created_time" => filectime($v303bc2287d), "modified_time" => filemtime($v303bc2287d), "status" => true, ) : false; } return true; } private static function mf9fe3791ee77($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v53c35d2106, &$v53a57f1353, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pa7b9f5d0, $v3069750558, $pea98b5ae, $v8282c7dd58, $v876c18d646, &$v2e8aa9d64e, $v657ab10c9c, $v29fec2ceaa) { $v90a8c6325d = false; if ($v53c35d2106) foreach ($v53c35d2106 as $pd69fb7d0 => $v65c04b81d2) if ($v65c04b81d2["user_type_id"] == UserUtil::PUBLIC_USER_TYPE_ID) { $v90a8c6325d = true; break; } if (!$v90a8c6325d) { if (!self::$v9ddaebbf03) self::$v9ddaebbf03 = CMSPresentationUIAutomaticFilesHandler::getActivityIdByName($v188b4f5fa6, "access"); if (!self::$v4a8e028ce8) self::$v4a8e028ce8 = CMSPresentationUIAutomaticFilesHandler::getObjectTypeIdByName($v188b4f5fa6, "page"); if (!self::$v9ddaebbf03 || !self::$v4a8e028ce8) return false; self::me909c45e0d7f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pea98b5ae, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e); self::f2cbf3afaf3($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pea98b5ae, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e); self::f781129e499($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pea98b5ae, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e); self::f4dc49c1ef7($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pea98b5ae, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e); self::mf0a36b0e9c15($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pea98b5ae, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e, self::$v9ddaebbf03, self::$v4a8e028ce8); if (!self::$v6a5af55cf8) return false; $v7a0901953c = $v3a0582127b = false; foreach ($v53a57f1353["regions_blocks"] as $v1758c645b6) { if (strtolower($v1758c645b6["region"]) == "head") $v3a0582127b = $v1758c645b6["region"]; if ($v1758c645b6["block"] == self::$v6a5af55cf8 && !$v1758c645b6["project"]) $v7a0901953c = true; } if (!$v7a0901953c) array_unshift($v53a57f1353["regions_blocks"], array("region" => $v3a0582127b ? $v3a0582127b : "Content", "block" => self::$v6a5af55cf8)); $pf0c1244d = !empty(self::$v6b8ab5a551); self::mc04b2fe0fe84($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $v3069750558, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e, $v53a57f1353, $v29fec2ceaa); if ($pa7b9f5d0) { $pa7b9f5d0 = is_array($pa7b9f5d0) ? $pa7b9f5d0 : explode(",", $pa7b9f5d0); $v9dc0fee8e7 = array(); foreach ($pa7b9f5d0 as $v6bbd1726b0) if ($v6bbd1726b0) $v9dc0fee8e7[] = array("user_type_id" => $v6bbd1726b0); $v1be3785911 = !empty(self::$v2b9248d393); $v1efa942227 = !empty(self::$v5f60005f1d); $pc4e858d2 = !empty(self::$v1d5dafbccf); $v64e05a047e = !empty(self::$pf9a2cdcb); self::mc0d2db3432f1($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $v3069750558, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e, $v53a57f1353, $v29fec2ceaa); self::f861170db20($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $v3069750558, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e, $v53a57f1353, $v29fec2ceaa); self::f6f2ae1b3af($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $v3069750558, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e, $v53a57f1353, $v29fec2ceaa); self::f58b1a7de31($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $v3069750558, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e, $v53a57f1353, $v29fec2ceaa); } if (!$pffbf7c43 || $pffbf7c43 == 1) { if (self::$v6b8ab5a551 && !self::f379796b4dc($v188b4f5fa6, $v53c35d2106, self::$v6b8ab5a551, !$pf0c1244d)) return false; if ($v9dc0fee8e7) { if (self::$v2b9248d393 && !self::f379796b4dc($v188b4f5fa6, $v9dc0fee8e7, self::$v2b9248d393, !$v1be3785911)) return false; if (self::$v5f60005f1d && !self::f379796b4dc($v188b4f5fa6, $v9dc0fee8e7, self::$v5f60005f1d, !$v1efa942227)) return false; if (self::$v1d5dafbccf && !self::f379796b4dc($v188b4f5fa6, $v9dc0fee8e7, self::$v1d5dafbccf, !$pc4e858d2)) return false; if (self::$pf9a2cdcb && !self::f379796b4dc($v188b4f5fa6, $v9dc0fee8e7, self::$pf9a2cdcb, !$v64e05a047e)) return false; } } } return true; } private static function f379796b4dc($v188b4f5fa6, $v53c35d2106, $v657ab10c9c, $v5878da8adc = true) { if ($v657ab10c9c) { $v9a84a79e2e = str_replace(APP_PATH, "", $v188b4f5fa6->getEntityPath($v657ab10c9c)); $v259567174e = HashCode::getHashCodePositive($v9a84a79e2e); $v5c1c342594 = !$v5878da8adc || CMSPresentationUIAutomaticFilesHandler::deleteUserTypeActivityObjects($v188b4f5fa6, self::$v9ddaebbf03, self::$v4a8e028ce8, $v259567174e); if ($v53c35d2106) { $pb3d337d8 = array(); $v7061e6b8d0 = CMSPresentationUIAutomaticFilesHandler::getUserTypeActivityObjectsByObject($v188b4f5fa6, self::$v4a8e028ce8, $v259567174e); if ($v7061e6b8d0) foreach ($v7061e6b8d0 as $v6f2a8d0047) if ($v6f2a8d0047["activity_id"] == self::$v9ddaebbf03) $pb3d337d8[] = $v6f2a8d0047["user_type_id"]; foreach ($v53c35d2106 as $pd69fb7d0 => $v65c04b81d2) if (!in_array($v65c04b81d2["user_type_id"], $pb3d337d8) && !CMSPresentationUIAutomaticFilesHandler::insertUserTypeActivityObject($v188b4f5fa6, $v65c04b81d2["user_type_id"], self::$v9ddaebbf03, self::$v4a8e028ce8, $v259567174e)) $v5c1c342594 = false; } CMSPresentationUIAutomaticFilesHandler::removeAllUserTypeActivitySessionsCache($v188b4f5fa6); return $v5c1c342594; } } private static function f65372eab6c($v188b4f5fa6, $pef34936b, $v657ab10c9c) { if ($v657ab10c9c) { $v9a84a79e2e = str_replace(APP_PATH, "", $v188b4f5fa6->getEntityPath($v657ab10c9c)); $v259567174e = HashCode::getHashCodePositive($v9a84a79e2e); $v5c1c342594 = true; $pc2116854 = CMSPresentationUIAutomaticFilesHandler::getAvailableActivities($v188b4f5fa6); if ($pc2116854) foreach ($pc2116854 as $v2a62bd1b82 => $v61e83a4c1c) if ($v2a62bd1b82 != self::$v9ddaebbf03) if (!CMSPresentationUIAutomaticFilesHandler::deleteUserTypeActivityObjects($v188b4f5fa6, $v2a62bd1b82, self::$v4a8e028ce8, $v259567174e)) $v5c1c342594 = false; $v349566b6cb = array(); $v7061e6b8d0 = CMSPresentationUIAutomaticFilesHandler::getUserTypeActivityObjectsByObject($v188b4f5fa6, self::$v4a8e028ce8, $v259567174e); if ($v7061e6b8d0) foreach ($v7061e6b8d0 as $v6f2a8d0047) $v349566b6cb[] = $v6f2a8d0047["activity_id"] . "_" . $v6f2a8d0047["user_type_id"]; if ($pef34936b) foreach ($pef34936b as $pd69fb7d0 => $v76032145ee) if (is_numeric($v76032145ee["user_type_id"]) && is_numeric($v76032145ee["activity_id"]) && !in_array($v76032145ee["activity_id"] . "_" . $v76032145ee["user_type_id"], $v349566b6cb)) if (!CMSPresentationUIAutomaticFilesHandler::insertUserTypeActivityObject($v188b4f5fa6, $v76032145ee["user_type_id"], $v76032145ee["activity_id"], self::$v4a8e028ce8, $v259567174e)) $v5c1c342594 = false; CMSPresentationUIAutomaticFilesHandler::removeAllUserTypeActivitySessionsCache($v188b4f5fa6); return $v5c1c342594; } } private static function f65082ba966($v188b4f5fa6, $v3ae34feaaf, $pdd397f0a, $v2a62bd1b82, $v0a035c60aa, $v3fab52f440) { if ($pdd397f0a && is_dir($pdd397f0a)) { $v6ee393d9fb = scandir($pdd397f0a); $pdd397f0a .= substr($pdd397f0a, -1) == "/" ? "" : "/"; if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if (substr($v7dffdb5a5b, -4) == ".php") { $v6490ea3a15 = file_get_contents("$pdd397f0a$v7dffdb5a5b"); $v7959970a41 = preg_match('/->( *)createBlock( *)\(( *)("|\')user\/validate_user_activity("|\')( *),( *)/', $v6490ea3a15) && preg_match('/"activity_id"( *)=>( *)("|\'|)' . $v2a62bd1b82 . '("|\'|)( *)(,|\n|\)| )/u', $v6490ea3a15) && preg_match('/"object_type_id"( *)=>( *)("|\'|)' . $v0a035c60aa . '("|\'|)( *)(,|\n|\)| )/u', $v6490ea3a15) && preg_match('/"object_id"( *)=>( *)("|\'|)' . str_replace('$', '\$', $v3fab52f440) . '("|\'|)( *)(,|\n|\)| )/u', $v6490ea3a15); if ($v7959970a41) { $v4876e08d4b = $v188b4f5fa6->getBlocksPath(); $v4876e08d4b .= substr($v4876e08d4b, -1) == "/" ? "" : "/"; $v4876e08d4b = substr("$pdd397f0a$v7dffdb5a5b", strlen($v4876e08d4b), -4); $v4876e08d4b = preg_replace("/^\/+/", "", $v4876e08d4b); return $v4876e08d4b; } } else if ($v7dffdb5a5b != "." && $v7dffdb5a5b != ".." && is_dir("$pdd397f0a$v7dffdb5a5b")) { $v29fec2ceaa = self::f65082ba966($v188b4f5fa6, $v3ae34feaaf, "$pdd397f0a$v7dffdb5a5b/", $v2a62bd1b82, $v0a035c60aa, $v3fab52f440); if ($v29fec2ceaa) return $v29fec2ceaa; } } return false; } private static function mf0a36b0e9c15($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, &$v2e8aa9d64e, $v2a62bd1b82, $v0a035c60aa) { $pb025a7be = array(); if (!self::$v6a5af55cf8) { $pb5cb1627 = '$entity_path'; $v4876e08d4b = $v188b4f5fa6->getBlocksPath() . $pf6b7bac7; $v29fec2ceaa = self::f65082ba966($v188b4f5fa6, $v3ae34feaaf, $v4876e08d4b, $v2a62bd1b82, $v0a035c60aa, $pb5cb1627); if (!$v29fec2ceaa) { $v29fec2ceaa = $pf6b7bac7 . "_validate_access"; $v29fec2ceaa = preg_replace("/^\/+/", "", $v29fec2ceaa); do { $v7959970a41 = file_exists($v188b4f5fa6->getBlockPath($v29fec2ceaa)); if ($v7959970a41) $v29fec2ceaa .= "_" . rand(0, 10000); } while($v7959970a41); $pec316626 = self::f4dc49c1ef7($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v6d17a5248e = '<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"activity_id" => "' . $v2a62bd1b82 . '",
	"object_type_id" => "' . $v0a035c60aa . '",
	"object_id" => ' . $pb5cb1627 . ',
	"object_id_page_level" => "",
	"group" => "",
	"group_page_level" => "",
	"validation_condition_type" => "",
	"validation_condition" => "",
	"validation_action" => "do_nothing",
	"validation_message" => "",
	"validation_redirect" => "",
	"validation_blocks_execution" => "do_nothing",
	"non_validation_action" => "' . ($pec316626 ? "alert_message_and_redirect" : "alert_message_and_die") . '",
	"non_validation_message" => "You do not have permission to access to this page. Please login with another user with enough permissions.",
	"non_validation_redirect" => "' . ($pec316626 ? '{$project_url_prefix}' . $pec316626 . "?hide=1" : "") . '",
	"non_validation_blocks_execution" => "do_nothing",
	"style_type" => "",
	"block_class" => "validate_access",
	"css" => "",
	"js" => "",
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("user/validate_user_activity", $block_id, $block_settings[$block_id]);
?>'; $v5c1c342594 = CMSPresentationUIAutomaticFilesHandler::saveBlockCode($v188b4f5fa6, $v29fec2ceaa, $v6d17a5248e, true, $pb025a7be); } } self::$v6a5af55cf8 = $v29fec2ceaa; } self::f6c35fb6a4f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v6a5af55cf8, $pb025a7be); return self::$v6a5af55cf8; } private static function f4dc49c1ef7($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, &$v2e8aa9d64e) { $pb025a7be = array(); if (!self::$v4a8c422aa6) { $v4876e08d4b = $v188b4f5fa6->getBlocksPath() . $pf6b7bac7; $v29fec2ceaa = self::f047a35af58($v188b4f5fa6, $v4876e08d4b, "user/logout"); if (!$v29fec2ceaa) { $v29fec2ceaa = $pf6b7bac7 . "_logout"; $v29fec2ceaa = preg_replace("/^\/+/", "", $v29fec2ceaa); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v40b70e70c3 = ''; $v35727ff744 = self::f781129e499($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e); if ($v35727ff744) $v40b70e70c3 = 'var hide = \\"{$_GET[\'hide\']}\\";

if (hide == \\"1\\")
    document.location = \'{$project_url_prefix}' . $v35727ff744 . '\';
else {
    window.onload = function() {
	   setTimeout(function() {
		  document.location = \'{$project_url_prefix}' . $v35727ff744 . '\';
	   }, 5000);    
    };
}'; $v6d17a5248e = '<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"style_type" => "template",
	"validation_action" => "' . ($v35727ff744 ? "show_message" : "show_message_and_redirect") . '",
	"validation_message" => "You are now logged out. <br>To go back to your login page please click <a href=\'{$project_url_prefix}' . $v35727ff744 . '\'>here</a>",
	"validation_class" => "logout",
	"validation_redirect" => "' . ($v35727ff744 ? '{$project_url_prefix}' . $v35727ff744 : "") . '",
	"validation_ttl" => ' . ($v35727ff744 ? '($_GET["hide"] ? 0 : 5) . ""' : '""') . ',
	"validation_blocks_execution" => "do_nothing",
	"non_validation_action" => "",
	"non_validation_message" => "",
	"non_validation_class" => "logout",
	"non_validation_redirect" => "",
	"non_validation_blocks_execution" => "do_nothing",
	"domain" => "",
	"client_id" => "",
	"css" => "",
	"js" => "' . $v40b70e70c3 . '",
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("user/logout", $block_id, $block_settings[$block_id]);
?>'; $v5c1c342594 = CMSPresentationUIAutomaticFilesHandler::saveBlockCode($v188b4f5fa6, $v29fec2ceaa, $v6d17a5248e, true, $pb025a7be); } } self::$v4a8c422aa6 = $v29fec2ceaa; } if (!self::$pec316626 && self::$v4a8c422aa6) { $v6b817be097 = $v188b4f5fa6->getEntitiesPath() . $pf6b7bac7; $v657ab10c9c = self::f7247476993($v188b4f5fa6, $v6b817be097, self::$v4a8c422aa6); if (!$v657ab10c9c) { $v657ab10c9c = $pf6b7bac7 . "_logout"; $v657ab10c9c = preg_replace("/^\/+/", "", $v657ab10c9c); if (!$pffbf7c43 || $pffbf7c43 == 1) { CMSPresentationUIAutomaticFilesHandler::createAndSaveEntityCode($v188b4f5fa6, $v657ab10c9c, array( "includes" => array( array("path" => $pa9e9a096, "once" => 1), ), "regions_blocks" => array( array("region" => "Content", "block" => self::$v4a8c422aa6) ), "template_params" => array( "Page Title" => "Logout" ), "template" => $pe7333513, ), false, $pb025a7be); self::mfb3060dfd640($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v657ab10c9c, $pb025a7be); } } self::$pec316626 = $v657ab10c9c; } self::f6c35fb6a4f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v4a8c422aa6, $pb025a7be); self::f6f0e405f89($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$pec316626, $pb025a7be); return self::$pec316626; } private static function f781129e499($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, &$v2e8aa9d64e) { $pb025a7be = array(); if (!self::$pa82053f1) { $v4876e08d4b = $v188b4f5fa6->getBlocksPath() . $pf6b7bac7; $v29fec2ceaa = self::f047a35af58($v188b4f5fa6, $v4876e08d4b, "user/login"); if (!$v29fec2ceaa) { $v29fec2ceaa = $pf6b7bac7 . "_login"; $v29fec2ceaa = preg_replace("/^\/+/", "", $v29fec2ceaa); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v79b4f08546 = self::me909c45e0d7f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e); $pd2ecca20 = $v79b4f08546 ? '{$project_url_prefix}' . $v79b4f08546 : ''; $v3b4cb56c97 = self::f2cbf3afaf3($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e); $v40fc0a97fb = $v3b4cb56c97 ? '{$project_url_prefix}' . $v3b4cb56c97 : ''; $v104be4f335 = $pf6b7bac7; if (!file_exists($v188b4f5fa6->getEntityPath($pf6b7bac7 . "index")) && is_array($v876c18d646)) { $v7b6747e3f6 = false; foreach ($v876c18d646 as $v7dffdb5a5b => $v723fbd1f9b) { $v719e8fa8a2 = array("/src/entity/", "/src/block/"); foreach ($v719e8fa8a2 as $v8007f64d4d) { $v391cc249fc = $v8007f64d4d . $pf6b7bac7; $pbd1bc7b0 = strpos($v7dffdb5a5b, $v391cc249fc); if ($pbd1bc7b0 !== false) { $v104be4f335 .= substr($v7dffdb5a5b, $pbd1bc7b0 + strlen($v391cc249fc)); $v7b6747e3f6 = true; break 2; } } } if (!$v7b6747e3f6) { $v6b817be097 = $v188b4f5fa6->getEntitiesPath() . $pf6b7bac7; $v7dffdb5a5b = self::mb66bca2ffe70($v6b817be097); if ($v7dffdb5a5b) $v104be4f335 .= $v7dffdb5a5b; } } $v0db7a47634 = '{$project_url_prefix}' . $v104be4f335; $v6d17a5248e = '<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"maximum_login_attempts_to_block_user" => 5,
	"show_captcha" => 1,
	"maximum_login_attempts_to_show_captcha" => 3,
	"redirect_page_url" => "' . $v0db7a47634 . '",
	"forgot_credentials_page_url" => "' . $v40fc0a97fb . '",
	"register_page_url" => "' . $pd2ecca20 . '",
	"style_type" => "template",
	"block_class" => "login",
	"css" => "",
	"js" => "",
	"do_not_encrypt_password" => 1,
	"show_username" => 1,
	"show_password" => 1,
	"username_default_value" => "",
	"password_default_value" => "",
	"register_attribute_label" => "Register?",
	"forgot_credentials_attribute_label" => "Forgot Credentials?",
	"fields" => array(
		"username" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "username",
				"label" => array(
					"type" => "label",
					"value" => "Username: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "your username",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "Username cannot be undefined.",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"password" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "password",
				"label" => array(
					"type" => "label",
					"value" => "Password: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "password",
					"class" => "",
					"place_holder" => "your password",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "Password cannot be undefined.",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
	),
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("user/login", $block_id, $block_settings[$block_id]);
?>'; $v5c1c342594 = CMSPresentationUIAutomaticFilesHandler::saveBlockCode($v188b4f5fa6, $v29fec2ceaa, $v6d17a5248e, true, $pb025a7be); } } self::$pa82053f1 = $v29fec2ceaa; } if (!self::$v35727ff744 && self::$pa82053f1) { $v6b817be097 = $v188b4f5fa6->getEntitiesPath() . $pf6b7bac7; $v657ab10c9c = self::f7247476993($v188b4f5fa6, $v6b817be097, self::$pa82053f1); if (!$v657ab10c9c) { $v657ab10c9c = $pf6b7bac7 . "_login"; $v657ab10c9c = preg_replace("/^\/+/", "", $v657ab10c9c); if (!$pffbf7c43 || $pffbf7c43 == 1) { CMSPresentationUIAutomaticFilesHandler::createAndSaveEntityCode($v188b4f5fa6, $v657ab10c9c, array( "includes" => array( array("path" => $pa9e9a096, "once" => 1), ), "regions_blocks" => array( array("region" => "Content", "block" => self::$pa82053f1) ), "template_params" => array( "Page Title" => "Login" ), "template" => $pe7333513, ), false, $pb025a7be); self::mfb3060dfd640($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v657ab10c9c, $pb025a7be); } } self::$v35727ff744 = $v657ab10c9c; } self::f6c35fb6a4f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$pa82053f1, $pb025a7be); self::f6f0e405f89($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v35727ff744, $pb025a7be); return self::$v35727ff744; } private static function mb66bca2ffe70($pa32be502) { $v6ee393d9fb = array_diff(scandir($pa32be502), array('..', '.')); foreach ($v6ee393d9fb as $v7dffdb5a5b) { $pff5830e4 = substr($v7dffdb5a5b, 0, 1); if ($pff5830e4 != "_" && $pff5830e4 != "." && preg_match("/\.php[0-9]*$/i", $v7dffdb5a5b) && !is_dir("$pa32be502$v7dffdb5a5b")) return pathinfo($v7dffdb5a5b, PATHINFO_FILENAME); } foreach ($v6ee393d9fb as $v7dffdb5a5b) { if (is_dir("$pa32be502$v7dffdb5a5b")) { $v1b08a89324 = self::mb66bca2ffe70("$pa32be502$v7dffdb5a5b/"); if ($v1b08a89324) return "$v7dffdb5a5b/$v1b08a89324"; } } return null; } private static function me909c45e0d7f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, &$v2e8aa9d64e) { $pb025a7be = array(); if (!self::$v058a242493) { $v4876e08d4b = $v188b4f5fa6->getBlocksPath() . $pf6b7bac7; $v29fec2ceaa = self::f047a35af58($v188b4f5fa6, $v4876e08d4b, "user/register"); if (!$v29fec2ceaa) { $v29fec2ceaa = $pf6b7bac7 . "_register"; $v29fec2ceaa = preg_replace("/^\/+/", "", $v29fec2ceaa); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v886a182a6f = self::mf7c96febaf93(); $v6d17a5248e = '<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"user_type_id" => "' . UserUtil::REGULAR_USER_TYPE_ID . '",
	"redirect_page_url" => "",
	"do_not_encrypt_password" => 1,
	"style_type" => "template",
	"block_class" => "register",
	"css" => "",
	"js" => "",
	"show_username" => "1",
	"username_default_value" => "",
	"fields" => array(
		"username" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "username",
				"label" => array(
					"type" => "label",
					"value" => "Username: ",
				),
				"input" => array(
					"type" => "text",
					"allow_null" => "",
					"place_holder" => "your username",
				)
			),
		),
		"password" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "password",
				"label" => array(
					"type" => "label",
					"value" => "Password: ",
				),
				"input" => array(
					"type" => "password",
					"allow_null" => "",
					"place_holder" => "your password",
				)
			),
		),
		"name" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "name",
				"label" => array(
					"type" => "label",
					"value" => "Name: ",
				),
				"input" => array(
					"type" => "text",
					"allow_null" => "",
					"place_holder" => "your name",
				)
			),
		),
		"email" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "email",
				"label" => array(
					"type" => "label",
					"value" => "Email: ",
				),
				"input" => array(
					"type" => "text",
					"allow_null" => 1,
					"place_holder" => "your email",
				)
			),
		),
		"security_question_1" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_question_1",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 1: ",
				),
				"input" => array(
					"type" => "select",
					"options" => ' . $v886a182a6f . ',
				)
			),
		),
		"security_answer_1" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_answer_1",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 1: ",
				),
				"input" => array(
					"type" => "text",
					"allow_null" => "",
				)
			),
		),
		"security_question_2" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_question_2",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 2: ",
				),
				"input" => array(
					"type" => "select",
					"options" => ' . $v886a182a6f . ',
				)
			),
		),
		"security_answer_2" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_answer_2",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 2: ",
				),
				"input" => array(
					"type" => "text",
					"allow_null" => "",
				)
			),
		),
		"security_question_3" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_question_3",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 3: ",
				),
				"input" => array(
					"type" => "select",
					"options" => ' . $v886a182a6f . ',
				)
			),
		),
		"security_answer_3" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_answer_3",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 3: ",
				),
				"input" => array(
					"type" => "text",
					"allow_null" => "",
				)
			),
		),
	),
	"show_password" => 1,
	"password_default_value" => "",
	"show_name" => 1,
	"name_default_value" => "",
	"show_email" => 1,
	"email_default_value" => "",
	"show_security_question_1" => 1,
	"security_question_1_default_value" => "",
	"show_security_answer_1" => 1,
	"security_answer_1_default_value" => "",
	"show_security_question_2" => 1,
	"security_question_2_default_value" => "",
	"show_security_answer_2" => 1,
	"security_answer_2_default_value" => "",
	"show_security_question_3" => 1,
	"security_question_3_default_value" => "",
	"show_security_answer_3" => 1,
	"security_answer_3_default_value" => "",
	"user_environments" => array(),
	"object_to_objects" => array(),
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("user/register", $block_id, $block_settings[$block_id]);
?>'; $v5c1c342594 = CMSPresentationUIAutomaticFilesHandler::saveBlockCode($v188b4f5fa6, $v29fec2ceaa, $v6d17a5248e, true, $pb025a7be); } } self::$v058a242493 = $v29fec2ceaa; } if (!self::$v79b4f08546 && self::$v058a242493) { $v6b817be097 = $v188b4f5fa6->getEntitiesPath() . $pf6b7bac7; $v657ab10c9c = self::f7247476993($v188b4f5fa6, $v6b817be097, self::$v058a242493); if (!$v657ab10c9c) { $v657ab10c9c = $pf6b7bac7 . "_register"; $v657ab10c9c = preg_replace("/^\/+/", "", $v657ab10c9c); if (!$pffbf7c43 || $pffbf7c43 == 1) { CMSPresentationUIAutomaticFilesHandler::createAndSaveEntityCode($v188b4f5fa6, $v657ab10c9c, array( "includes" => array( array("path" => $pa9e9a096, "once" => 1), ), "regions_blocks" => array( array("region" => "Content", "block" => self::$v058a242493) ), "template_params" => array( "Page Title" => "Register" ), "template" => $pe7333513, ), false, $pb025a7be); self::mfb3060dfd640($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v657ab10c9c, $pb025a7be); } } self::$v79b4f08546 = $v657ab10c9c; } self::f6c35fb6a4f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v058a242493, $pb025a7be); self::f6f0e405f89($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v79b4f08546, $pb025a7be); return self::$v79b4f08546; } private static function f2cbf3afaf3($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, &$v2e8aa9d64e) { $pb025a7be = array(); if (!self::$v86098bc5f1) { $v4876e08d4b = $v188b4f5fa6->getBlocksPath() . $pf6b7bac7; $v29fec2ceaa = self::f047a35af58($v188b4f5fa6, $v4876e08d4b, "user/forgot_credentials"); if (!$v29fec2ceaa) { $v29fec2ceaa = $pf6b7bac7 . "_forgot_credentials"; $v29fec2ceaa = preg_replace("/^\/+/", "", $v29fec2ceaa); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v6d17a5248e = '<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"show_recover_username_through_email" => 0,
	"show_recover_username_through_email_and_security_questions" => 0,
	"show_recover_password_through_email" => 0,
	"show_recover_password_through_security_questions" => 1,
	"admin_email" => "",
	"smtp_host" => "",
	"smtp_port" => "",
	"smtp_secure" => "",
	"smtp_user" => "",
	"smtp_pass" => "",
	"redirect_page_url" => "",
	"username_attribute_label" => "Username",
	"password_attribute_label" => "Password",
	"do_not_encrypt_password" => 1,
	"user_environments" => array(),
	"style_type" => "template",
	"block_class" => "forgot_credentials",
	"css" => "",
	"js" => "",
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("user/forgot_credentials", $block_id, $block_settings[$block_id]);
?>'; $v5c1c342594 = CMSPresentationUIAutomaticFilesHandler::saveBlockCode($v188b4f5fa6, $v29fec2ceaa, $v6d17a5248e, true, $pb025a7be); } } self::$v86098bc5f1 = $v29fec2ceaa; } if (!self::$v3b4cb56c97 && self::$v86098bc5f1) { $v6b817be097 = $v188b4f5fa6->getEntitiesPath() . $pf6b7bac7; $v657ab10c9c = self::f7247476993($v188b4f5fa6, $v6b817be097, self::$v86098bc5f1); if (!$v657ab10c9c) { $v657ab10c9c = $pf6b7bac7 . "_forgot_credentials"; $v657ab10c9c = preg_replace("/^\/+/", "", $v657ab10c9c); if (!$pffbf7c43 || $pffbf7c43 == 1) { CMSPresentationUIAutomaticFilesHandler::createAndSaveEntityCode($v188b4f5fa6, $v657ab10c9c, array( "includes" => array( array("path" => $pa9e9a096, "once" => 1), ), "regions_blocks" => array( array("region" => "Content", "block" => self::$v86098bc5f1) ), "template_params" => array( "Page Title" => "Forgot Credentials" ), "template" => $pe7333513, ), false, $pb025a7be); self::mfb3060dfd640($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v657ab10c9c, $pb025a7be); } } self::$v3b4cb56c97 = $v657ab10c9c; } self::f6c35fb6a4f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v86098bc5f1, $pb025a7be); self::f6f0e405f89($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v3b4cb56c97, $pb025a7be); return self::$v3b4cb56c97; } private static function mc04b2fe0fe84($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, &$v2e8aa9d64e, $pd12066a9, $pd488531f) { $pb025a7be = array(); if (!self::$v7444124b34) { $v4876e08d4b = $v188b4f5fa6->getBlocksPath() . $pf6b7bac7; $v29fec2ceaa = self::f047a35af58($v188b4f5fa6, $v4876e08d4b, "user/edit_profile"); if (!$v29fec2ceaa) { $v29fec2ceaa = $pf6b7bac7 . "_edit_profile"; $v29fec2ceaa = preg_replace("/^\/+/", "", $v29fec2ceaa); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v886a182a6f = self::mf7c96febaf93(); $v6d17a5248e = '<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"do_not_encrypt_password" => 1,
	"show_username" => "1",
	"username_default_value" => "",
	"fields" => array(
		"username" => array(
			"field" => array(
				"class" => "username",
				"label" => array(
					"value" => "Username: ",
				),
				"input" => array(
					"place_holder" => "your username",
					"href" => "",
					"target" => "",
					"src" => "",
					"extra_html" => "",
					"confirmed" => "",
					"confirmmessage" => "",
					"checkallownull" => "",
					"checkmessage" => "",
					"checktype" => "",
					"checktyperegex" => "",
					"checkminlen" => "",
					"checkmaxlen" => "",
					"checkminvalue" => "",
					"checkmaxvalue" => "",
					"checkminwords" => "",
					"checkmaxwords" => "",
				),
			)
		),
		"current_password" => array(
			"field" => array(
				"class" => "current_password",
				"label" => array(
					"value" => "Current Password: ",
				),
				"input" => array(
					"place_holder" => "your current password",
					"href" => "",
					"target" => "",
					"src" => "",
					"extra_html" => "",
					"confirmed" => "",
					"confirmmessage" => "",
					"checkallownull" => "",
					"checkmessage" => "",
					"checktype" => "",
					"checktyperegex" => "",
					"checkminlen" => "",
					"checkmaxlen" => "",
					"checkminvalue" => "",
					"checkmaxvalue" => "",
					"checkminwords" => "",
					"checkmaxwords" => "",
				)
			),
		),
		"password" => array(
			"field" => array(
				"class" => "password",
				"label" => array(
					"value" => "Password: ",
				),
				"input" => array(
					"place_holder" => "your new password",
					"allow_null" => 1,
					"href" => "",
					"target" => "",
					"src" => "",
					"extra_html" => "",
					"confirmed" => "",
					"confirmmessage" => "",
					"checkallownull" => "",
					"checkmessage" => "",
					"checktype" => "",
					"checktyperegex" => "",
					"checkminlen" => "",
					"checkmaxlen" => "",
					"checkminvalue" => "",
					"checkmaxvalue" => "",
					"checkminwords" => "",
					"checkmaxwords" => "",
				)
			),
		),
		"name" => array(
			"field" => array(
				"class" => "name",
				"label" => array(
					"value" => "Name: ",
				),
				"input" => array(
					"place_holder" => "your name",
					"href" => "",
					"target" => "",
					"src" => "",
					"extra_html" => "",
					"confirmed" => "",
					"confirmmessage" => "",
					"checkallownull" => "",
					"checkmessage" => "",
					"checktype" => "",
					"checktyperegex" => "",
					"checkminlen" => "",
					"checkmaxlen" => "",
					"checkminvalue" => "",
					"checkmaxvalue" => "",
					"checkminwords" => "",
					"checkmaxwords" => "",
				)
			),
		),
		"email" => array(
			"field" => array(
				"class" => "email",
				"label" => array(
					"value" => "Email: ",
				),
				"input" => array(
					"place_holder" => "your email",
					"allow_null" => 1,
					"href" => "",
					"target" => "",
					"src" => "",
					"extra_html" => "",
					"confirmed" => "",
					"confirmmessage" => "",
					"checkallownull" => "",
					"checkmessage" => "",
					"checktype" => "",
					"checktyperegex" => "",
					"checkminlen" => "",
					"checkmaxlen" => "",
					"checkminvalue" => "",
					"checkmaxvalue" => "",
					"checkminwords" => "",
					"checkmaxwords" => "",
				)
			),
		),
		"security_question_1" => array(
			"field" => array(
				"class" => "security_question_1",
				"label" => array(
					"value" => "Security Question 1: ",
				),
				"input" => array(
					"href" => "",
					"target" => "",
					"src" => "",
					"extra_html" => "",
					"options" => ' . $v886a182a6f . ',
					"confirmed" => "",
					"confirmmessage" => "",
					"checkallownull" => "",
					"checkmessage" => "",
					"checktype" => "",
					"checktyperegex" => "",
					"checkminlen" => "",
					"checkmaxlen" => "",
					"checkminvalue" => "",
					"checkmaxvalue" => "",
					"checkminwords" => "",
					"checkmaxwords" => "",
				)
			),
		),
		"security_answer_1" => array(
			"field" => array(
				"class" => "security_answer_1",
				"label" => array(
					"value" => "Security Answer 1: ",
				),
				"input" => array(
					"href" => "",
					"target" => "",
					"src" => "",
					"extra_html" => "",
					"confirmed" => "",
					"confirmmessage" => "",
					"checkallownull" => "",
					"checkmessage" => "",
					"checktype" => "",
					"checktyperegex" => "",
					"checkminlen" => "",
					"checkmaxlen" => "",
					"checkminvalue" => "",
					"checkmaxvalue" => "",
					"checkminwords" => "",
					"checkmaxwords" => "",
				)
			),
		),
		"security_question_2" => array(
			"field" => array(
				"class" => "security_question_2",
				"label" => array(
					"value" => "Security Question 2: ",
				),
				"input" => array(
					"href" => "",
					"target" => "",
					"src" => "",
					"extra_html" => "",
					"options" => ' . $v886a182a6f . ',
					"confirmed" => "",
					"confirmmessage" => "",
					"checkallownull" => "",
					"checkmessage" => "",
					"checktype" => "",
					"checktyperegex" => "",
					"checkminlen" => "",
					"checkmaxlen" => "",
					"checkminvalue" => "",
					"checkmaxvalue" => "",
					"checkminwords" => "",
					"checkmaxwords" => "",
				)
			),
		),
		"security_answer_2" => array(
			"field" => array(
				"class" => "security_answer_2",
				"label" => array(
					"value" => "Security Answer 2: ",
				),
				"input" => array(
					"href" => "",
					"target" => "",
					"src" => "",
					"extra_html" => "",
					"confirmed" => "",
					"confirmmessage" => "",
					"checkallownull" => "",
					"checkmessage" => "",
					"checktype" => "",
					"checktyperegex" => "",
					"checkminlen" => "",
					"checkmaxlen" => "",
					"checkminvalue" => "",
					"checkmaxvalue" => "",
					"checkminwords" => "",
					"checkmaxwords" => "",
				)
			),
		),
		"security_question_3" => array(
			"field" => array(
				"class" => "security_question_3",
				"label" => array(
					"value" => "Security Question 3: ",
				),
				"input" => array(
					"href" => "",
					"target" => "",
					"src" => "",
					"extra_html" => "",
					"options" => ' . $v886a182a6f . ',
					"confirmed" => "",
					"confirmmessage" => "",
					"checkallownull" => "",
					"checkmessage" => "",
					"checktype" => "",
					"checktyperegex" => "",
					"checkminlen" => "",
					"checkmaxlen" => "",
					"checkminvalue" => "",
					"checkmaxvalue" => "",
					"checkminwords" => "",
					"checkmaxwords" => "",
				)
			),
		),
		"security_answer_3" => array(
			"field" => array(
				"class" => "security_answer_3",
				"label" => array(
					"value" => "Security Answer 3: ",
				),
				"input" => array(
					"href" => "",
					"target" => "",
					"src" => "",
					"extra_html" => "",
					"confirmed" => "",
					"confirmmessage" => "",
					"checkallownull" => "",
					"checkmessage" => "",
					"checktype" => "",
					"checktyperegex" => "",
					"checkminlen" => "",
					"checkmaxlen" => "",
					"checkminvalue" => "",
					"checkmaxvalue" => "",
					"checkminwords" => "",
					"checkmaxwords" => "",
				)
			),
		),
	),
	"show_current_password" => "1",
	"current_password_default_value" => "",
	"show_password" => "1",
	"password_default_value" => "",
	"show_name" => "1",
	"name_default_value" => "",
	"show_email" => "1",
	"email_default_value" => "",
	"show_security_question_1" => "1",
	"security_question_1_default_value" => "",
	"show_security_answer_1" => "1",
	"security_answer_1_default_value" => "",
	"show_security_question_2" => "1",
	"security_question_2_default_value" => "",
	"show_security_answer_2" => "1",
	"security_answer_2_default_value" => "",
	"show_security_question_3" => "1",
	"security_question_3_default_value" => "",
	"show_security_answer_3" => "1",
	"security_answer_3_default_value" => "",
	"allow_update" => "1",
	"on_update_ok_message" => "",
	"on_update_ok_action" => "show_message",
	"on_update_ok_redirect_url" => "",
	"on_update_error_message" => "",
	"on_update_error_action" => "show_message",
	"on_update_error_redirect_url" => "",
	"on_undefined_object_ok_message" => "",
	"on_undefined_object_ok_action" => "",
	"on_undefined_object_ok_redirect_url" => "",
	"on_undefined_object_error_message" => "",
	"on_undefined_object_error_action" => "show_message_and_stop",
	"on_undefined_object_error_redirect_url" => "",
	"style_type" => "template",
	"block_class" => "edit_profile",
	"css" => "",
	"js" => "",
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("user/edit_profile", $block_id, $block_settings[$block_id]);
?>'; $v5c1c342594 = CMSPresentationUIAutomaticFilesHandler::saveBlockCode($v188b4f5fa6, $v29fec2ceaa, $v6d17a5248e, true, $pb025a7be); } } self::$v7444124b34 = $v29fec2ceaa; } if (!self::$v6b8ab5a551 && self::$v7444124b34) { $v6b817be097 = $v188b4f5fa6->getEntitiesPath() . $pf6b7bac7; $v657ab10c9c = self::f7247476993($v188b4f5fa6, $v6b817be097, self::$v7444124b34); if (!$v657ab10c9c) { $v657ab10c9c = $pf6b7bac7 . "_edit_profile"; $v657ab10c9c = preg_replace("/^\/+/", "", $v657ab10c9c); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v53a57f1353 = $pd12066a9; if ($pa9e9a096) { $v7959970a41 = false; foreach ($v53a57f1353["includes"] as $v2d22d85b1f) if (strpos($v2d22d85b1f["path"], $pa9e9a096) !== false) { $v7959970a41 = true; break; } if (!$v7959970a41) $v53a57f1353["includes"][] = array("path" => $pa9e9a096, "once" => 1); } foreach ($v53a57f1353["regions_blocks"] as $pe5c5e2fe => $v1758c645b6) if ($v1758c645b6["block"] == $pd488531f && !$v1758c645b6["project"]) unset($v53a57f1353["regions_blocks"][$pe5c5e2fe]); $v53a57f1353["template_params"]["Page Title"] = "Edit Profile"; $v53a57f1353["regions_blocks"][] = array("region" => "Content", "block" => self::$v7444124b34); $v53a57f1353["template"] = $pe7333513; CMSPresentationUIAutomaticFilesHandler::createAndSaveEntityCode($v188b4f5fa6, $v657ab10c9c, $v53a57f1353, false, $pb025a7be); self::mfb3060dfd640($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v657ab10c9c, $pb025a7be); } } self::$v6b8ab5a551 = $v657ab10c9c; } self::f6c35fb6a4f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v7444124b34, $pb025a7be); self::f6f0e405f89($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v6b8ab5a551, $pb025a7be); return self::$v6b8ab5a551; } private static function mc0d2db3432f1($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, &$v2e8aa9d64e, $pd12066a9, $pd488531f) { $pb025a7be = array(); if (!self::$v552676fb3d) { $v4876e08d4b = $v188b4f5fa6->getBlocksPath() . $pf6b7bac7; $v29fec2ceaa = self::f047a35af58($v188b4f5fa6, $v4876e08d4b, "user/list_and_edit_users_with_user_types"); if (!$v29fec2ceaa) { $v29fec2ceaa = $pf6b7bac7 . "_list_and_edit_users"; $v29fec2ceaa = preg_replace("/^\/+/", "", $v29fec2ceaa); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v886a182a6f = self::mf7c96febaf93(); $v6d17a5248e = '<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"query_type" => "all_users",
	"object_type_id" => 1,
	"object_id" => "",
	"group" => "",
	"user_type_id" => 2,
	"do_not_encrypt_password" => 1,
	"style_type" => "template",
	"block_class" => "list_and_edit_users",
	"table_class" => "table-bordered table table-striped table-hover table-sm",
	"rows_class" => "",
	"css" => "",
	"js" => "window.addEventListener(\\"load\\", function() {
    $(\\".module_list.module_list_and_edit_users .list_items .list_container\\").addClass(\\"table-responsive\\");
});

function onListItemFieldKeyPress(elm) {
	\$(elm).parent().closest(\\"tr\\").find(\\" > .selected_item > input\\").prop(\\"checked\\", true);
}

function onListAddUser(elm) {
	if (!new_user_html) {
		alert(\\"Insert action not allowed!\\");
		return false;
	}
	
	var table = \$(elm).parent().closest(\\"table\\");
	var tbody = table.children(\\"tbody\\")[0] ? table.children(\\"tbody\\") : table;
	var new_index = 0;
	
	var inputs = tbody.find(\\"input, textarea, select\\");
	\$.each(inputs, function(idx, input) {
		if ((\\"\\" + input.name).substr(0, 6) == \\"users[\\") {
			var input_index = parseInt(input.name.substr(6, input.name.indexOf(\\"]\\") - 6));
			
			if (input_index > new_index)
				new_index = input_index;
		}
	});
	new_index++;
	
	var new_item = \$(new_user_html.replace(/#idx#/g, new_index)); //new_user_html is a variable that will be created automatically with the correspondent html.
	
	tbody.append(new_item);
	
	if (typeof onNewHtml == \\"function\\") 
		onNewHtml(elm, new_item);
	
	return new_item;
}

function onListRemoveNewUser(elm) {
	if (confirm(\\"Do you wish to remove this user?\\"))
		\$(elm).parent().closest(\\"tr\\").remove();
}

function onSaveMultipleUsers(btn) {
	var tbody = \$(btn).parent().closest(\\".list_items\\").find(\\" > .list_container > table > tbody\\");
	prepareSelectedUsersForAction(tbody);
	
	return true;
}

function onDeleteMultipleUsers(btn, msg) {
	if (!msg || confirm(msg)) {
		var tbody = \$(btn).parent().closest(\\".list_items\\").find(\\" > .list_container > table > tbody\\");
		prepareSelectedUsersForAction(tbody);
		return true;
	}
	
	return false;
}

function prepareSelectedUsersForAction(tbody) {
	if (tbody[0]) {
		var trs = tbody.children(\\"tr\\");
		
		\$.each(trs, function(idx, tr) {
			tr = \$(tr);
			var is_selected = tr.find(\\"td.selected_item input[type=checkbox]\\").is(\\":checked\\");
			var inputs = tr.find(\\"td:not(.selected_item)\\").find(\\"input, select, textarea\\");
			
			\$.each(inputs, function(idy, input) {
				input = \$(input);
				
				if (is_selected) {
					if (input[0].hasAttribute(\\"orig-data-allow-null\\"))
						input.attr(\\"data-allow-null\\", input.attr(\\"orig-data-allow-null\\"));
					
					if (input[0].hasAttribute(\"orig-data-validation-type\"))
						input.attr(\\"data-validation-type\\", input.attr(\\"orig-data-validation-type\\"));
				}
				else {
					if (input[0].hasAttribute(\\"data-allow-null\\")) {
						input.attr(\\"orig-data-allow-null\\", input.attr(\\"data-allow-null\\"));
						input.removeAttr(\\"data-allow-null\\");
					}
					
					if (input[0].hasAttribute(\\"data-validation-type\\")) {
						input.attr(\\"orig-data-validation-type\\", input.attr(\\"data-validation-type\\"));
						input.removeAttr(\\"data-validation-type\\");
					}
				}
			});
		});
	}
}
	 	",
	"fields" => array(
		"selected_item" => array(
			"field" => array(
				"class" => "selected_item",
				"label" => array(
					"type" => "label",
					"value" => "",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "<span class=\\"glyphicon glyphicon-plus icon add\\" onClick=\\"onListAddUser(this)\\">Add</span>",
				),
				"input" => array(
					"type" => "checkbox",
					"class" => "",
					"value" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"user_type_ids" => array(
			"field" => array(
				"class" => "user_type_ids",
				"label" => array(
					"type" => "label",
					"value" => "User Types",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "select",
					"class" => "",
					"value" => "#[\\$idx][user_type_ids]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(' . '
						array(
							"name" => "onChange",
							"value" => "onListItemFieldKeyPress(this)",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "User Types",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"user_id" => array(
			"field" => array(
				"class" => "user_id",
				"label" => array(
					"type" => "label",
					"value" => "User Id",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\\$idx][user_id]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "User Id",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"username" => array(
			"field" => array(
				"class" => "username",
				"label" => array(
					"type" => "label",
					"value" => "Username",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"value" => "#[\\$idx][username]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onKeyPress",
							"value" => "onListItemFieldKeyPress(this)",
						),
						array(
							"name" => "onChange",
							"value" => "onListItemFieldKeyPress(this)",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "Username",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"password" => array(
			"field" => array(
				"class" => "password",
				"label" => array(
					"type" => "label",
					"value" => "Password",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "password",
					"class" => "",
					"value" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onKeyPress",
							"value" => "onListItemFieldKeyPress(this)",
						),
						array(
							"name" => "onChange",
							"value" => "onListItemFieldKeyPress(this)",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "Password",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"name" => array(
			"field" => array(
				"class" => "name",
				"label" => array(
					"type" => "label",
					"value" => "Name",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"value" => "#[\\$idx][name]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onKeyPress",
							"value" => "onListItemFieldKeyPress(this)",
						),
						array(
							"name" => "onChange",
							"value" => "onListItemFieldKeyPress(this)",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "Name",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"email" => array(
			"field" => array(
				"class" => "email",
				"label" => array(
					"type" => "label",
					"value" => "Email",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "email",
					"class" => "",
					"value" => "#[\\$idx][email]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onKeyPress",
							"value" => "onListItemFieldKeyPress(this)",
						),
						array(
							"name" => "onChange",
							"value" => "onListItemFieldKeyPress(this)",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "Email",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_question_1" => array(
			"field" => array(
				"class" => "security_question_1",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 1",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "select",
					"class" => "",
					"value" => "#[\\$idx][security_question_1]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"options" => ' . $v886a182a6f . ',
					"extra_attributes" => array(
						array(
							"name" => "onChange",
							"value" => "onListItemFieldKeyPress(this)",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "Security Question 1",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_answer_1" => array(
			"field" => array(
				"class" => "security_answer_1",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 1",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"value" => "#[\\$idx][security_answer_1]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onKeyPress",
							"value" => "onListItemFieldKeyPress(this)",
						),
						array(
							"name" => "onChange",
							"value" => "onListItemFieldKeyPress(this)",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "Security Answer 1",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_question_2" => array(
			"field" => array(
				"class" => "security_question_2",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 2",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "select",
					"class" => "",
					"value" => "#[\\$idx][security_question_2]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"options" => ' . $v886a182a6f . ',
					"extra_attributes" => array(
						array(
							"name" => "onChange",
							"value" => "onListItemFieldKeyPress(this)",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "Security Question 2",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_answer_2" => array(
			"field" => array(
				"class" => "security_answer_2",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 2",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"value" => "#[\\$idx][security_answer_2]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onKeyPress",
							"value" => "onListItemFieldKeyPress(this)",
						),
						array(
							"name" => "onChange",
							"value" => "onListItemFieldKeyPress(this)",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "Security Answer 2",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_question_3" => array(
			"field" => array(
				"class" => "security_question_3",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 3",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "select",
					"class" => "",
					"value" => "#[\\$idx][security_question_3]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"options" => ' . $v886a182a6f . ',
					"extra_attributes" => array(
						array(
							"name" => "onChange",
							"value" => "onListItemFieldKeyPress(this)",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "Security Question 3",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_answer_3" => array(
			"field" => array(
				"class" => "security_answer_3",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 3",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"value" => "#[\\$idx][security_answer_3]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onKeyPress",
							"value" => "onListItemFieldKeyPress(this)",
						),
						array(
							"name" => "onChange",
							"value" => "onListItemFieldKeyPress(this)",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "Security Answer 3",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"created_date" => array(
			"field" => array(
				"class" => "created_date",
				"label" => array(
					"type" => "label",
					"value" => "Created Date",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "datetime",
					"class" => "",
					"value" => "#[\\$idx][created_date]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onKeyPress",
							"value" => "onListItemFieldKeyPress(this)",
						),
						array(
							"name" => "onChange",
							"value" => "onListItemFieldKeyPress(this)",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "Created Date",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"modified_date" => array(
			"field" => array(
				"class" => "modified_date",
				"label" => array(
					"type" => "label",
					"value" => "Modified Date",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "datetime",
					"class" => "",
					"value" => "#[\\$idx][modified_date]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onKeyPress",
							"value" => "onListItemFieldKeyPress(this)",
						),
						array(
							"name" => "onChange",
							"value" => "onListItemFieldKeyPress(this)",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "Modified Date",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
	),
	"show_user_id" => 1,
	"user_id_search_value" => "",
	"show_username" => 1,
	"username_search_value" => "",
	"show_password" => 0,
	"password_search_value" => "",
	"show_name" => 1,
	"name_search_value" => "",
	"show_email" => 1,
	"email_search_value" => "",
	"show_security_question_1" => 0,
	"security_question_1_search_value" => "",
	"show_security_answer_1" => 0,
	"security_answer_1_search_value" => "",
	"show_security_question_2" => 0,
	"security_question_2_search_value" => "",
	"show_security_answer_2" => 0,
	"security_answer_2_search_value" => "",
	"show_security_question_3" => 0,
	"security_question_3_search_value" => "",
	"show_security_answer_3" => 0,
	"security_answer_3_search_value" => "",
	"show_created_date" => 0,
	"created_date_search_value" => "",
	"show_modified_date" => 0,
	"modified_date_search_value" => "",
	"show_user_type_ids" => 1,
	"user_type_ids_search_value" => "",
	"allow_insertion" => 1,
	"on_insert_ok_message" => "Users saved successfully.",
	"on_insert_ok_action" => "show_message",
	"on_insert_ok_redirect_url" => "",
	"on_insert_error_message" => "Error: Users not saved successfully!",
	"on_insert_error_action" => "show_message",
	"on_insert_error_redirect_url" => "",
	"buttons" => array(
		"insert" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "button_save submit_button",
				"label" => array(
					"type" => "label",
					"value" => "",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "submit",
					"class" => "",
					"value" => "Add",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onClick",
							"value" => "return onSaveMultipleUsers(this);",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"update" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "button_save submit_button",
				"label" => array(
					"type" => "label",
					"value" => "",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "submit",
					"class" => "",
					"value" => "Save",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onClick",
							"value" => "return onSaveMultipleUsers(this);",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"delete" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "button_delete submit_button",
				"label" => array(
					"type" => "label",
					"value" => "",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "submit",
					"class" => "",
					"value" => "Delete",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onClick",
							"value" => "return onDeleteMultipleUsers(this, \'" . translateProjectText($EVC, \'Do you wish to delete this item?\') . "\');",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
	),
	"allow_update" => 1,
	"on_update_ok_message" => "Users saved successfully.",
	"on_update_ok_action" => "show_message",
	"on_update_ok_redirect_url" => "",
	"on_update_error_message" => "Error: Users not saved successfully!",
	"on_update_error_action" => "show_message",
	"on_update_error_redirect_url" => "",
	"allow_deletion" => 1,
	"on_delete_ok_message" => "Users deleted successfully.",
	"on_delete_ok_action" => "show_message",
	"on_delete_ok_redirect_url" => "",
	"on_delete_error_message" => "Error: Users not deleted successfully!",
	"on_delete_error_action" => "show_message",
	"on_delete_error_redirect_url" => "",
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("user/list_and_edit_users_with_user_types", $block_id, $block_settings[$block_id]);
?>'; $v5c1c342594 = CMSPresentationUIAutomaticFilesHandler::saveBlockCode($v188b4f5fa6, $v29fec2ceaa, $v6d17a5248e, true, $pb025a7be); } } self::$v552676fb3d = $v29fec2ceaa; } if (!self::$v2b9248d393 && self::$v552676fb3d) { $v6b817be097 = $v188b4f5fa6->getEntitiesPath() . $pf6b7bac7; $v657ab10c9c = self::f7247476993($v188b4f5fa6, $v6b817be097, self::$v552676fb3d); if (!$v657ab10c9c) { $v657ab10c9c = $pf6b7bac7 . "_list_and_edit_users"; $v657ab10c9c = preg_replace("/^\/+/", "", $v657ab10c9c); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v53a57f1353 = $pd12066a9; if ($pa9e9a096) { $v7959970a41 = false; foreach ($v53a57f1353["includes"] as $v2d22d85b1f) if (strpos($v2d22d85b1f["path"], $pa9e9a096) !== false) { $v7959970a41 = true; break; } if (!$v7959970a41) $v53a57f1353["includes"][] = array("path" => $pa9e9a096, "once" => 1); } foreach ($v53a57f1353["regions_blocks"] as $pe5c5e2fe => $v1758c645b6) if ($v1758c645b6["block"] == $pd488531f && !$v1758c645b6["project"]) unset($v53a57f1353["regions_blocks"][$pe5c5e2fe]); $v53a57f1353["template_params"]["Page Title"] = "List and Edit Users"; $v53a57f1353["regions_blocks"][] = array("region" => "Content", "block" => self::$v552676fb3d); $v53a57f1353["template"] = $pe7333513; CMSPresentationUIAutomaticFilesHandler::createAndSaveEntityCode($v188b4f5fa6, $v657ab10c9c, $v53a57f1353, false, $pb025a7be); self::mfb3060dfd640($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v657ab10c9c, $pb025a7be); } } self::$v2b9248d393 = $v657ab10c9c; } self::f6c35fb6a4f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v552676fb3d, $pb025a7be); self::f6f0e405f89($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v2b9248d393, $pb025a7be); return self::$v2b9248d393; } private static function f861170db20($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, &$v2e8aa9d64e, $pd12066a9, $pd488531f) { $pb025a7be = array(); if (!self::$v298bc3731e) { $v4876e08d4b = $v188b4f5fa6->getBlocksPath() . $pf6b7bac7; $v29fec2ceaa = self::f047a35af58($v188b4f5fa6, $v4876e08d4b, "user/list_users"); if (!$v29fec2ceaa) { $v29fec2ceaa = $pf6b7bac7 . "_list_users"; $v29fec2ceaa = preg_replace("/^\/+/", "", $v29fec2ceaa); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v1d5dafbccf = self::f6f2ae1b3af($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e, $pd12066a9, $pd488531f); $pfc76a450 = $v1d5dafbccf ? '{$project_url_prefix}' . $v1d5dafbccf : ''; $pf9a2cdcb = self::f58b1a7de31($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, $v2e8aa9d64e, $pd12066a9, $pd488531f); $v1249978b15 = $pf9a2cdcb ? '{$project_url_prefix}' . $pf9a2cdcb : ''; $v6d17a5248e = '<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"query_type" => "all_users",
	"object_type_id" => 1,
	"object_id" => "",
	"group" => "",
	"user_type_id" => 2,
	"style_type" => "template",
	"block_class" => "list_users",
	"table_class" => "table-bordered table table-striped table-hover table-sm",
	"rows_class" => "",
	"css" => "",
	"js" => "window.addEventListener(\\"load\\", function() {
    var list_items = $(\\".module_list.module_list_users .list_items\\").first();
    ' . ($v1249978b15 ? 'list_items.prepend(\'<div class=\\"buttons mb-4 text-right\\"><div class=\\"button\\"><a class=\\"btn btn-primary\\" href=\\"' . $v1249978b15 . '\\">Add User</a></div></div>\');' : '') . '
    list_items.children(\\".list_container\\").addClass(\\"table-responsive\\");
});",
	"show_user_id" => 1,
	"user_id_search_value" => "",
	"fields" => array(
		"user_id" => array(
			"field" => array(
				"class" => "user_id",
				"label" => array(
					"type" => "label",
					"value" => "User Id",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\\$idx][user_id]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"username" => array(
			"field" => array(
				"class" => "username",
				"label" => array(
					"type" => "label",
					"value" => "Username",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\\$idx][username]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"password" => array(
			"field" => array(
				"class" => "password",
				"label" => array(
					"type" => "label",
					"value" => "Password",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\\$idx][password]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"name" => array(
			"field" => array(
				"class" => "name",
				"label" => array(
					"type" => "label",
					"value" => "Name",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\\$idx][name]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"email" => array(
			"field" => array(
				"class" => "email",
				"label" => array(
					"type" => "label",
					"value" => "Email",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\\$idx][email]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_question_1" => array(
			"field" => array(
				"class" => "security_question_1",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 1",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\\$idx][security_question_1]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_answer_1" => array(
			"field" => array(
				"class" => "security_answer_1",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 1",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\\$idx][security_answer_1]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_question_2" => array(
			"field" => array(
				"class" => "security_question_2",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 2",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\\$idx][security_question_2]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_answer_2" => array(
			"field" => array(
				"class" => "security_answer_2",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 2",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\\$idx][security_answer_2]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_question_3" => array(
			"field" => array(
				"class" => "security_question_3",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 3",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\\$idx][security_question_3]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_answer_3" => array(
			"field" => array(
				"class" => "security_answer_3",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 3",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\\$idx][security_answer_3]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"created_date" => array(
			"field" => array(
				"class" => "created_date",
				"label" => array(
					"type" => "label",
					"value" => "Created Date",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\\$idx][created_date]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"modified_date" => array(
			"field" => array(
				"class" => "modified_date",
				"label" => array(
					"type" => "label",
					"value" => "Modified Date",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "label",
					"class" => "",
					"value" => "#[\$idx][modified_date]#",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
	),
	"show_username" => 1,
	"username_search_value" => "",
	"show_password" => 0,
	"password_search_value" => "",
	"show_name" => 1,
	"name_search_value" => "",
	"show_email" => 1,
	"email_search_value" => "",
	"show_security_question_1" => 0,
	"security_question_1_search_value" => "",
	"show_security_answer_1" => 0,
	"security_answer_1_search_value" => "",
	"show_security_question_2" => 0,
	"security_question_2_search_value" => "",
	"show_security_answer_2" => 0,
	"security_answer_2_search_value" => "",
	"show_security_question_3" => 0,
	"security_question_3_search_value" => "",
	"show_security_answer_3" => 0,
	"security_answer_3_search_value" => "",
	"show_created_date" => 0,
	"created_date_search_value" => "",
	"show_modified_date" => 0,
	"modified_date_search_value" => "",
	"show_edit_button" => 1,
	"edit_page_url" => "' . $pfc76a450 . '",
	"show_delete_button" => "",
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("user/list_users", $block_id, $block_settings[$block_id]);
?>'; $v5c1c342594 = CMSPresentationUIAutomaticFilesHandler::saveBlockCode($v188b4f5fa6, $v29fec2ceaa, $v6d17a5248e, true, $pb025a7be); } } self::$v298bc3731e = $v29fec2ceaa; } if (!self::$v5f60005f1d && self::$v298bc3731e) { $v6b817be097 = $v188b4f5fa6->getEntitiesPath() . $pf6b7bac7; $v657ab10c9c = self::f7247476993($v188b4f5fa6, $v6b817be097, self::$v298bc3731e); if (!$v657ab10c9c) { $v657ab10c9c = $pf6b7bac7 . "_list_users"; $v657ab10c9c = preg_replace("/^\/+/", "", $v657ab10c9c); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v53a57f1353 = $pd12066a9; if ($pa9e9a096) { $v7959970a41 = false; foreach ($v53a57f1353["includes"] as $v2d22d85b1f) if (strpos($v2d22d85b1f["path"], $pa9e9a096) !== false) { $v7959970a41 = true; break; } if (!$v7959970a41) $v53a57f1353["includes"][] = array("path" => $pa9e9a096, "once" => 1); } foreach ($v53a57f1353["regions_blocks"] as $pe5c5e2fe => $v1758c645b6) if ($v1758c645b6["block"] == $pd488531f && !$v1758c645b6["project"]) unset($v53a57f1353["regions_blocks"][$pe5c5e2fe]); $v53a57f1353["template_params"]["Page Title"] = "List Users"; $v53a57f1353["regions_blocks"][] = array("region" => "Content", "block" => self::$v298bc3731e); $v53a57f1353["template"] = $pe7333513; CMSPresentationUIAutomaticFilesHandler::createAndSaveEntityCode($v188b4f5fa6, $v657ab10c9c, $v53a57f1353, false, $pb025a7be); self::mfb3060dfd640($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v657ab10c9c, $pb025a7be); } } self::$v5f60005f1d = $v657ab10c9c; } self::f6c35fb6a4f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v298bc3731e, $pb025a7be); self::f6f0e405f89($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v5f60005f1d, $pb025a7be); return self::$v5f60005f1d; } private static function f6f2ae1b3af($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, &$v2e8aa9d64e, $pd12066a9, $pd488531f) { $pb025a7be = array(); if (!self::$v50e29b88ec) { $v4876e08d4b = $v188b4f5fa6->getBlocksPath() . $pf6b7bac7; $v29fec2ceaa = self::f047a35af58($v188b4f5fa6, $v4876e08d4b, "user/edit_user", "/\"allow_update\"(\s*)=>(\s*)([1-9]+|true)/i"); if (!$v29fec2ceaa) { $v29fec2ceaa = $pf6b7bac7 . "_edit_user"; $v29fec2ceaa = preg_replace("/^\/+/", "", $v29fec2ceaa); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v886a182a6f = self::mf7c96febaf93(); $v6d17a5248e = '<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"do_not_encrypt_password" => 1,
	"style_type" => "template",
	"block_class" => "edit_user",
	"css" => "",
	"js" => "window.addEventListener(\\"load\\", function() {
    var user_id_elm = $(\\".module_edit.module_edit_user .form_field.user_id.form-group\\");
    user_id_elm.children(\\"label\\").addClass(\\"col-12 col-sm-4 col-lg-2\\");
    user_id_elm.children(\\"span\\").addClass(\\"col-12 col-sm-8 col-lg-10 form-control\\");
});",
	"show_user_id" => 1,
	"user_id_default_value" => "",
	"fields" => array(
		"user_id" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "user_id",
				"label" => array(
					"type" => "label",
					"value" => "User Id: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "bigint",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"user_type_id" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "user_type_id",
				"label" => array(
					"type" => "label",
					"value" => "User Type Id: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "bigint",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"username" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "username",
				"label" => array(
					"type" => "label",
					"value" => "Username: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"password" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "password",
				"label" => array(
					"type" => "label",
					"value" => "Password: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "password",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"name" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "name",
				"label" => array(
					"type" => "label",
					"value" => "Name: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"email" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "email",
				"label" => array(
					"type" => "label",
					"value" => "Email: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "Invalid Email format.",
					"validation_type" => "email",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_question_1" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_question_1",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 1: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "select",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"options" => ' . $v886a182a6f . ',
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_answer_1" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_answer_1",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 1: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_question_2" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_question_2",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 2: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "select",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"options" => ' . $v886a182a6f . ',
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_answer_2" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_answer_2",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 2: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_question_3" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_question_3",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 3: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "select",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"options" => ' . $v886a182a6f . ',
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_answer_3" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_answer_3",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 3: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
	),
	"show_user_type_id" => 1,
	"user_type_id_default_value" => "",
	"show_username" => 1,
	"username_default_value" => "",
	"show_password" => 1,
	"password_default_value" => "",
	"show_name" => 1,
	"name_default_value" => "",
	"show_email" => 1,
	"email_default_value" => "",
	"show_security_question_1" => 1,
	"security_question_1_default_value" => "",
	"show_security_answer_1" => 1,
	"security_answer_1_default_value" => "",
	"show_security_question_2" => 1,
	"security_question_2_default_value" => "",
	"show_security_answer_2" => 1,
	"security_answer_2_default_value" => "",
	"show_security_question_3" => 1,
	"security_question_3_default_value" => "",
	"show_security_answer_3" => 1,
	"security_answer_3_default_value" => "",
	"allow_view" => 1,
	"allow_insertion" => 0,
	"on_insert_ok_message" => "Users saved successfully.",
	"on_insert_ok_action" => "show_message_and_stop",
	"on_insert_ok_redirect_url" => "",
	"on_insert_error_message" => "Error: Users not saved successfully!",
	"on_insert_error_action" => "show_message",
	"on_insert_error_redirect_url" => "",
	"buttons" => array(
		"insert" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "button_save submit_button",
				"label" => array(
					"type" => "label",
					"value" => "",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "submit",
					"class" => "",
					"value" => "Add",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"update" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "button_save submit_button",
				"label" => array(
					"type" => "label",
					"value" => "",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "submit",
					"class" => "",
					"value" => "Save",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"delete" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "button_delete submit_button",
				"label" => array(
					"type" => "label",
					"value" => "",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "submit",
					"class" => "",
					"value" => "Delete",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onClick",
							"value" => "return confirm(\'" . translateProjectText($EVC, \'Do you wish to delete this item?\') . "\');",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
	),
	"allow_update" => 1,
	"on_update_ok_message" => "Users saved successfully.",
	"on_update_ok_action" => "show_message",
	"on_update_ok_redirect_url" => "",
	"on_update_error_message" => "Error: Users not saved successfully!",
	"on_update_error_action" => "show_message",
	"on_update_error_redirect_url" => "",
	"allow_deletion" => 1,
	"on_delete_ok_message" => "Users deleted successfully.",
	"on_delete_ok_action" => "show_message_and_stop",
	"on_delete_ok_redirect_url" => "",
	"on_delete_error_message" => "Error: Users not deleted successfully!",
	"on_delete_error_action" => "show_message",
	"on_delete_error_redirect_url" => "",
	"on_undefined_object_ok_message" => "",
	"on_undefined_object_ok_action" => "",
	"on_undefined_object_ok_redirect_url" => "",
	"on_undefined_object_error_message" => "",
	"on_undefined_object_error_action" => "show_message_and_stop",
	"on_undefined_object_error_redirect_url" => "",
	"user_environments" => array(
		"",
	),
	"object_to_objects" => array(
		array(
			"object_type_id" => "",
			"object_id" => "",
			"group" => "",
		),
	),
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("user/edit_user", $block_id, $block_settings[$block_id]);
?>'; $v5c1c342594 = CMSPresentationUIAutomaticFilesHandler::saveBlockCode($v188b4f5fa6, $v29fec2ceaa, $v6d17a5248e, true, $pb025a7be); } } self::$v50e29b88ec = $v29fec2ceaa; } if (!self::$v1d5dafbccf && self::$v50e29b88ec) { $v6b817be097 = $v188b4f5fa6->getEntitiesPath() . $pf6b7bac7; $v657ab10c9c = self::f7247476993($v188b4f5fa6, $v6b817be097, self::$v50e29b88ec); if (!$v657ab10c9c) { $v657ab10c9c = $pf6b7bac7 . "_edit_user"; $v657ab10c9c = preg_replace("/^\/+/", "", $v657ab10c9c); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v53a57f1353 = $pd12066a9; if ($pa9e9a096) { $v7959970a41 = false; foreach ($v53a57f1353["includes"] as $v2d22d85b1f) if (strpos($v2d22d85b1f["path"], $pa9e9a096) !== false) { $v7959970a41 = true; break; } if (!$v7959970a41) $v53a57f1353["includes"][] = array("path" => $pa9e9a096, "once" => 1); } foreach ($v53a57f1353["regions_blocks"] as $pe5c5e2fe => $v1758c645b6) if ($v1758c645b6["block"] == $pd488531f && !$v1758c645b6["project"]) unset($v53a57f1353["regions_blocks"][$pe5c5e2fe]); $v53a57f1353["template_params"]["Page Title"] = "Edit User"; $v53a57f1353["regions_blocks"][] = array("region" => "Content", "block" => self::$v50e29b88ec); $v53a57f1353["template"] = $pe7333513; CMSPresentationUIAutomaticFilesHandler::createAndSaveEntityCode($v188b4f5fa6, $v657ab10c9c, $v53a57f1353, false, $pb025a7be); self::mfb3060dfd640($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v657ab10c9c, $pb025a7be); } } self::$v1d5dafbccf = $v657ab10c9c; } self::f6c35fb6a4f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v50e29b88ec, $pb025a7be); self::f6f0e405f89($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$v1d5dafbccf, $pb025a7be); return self::$v1d5dafbccf; } private static function f58b1a7de31($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $pa9e9a096, $pf6b7bac7, $pe7333513, $v8282c7dd58, $v876c18d646, &$v2e8aa9d64e, $pd12066a9, $pd488531f) { $pb025a7be = array(); if (!self::$pff4c8506) { $v4876e08d4b = $v188b4f5fa6->getBlocksPath() . $pf6b7bac7; $v29fec2ceaa = self::f047a35af58($v188b4f5fa6, $v4876e08d4b, "user/add_user", "/\"allow_insertion\"(\s*)=>(\s*)([1-9]+|true)/i"); if (!$v29fec2ceaa) { $v29fec2ceaa = $pf6b7bac7 . "_add_user"; $v29fec2ceaa = preg_replace("/^\/+/", "", $v29fec2ceaa); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v886a182a6f = self::mf7c96febaf93(); $v6d17a5248e = '<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"do_not_encrypt_password" => 1,
	"style_type" => "template",
	"block_class" => "add_user",
	"css" => "",
	"js" => "",
	"show_user_id" => 0,
	"user_id_default_value" => "",
	"fields" => array(
		"user_id" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "user_id",
				"label" => array(
					"type" => "label",
					"value" => "User Id: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "bigint",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"user_type_id" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "user_type_id",
				"label" => array(
					"type" => "label",
					"value" => "User Type Id: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "bigint",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"username" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "username",
				"label" => array(
					"type" => "label",
					"value" => "Username: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"password" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "password",
				"label" => array(
					"type" => "label",
					"value" => "Password: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "password",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"name" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "name",
				"label" => array(
					"type" => "label",
					"value" => "Name: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"email" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "email",
				"label" => array(
					"type" => "label",
					"value" => "Email: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "Invalid Email format.",
					"validation_type" => "email",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_question_1" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_question_1",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 1: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "select",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"options" => ' . $v886a182a6f . ',
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_answer_1" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_answer_1",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 1: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_question_2" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_question_2",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 2: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "select",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"options" => ' . $v886a182a6f . ',
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_answer_2" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_answer_2",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 2: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_question_3" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_question_3",
				"label" => array(
					"type" => "label",
					"value" => "Security Question 3: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "select",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"options" => ' . $v886a182a6f . ',
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"security_answer_3" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "security_answer_3",
				"label" => array(
					"type" => "label",
					"value" => "Security Answer 3: ",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "text",
					"class" => "",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => "",
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
	),
	"show_user_type_id" => 1,
	"user_type_id_default_value" => "",
	"show_username" => 1,
	"username_default_value" => "",
	"show_password" => 1,
	"password_default_value" => "",
	"show_name" => 1,
	"name_default_value" => "",
	"show_email" => 1,
	"email_default_value" => "",
	"show_security_question_1" => 1,
	"security_question_1_default_value" => "",
	"show_security_answer_1" => 1,
	"security_answer_1_default_value" => "",
	"show_security_question_2" => 1,
	"security_question_2_default_value" => "",
	"show_security_answer_2" => 1,
	"security_answer_2_default_value" => "",
	"show_security_question_3" => 1,
	"security_question_3_default_value" => "",
	"show_security_answer_3" => 1,
	"security_answer_3_default_value" => "",
	"allow_view" => 1,
	"allow_insertion" => 1,
	"on_insert_ok_message" => "Users saved successfully.",
	"on_insert_ok_action" => "show_message_and_stop",
	"on_insert_ok_redirect_url" => "",
	"on_insert_error_message" => "Error: Users not saved successfully!",
	"on_insert_error_action" => "show_message",
	"on_insert_error_redirect_url" => "",
	"buttons" => array(
		"insert" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "button_save submit_button",
				"label" => array(
					"type" => "label",
					"value" => "",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "submit",
					"class" => "",
					"value" => "Add",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"update" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "button_save submit_button",
				"label" => array(
					"type" => "label",
					"value" => "",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "submit",
					"class" => "",
					"value" => "Save",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
		"delete" => array(
			"field" => array(
				"disable_field_group" => "",
				"class" => "button_delete submit_button",
				"label" => array(
					"type" => "label",
					"value" => "",
					"class" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
				),
				"input" => array(
					"type" => "submit",
					"class" => "",
					"value" => "Delete",
					"place_holder" => "",
					"href" => "",
					"target" => "",
					"src" => "",
					"title" => "",
					"previous_html" => "",
					"next_html" => "",
					"extra_attributes" => array(
						array(
							"name" => "onClick",
							"value" => "return confirm(\'" . translateProjectText($EVC, \'Do you wish to delete this item?\') . "\');",
						),
					),
					"confirmation" => "",
					"confirmation_message" => "",
					"allow_null" => 1,
					"allow_javascript" => "",
					"validation_label" => "",
					"validation_message" => "",
					"validation_type" => "",
					"validation_regex" => "",
					"min_length" => "",
					"max_length" => "",
					"min_value" => "",
					"max_value" => "",
					"min_words" => "",
					"max_words" => "",
				),
			),
		),
	),
	"allow_update" => 0,
	"on_update_ok_message" => "Users saved successfully.",
	"on_update_ok_action" => "show_message",
	"on_update_ok_redirect_url" => "",
	"on_update_error_message" => "Error: Users not saved successfully!",
	"on_update_error_action" => "show_message",
	"on_update_error_redirect_url" => "",
	"allow_deletion" => 0,
	"on_delete_ok_message" => "Users deleted successfully.",
	"on_delete_ok_action" => "show_message_and_stop",
	"on_delete_ok_redirect_url" => "",
	"on_delete_error_message" => "Error: Users not deleted successfully!",
	"on_delete_error_action" => "show_message",
	"on_delete_error_redirect_url" => "",
	"on_undefined_object_ok_message" => "",
	"on_undefined_object_ok_action" => "",
	"on_undefined_object_ok_redirect_url" => "",
	"on_undefined_object_error_message" => "",
	"on_undefined_object_error_action" => "show_message_and_stop",
	"on_undefined_object_error_redirect_url" => "",
	"user_environments" => array(
		"",
	),
	"object_to_objects" => array(
		array(
			"object_type_id" => "",
			"object_id" => "",
			"group" => "",
		),
	),
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("user/edit_user", $block_id, $block_settings[$block_id]);
?>'; $v5c1c342594 = CMSPresentationUIAutomaticFilesHandler::saveBlockCode($v188b4f5fa6, $v29fec2ceaa, $v6d17a5248e, true, $pb025a7be); } } self::$pff4c8506 = $v29fec2ceaa; } if (!self::$pf9a2cdcb && self::$pff4c8506) { $v6b817be097 = $v188b4f5fa6->getEntitiesPath() . $pf6b7bac7; $v657ab10c9c = self::f7247476993($v188b4f5fa6, $v6b817be097, self::$pff4c8506); if (!$v657ab10c9c) { $v657ab10c9c = $pf6b7bac7 . "_add_user"; $v657ab10c9c = preg_replace("/^\/+/", "", $v657ab10c9c); if (!$pffbf7c43 || $pffbf7c43 == 1) { $v53a57f1353 = $pd12066a9; if ($pa9e9a096) { $v7959970a41 = false; foreach ($v53a57f1353["includes"] as $v2d22d85b1f) if (strpos($v2d22d85b1f["path"], $pa9e9a096) !== false) { $v7959970a41 = true; break; } if (!$v7959970a41) $v53a57f1353["includes"][] = array("path" => $pa9e9a096, "once" => 1); } foreach ($v53a57f1353["regions_blocks"] as $pe5c5e2fe => $v1758c645b6) if ($v1758c645b6["block"] == $pd488531f && !$v1758c645b6["project"]) unset($v53a57f1353["regions_blocks"][$pe5c5e2fe]); $v53a57f1353["template_params"]["Page Title"] = "Add User"; $v53a57f1353["regions_blocks"][] = array("region" => "Content", "block" => self::$pff4c8506); $v53a57f1353["template"] = $pe7333513; CMSPresentationUIAutomaticFilesHandler::createAndSaveEntityCode($v188b4f5fa6, $v657ab10c9c, $v53a57f1353, false, $pb025a7be); self::mfb3060dfd640($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v657ab10c9c, $pb025a7be); } } self::$pf9a2cdcb = $v657ab10c9c; } self::f6c35fb6a4f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$pff4c8506, $pb025a7be); self::f6f0e405f89($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, $v2e8aa9d64e, self::$pf9a2cdcb, $pb025a7be); return self::$pf9a2cdcb; } private static function mf7c96febaf93() { return '
						array(
							array(
								"value" => "What is the first name of the person you first kissed?",
								"label" => "What is the first name of the person you first kissed?",
							),
							array(
								"value" => "What is the last name of the teacher who gave you your first failing grade?",
								"label" => "What is the last name of the teacher who gave you your first failing grade?",
							),
							array(
								"value" => "What is the name of the place your wedding reception was held?",
								"label" => "What is the name of the place your wedding reception was held?",
							),
							array(
								"value" => "What was the name of your elementary / primary school?",
								"label" => "What was the name of your elementary / primary school?",
							),
							array(
								"value" => "In what city or town does your nearest sibling live?",
								"label" => "In what city or town does your nearest sibling live?",
							),
							array(
								"value" => "What time of the day were you born? (hh:mm)",
								"label" => "What time of the day were you born? (hh:mm)",
							),
						)'; } private static function f6c35fb6a4f($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, &$v2e8aa9d64e, $v29fec2ceaa, $pb025a7be = null) { if ($v29fec2ceaa) { $v303bc2287d = $v188b4f5fa6->getBlockPath($v29fec2ceaa); if ($pffbf7c43 == 2) $v2e8aa9d64e[$v8282c7dd58][$v303bc2287d] = file_exists($v303bc2287d) ? array( "file_id" => CMSPresentationLayerHandler::getFilePathId($v188b4f5fa6, $v303bc2287d), "created_time" => filectime($v303bc2287d), "modified_time" => filemtime($v303bc2287d), "type" => "reserved", ) : array("type" => "reserved"); else if ($pffbf7c43 == 3) $v2e8aa9d64e[$v8282c7dd58][$v303bc2287d] = array( "old_code" => file_exists($v303bc2287d) ? file_get_contents($v303bc2287d) : "", "new_code" => "", ); else if (!$pffbf7c43 || $pffbf7c43 == 1) $v2e8aa9d64e[$v8282c7dd58][$v303bc2287d] = file_exists($v303bc2287d) && (!$pb025a7be || !isset($pb025a7be[$v303bc2287d]) || $pb025a7be[$v303bc2287d]) ? array( "file_id" => CMSPresentationLayerHandler::getFilePathId($v188b4f5fa6, $v303bc2287d), "created_time" => filectime($v303bc2287d), "modified_time" => filemtime($v303bc2287d), "status" => true, ) : false; } } private static function f6f0e405f89($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pffbf7c43, $v8282c7dd58, &$v2e8aa9d64e, $v657ab10c9c, $pb025a7be = null) { if ($v657ab10c9c) { $pb0f85b2f = $v188b4f5fa6->getEntityPath($v657ab10c9c); if ($pffbf7c43 == 2) $v2e8aa9d64e[$v8282c7dd58][$pb0f85b2f] = file_exists($pb0f85b2f) ? array( "file_id" => CMSPresentationLayerHandler::getFilePathId($v188b4f5fa6, $pb0f85b2f), "created_time" => filectime($pb0f85b2f), "modified_time" => filemtime($pb0f85b2f), "type" => "reserved", "hard_coded" => CMSPresentationLayerHandler::isEntityFileHardCoded($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pb0f85b2f), ) : array( "type" => "reserved", "allow_non_authenticated_file" => $v657ab10c9c == self::$pec316626 || $v657ab10c9c == self::$v35727ff744 || $v657ab10c9c == self::$v79b4f08546 || $v657ab10c9c == self::$v3b4cb56c97, ); else if ($pffbf7c43 == 3) $v2e8aa9d64e[$v8282c7dd58][$pb0f85b2f] = array( "old_code" => file_exists($pb0f85b2f) ? file_get_contents($pb0f85b2f) : "", "new_code" => "", ); else if (!$pffbf7c43 || $pffbf7c43 == 1) $v2e8aa9d64e[$v8282c7dd58][$pb0f85b2f] = file_exists($pb0f85b2f) && (!$pb025a7be || !isset($pb025a7be[$pb0f85b2f]) || $pb025a7be[$pb0f85b2f]) ? array( "file_id" => CMSPresentationLayerHandler::getFilePathId($v188b4f5fa6, $pb0f85b2f), "created_time" => filectime($pb0f85b2f), "modified_time" => filemtime($pb0f85b2f), "status" => true, ) : false; } } private static function mfb3060dfd640($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $v657ab10c9c, $pb025a7be) { $pb0f85b2f = $v188b4f5fa6->getEntityPath($v657ab10c9c); if ($pb025a7be[$pb0f85b2f]) { $v9ab35f1f0d = $v188b4f5fa6->getPresentationLayer(); $pa2bba2ac = $v9ab35f1f0d->getLayerPathSetting(); $v2508589a4c = $v9ab35f1f0d->getSelectedPresentationId(); $v124d7bd46a = str_replace($pa2bba2ac, "", $pb0f85b2f); if (strpos($v124d7bd46a, "$v2508589a4c/src/entity/") === 0) CMSPresentationLayerHandler::cacheEntitySaveActionTime($v188b4f5fa6, $v3ae34feaaf, $v29c163a46f, $pb0f85b2f); } } private static function f047a35af58($v188b4f5fa6, $pdd397f0a, $pcd8c70bc, $v92e7f9f1e7 = false) { if ($pdd397f0a && is_dir($pdd397f0a)) { $v6ee393d9fb = scandir($pdd397f0a); $pdd397f0a .= substr($pdd397f0a, -1) == "/" ? "" : "/"; if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if (substr($v7dffdb5a5b, -4) == ".php") { $v6490ea3a15 = file_get_contents("$pdd397f0a$v7dffdb5a5b"); $v7959970a41 = preg_match('/->( *)createBlock( *)\(( *)("|\')' . str_replace("/", "\\/", $pcd8c70bc) . '("|\')( *),( *)/u', $v6490ea3a15); if ($v7959970a41 && $v92e7f9f1e7) { $v92e7f9f1e7 = is_array($v92e7f9f1e7) ? $v92e7f9f1e7 : array($v92e7f9f1e7); foreach ($v92e7f9f1e7 as $v457eb6252b) if (!preg_match($v457eb6252b, $v6490ea3a15)) $v7959970a41 = false; } if ($v7959970a41) { $v4876e08d4b = $v188b4f5fa6->getBlocksPath(); $v4876e08d4b .= substr($v4876e08d4b, -1) == "/" ? "" : "/"; $v4876e08d4b = substr("$pdd397f0a$v7dffdb5a5b", strlen($v4876e08d4b), -4); $v4876e08d4b = preg_replace("/^\/+/", "", $v4876e08d4b); return $v4876e08d4b; } } else if ($v7dffdb5a5b != "." && $v7dffdb5a5b != ".." && is_dir("$pdd397f0a$v7dffdb5a5b")) { $v29fec2ceaa = self::f047a35af58($v188b4f5fa6, "$pdd397f0a$v7dffdb5a5b/", $pcd8c70bc); if ($v29fec2ceaa) return $v29fec2ceaa; } } return false; } private static function f7247476993($v188b4f5fa6, $pdd397f0a, $v29fec2ceaa) { if ($pdd397f0a && is_dir($pdd397f0a)) { $v6ee393d9fb = scandir($pdd397f0a); $pdd397f0a .= substr($pdd397f0a, -1) == "/" ? "" : "/"; if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if (substr($v7dffdb5a5b, -4) == ".php") { $v6490ea3a15 = file_get_contents("$pdd397f0a$v7dffdb5a5b"); $v7959970a41 = preg_match('/(include|include_once|require|require_once)( *)\$EVC( *)->( *)getBlockPath( *)\(( *)"' . str_replace("/", "\\/", $v29fec2ceaa) . '"( *)\)( *)/u', $v6490ea3a15); if ($v7959970a41) { $v6b817be097 = $v188b4f5fa6->getEntitiesPath(); $v6b817be097 .= substr($v6b817be097, -1) == "/" ? "" : "/"; $v6b817be097 = substr("$pdd397f0a$v7dffdb5a5b", strlen($v6b817be097), -4); $v6b817be097 = preg_replace("/^\/+/", "", $v6b817be097); return $v6b817be097; } } else if ($v7dffdb5a5b != "." && $v7dffdb5a5b != ".." && is_dir("$pdd397f0a$v7dffdb5a5b")) { $pc11b7acb = self::f7247476993($v188b4f5fa6, "$pdd397f0a$v7dffdb5a5b/", $v29fec2ceaa); if ($pc11b7acb) return $pc11b7acb; } } return false; } } ?>
