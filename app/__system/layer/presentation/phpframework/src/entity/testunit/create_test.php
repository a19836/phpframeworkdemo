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
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $path = $_GET["path"]; $file_name = ucfirst($_GET["file_name"]); $path = str_replace("../", "", $path); $path = TEST_UNIT_PATH . $path; if (file_exists($path) && $file_name) { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication("vendor/testunit/$path", "layer", "access"); $file_path = "$path/$file_name"; $path_info = pathinfo($file_path); $file_path .= $path_info["extension"] == "php" ? "" : ".php"; $contents = getTestUnitClassContents($path_info["filename"]); if (!$contents) $file_path = ""; else if (!PHPScriptHandler::isValidPHPContents($contents, $error_message)) { echo $error_message ? $error_message : "Error creating $type with name: $file_name"; die(); } $status = $file_path ? file_put_contents($file_path, $contents) !== false : false; } die($status); function getTestUnitClassContents($v1335217393) { include_once get_lib("org.phpframework.testunit.TestUnit"); if (class_exists($v1335217393)) return false; return '<?php
include_once get_lib("org.phpframework.testunit.TestUnit");

class ' . $v1335217393 . ' extends TestUnit {
	
	/**
	 * @enabled
	 */
	public function execute() {
		//TODO: add some code to create your test unit...
		
		/*
		 * You can call the following inner methods:
		 * - $this->getLayersObjects()
		 * - $this->getLayerObject($type, $name = null) $type: db_layers, data_access_layers, ibatis_layers, hibernate_layers, business_logic_layers, presentation_layers, presentation_layers_evc
		 * - $this->getDBLayer($name = null) $name is a string with the layer name
		 * - $this->getDataAcessLayer($name = null) $name is a string with the layer name
		 * - $this->getIbatisLayer($name = null) $name is a string with the layer name
		 * - $this->getHibernateLayer($name = null) $name is a string with the layer name
		 * - $this->getBusinessLogicLayer($name = null) $name is a string with the layer name
		 * - $this->getPresentationLayer($name = null) $name is a string with the layer name
		 * - $this->getPresentationLayerEVC($name = null) $name is a string with the layer name
		 * - $this->addError($error) $error is a string
		 * - $this->setErrors($errors) $errors is an array of strings
		 * - $this->getErrors()
		 */
		 
		 return true; //it must return something. If you would like to display something when this test gets executed, return a string with what you wish to display.
	}
}
?>'; } ?>
