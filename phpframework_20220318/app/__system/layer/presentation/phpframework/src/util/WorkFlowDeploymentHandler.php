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

include_once $EVC->getUtilPath("CMSDeploymentHandler"); class WorkFlowDeploymentHandler { public static function validateTemplate($v8a4ed461b2, $v1495c93fca, $pdb9e96e6, $pf665220f, &$pef612b9d = null) { $v5c1c342594 = false; if ($v8a4ed461b2 && is_numeric($v1495c93fca)) { $v5c1c342594 = true; $pb4201a4b = WorkFlowTasksFileHandler::getTaskFilePathByPath($pdb9e96e6, "deployment"); $v8cd3e3837d = new WorkFlowTasksFileHandler($pb4201a4b); $v8cd3e3837d->init(); $v4d8ea562f0 = $v8cd3e3837d->getWorkflowData(); $pb4201a4b = WorkFlowTasksFileHandler::getTaskFilePathByPath($pdb9e96e6, "layer"); $v8cd3e3837d = new WorkFlowTasksFileHandler($pb4201a4b); $v8cd3e3837d->init(); $pe9c62c08 = $v8cd3e3837d->getWorkflowData(); $v32892bba0c = CMSDeploymentHandler::getTasksByLabel($pe9c62c08); $paf0ee1a7 = CMSDeploymentHandler::getServerTaskTemplate($v4d8ea562f0, $v8a4ed461b2, $v1495c93fca); if ($paf0ee1a7) { $v9c6a395bfd = $paf0ee1a7["properties"]; $v7454aca359 = $v9c6a395bfd["task"]; $v2639aa6a16 = $v9c6a395bfd["actions"]; $pddc51a8e = array(); if (!CMSDeploymentHandler::validateServerTemplateLicenceData($paf0ee1a7, $pf665220f, $pddc51a8e)) { $pef612b9d = implode("\n" , $pddc51a8e); $v5c1c342594 = false; } if ($v5c1c342594 && $v7454aca359) foreach ($v7454aca359 as $v7f5911d32d) { $v89cfc6ba9c = $v7f5911d32d["label"]; $pd747dfc1 = $v7f5911d32d["properties"]; if ($pd747dfc1 && $pd747dfc1["active"]) { $v7f5911d32d = $v32892bba0c[$v89cfc6ba9c]; if (!$v7f5911d32d) { $pef612b9d = "Error: '$v89cfc6ba9c' Task does not exists anymore!"; $v5c1c342594 = false; break; } } } if ($v5c1c342594 && $v2639aa6a16) { $v651d593e1f = array_keys($v2639aa6a16) !== range(0, count($v2639aa6a16) - 1); if ($v651d593e1f) $v2639aa6a16 = array($v2639aa6a16); foreach ($v2639aa6a16 as $pd69fb7d0 => $v98e8b259aa) foreach ($v98e8b259aa as $v256e3a39a7 => $v1b5ae9c139) if (($v256e3a39a7 == "run_test_units" || $v256e3a39a7 == "copy_files") && (!isset($v1b5ae9c139["active"]) || $v1b5ae9c139["active"])) { $v6ee393d9fb = is_array($v1b5ae9c139["files"]) ? $v1b5ae9c139["files"] : array($v1b5ae9c139["files"]); if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v8ad5107f9a => $v7dffdb5a5b) if ($v7dffdb5a5b) { $pf3dc0762 = ($v256e3a39a7 == "run_test_units" ? TEST_UNIT_PATH : CMS_PATH) . $v7dffdb5a5b; if (!file_exists($pf3dc0762)) { $pef612b9d = "Error: Selected file for action '" . ucwords(str_replace("_", " ", $v256e3a39a7)) . "' does not exists! File path: $v7dffdb5a5b"; $v5c1c342594 = false; break; } } } } } else { $pef612b9d = "Error: Template '$v1495c93fca' in '$v8a4ed461b2' server does not exists or not saved yet!"; $v5c1c342594 = false; } } return $v5c1c342594; } } ?>
