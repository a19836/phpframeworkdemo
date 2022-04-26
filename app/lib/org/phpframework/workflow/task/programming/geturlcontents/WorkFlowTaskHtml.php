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
?><div class="get_url_contents_task_html">
	<div class="info">
		This can be used to connect with <b>webhooks</b> in IPAAS services, like: 
		<a href="https://zapier.com/page/webhooks/" target="zapier">Zapier</a>, 
		<a href="https://tray.io/solutions/webhooks" target="tray.io">Tray.io</a>, 
		<a href="https://apiant.com/connections/Webhook" target="apiant">Apiant</a>, 
		<a href="https://www.zoho.com/developer/help/extensions/automation/webhooks.html" target="zoho">Zoho</a>, 
		<a href="https://www.elastic.io/connectors/webhook-integration/" target="elastic">Elastic</a>, 
		<a href="https://docs.workato.com/connectors/workato-webhooks.html" target="workato">Workato</a>, 
		<a href="https://ifttt.com/maker_webhooks" target="ifttt">IFTTT</a>, 
		<a href="https://www.integromat.com/en/integrations/gateway/http" target="integromat">Integromat</a>, 
		<a href="https://blogs.mulesoft.com/dev-guides/how-to-tutorials/webhooks-integration-fun-with-mule/" target="mulesoft">Mulesoft</a>, 
		<a href="https://docs.celigo.com/hc/en-us/articles/360015827372-Create-webhook-listeners" target="celigo">Celigo</a>, 
		<a href="https://www.appypie.com/connect/apps/webhooks-by-connect/integrations" target="appypie">AppyPie</a>, 
		<a href="https://help.talend.com/r/6SB6Qfc014RWM4mEltupHA/5SzrIShpW6sCuQXlekpBNQ" target="talend">Talend</a>, 
		<a href="https://integrately.com/store/webhook" target="integrately">Integrately</a>, 
		<a href="https://www.torocloud.com/martini" target="torocloud">Torocloud Martini</a>, 
		<a href="https://automate.io/integration/webhooks" target="automate">Automate.io</a>, 
		<a href="https://www.ibm.com/support/knowledgecenter/SSTTDS_cloud/com.ibm.appconnect.dev.doc/how-to-guides-for-apps/configure-marketo-webook.html" target="ibm">IBM</a>, 
		<a href="https://panoply.io/integrations/snaplogic/webhooks/" target="panoply">Panoply</a>, 
		<a href="https://cyclr.com/integrate/generic-webhook" target="cyclr">Cyclr</a>, 
		<a href="https://www.mydbsync.com/product/cloud-workflow" target="mydbsync">MyDBSync</a>, 
		<a href="https://www.blendo.co/documents/incoming-webhook-integration/" target="blendo">Blendo</a> 
		and others more...
	</div>
	
	<div class="dts">
		<label>Data:</label>
		<input type="text" class="task_property_field data_code" name="data" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="task_property_field data_type" name="data_type" onChange="GetUrlContentsTaskPropertyObj.onChangeDataType(this, 'data')">
			<option>string</option>
			<option>variable</option>
			<option value="">code</option>
			<option>array</option>
		</select>
		<div class="data array_items"></div>
		<div class="info">
			Note that the "Other Settings" option can be "Curl Opt" settings too, like CURLOPT_xxx.
		</div>
	</div>
	<div class="result_type">
		<label>Result Type: </label>
		<input type="text" class="task_property_field" name="result_type" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select name="result_type">
			<option></option>
			<option>header</option>
			<option value="content">content text</option>
			<option value="content_json">content json</option>
			<option value="content_xml">content xml</option>
			<option value="content_serialized">content serialized</option>
			<option>settings</option>
		</select>
		<select class="task_property_field" name="result_type_type" onChange="GetUrlContentsTaskPropertyObj.onChangeResultType(this)">
			<option>options</option>
			<option>string</option>
			<option>variable</option>
			<option value="">code</option>
		</select>
		<div class="info">
			The result type can have 3 values:
			<ul>
				<li>blank value: which will return a associative array with the request header, html contents, errors...</li>
				<li>"header": which will return a associative array with the request headers.</li>
				<li>"content": which will return request html contents.</li>
				<li>"settings": which will return request settings.</li>
			</ul>
		</div>
	</div>
	
	<?php include dirname(dirname($file_path)) . "/common/ResultVariableHtml.php"; ?>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
