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
include_once $EVC->getUtilPath("WorkFlowBeansFolderHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $WorkFlowBeansFolderHandler = new WorkFlowBeansFolderHandler($user_beans_folder_path, $user_global_variables_file_path, $user_global_settings_file_path); if (!empty($_POST["project_name"])) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $status = true; if ($_POST["project_name"]) $_POST["project_name"] = str_replace(" ", "_", strtolower($_POST["project_name"])); $_POST["project_name"] = empty($_POST["project_name"]) ? $WorkFlowBeansFolderHandler->getSetupDefaultProjectName() : $_POST["project_name"]; if ($WorkFlowBeansFolderHandler->setSetupProjectName($_POST["project_name"])) { if ($WorkFlowBeansFolderHandler->createDefaultFiles()) { header("location: ?step=3"); echo '<script>window.location = "?step=3"</script>'; die(); } else { $error_message = "There was an error trying to save the default project name. Please try again..."; } } else { $error_message = "There was an error trying to save the default project name. Please try again..."; } } $project_name = !empty($_POST["project_name"]) ? $_POST["project_name"] : $WorkFlowBeansFolderHandler->getSetupProjectName(); ?>
