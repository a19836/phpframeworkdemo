<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
?><div class="presentation_connection_html">
	<div class="connection_type">
		<label>Connection Type: </label>
		<select class="connection_property_field" name="connection_type" onChange="PresentationTaskUtil.onChangeConnectionType(this)">
			<option value="link">Link</option>
			<option value="popup">Popup</option>
			<option value="parent">Parent</option>
		</select>
	</div>
	<div class="connection_label">
		<label>Connection Label: </label>
		<input class="connection_property_field" name="connection_label" />
	</div>
	<div class="connection_title">
		<label>Connection Title: </label>
		<input class="connection_property_field" name="connection_title" />
	</div>
	<div class="connection_class">
		<label>Connection Class: </label>
		<input class="connection_property_field" name="connection_class" />
	</div>
	<div class="connection_target">
		<label>Connection Target: </label>
		<input class="connection_property_field" name="connection_target" />
	</div>
</div>
