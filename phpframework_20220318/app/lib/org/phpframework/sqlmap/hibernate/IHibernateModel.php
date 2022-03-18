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

interface IHibernateModel { public function insert($v539082ff30, &$v32f28291a1 = false); public function insertAll($v539082ff30, &$v806a006822 = false, &$v32f28291a1 = false); public function update($v539082ff30); public function updateAll($v539082ff30, &$v806a006822 = false); public function insertOrUpdate($v539082ff30, &$v32f28291a1 = false); public function insertOrUpdateAll($v539082ff30, &$v806a006822 = false, &$v32f28291a1 = false); public function delete($v539082ff30); public function deleteAll($v539082ff30, &$v806a006822 = false); public function findById($v32f28291a1, $v539082ff30 = array(), $v5d3813882f = false); public function find($v539082ff30 = array(), $v5d3813882f = false); public function findRelationships($pa5b8420a, $v5d3813882f = false); public function findRelationship($v016220e8f0, $pa5b8420a, $v5d3813882f = false); public function callQuerySQL($pc221318a, $v71571534b0, $v9367d5be85 = false); public function callQuery($pc221318a, $v71571534b0, $v9367d5be85 = false, $v5d3813882f = false); public function callInsertSQL($v71571534b0, $v9367d5be85 = false); public function callInsert($v71571534b0, $v9367d5be85 = false, $v5d3813882f = false); public function callUpdateSQL($v71571534b0, $v9367d5be85 = false); public function callUpdate($v71571534b0, $v9367d5be85 = false, $v5d3813882f = false); public function callDeleteSQL($v71571534b0, $v9367d5be85 = false); public function callDelete($v71571534b0, $v9367d5be85 = false, $v5d3813882f = false); public function callSelectSQL($v71571534b0, $v9367d5be85 = false); public function callSelect($v71571534b0, $v9367d5be85 = false, $v5d3813882f = false); public function callProcedureSQL($v71571534b0, $v9367d5be85 = false); public function callProcedure($v71571534b0, $v9367d5be85 = false, $v5d3813882f = false); public function getData($v3c76382d93, $v5d3813882f = false); public function setData($v3c76382d93, $v5d3813882f = false); public function getInsertedId($v5d3813882f = false); } ?>
