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

include_once $EVC->getUtilPath("AdminMenuHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $is_popup = $_GET["is_popup"]; $layers = AdminMenuHandler::getLayers($user_global_variables_file_path); $is_flush_cache_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("admin/flush_cache"), "delete"); $is_manage_layers_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("setup/layers"), "access"); $is_manage_modules_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("admin/manage_modules"), "access"); $is_manage_projects_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("presentation/manage_projects"), "access"); $is_manage_users_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("user/manage_users"), "access"); $is_deployment_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("deployment/index"), "access"); $is_testunits_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("testunit/index"), "access"); $is_program_installation_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("admin/install_program"), "access"); $is_diff_files_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("diff/index"), "access"); ?>
