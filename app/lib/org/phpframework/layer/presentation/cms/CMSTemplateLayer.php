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

include_once get_lib("org.phpframework.layer.presentation.cms.CMSLayer"); include_once get_lib("org.phpframework.layer.presentation.cms.SequentialLogicalActivities"); include_once get_lib("org.phpframework.util.HashTagParameter"); include_once get_lib("org.phpframework.ptl.PHPTemplateLanguage"); class CMSTemplateLayer { private $v874d5d2d79; private $v86f703e78b; private $v9b391a5b1f; private $pb718e4e1; private $v0562978d4c; private $v0b7214ee9f; private $pebadbc9e; private $pe487d2b3; public function __construct(CMSLayer $v874d5d2d79) { $this->v874d5d2d79 = $v874d5d2d79; $this->v86f703e78b = array(); $this->v9b391a5b1f = array(); $this->pb718e4e1 = false; $this->v0562978d4c = array(); $this->v0b7214ee9f = null; $this->pebadbc9e = null; $this->pe487d2b3 = false; } public function addRegionHtml($v9f2c84d2ad, $pf8ed4912) { if ($this->isRegionExecutionValid($v9f2c84d2ad) && $pf8ed4912) { $this->v86f703e78b[$v9f2c84d2ad][] = array(1, $pf8ed4912); return true; } return false; } public function addRegionBlock($v9f2c84d2ad, $v29fec2ceaa, $v4eca125fbe = false) { if ($v9f2c84d2ad && $v29fec2ceaa) { $v29fec2ceaa = $v4eca125fbe ? "$v4eca125fbe/$v29fec2ceaa" : $v29fec2ceaa; $this->v874d5d2d79->getCMSBlockLayer()->addBlockRegion($v29fec2ceaa, $v9f2c84d2ad); if ($this->isRegionExecutionValid($v9f2c84d2ad) && $this->v874d5d2d79->getCMSBlockLayer()->isBlockExecutionValid($v29fec2ceaa)) { $this->v86f703e78b[$v9f2c84d2ad][] = array(2, $v29fec2ceaa); return true; } } return false; } public function renderRegion($v9f2c84d2ad) { $pf8ed4912 = ""; $pfff36d74 = $this->v86f703e78b[$v9f2c84d2ad]; if (is_array($pfff36d74)) { foreach ($pfff36d74 as $peebaaf55) $pf8ed4912 .= $peebaaf55[0] == 1 ? $peebaaf55[1] : $this->v874d5d2d79->getCMSBlockLayer()->getBlock($peebaaf55[1]); $this->f58d7469e72($pf8ed4912); } return $pf8ed4912; } public function stopAllRegions() { $this->pb718e4e1 = true; } public function startAllRegions() { $this->pb718e4e1 = false; } public function stopRegion($v9f2c84d2ad) { if ($v9f2c84d2ad) $this->v0562978d4c[$v9f2c84d2ad] = true; } public function startRegion($v9f2c84d2ad) { if ($v9f2c84d2ad) $this->v0562978d4c[$v9f2c84d2ad] = false; } public function isAllRegionsExecutionValid() { return !$this->pb718e4e1; } public function isRegionExecutionValid($v9f2c84d2ad) { return $this->isAllRegionsExecutionValid() && $v9f2c84d2ad && empty($this->v0562978d4c[$v9f2c84d2ad]); } public function setParam($pb2c326f5, $v67db1bd535) { $this->v9b391a5b1f[$pb2c326f5] = $v67db1bd535; } public function getParam($pb2c326f5) { return $this->v9b391a5b1f[$pb2c326f5]; } public function setSequentialLogicalActivities($v0b7214ee9f) { $this->v0b7214ee9f = $v0b7214ee9f; } private function maed2641deeb2() { if (!$this->pe487d2b3 && $this->v0b7214ee9f) { $this->pe487d2b3 = true; $v08d9602741 = $this->v874d5d2d79->getEVC(); $v9b0a2531e9 = new SequentialLogicalActivities(); $v9b0a2531e9->setEVC($v08d9602741); $pf8ed4912 = $v9b0a2531e9->execute($this->v0b7214ee9f, $this->pebadbc9e); } } private function f58d7469e72(&$pf8ed4912) { $this->maed2641deeb2(); if (is_array($this->pebadbc9e) && count($this->pebadbc9e)) { $v711d3addfd = array_keys($this->pebadbc9e); $pf8ed4912 = HashTagParameter::replaceHTMLHashTagParametersWithValues($pf8ed4912, $this->pebadbc9e, $v711d3addfd, true); if (stripos($pf8ed4912, '<ptl:') === false) { $v08d9602741 = $this->v874d5d2d79->getEVC(); $pe77f177a = $v08d9602741->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler"); $v64dc76b25c = new PHPTemplateLanguage(); if ($pe77f177a) $v64dc76b25c->setCacheHandler($pe77f177a); $pc5a892eb = $this->pebadbc9e; $pc5a892eb["EVC"] = $v08d9602741; $pf8ed4912 = $v64dc76b25c->parseTemplate($pf8ed4912, $pc5a892eb); } } } } ?>
