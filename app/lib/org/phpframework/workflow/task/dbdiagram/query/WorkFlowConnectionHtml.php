<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
?><div class="db_table_connection_html">
	<div class="header">
		<label>Tables Join:</label>
		<select class="connection_property_field tables_join" name="tables_join">
			<option>inner</option>
			<option>left</option>
			<option>righ</option>
		</select>
		<input class="connection_property_field source_table" type="text" name="source_table" value="" />
		<input class="connection_property_field target_table" type="text" name="target_table" value="" />
	</div>
	
	<table>
		<thead>
			<tr>
				<th class="source_column table_header"></th>
				<th class="operator table_header">operator</th>
				<th class="target_column table_header"></th>
				<th class="column_value table_header">value</th>
				<th class="table_attr_icons">
					<a class="icon add" onClick="DBQueryTaskPropertyObj.addTableJoinKey()">ADD</a>
				</th>
			</tr>
		</thead>
		<tbody class="table_attrs">
			
		</tbody>
	</table>
	
	<div class="delete_connection_button">
		<button onClick="removeTableConnectionFromConnectionProperties(this)">Delete Connection Between Tables</button>
	</div>
</div>
