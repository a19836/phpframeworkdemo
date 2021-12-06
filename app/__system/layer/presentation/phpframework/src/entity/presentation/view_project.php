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

include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); $url = getProjectUrl($user_beans_folder_path, $user_global_variables_file_path); function getProjectUrl($v5039a77f9d, $v3d55458bcd) { $v8ffce2a791 = $_GET["bean_name"]; $pa0462a8e = $_GET["bean_file_name"]; $pa32be502 = $_GET["path"]; $pc2defd39 = $_GET["query_string"]; $pd1b51d4f = $_GET["get_vars"]; $pa32be502 = str_replace("../", "", $pa32be502); if ($pa32be502) { $pb512d021 = new WorkFlowBeansFileHandler($v5039a77f9d . $pa0462a8e, $v3d55458bcd); $v188b4f5fa6 = $pb512d021->getEVCBeanObject($v8ffce2a791, $pa32be502); if ($v188b4f5fa6) { $pfaf08f23 = new PHPVariablesFileHandler(array($v3d55458bcd, $v188b4f5fa6->getConfigPath("pre_init_config"))); $pfaf08f23->startUserGlobalVariables(); $v9ab35f1f0d = $v188b4f5fa6->getPresentationLayer(); $pa2bba2ac = $v9ab35f1f0d->getLayerPathSetting(); $v2508589a4c = $v9ab35f1f0d->getSelectedPresentationId(); $v6bfcc44e7b = $v9ab35f1f0d->getPresentationFileExtension(); $peb014cfd = mf774c99d0ef1($v188b4f5fa6, $v2508589a4c); $peb014cfd .= substr($peb014cfd, -1) != "/" ? "/" : ""; $v9ca6e84d1e = substr($pa32be502, strlen($v2508589a4c . $v9ab35f1f0d->settings["presentation_entities_path"])); $v9ca6e84d1e = file_exists($pa2bba2ac . $pa32be502) && !is_dir($pa2bba2ac . $pa32be502) ? substr($v9ca6e84d1e, 0, strlen($v9ca6e84d1e) - strlen($v6bfcc44e7b) - 1) : $v9ca6e84d1e; $v9ca6e84d1e = substr($v9ca6e84d1e, 0, 1) == "/" ? substr($v9ca6e84d1e, 1) : $v9ca6e84d1e; $pfaf08f23->endUserGlobalVariables(); $v6f3a2700dd = $peb014cfd . $v9ca6e84d1e; $pc2defd39 = $pc2defd39 ? $pc2defd39 : ""; if ($pd1b51d4f) foreach ($pd1b51d4f as $pe5c5e2fe => $v956913c90f) $pc2defd39 .= "&$pe5c5e2fe=$v956913c90f"; if ($pc2defd39) $v6f3a2700dd .= (strpos($v6f3a2700dd, "?") !== false ? "&" : "?") . $pc2defd39; return $v6f3a2700dd; } else { launch_exception(new Exception("PEVC doesn't exists!")); die(); } } else { launch_exception(new Exception("Undefined path!")); die(); } } function mf774c99d0ef1($EVC, $v2508589a4c) { @include $EVC->getConfigPath("config", $v2508589a4c); return $project_url_prefix; } ?>
