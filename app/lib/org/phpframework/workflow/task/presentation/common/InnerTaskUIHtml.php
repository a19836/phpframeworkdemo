<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */
?><div class="inner_task_settings">
	<ul>
		<li><a href="#inner_task_parent_link_settings">Parent Link Settings</a></li>
		<li><a href="#inner_task_interface_settings">Interface Settings</a></li>
	</ul>
	
	<div id="inner_task_parent_link_settings">
		<div class="parent_link_class">
			<label>Parent Link Class:</label>
			<input class="task_property_field" type="text" name="parent_link_class" />
		</div>
		
		<div class="parent_link_value">
			<label>Parent Link Value:</label>
			<input class="task_property_field" type="text" name="parent_link_value" />
		</div>
		
		<div class="parent_link_title">
			<label>Parent Link Title:</label>
			<input class="task_property_field" type="text" name="parent_link_title" />
		</div>
		
		<div class="parent_link_previous_html">
			<label>Parent Link Previous Html:</label>
			<textarea class="task_property_field" name="parent_link_previous_html"></textarea>
		</div>
		
		<div class="parent_link_next_html">
			<label>Parent Link Next Html:</label>
			<textarea class="task_property_field" name="parent_link_next_html"></textarea>
		</div>
	</div>
	
	<div id="inner_task_interface_settings">
		<div class="interface_type">
			<label>Interface Type:</label>
			<select class="task_property_field" name="interface_type">
				<option value="embeded">Embeded</option>
				<option value="popup">Popup</option>
			</select>
		</div>

		<div class="interface_class">
			<label>Interface Class:</label>
			<input class="task_property_field" type="text" name="interface_class" />
		</div>

		<div class="interface_previous_html">
			<label>Interface Previous Html:</label>
			<textarea class="task_property_field" name="interface_previous_html"></textarea>
		</div>

		<div class="interface_next_html">
			<label>Interface Next Html:</label>
			<textarea class="task_property_field" name="interface_next_html"></textarea>
		</div>
	</div>
</div>
