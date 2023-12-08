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
namespace DocBlockParser\Annotation; class ParamAnnotation extends Annotation { public function __construct() { $this->is_input = true; $this->vectors = array("type", "name", "desc"); } public function parseArgs($v6da2e4df28, $v86066462c3) { $v6beb66fea4 = self::getConfiguredArgs($v86066462c3); if (!empty($v86066462c3["name"])) { $v5e813b295b = self::parseValue($v86066462c3["name"]); $v5e813b295b = substr($v5e813b295b, 0, 1) == '$' ? substr($v5e813b295b, 1) : $v5e813b295b; $v6beb66fea4["name"] = $v5e813b295b; if (strpos($v5e813b295b, "[") !== false) { $v771db9690d = str_replace(array('"', "'"), "", $v5e813b295b); preg_match_all("/([^\[\]]+)/u", $v771db9690d, $pbae7526c, PREG_PATTERN_ORDER); if (!empty($pbae7526c[1])) $v6beb66fea4["sub_name"] = implode('"]["', $pbae7526c[1]); } } if (isset($v86066462c3["index"]) && is_numeric($v86066462c3["index"]) && $v86066462c3["index"] >= 0) $v6beb66fea4["index"] = $v86066462c3["index"]; $this->args = $v6beb66fea4; } public function checkMethodAnnotations(&$v5730eacfdc, $pcc2d93a5) { $v5c1c342594 = true; if (!empty($this->args)) { if (isset($this->args["sub_name"])) $v0afadcb2a5 = $this->args["sub_name"]; else if (isset($this->args["name"])) $v0afadcb2a5 = $this->args["name"]; else { $v8a4df75785 = isset($this->args["index"]) ? $this->args["index"] : $pcc2d93a5; $v9994512d98 = array_keys($v5730eacfdc); $v0afadcb2a5 = isset($v9994512d98[$v8a4df75785]) ? $v9994512d98[$v8a4df75785] : null; } if (isset($v0afadcb2a5)) { eval ('$v67db1bd535 = isset($v5730eacfdc["' . $v0afadcb2a5 . '"]) ? $v5730eacfdc["' . $v0afadcb2a5 . '"] : null;'); $v5c1c342594 = $this->checkValueAnnotations($v67db1bd535, $v687583a48f); if ($v687583a48f) eval ('$v5730eacfdc["' . $v0afadcb2a5 . '"] = $v67db1bd535;'); } else if (isset($this->args["not_null"])) { $v5c1c342594 = $this->checkValueAnnotations(null); } } return $v5c1c342594; } } ?>
