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

$is_admin_ui_simple_allowed = $UserAuthenticationHandler->isFilePermissionAllowed("simple", "admin_ui", "access"); $is_admin_ui_citizen_allowed = $UserAuthenticationHandler->isFilePermissionAllowed("citizen", "admin_ui", "access"); $is_admin_ui_low_code_allowed = $UserAuthenticationHandler->isFilePermissionAllowed("low_code", "admin_ui", "access"); $is_admin_ui_advanced_allowed = $UserAuthenticationHandler->isFilePermissionAllowed("advanced", "admin_ui", "access"); $is_admin_ui_expert_allowed = $UserAuthenticationHandler->isFilePermissionAllowed("expert", "admin_ui", "access"); $admin_uis_count = 0; if ($is_admin_ui_simple_allowed) $admin_uis_count++; if ($is_admin_ui_citizen_allowed) $admin_uis_count++; if ($is_admin_ui_low_code_allowed) $admin_uis_count++; if ($is_admin_ui_advanced_allowed) $admin_uis_count++; if ($is_admin_ui_expert_allowed) $admin_uis_count++; $is_switch_admin_ui_allowed = $admin_uis_count > 1; ?>
