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
