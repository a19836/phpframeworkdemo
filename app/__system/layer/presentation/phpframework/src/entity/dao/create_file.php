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

$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $type = $_GET["type"]; $path = $_GET["path"]; $file_name = ucfirst($_GET["file_name"]); $path = str_replace("../", "", $path); $path = DAO_PATH . $path; if (file_exists($path) && $file_name) { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("vendor/dao/$path", "layer", "access"); $file_path = "$path/$file_name"; $path_info = pathinfo($file_path); $contents = ""; if ($type == "hibernatemodel" || $type == "objtype") { $file_path .= $path_info["extension"] == "php" ? "" : ".php"; $contents = $type == "hibernatemodel" ? getHibernateModelClassContents($path_info["filename"]) : getObjTypeClassContents($path_info["filename"]); if (!$contents) $file_path = ""; else if (!PHPScriptHandler::isValidPHPContents($contents, $error_message)) { echo $error_message ? $error_message : "Error creating $type with name: $file_name"; die(); } } $status = $file_path ? file_put_contents($file_path, $contents) !== false : false; } die($status); function getHibernateModelClassContents($v1335217393) { include_once get_lib("org.phpframework.sqlmap.hibernate.HibernateModel"); if (class_exists($v1335217393)) return false; return '<?php
include_once get_lib("org.phpframework.sqlmap.hibernate.HibernateModel");

class ' . $v1335217393 . ' extends HibernateModel {
	//TODO: overwrite some methods from HibernateModel or create new ones...
}
?>'; } function getObjTypeClassContents($v1335217393) { include_once get_lib("org.phpframework.object.ObjType"); if (class_exists($v1335217393)) return false; return '<?php
include_once get_lib("org.phpframework.object.ObjType");

class ' . $v1335217393 . ' extends ObjType {
	//TODO: create some properties if you wish
	//sample: private $status;
	
	public function __construct() {
		//TODO: init some properties or do some other complex logic
		//sample: $this->status = false;
	}
	
	public function setData($data) {
		parent::setData($data);

		//TODO: change the $this->data value or assign the $this->data\'s values to some properties
		//sample: $this->status = $this->data["status"];
	}
	
	public function getData() {
		//TODO: change the $this->data values or the properties values
		//sample: $this->data["status"] = $this->status;
		
		return parent::getData();
	}
	
	//TODO: create some getters and setters
	//sample: public function setStatus($status) {$this->status = $status;}
	//sample: public function getStatus() {return $this->status;}
}
?>'; } ?>
