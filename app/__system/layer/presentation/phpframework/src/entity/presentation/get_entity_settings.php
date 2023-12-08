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
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $_GET["edit_entity_type"] = "simple"; $file_type = "edit_entity"; include $EVC->getEntityPath("presentation/edit"); $EVC->setView("get_entity_settings"); $obj = prepareMainObject($templates[0]["template"], $available_regions_list, $regions_blocks_list, $available_blocks_list, $available_block_params_list, $block_params_values_list, $includes, $available_params_list, $template_params_values_list, $blocks_join_points, $hard_coded); function prepareMainObject($pe7333513, $v3f8f1acfdb, $v2b1e634696, $v5aaf0d3496, $v5e5b435544, $peb496cef, $pc06f1034, $v3fd37663c7, $v1fb4b254d3, $pf9d1c559, $v86a2fe4410) { $v972f1a5c2b = array( "regions" => array(), "includes" => array(), "template" => $pe7333513, "template_params" => array(), "available_blocks_list" => $v5aaf0d3496, "hard_coded" => $v86a2fe4410, ); if ($v3f8f1acfdb) { $pc37695cb = count($v3f8f1acfdb); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v9b9b8653bc = $v3f8f1acfdb[$v43dd7d0051]; if (!$v972f1a5c2b["regions"][$v9b9b8653bc]) $v972f1a5c2b["regions"][$v9b9b8653bc] = array(); } } if ($v2b1e634696) { $v5bd013bcfe = array(); $pc37695cb = count($v2b1e634696); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v49cb7db1ab = $v2b1e634696[$v43dd7d0051]; $v9b9b8653bc = $v49cb7db1ab[0]; $peebaaf55 = $v49cb7db1ab[1]; $pd6ec966e = $v49cb7db1ab[2]; if (isset($v5bd013bcfe["$v9b9b8653bc-$peebaaf55-$pd6ec966e"])) $v5bd013bcfe["$v9b9b8653bc-$peebaaf55-$pd6ec966e"]++; else $v5bd013bcfe["$v9b9b8653bc-$peebaaf55-$pd6ec966e"] = 0; $pe603f3eb = $v5bd013bcfe["$v9b9b8653bc-$peebaaf55-$pd6ec966e"]; $v23caa16bce = $v5e5b435544[$v9b9b8653bc][$peebaaf55]; $v36aefa195e = $peb496cef[$v9b9b8653bc][$peebaaf55][$pe603f3eb]; $v9b391a5b1f = $v36aefa195e ? $v36aefa195e : array(); if ($v23caa16bce) foreach ($v23caa16bce as $v9d27441e80 => $v9acf40c110) $v9b391a5b1f[$v9acf40c110] = $v36aefa195e[$v9acf40c110]; $pf615c15a = array(); $v0fa547ce72 = $pf9d1c559[$v9b9b8653bc][$peebaaf55][$pe603f3eb]; if (is_array($v0fa547ce72)) { foreach ($v0fa547ce72 as $pc5f2e454) { $v34bca6a112 = $pc5f2e454["join_point_name"]; if ($v34bca6a112) { $v77784c4ecd = isset($pc5f2e454["join_point_settings"]["key"]) ? array($pc5f2e454["join_point_settings"]) : $pc5f2e454["join_point_settings"]; $v221de5d5ea = CMSPresentationLayerJoinPointsUIHandler::convertBlockSettingsArrayToObj($v77784c4ecd); $pf615c15a[$v34bca6a112][] = $v221de5d5ea; } } } $v972f1a5c2b["regions"][$v9b9b8653bc][] = array( "block" => $peebaaf55, "proj" => $pd6ec966e, "params" => $v9b391a5b1f, "join_points" => $pf615c15a, ); } } if ($pc06f1034) { $pc37695cb = count($pc06f1034); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pc24afc88 = $pc06f1034[$v43dd7d0051]; $v154d33eec4 = PHPUICodeExpressionHandler::getArgumentCode($pc24afc88["path"], $pc24afc88["path_type"]); $v972f1a5c2b["includes"][] = array("path" => $v154d33eec4, "once" => $pc24afc88["once"]); } } if ($v3fd37663c7) { $v9e3513bc0e = $v1fb4b254d3 ? $v1fb4b254d3 : array(); $pc37695cb = count($v3fd37663c7); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v58b61e02bc = $v3fd37663c7[$v43dd7d0051]; $v9e3513bc0e[$v58b61e02bc] = $v1fb4b254d3[$v58b61e02bc]; } $v972f1a5c2b["template_params"] = $v9e3513bc0e; } return $v972f1a5c2b; } ?>
