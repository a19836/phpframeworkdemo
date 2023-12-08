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
include_once get_lib("org.phpframework.layer.presentation.cms.module.ICMSModuleHandler"); abstract class CMSModuleHandler implements ICMSModuleHandler { private $v08d9602741; private $pcd8c70bc; private $pfd76dfba; private $v8f96a41927 = false; public function setEVC($v08d9602741) { $this->v08d9602741 = $v08d9602741; } public function getEVC() { return $this->v08d9602741; } public function setModuleId($pcd8c70bc) { $this->pcd8c70bc = $pcd8c70bc; } public function getModuleId() { return $this->pcd8c70bc; } public function setCMSSettings($pfd76dfba) { $this->pfd76dfba = $pfd76dfba; } public function getCMSSettings() { return $this->pfd76dfba; } public function getCMSSetting($v5e813b295b) { return is_array($this->pfd76dfba) ? $this->pfd76dfba[$v5e813b295b] : null; } public function enable() { $this->v8f96a41927 = true; } public function disable() { $this->v8f96a41927 = false; } public function isEnabled() { return $this->v8f96a41927; } public static function getCMSModuleHandlerImplFilePath($v4a650a2b36) { return "$v4a650a2b36/CMSModuleHandlerImpl.php"; } } ?>
