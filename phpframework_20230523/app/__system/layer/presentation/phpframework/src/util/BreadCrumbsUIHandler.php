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

class BreadCrumbsUIHandler { public static function getFilePathBreadCrumbsHtml($pf3dc0762, $v5ce4bd29b6 = null, $v3e0227d4b9 = false, $v6f3a2700dd = false, $v3d13dbcdf4 = false, $pdc0164ab = false) { $pf8ed4912 = '<div class="breadcrumbs' . ($v3d13dbcdf4 ? " $v3d13dbcdf4" : "") . '">' . self::getFilePathBreadCrumbsItemsHtml($pf3dc0762, $v5ce4bd29b6, $v3e0227d4b9, $v6f3a2700dd, $pdc0164ab) . '</div>'; return $pf8ed4912; } public static function getFilePathBreadCrumbsItemsHtml($pf3dc0762, $v5ce4bd29b6 = null, $v3e0227d4b9 = false, $v6f3a2700dd = false, $pdc0164ab = false) { $pf8ed4912 = ''; $pf3dc0762 = preg_replace("/[\/]+/", "/", $pf3dc0762); $pa32be502 = $pf3dc0762; if ($v5ce4bd29b6 && is_a($v5ce4bd29b6, "Layer")) { $pa2bba2ac = $v5ce4bd29b6->getLayerPathSetting(); $v7d0332245c = WorkFlowBeansFileHandler::getLayerObjFolderName($v5ce4bd29b6); } else $pa2bba2ac = CMS_PATH; if ($pa2bba2ac && strpos($pf3dc0762, $pa2bba2ac) === 0) $pa32be502 = substr($pf3dc0762, strlen($pa2bba2ac)); if ($v5ce4bd29b6 && is_a($v5ce4bd29b6, "PresentationLayer")) { $v2508589a4c = $v5ce4bd29b6->getSelectedPresentationId(); $v327f72fb62 = "$v2508589a4c/src/"; if (strpos($pa32be502, $v327f72fb62) === 0) { $pbd1bc7b0 = strpos($pa32be502, "/", strlen($v327f72fb62)); $pbd1bc7b0 = $pbd1bc7b0 !== false ? $pbd1bc7b0 : strlen($pa32be502); $v08a68a03cf = substr($pa32be502, strlen($v327f72fb62), $pbd1bc7b0 - strlen($v327f72fb62)); if ($v08a68a03cf) { if ($v08a68a03cf == "entity") $v08a68a03cf = "pages"; else $v08a68a03cf .= "s"; $pa32be502 = substr($v327f72fb62, 0, -4) . $v08a68a03cf . substr($pa32be502, $pbd1bc7b0); } } } if ($v3e0227d4b9 && substr($pa32be502, -4, 1) == ".") $pa32be502 = substr($pa32be502, 0, -4); if ($v7d0332245c) $pf8ed4912 .= '<span class="breadcrumb-item' . ($pdc0164ab ? " $pdc0164ab" : "") . '">' . $v7d0332245c . '</span>'; $pa32be502 = preg_replace("/^[\/]+/", "", $pa32be502); $pa32be502 = preg_replace("/[\/]+$/", "", $pa32be502); $v9cd205cadb = explode("/", $pa32be502); $pc37695cb = count($v9cd205cadb); $pabdb0169 = ""; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1d2d80ed32 = $v9cd205cadb[$v43dd7d0051]; if ($v6f3a2700dd) { $pabdb0169 .= ($pabdb0169 ? "/" : "") . $v1d2d80ed32; $v1d2d80ed32 = '<a href="' . str_replace("#path#", $pabdb0169, $v6f3a2700dd) . '" title="' . $pabdb0169 . '">' . $v1d2d80ed32 . '</a>'; } $pf8ed4912 .= '<span class="breadcrumb-item' . ($pdc0164ab ? " $pdc0164ab" : "") . '">' . $v1d2d80ed32 . '</span>'; } return $pf8ed4912; } } ?>
