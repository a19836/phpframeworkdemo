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
include_once $EVC->getutilPath("DependenciesInstallationHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); if (!empty($_POST["acceptance"])) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $continue = true; if (!empty($_POST["dependencies"])) { $zips = DependenciesInstallationHandler::getDependencyZipFilesToInstall(); $continue = DependenciesInstallationHandler::installDependencies($dependencies_repo_url, $zips, $error_message); if (!$continue) $error_message = "Error could not download and install dependencies.<br/>Please confirm if you are connected to the internet." . ($error_message ? "<br/>$error_message" : ""); } if ($continue) { header("location: ?step=2"); echo '<script>window.location = "?step=2"</script>'; die(); } } if (file_exists($license_path)) { $contents = file_get_contents($license_path); $contents = preg_replace("/\n( \* )/", "\n", $contents); $contents = preg_replace("/\n( \*)/", "\n", $contents); $contents = preg_replace("/^( \*)/", "", $contents); $terms_and_conditions = 'TERMS AND CONDITIONS<br/>
	<br/>' . str_replace("\n", "<br/>", $contents); } else $terms_and_conditions = "No License found in '<root path>/" . (substr($license_path, strlen(CMS_PATH))) . "'!<br/>In order to use this software, you must get your license first! <br/>Otherwise you are not allowed to use this software!"; ?>
