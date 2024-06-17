<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
?><div class="db_table_connection_html">
	<div class="relationship_props">
		<div class="source"></div>
		<div class="relationship">
			<select></select>
		</div>
		<div class="target"></div>
	</div>
	
	<div class="buttons">
		<label>Foreign Keys:</label>
	</div>
	
	<table>
		<thead>
			<tr>
				<th class="source_column table_header"></th>
				<th class="target_column table_header"></th>
				<th class="table_attr_icons">
					<a class="icon add" onClick="DBTableTaskPropertyObj.addTableForeignKey()">ADD</a>
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
