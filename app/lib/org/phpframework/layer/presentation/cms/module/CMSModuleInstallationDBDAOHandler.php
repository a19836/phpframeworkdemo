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

include_once get_lib("org.phpframework.util.xml.MyXML"); include_once get_lib("org.phpframework.util.xml.MyXMLArray"); class CMSModuleInstallationDBDAOHandler { public static function createModuleDBDAOUtilFilesFromHibernateFile($pa58b0566, $v676bb8b56c, $pcd8c70bc, &$v9ff9df9b4e = array()) { $v5c1c342594 = true; if ($pa58b0566) foreach ($pa58b0566 as $pe28a9b21) foreach ($v676bb8b56c as $pfd248cca => $pe0cb6cb3) { if ($pfd248cca == "businesslogic" && !self::createBusinessLogicModuleDBDAOUtilFileFromHibernateFile($pe28a9b21, $pe0cb6cb3, $pcd8c70bc, $v9ff9df9b4e)) $v5c1c342594 = false; else if (($pfd248cca == "presentation" || $pfd248cca == "system_settings") && !self::createPresentationModuleDBDAOUtilFileFromHibernateFile($pe28a9b21, $pe0cb6cb3, $v9ff9df9b4e)) $v5c1c342594 = false; } return $v5c1c342594; } public static function createBusinessLogicModuleDBDAOUtilFileFromHibernateFile($pe28a9b21, $v57a9807e67, $pcd8c70bc, &$v9ff9df9b4e = array()) { $v5c1c342594 = true; if ($v57a9807e67) { $v250a1176c9 = pathinfo($pe28a9b21, PATHINFO_FILENAME); $v8e37641528 = str_replace(" ", "", ucwords(str_replace(array("_", "-"), " ", strtolower($v250a1176c9)))); $v4a2fedb8f0 = str_replace(" ", "", ucwords(str_replace(array("_", "-"), " ", strtolower($pcd8c70bc)))); $v1335217393 = "{$v8e37641528}DBDAOServiceUtil"; $v067674f4e4 = self::mfdc7db5ae937($pe28a9b21, $v1335217393, "Module\\$v4a2fedb8f0"); foreach ($v57a9807e67 as $pa32be502) { $v9a84a79e2e = "$pa32be502/$v1335217393.php"; if (file_put_contents($v9a84a79e2e, $v067674f4e4) === false) { $v9ff9df9b4e[] = "Error trying to create file: " . str_replace(LAYER_PATH, "", $v9a84a79e2e); $v5c1c342594 = false; } } } return $v5c1c342594; } public static function createPresentationModuleDBDAOUtilFileFromHibernateFile($pe28a9b21, $v57a9807e67, &$v9ff9df9b4e = array()) { $v5c1c342594 = true; if ($v57a9807e67) { $v250a1176c9 = pathinfo($pe28a9b21, PATHINFO_FILENAME); $v8e37641528 = str_replace(" ", "", ucwords(str_replace(array("_", "-"), " ", strtolower($v250a1176c9)))); $v1335217393 = "{$v8e37641528}DBDAOUtil"; $v067674f4e4 = self::mfdc7db5ae937($pe28a9b21, $v1335217393); foreach ($v57a9807e67 as $pa32be502) { $v9a84a79e2e = "$pa32be502/$v1335217393.php"; if (file_put_contents($v9a84a79e2e, $v067674f4e4) === false) { $v9ff9df9b4e[] = "Error trying to create file: " . str_replace(LAYER_PATH, "", $v9a84a79e2e); $v5c1c342594 = false; } } } return $v5c1c342594; } private static function mfdc7db5ae937($pe28a9b21, $v1335217393, $v1efaf06c58 = null) { $v1612a5ddce = self::f9f730fe064($pe28a9b21); $v067674f4e4 = '<?php'; if ($v1efaf06c58) $v067674f4e4 .= '
namespace ' . $v1efaf06c58 . ';
'; $v067674f4e4 .= '
if (!class_exists("' . $v1335217393 . '")) {
	class ' . $v1335217393 . ' {
		'; if ($v1612a5ddce) foreach ($v1612a5ddce as $v71571534b0 => $v9d1744e29c) { $v71571534b0 = str_replace(" ", "_", $v71571534b0); $pe30cc92e = self::f43f9e5f47f($v9d1744e29c); $pe30cc92e = str_replace("\n", "\n\t", $pe30cc92e); $v067674f4e4 .= '
		public static function ' . $v71571534b0 . '($data = array()) {
			' . $pe30cc92e . '
		}
	'; } $v067674f4e4 .= '
	}
}
?>'; return $v067674f4e4; } private static function f9f730fe064($pe28a9b21) { $v1612a5ddce = array(); if (file_exists($pe28a9b21)) { $pae77d38c = file_get_contents($pe28a9b21); $v6dcd71ad57 = new MyXML($pae77d38c); $pfb662071 = $v6dcd71ad57->toArray(array("simple" => false, "lower_case_keys" => true, "xml_order_id_prefix" => false)); $pa694ba99 = new MyXMLArray($pfb662071); $v50d32a6fc4 = $pa694ba99->getNodes("sql_mapping/class/queries"); $v50d32a6fc4 = isset($v50d32a6fc4[0]["childs"]) ? $v50d32a6fc4[0]["childs"] : null; if ($v50d32a6fc4) foreach ($v50d32a6fc4 as $v3fb9f41470 => $ped8fa2bc) if ($ped8fa2bc) foreach ($ped8fa2bc as $v7b90d613d5) { $v71571534b0 = isset($v7b90d613d5["@"]["id"]) ? $v7b90d613d5["@"]["id"] : null; $v3c76382d93 = isset($v7b90d613d5["value"]) ? $v7b90d613d5["value"] : null; if (isset($v71571534b0)) $v1612a5ddce[$v71571534b0] = $v3c76382d93; } } return $v1612a5ddce; } private static function f43f9e5f47f($v9d1744e29c) { $v9d1744e29c = trim($v9d1744e29c); $v9d1744e29c = addcslashes($v9d1744e29c, '\\"'); preg_match_all("/#(\w+)#/", $v9d1744e29c, $pbae7526c, PREG_SET_ORDER); if ($pbae7526c) foreach ($pbae7526c as $v6107abf109) if ($v6107abf109[1]) $v9d1744e29c = str_replace($v6107abf109[0], '" . $data["' . $v6107abf109[1] . '"] . "', $v9d1744e29c); $v067674f4e4 = 'return "' . $v9d1744e29c . '";'; $v067674f4e4 = str_replace(' . "";', ';', $v067674f4e4); return $v067674f4e4; } } ?>
