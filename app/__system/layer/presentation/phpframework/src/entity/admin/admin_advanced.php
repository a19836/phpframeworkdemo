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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $default_page = $_GET["default_page"]; $tree_layout = $_GET["tree_layout"]; $advanced_level = $_GET["advanced_level"]; $theme_layout = $_GET["theme_layout"]; $filter_by_layout = $_GET["filter_by_layout"]; $filter_by_layout_permission = UserAuthenticationHandler::$PERMISSION_BELONG_NAME; if (array_key_exists("advanced_level", $_GET)) { $advanced_level = $advanced_level; UserAuthenticationHandler::setEternalRootCookie("advanced_level", $advanced_level, 0, "/"); } else if (!empty($_COOKIE["advanced_level"])) $advanced_level = $_COOKIE["advanced_level"]; else $advanced_level = "simple_level"; if (array_key_exists("tree_layout", $_GET)) { $tree_layout = $tree_layout; UserAuthenticationHandler::setEternalRootCookie("tree_layout", $tree_layout, 0, "/"); } else if (!empty($_COOKIE["tree_layout"])) $tree_layout = $_COOKIE["tree_layout"]; else $tree_layout = "left_panel_with_tabs"; if (array_key_exists("theme_layout", $_GET)) { $theme_layout = $theme_layout; UserAuthenticationHandler::setEternalRootCookie("theme_layout", $theme_layout, 0, "/"); } else if (!empty($_COOKIE["theme_layout"])) $theme_layout = $_COOKIE["theme_layout"]; else $theme_layout = "dark_theme"; if (array_key_exists("filter_by_layout", $_GET)) UserAuthenticationHandler::setEternalRootCookie("filter_by_layout", $filter_by_layout, 0, "/"); else if (!empty($_COOKIE["filter_by_layout"])) $filter_by_layout = $_COOKIE["filter_by_layout"]; include $EVC->getUtilPath("admin_uis_layers_and_permissions"); ?>
