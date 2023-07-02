/** START: Add some default functions in case they don't exist **/

if (!Element.prototype.querySelectorAll || !Element.prototype.querySelector)
	alert("Browser doesn't support this features. Please run this on a more modern browser!");

//create closest function bc on some IE this doesn't exists
if (!Element.prototype.closest) 
	Element.prototype.closest = function (css) {
		var node = this;
		while (node) {
			if (node.matches(css)) 
				return node;
			else 
				node = node.parentElement;
		}
		return null;
	};

//create matches function bc on some IE this doesn't exists
if (!Element.prototype.matches)
	Element.prototype.matches = Element.prototype.matchesSelector || Element.prototype.webkitMatchesSelector || Element.prototype.mozMatchesSelector || Element.prototype.msMatchesSelector;

//Fix for IE7 and lower bc on some IE this doesn't exists
if (!document.querySelectorAll)
	document.querySelectorAll = function (selectors) {
		var style = document.createElement('style'), elements = [], element;
		document.documentElement.firstChild.appendChild(style);
		document._qsa = [];

		style.styleSheet.cssText = selectors + '{x-qsa:expression(document._qsa && document._qsa.push(this))}';
		window.scrollBy(0, 0);
		style.parentNode.removeChild(style);

		while (document._qsa.length) {
			element = document._qsa.shift();
			element.style.removeAttribute('x-qsa');
			elements.push(element);
		}

		document._qsa = null;
		return elements;
	};

//Fix for IE7 and lower bc on some IE this doesn't exists 
if (!document.querySelector)
	document.querySelector = function (selectors) {
		var elements = document.querySelectorAll(selectors);
		return (elements.length) ? elements[0] : null;
	};

//add querySelectorAllInNodes - new methods by JP
if (typeof querySelectorAllInNodes != "function")
	function querySelectorAllInNodes(nodes, selectors) {
		var elements = [], l2, node, elms, j;
		
		if (nodes)
			for (var i = 0, l1 = nodes.length; i < l1; i++) {
				node = nodes[i];

				if (node && node.nodeType == Node.ELEMENT_NODE) {
					elms = node.querySelectorAll(selectors);
					l2 = elms ? elms.length : 0;

					if (l2)
						for (j = 0; j < l2; j++)
							elements.push(elms[j]);
				}
			}

		return elements;
	};

//add querySelectorInNodes - new methods by JP
if (typeof querySelectorInNodes != "function")
	function querySelectorInNodes(nodes, selectors) {
		var elements = querySelectorAllInNodes(nodes, selectors);
		return (elements.length) ? elements[0] : null;
	};

//add filterSelectorAllInNodes - new methods by JP
if (typeof filterSelectorAllInNodes != "function")
	function filterSelectorAllInNodes(nodes, selectors) {
		var elements = [], node;
		
		if (nodes) 
			for (var i = 0, l = nodes.length; i < l; i++) {
				node = nodes[i];

				if (node && node.nodeType == Node.ELEMENT_NODE && node.matches(selectors))
					elements.push(node);
			}

		return elements;
	};

//add filterSelectorInNodes - new methods by JP
if (typeof filterSelectorInNodes != "function")
	function filterSelectorInNodes(nodes, selectors) {
		var elements = filterSelectorAllInNodes(nodes, selectors);
		return (elements.length) ? elements[0] : null;
	};

//Leave this code here, because is adding the TRIM function to the IE browsers. Otherwise the browser gives errors.
if(typeof String.prototype.trim !== 'function')
	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g, ''); 
	};

//Leave this code here, because is adding the hashCode function to all browsers.
if(typeof String.prototype.hashCode !== 'function')
	String.prototype.hashCode = function() {
		var hash = 0;
		
		if (this.length == 0) 
			return hash;
		
		for (i = 0; i < this.length; i++) {
			char = this.charCodeAt(i);
			hash = ((hash<<5) - hash) + char;
			hash = hash & hash; // Convert to 32bit integer
		}
		return hash;
	};

