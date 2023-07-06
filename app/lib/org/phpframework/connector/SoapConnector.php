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

include_once get_lib("org.phpframework.util.xml.MyXML"); class SoapConnector { protected $SoapClient = null; public static function connect($v539082ff30, $pbd9f98de = null) { $v36a9d96c9f = new SoapConnector(); return $v539082ff30["type"] == "callSoapClient" ? $v36a9d96c9f->callSoapClient($v539082ff30) : $v36a9d96c9f->callSoapFunction($v539082ff30, $pbd9f98de); } public function callSoapFunction($v539082ff30, $pbd9f98de = null) { $this->SoapClient = $this->callSoapClient($v539082ff30); $pae77d38c = null; $v0f9512fda4 = null; try { $pae77d38c = $this->SoapClient->__call($v539082ff30["remote_function_name"], array($v539082ff30["remote_function_args"])); } catch (SoapFault $pa9aeb971) { $v0f9512fda4 = "Soap Server returned the following ERROR: " . $pa9aeb971->faultcode . " - " . $pa9aeb971->faultstring; } $pba23d78c = array( "settings" => $v539082ff30, "content" => $pae77d38c, "error" => $v0f9512fda4, ); $pba23d78c = in_array($pbd9f98de, array("content", "content_json", "content_xml", "content_xml_simple", "content_serialized")) ? $pba23d78c["content"] : ( $pbd9f98de == "settings" ? $pae77d38c["settings"] : $pba23d78c ); if ($pba23d78c) { if ($pbd9f98de == "content_json") $pba23d78c = json_decode($pba23d78c, true); else if ($pbd9f98de == "content_xml") { $v6dcd71ad57 = new MyXML($pba23d78c); $pba23d78c = $v6dcd71ad57->toArray(); if ($pbd9f98de == "content_xml_simple") $pba23d78c = MyXML::complexArrayToBasicArray($pba23d78c, array("convert_attributes_to_childs" => true)); } else if ($pbd9f98de == "content_serialized") $pba23d78c = unserialize($pba23d78c); } return $pba23d78c; } public function callSoapClient($v539082ff30) { if (!$this->SoapClient) { $this->SoapClient = new SoapClient($v539082ff30["wsdl_url"], $v539082ff30["options"] ? $v539082ff30["options"] : array()); if ($v539082ff30["headers"]) { $v15493e4c60 = array(); foreach ($v539082ff30["headers"] as $v6c438ea8cd) $v15493e4c60[] = new SoapHeader($v6c438ea8cd["namespace"], $v6c438ea8cd["name"], $v6c438ea8cd["parameters"], $v6c438ea8cd["must_understand"], $v6c438ea8cd["actor"]); if ($v15493e4c60) $this->SoapClient->__setSoapHeaders($v15493e4c60); } } return $this->SoapClient; } } ?>
