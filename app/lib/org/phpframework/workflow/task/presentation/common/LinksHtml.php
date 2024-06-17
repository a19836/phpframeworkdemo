<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
?><div class="links">
	<label>Links:</label>
	
	<table>
		<thead>
			<tr>
				<th class="value">Text</th>
				<th class="title">Title</th>
				<th class="url">Url</th>
				<th class="class">Class</th>
				<th class="target">Target</th>
				<th class="actions">
					<i class="icon add" onClick="PresentationTaskUtil.addLink(this)"></i>
				</th>
			</tr>
		</thead>
		<tbody index_prefix="links">
			<tr class="no_links"><td colspan="6">There are no links...</td></tr>
		</tbody>
	</table>
</div>
