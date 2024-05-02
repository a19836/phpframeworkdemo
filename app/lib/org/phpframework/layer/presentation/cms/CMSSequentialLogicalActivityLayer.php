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
include_once get_lib("org.phpframework.layer.presentation.cms.CMSLayer"); include_once get_lib("org.phpframework.layer.presentation.cms.SequentialLogicalActivity"); include_once get_lib("org.phpframework.util.HashTagParameter"); include_once get_lib("org.phpframework.ptl.PHPTemplateLanguage"); class CMSSequentialLogicalActivityLayer { private $v874d5d2d79; private $v0b7214ee9f; private $pebadbc9e; private $pe487d2b3; private $pa620c18c; public function __construct(CMSLayer $v874d5d2d79) { $this->v874d5d2d79 = $v874d5d2d79; $this->resetSequentialLogicalActivities(); } public function resetSequentialLogicalActivities() { $this->v0b7214ee9f = array(); $this->pebadbc9e = null; $this->pe487d2b3 = false; $this->pa620c18c = array(); } public function addSequentialLogicalActivities($v0b7214ee9f) { if (is_array($v0b7214ee9f)) { $this->v0b7214ee9f = array_merge($this->v0b7214ee9f, $v0b7214ee9f); $v1cbfbb49c5 = $this->f9bd87374e1($v0b7214ee9f); $this->pa620c18c[$v1cbfbb49c5] = $v0b7214ee9f; } } public function existSequentialLogicalActivitiesToBeAlwaysExecuted($v0b7214ee9f = null) { $v0b7214ee9f = $v0b7214ee9f ? $v0b7214ee9f : $this->v0b7214ee9f; $v08d9602741 = $this->v874d5d2d79->getEVC(); $v8df35b88dc = new SequentialLogicalActivity(); $v8df35b88dc->setEVC($v08d9602741); $v7959970a41 = $v8df35b88dc->existActionsValidCondition($v0b7214ee9f, $this->pebadbc9e); return $v7959970a41; } public function prepareHTMLWithSequentialLogicalActivities(&$pf8ed4912) { $this->prepareHTMLHashTagsWithSequentialLogicalActivities($pf8ed4912); $this->prepareHTMLPTLWithSequentialLogicalActivities($pf8ed4912); } public function prepareHTMLHashTagsWithSequentialLogicalActivities(&$pf8ed4912) { if (stripos($pf8ed4912, '#') !== false) { $pb7cb50c3 = is_array($this->pebadbc9e) && count($this->pebadbc9e); $external_vars = $pb7cb50c3 ? $this->pebadbc9e : array(); if (!$external_vars) { $v607356a497 = "/#-1#/"; } else { $v607356a497 = array(); foreach ($external_vars as $pe5c5e2fe => $v02a69d4e0f) if ($pe5c5e2fe) { $v607356a497[] = "/#\[(\"|')?(Resource|SLA|Resources|SLAs)(\"|')?\]\[?$pe5c5e2fe(\]|#)/i"; $v607356a497[] = "/#(\"|')?(Resource|SLA|Resources|SLAs)(\"|')?\[$pe5c5e2fe(\]|#)/i"; } } $pf72c1d58 = HashTagParameter::getHTMLHashTagParametersValues($pf8ed4912, $v607356a497, true, "external_vars"); foreach ($pf72c1d58 as $paf27f6b9 => $replacement) if ($replacement) { $replacement = preg_replace("/^(\\\$external_vars)\[(\"|')(Resource|SLA|Resources|SLAs)(\"|')\]/i", '$1', $replacement); eval('$replacement = ' . $replacement . ';'); $pf8ed4912 = str_replace($paf27f6b9, $replacement, $pf8ed4912); } } } public function prepareHTMLPTLWithSequentialLogicalActivities(&$pf8ed4912) { if (stripos($pf8ed4912, '<ptl:') !== false) { $v08d9602741 = $this->v874d5d2d79->getEVC(); $pe77f177a = $v08d9602741->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler"); $v64dc76b25c = new PHPTemplateLanguage(); if ($pe77f177a) $v64dc76b25c->setCacheHandler($pe77f177a); $pb7cb50c3 = is_array($this->pebadbc9e) && count($this->pebadbc9e); $pc5a892eb = $pb7cb50c3 ? $this->pebadbc9e : array(); $pc5a892eb["EVC"] = $v08d9602741; $pf8ed4912 = $v64dc76b25c->parseTemplate($pf8ed4912, $pc5a892eb); } } public function executeSequentialLogicalActivities() { $pf7dd614f = ""; if (!$this->pe487d2b3 && is_array($this->v0b7214ee9f) && count($this->v0b7214ee9f)) { $this->pe487d2b3 = true; $this->pa620c18c = array(); $v08d9602741 = $this->v874d5d2d79->getEVC(); $v8df35b88dc = new SequentialLogicalActivity(); $v8df35b88dc->setEVC($v08d9602741); $pf7dd614f = $v8df35b88dc->execute($this->v0b7214ee9f, $this->pebadbc9e); } else if ($this->pa620c18c) { $v08d9602741 = $this->v874d5d2d79->getEVC(); $v8df35b88dc = new SequentialLogicalActivity(); $v8df35b88dc->setEVC($v08d9602741); foreach ($this->pa620c18c as $v1cbfbb49c5 => $v0b7214ee9f) { $pf7dd614f .= $v8df35b88dc->execute($v0b7214ee9f, $this->pebadbc9e); unset($this->pa620c18c[$v1cbfbb49c5]); } } if ($pf7dd614f) echo $pf7dd614f; } private function f9bd87374e1($v0b7214ee9f) { return md5(json_encode($v0b7214ee9f)); } public function getSLAResults() { return $this->pebadbc9e; } public function getSLASettings() { return $this->v0b7214ee9f; } public function isSLAExecuted() { return $this->pe487d2b3 && empty($this->pa620c18c); } } ?>