//Fixing IE issue with the console apply method. This is used in the ACE.js file
if(console.warn && typeof console.warn.apply !== 'function')
	console.warn = Function.prototype.bind.call(console.warn, console);

//Fixing IE issue with the console apply method. This is used in the ACE.js file
if(console.log && typeof console.log.apply !== 'function') 
	console.log = Function.prototype.bind.call(console.log, console);

/** END: Add some default functions in case they don't exist **/

/* START: List/Table indexes functions
	Example:
		<table>
			<thead>
				<tr>
					<th class="value">Text</th>
					<th class="title">Title</th>
					<th class="url">Url</th>
					<th class="class">Class</th>
					<th class="actions">
						<i class="icon add" onClick="addLink(this)"></i>
					</th>
				</tr>
			</thead>
			<tbody index_prefix="links">
				<tr class="no_links"><td colspan="4">There are no links...</td></tr>
			</tbody>
		</table>
		
		<script>
			function addLink(elm) {
				var tbody = $(elm).parent().closest("table").children("tbody");
				tbody.children(".no_links").hide();
				var index = getListNewIndex(tbody);
				
				var row = '<tr>'
					+ '<td class="value"><input class="task_property_field" type="text" name="links[' + index + '][value]"/></td>'
					+ '<td class="url"><input class="task_property_field" type="text" name="links[' + index + '][url]"/></td>'
					+ '<td class="actions"><i class="icon remove" onClick="removeLink(this)"></i></td>'
				+ '</tr>';
				
				row = $(row);
				tbody.append(row);
				
				return row;
			}
			
			function removeLink(elm) {
				var tr = $(elm).parent().closest("tr");
				var tbody = tr.parent();
				
				tr.remove();
				
				if (tbody.children().length == 1)
					tbody.children(".no_links").show();
			}
		</script>
*/
function getListChildItemDefaultSelector() {
	return "input, textarea, select";;
}

function getListChildItemNameIndex(name, prefix) {
	if (name) {
		if (prefix)
			prefix = prefix.replace(/\(/g, "\\(").replace(/\)/g, "\\)").replace(/\[/g, "\\[").replace(/\]/g, "\\]");
		
		var myRegexp = new RegExp("^" + prefix + "\\[([0-9]+)\\]");
		var match = myRegexp.exec(name);
		var index = match ? parseInt(match[1]) : null;
		
		if ($.isNumeric(index))
			return index;
	}
	
	return null;
}

function getListChildItemIndex(item, prefix) {
	return getListChildItemNameIndex(item.attr("name"), prefix);
}

function getListChildIndex(child, prefix, selector) {
	if (prefix) {
		//getting last index for children
		selector = selector ? selector : getListChildItemDefaultSelector();
		var items = child.find(selector);
		
		for (var i = 0; i < items.length; i++) {
			var index = getListChildItemIndex( $(items[i]), prefix);
			
			if ($.isNumeric(index)) 
				return index;
		};
	}
	
	return null;
}

function getListNewIndex(parent, selector) {
	var last_index = 0;
	
	//getting prefix
	var prefix = parent.attr("index_prefix");
	
	if (prefix) {
		//getting last index for children
		selector = selector ? selector : getListChildItemDefaultSelector();
		var items = parent.find(selector);
		
		$.each(items, function(idx, item) {
			var index = getListChildItemIndex( $(item), prefix);
			
			if ($.isNumeric(index) && index > last_index)
				last_index = index;
		});
	}
	
	return last_index + 1;
}

function updateListChildrenIndexes(parent, start_index, selector, only_if_numeric) {
	start_index = $.isNumeric(start_index) ? start_index : 0;
	
	var children = parent.children();
	
	$.each(children, function(idx, child) {
		changeListChildWithNewIndex(parent, $(child), start_index, selector, only_if_numeric);
		start_index++;
	});
}

