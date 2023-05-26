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

include_once $EVC->getUtilPath("CMSDeploymentHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); UserAuthenticationHandler::checkUsersMaxNum($UserAuthenticationHandler); UserAuthenticationHandler::checkActionsMaxNum($UserAuthenticationHandler); $server_name = $_GET["server"]; $template_id = $_GET["template_id"]; $deployment_id = $_GET["deployment_id"]; $action = $_GET["action"]; $li = $EVC->getPresentationLayer()->getPHPFrameWork()->gLI(); $CMSDeploymentHandler = new CMSDeploymentHandler($workflow_paths_id, $webroot_cache_folder_path, $webroot_cache_folder_url, $deployments_temp_folder_path, $user_beans_folder_path, $user_global_variables_file_path, $user_global_settings_file_path, $li); $res = $CMSDeploymentHandler->executeServerAction($server_name, $template_id, $deployment_id, $action); if ($res && $res["status"]) $UserAuthenticationHandler->incrementUsedActionsTotal(); ?>
