<?php
//prepare some utils functions
function getActionHtml($action, $with_options = 1) {
	$label = ucwords(str_replace(array("-", "_"), " ", $action));
	$parts = explode("_", $action);
	
	if ($parts[0] == "single")
		$title = $label . ": action to " . $parts[1] . " a single item.";
	else if ($parts[0] == "multiple")
		$title = $label . ": action to " . ($action == "multiple_insert_update" ? "insert and update" : $parts[1]) . " multiple items at once.";
	
	$html = '
	<div class="action action_' . str_replace(array(" ", "-"), "_", $action) . '">
		<input type="checkbox" onClick="activateFormWizardAction(this)" />
		<label title="' . $title . '">' . $label . '</label>';
	
	if ($with_options) {
		$html .= '<i class="icon expand_content toggle" onClick="toggleFormWizardActionOptions(this)"></i>
		<div class="action_options">';
		
		if ($with_options == 1)
			$html .= '
				<div class="action_option action_type">
					<label>Choose the type of action that you wish:</label>
					<select onChange="toggleFormWizardActionTypeOptions(this)">
						<option value=""> Default </option>
						<option value="ajax_on_click">Ajax - on click</option>
						' . ($action == "single_update" ? '<option value="ajax_on_blur">Ajax - on blur</option>' : '') . '
					</select>
				</div>
				<div class="action_option ajax_url">
					<label>Ajax Url:</label>
					<input placeHolder="Write a url here" title="This url should correspond to the ajax request, that if successfully should return ' . ($action == "single_insert" ? "the inserted object id" : ($action == "multiple_insert" || $action == "multiple_insert_update" ? "the inserted object ids in an array ordered by the same row index" : "1")) . ' or a json object with a status property." />
				</div>
				<div class="action_option successful_msg_options">
					<label>When this action is successful:</label>
					' . getMessageOptionsHtml($action) . '
				</div>
				<div class="action_option unsuccessful_msg_options">
					<label>When this action is unsuccessful:</label>
					' . getMessageOptionsHtml() . '
				</div>';
		else if ($with_options == 2)
			$html .= '
				<div class="action_option action_links">
					<label>Links:</label>
					<i class="icon add" onClick="addFormWizardActionLinkOptionUrl(this)"></i>
					<div class="info">The primary keys of the selected table will be append to the urls!</div>
					
					<div class="action_link">
						<input class="action_link_url" placeHolder="Write a url here or leave it blank" />
						<input class="action_link_title" placeHolder="url title" />
						<input class="action_link_class" placeHolder="link css class" />
						<i class="icon delete" onClick="$(this).parent().remove()"></i>
					</div>
				</div>';
	
		$html .= '</div>';
	}
	
	$html .= '</div>';
	
	return $html;
}

function getMessageOptionsHtml($action = false) {
	return '<ul>
		<li class="msg_type">
			<label>Message Type:</label>
			<select>
				<option>show</option>
				<option>alert</option>
			</select>
		</li>
		<li class="msg_message">
			<label>Message:</label>
			<input placeHolder="Write a message here" />
		</li>
		<li class="msg_redirect_url">
			<label>Redirerct Url:</label>
			<input placeHolder="Write a url here" title="If a URL is presented, the system will redirect the user to this url after this action been executed." />
			<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
		</li>
	</ul>';
}
?>