function updateListChildrenInnerIndexPrefixIndexes(parent, start_index, selector, only_if_numeric) {
	start_index = $.isNumeric(start_index) ? start_index : 0;
	
	var children = parent.children();
	
	$.each(children, function(idx, child) {
		changeListChildInnerIndexPrefixWithNewIndex(parent, $(child), start_index, selector, only_if_numeric);
		start_index++;
	});
}

function changeListChildWithNewIndex(parent, child, new_index, selector, only_if_numeric) {
	var prefix = parent.attr("index_prefix");
	
	if (prefix) {
		selector = selector ? selector : getListChildItemDefaultSelector();
		var items = child.find(selector);
		var new_name_prefix = prefix + "[" + new_index + "]";
		
		$.each(items, function(idx, item) {
			item = $(item);
			var name = item.attr("name");
			
			if (name && name.substr(0, prefix.length + 1) == prefix + "[") {
				var index = getListChildItemIndex(item, prefix);
				
				if (!only_if_numeric || $.isNumeric(index)) {
					index = $.isNumeric(index) ? index : "";
					
					var old_name_prefix = prefix + "[" + index + "]"; //replaces 'prefix + "[]"' too, this is, replaces empty prefixes with the new index
					name = new_name_prefix + name.substr(old_name_prefix.length);
					
					item.attr("name", name);
				}
			}
		});
	}
}

//prepare child inner elms
function changeListChildInnerIndexPrefixWithNewIndex(parent, child, new_index, selector, only_if_numeric) {
	var prefix = parent.attr("index_prefix");
	
	if (prefix) {
		selector = selector ? selector : "[index_prefix]";
		var items = child.find(selector);
		var new_name_prefix = prefix + "[" + new_index + "]";
		
		$.each(items, function(idx, item) {
			item = $(item);
			var name = item.attr("index_prefix");
			
			if (name && name.substr(0, prefix.length + 1) == prefix + "[") {
				var index = getListChildItemNameIndex(name, prefix);
				
				if (!only_if_numeric || $.isNumeric(index)) {
					index = $.isNumeric(index) ? index : "";
					
					var old_name_prefix = prefix + "[" + index + "]"; //replaces 'prefix + "[]"' too, this is, replaces empty prefixes with the new index
					name = new_name_prefix + name.substr(old_name_prefix.length);
					
					item.attr("index_prefix", name);
				}
			}
		});
	}
}

//when move up or down an element child, then we must call this function
function switchListChildrenIndexes(parent, child_1, child_2, selector, only_if_numeric) {
	var prefix = parent.attr("index_prefix");
	
	if (prefix) {
		selector = selector ? selector : getListChildItemDefaultSelector();
		var index_1 = getListChildIndex(child_1, prefix, selector);
		var index_2 = getListChildIndex(child_2, prefix, selector);
		
		changeListChildWithNewIndex(parent, child_1, index_2, selector, only_if_numeric);
		changeListChildWithNewIndex(parent, child_2, index_1, selector, only_if_numeric);
	}
}
/* END: List/Table indexes functions */

//Detect if IE
function isMSIE() {
	if (typeof $.browser == "object" && $.browser.msie) {
		return true;
	}
	
	var ua = window.navigator.userAgent;
	return ua.indexOf("MSIE ") > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./);
}

//Note: Is very important to add the encodeAccentsInURL otherwise if a value has accents, won't work in IE.
//This function is used in the admin/admin-advanced.js, presentation/list.js...
//Any function that uses ajax should call this function
function encodeUrlWeirdChars(url) {
	if (url) {
		var accents_chars = url.match(/([\x7f-\xff\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u024F\u1EBD\u1EBC])/gi); //'x' and 'u' means accents and ç.
		
		if (accents_chars) 
			for (var i = 0; i < accents_chars.length; i++) {
				var c = accents_chars[i];
				var r = encodeURI(c);
				
				try {
					eval("url = url.replace(/" + c + "/g, r);");
				}
				catch(e) {
					if (console && console.log)
						console.log(e);
				}
			}
	}
	
	return url;
}

