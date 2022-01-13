function onChangeBrokersLayerType(type, parent) {
	switch(type) {
		case "callbusinesslogic":
			parent.children(".call_business_logic_task_html").show();
			parent.children(":not(.call_business_logic_task_html)").hide();
			break;
		case "callibatisquery":
			parent.children(".call_ibatis_query_task_html").show();
			parent.children(":not(.call_ibatis_query_task_html)").hide();
			break;
		case "callhibernatemethod":
			parent.children(".call_hibernate_method_task_html").show();
			parent.children(":not(.call_hibernate_method_task_html)").hide();
			break;
		case "getquerydata":
			parent.children(".get_query_data_task_html").show();
			parent.children(":not(.get_query_data_task_html)").hide();
			break;
		case "setquerydata":
			parent.children(".set_query_data_task_html").show();
			parent.children(":not(.set_query_data_task_html)").hide();
			break;
		case "callfunction":
			parent.children(".call_function_task_html").show();
			parent.children(":not(.call_function_task_html)").hide();
			break;
		case "callobjectmethod":
			parent.children(".call_object_method_task_html").show();
			parent.children(":not(.call_object_method_task_html)").hide();
			break;
		case "restconnector":
			parent.children(".get_url_contents_task_html").show();
			parent.children(":not(.get_url_contents_task_html)").hide();
			break;
		case "soapconnector":
			parent.children(".soap_connector_task_html").show();
			parent.children(":not(.soap_connector_task_html)").hide();
			break;
	}
}

function getBrokerSettings(elm, brokers_layer_type) {
	var settings = {};
	
	switch(brokers_layer_type) {
		case "callbusinesslogic":
			var task_html_elm = elm.children(".call_business_logic_task_html");
			
			if (task_html_elm[0]) {
				settings["method_obj"] = prepareMethodObj( task_html_elm.find(".broker_method_obj input").val() );
				settings["module_id"] = task_html_elm.find(".module_id input").val();
				settings["module_id_type"] = task_html_elm.find(".module_id select").val();
				settings["service_id"] = task_html_elm.find(".service_id input").val();
				settings["service_id_type"] = task_html_elm.find(".service_id select").val();
				
				var params = task_html_elm.children(".params");
				settings["parameters_type"] = params.children(".parameters_type").val();
				if (settings["parameters_type"] == "array") {
					var aux = parseArray( params.children(".parameters") );
					settings["parameters"] = aux["parameters"];
				}
				else {
					settings["parameters"] = params.children(".parameters_code").val();
				}
		
				var opts = task_html_elm.children(".opts");
				settings["options_type"] = opts.children(".options_type").val();
				if (settings["options_type"] == "array") {
					var aux = parseArray( opts.children(".options") );
					settings["options"] = aux["options"];
				}
				else {
					settings["options"] = opts.children(".options_code").val();
				}
			}
				
			break;
		case "callibatisquery":
			var task_html_elm = elm.children(".call_ibatis_query_task_html");
			
			if (task_html_elm[0]) {
				settings["method_obj"] = prepareMethodObj( task_html_elm.find(".broker_method_obj input").val() );
				settings["module_id"] = task_html_elm.find(".module_id input").val();
				settings["module_id_type"] = task_html_elm.find(".module_id select").val();
				settings["service_id"] = task_html_elm.find(".service_id input").val();
				settings["service_id_type"] = task_html_elm.find(".service_id select").val();
				
				var service_type = task_html_elm.children(".service_type");
				settings["service_type_type"] = service_type.children("select.service_type_type").val();
				if (settings["service_type_type"] == "string") {
					settings["service_type"] = service_type.children("select.service_type_string").val();
				}
				else {
					settings["service_type"] = service_type.children("input.service_type_code").val();
				}
				
				var params = task_html_elm.children(".params");
				settings["parameters_type"] = params.children(".parameters_type").val();
				if (settings["parameters_type"] == "array") {
					var aux = parseArray( params.children(".parameters") );
					settings["parameters"] = aux["parameters"];
				}
				else {
					settings["parameters"] = params.children(".parameters_code").val();
				}
		
				var opts = task_html_elm.children(".opts");
				settings["options_type"] = opts.children(".options_type").val();
				if (settings["options_type"] == "array") {
					var aux = parseArray( opts.children(".options") );
					settings["options"] = aux["options"];
				}
				else {
					settings["options"] = opts.children(".options_code").val();
				}
			}
			
			break;
		case "callhibernatemethod":
			var task_html_elm = elm.children(".call_hibernate_method_task_html");
			
			if (task_html_elm[0]) {
				settings["broker_method_obj_type"] = task_html_elm.find(".broker_method_obj select").val();
				settings["method_obj"] = prepareMethodObj( task_html_elm.find(".broker_method_obj input").val() );
				settings["module_id"] = task_html_elm.find(".module_id input").val();
				settings["module_id_type"] = task_html_elm.find(".module_id select").val();
				settings["service_id"] = task_html_elm.find(".service_id input").val();
				settings["service_id_type"] = task_html_elm.find(".service_id select").val();
				
				var opts = task_html_elm.children(".opts");
				settings["options_type"] = opts.children(".options_type").val();
				if (settings["options_type"] == "array") {
					var aux = parseArray( opts.children(".options") );
					settings["options"] = aux["options"];
				}
				else {
					settings["options"] = opts.children(".options_code").val();
				}
				
				var service_method = task_html_elm.children(".service_method");
				settings["service_method_type"] = service_method.children("select.service_method_type").val();
				if (settings["service_method_type"] == "string") {
					settings["service_method"] = service_method.children("select.service_method_string").val();
				}
				else {
					settings["service_method"] = service_method.children("input.service_method_code").val();
				}
				
				var service_method_args = task_html_elm.children(".service_method_args");
				
				var sma = service_method_args.children(".sma_query_type");
				settings["sma_query_type_type"] = sma.children("select.service_method_arg_type").val();
				if (settings["sma_query_type_type"] == "string") {
					settings["sma_query_type"] = sma.children("select.sma_query_type_string").val();
				}
				else {
					settings["sma_query_type"] = sma.children("input").val();
				}
				
				settings["sma_query_id"] = service_method_args.find(".sma_query_id input").val();
				settings["sma_query_id_type"] = service_method_args.find(".sma_query_id select").val();
				settings["sma_function_name"] = service_method_args.find(".sma_function_name input").val();
				settings["sma_function_name_type"] = service_method_args.find(".sma_function_name select").val();
				
				var sma = service_method_args.children(".sma_data");
				settings["sma_data_type"] = sma.children("select").val();
				if (settings["sma_data_type"] == "array") {
					var aux = parseArray( sma.children(".array_items") );
					settings["sma_data"] = aux["sma_data"];
				}
				else {
					settings["sma_data"] = sma.children("input").val();
				}
				
				settings["sma_statuses"] = service_method_args.find(".sma_statuses input").val();
				settings["sma_statuses_type"] = "variable";
				settings["sma_ids"] = service_method_args.find(".sma_ids input").val();
				settings["sma_ids_type"] = "variable";
				settings["sma_rel_name"] = service_method_args.find(".sma_rel_name input").val();
				settings["sma_rel_name_type"] = service_method_args.find(".sma_rel_name select").val();
				
				var sma = service_method_args.children(".sma_parent_ids");
				settings["sma_parent_ids_type"] = sma.children("select").val();
				if (settings["sma_parent_ids_type"] == "array") {
					var aux = parseArray( sma.children(".array_items") );
					settings["sma_parent_ids"] = aux["sma_parent_ids"];
				}
				else {
					settings["sma_parent_ids"] = sma.children("input").val();
				}
				
				var sma = service_method_args.children(".sma_sql");
				settings["sma_sql_type"] = sma.children("select").val();
				if (settings["sma_sql_type"] == "string") {
					var editor = sma.data("editor");
					settings["sma_sql"] = editor ? editor.getValue() : sma.children("textarea.sql_editor").val();
				}
				else {
					settings["sma_sql"] = sma.children("input").val();
				}
				
				var sma = service_method_args.children(".sma_options");
				settings["sma_options_type"] = sma.children("select").val()
				if (settings["sma_options_type"] == "array") {
					var items = sma.children(".array_items").find(".task_property_field");
					var bkp_items = [];
					for (var i = 0; i < items.length; i++) {
						var item = $(items[i]);
						var name = item.attr("name");
						
						item.attr("name", "sma_" + name);
						bkp_items.push([ item[0], name ]);
					}
					
					var aux = parseArray( sma.children(".array_items") );
					settings["sma_options"] = aux["sma_options"];
					
					for (var i = 0; i < bkp_items.length; i++) {
						$(bkp_items[i][0]).attr("name", bkp_items[i][1]);
					}
				}
				else {
					settings["sma_options"] = sma.children("input").val();
				}
			}
			
			break;
		case "getquerydata":
		case "setquerydata":
			var task_html_elm = elm.children(brokers_layer_type == "getquerydata" ? ".get_query_data_task_html" : ".set_query_data_task_html");
				
			if (task_html_elm[0]) {
				settings["method_obj"] = prepareMethodObj( task_html_elm.find(".broker_method_obj input").val() );
				
				var sql = task_html_elm.children(".sql");
				settings["sql_type"] = sql.children("select").val();
				if (settings["sql_type"] == "string") {
					var editor = sql.data("editor");
					settings["sql"] = editor ? editor.getValue() : sql.children("textarea.sql_editor").val();
				}
				else {
					settings["sql"] = sql.children("input.sql_variable").val();
				}
				
				var opts = task_html_elm.children(".opts");
				settings["options_type"] = opts.children(".options_type").val();
				if (settings["options_type"] == "array") {
					var aux = parseArray( opts.children(".options") );
					settings["options"] = aux["options"];
				}
				else {
					settings["options"] = opts.children(".options_code").val();
				}
			}
			
			break;
		case "callfunction":
			var task_html_elm = elm.children(".call_function_task_html");
			
			settings["func_name"] = task_html_elm.find(".func_name input").val();
			settings["func_args"] = parseArgs(task_html_elm.children(".func_args"), "func_args");
			
			break;
		case "callobjectmethod":
			var task_html_elm = elm.children(".call_object_method_task_html");
			
			settings["method_obj"] = task_html_elm.find(".method_obj_name input").val();
			settings["method_name"] = task_html_elm.find(".method_name input").val();
			settings["method_static"] = task_html_elm.find(".method_static input:checked").val();
			settings["method_args"] = parseArgs(task_html_elm.children(".method_args"), "method_args");
			
			break;
		case "restconnector":
			var task_html_elm = elm.children(".get_url_contents_task_html");
			
			var dts = task_html_elm.children(".dts");
			settings["data_type"] = dts.children(".data_type").val();
			if (settings["data_type"] == "array") {
				var aux = parseArray( dts.children(".data") );
				settings["data"] = aux["data"];
			}
			else {
				settings["data"] = dts.children(".data_code").val();
			}
			
			settings["result_type_type"] = task_html_elm.find(".result_type > select[name=result_type_type]").val();
			settings["result_type"] = settings["result_type_type"] == "options" ? task_html_elm.find(".result_type > select[name=result_type]").val() : task_html_elm.find(".result_type > input").val();
			
			break;
		case "soapconnector":
			var task_html_elm = elm.children(".soap_connector_task_html");
			var is_call_soap_client = false;
			
			settings["data_type"] = task_html_elm.find(".data > select").val();
			if (settings["data_type"] != "options") 
				settings["data"] = task_html_elm.find(".data > input").val();
			else {
				settings["data"] = {};
				
				settings["data"]["type_type"] = task_html_elm.find(".type > select.type_type").val();
				settings["data"]["type"] = settings["data"]["type_type"] == "options" ? task_html_elm.find(".type > select.type_options").val() : task_html_elm.find(".type > input.type_code").val();
				
				if (settings["data"]["type"] == "callSoapClient")
					is_call_soap_client = true;
				
				settings["data"]["wsdl_url_type"] = task_html_elm.find(".wsdl_url > select").val();
				settings["data"]["wsdl_url"] = task_html_elm.find(".wsdl_url > input").val();
				
				settings["data"]["options_type"] = task_html_elm.find(".client_options > select").val();
				if (settings["data"]["options_type"] == "options") {
					 var aux = parseArray( task_html_elm.find(".client_options > table > tbody") );
					 settings["data"]["options"] = aux && aux["data"] && aux["data"]["options"] ? aux["data"]["options"] : {};
				}
				else
					settings["data"]["options"] = task_html_elm.find(".client_options > input").val();
				
				settings["data"]["headers_type"] = task_html_elm.find(".client_headers > select").val();
				if (settings["data"]["headers_type"] == "options") {
					settings["data"]["headers"] = [];
					
					var lis = task_html_elm.find(".client_headers > ul > li");
					$.each(lis, function(idx, li) {
						li = $(li);
						
						var must_understand_type = li.find(".client_header_must_understand > select").val();
						var must_understand = li.find(".client_header_must_understand > input[type=text]").val();
						
						if (must_understand_type == "options") {
							var checkbox = li.find(".client_header_must_understand > input[type=checkbox]");
							must_understand = checkbox.is(":checked") ? checkbox.val() : "";
						}
						
						var parameters_type = li.find(".client_header_parameters > select").val();
						var parameters = li.find(".client_header_parameters > input.parameters_code").val();
						
						if (parameters_type == "array") {
							var aux = parseArray( li.find(".client_header_parameters > .parameters") );
							
							if (aux && aux["data"] && aux["data"]["headers"]) {
								for (var idx in aux["data"]["headers"])
									break;
								
								parameters = $.isNumeric(idx) && aux["data"]["headers"][idx] && aux["data"]["headers"][idx]["parameters"] ? aux["data"]["headers"][idx]["parameters"] : {};
							}
						}
						
						var header = {
							namespace: li.find(".client_header_namespace > input").val(),
							namespace_type: li.find(".client_header_namespace > select").val(),
							name: li.find(".client_header_name > input").val(),
							name_type: li.find(".client_header_name > select").val(),
							must_understand: must_understand,
							must_understand_type: must_understand_type,
							actor: li.find(".client_header_actor > input").val(),
							actor_type: li.find(".client_header_actor > select").val(),
							parameters: parameters,
							parameters_type: parameters_type,
						};
						
						settings["data"]["headers"].push(header);
					});
				}
				else
					settings["data"]["headers"] = task_html_elm.find(".client_headers > input").val();
				
				if (!is_call_soap_client) {
					settings["data"]["remote_function_name_type"] = task_html_elm.find(".remote_function_name > select").val();
					settings["data"]["remote_function_name"] = task_html_elm.find(".remote_function_name > input").val();
					
					var rfa = task_html_elm.children(".remote_function_arguments");
					settings["data"]["remote_function_args_type"] = rfa.children("select.remote_function_args_type").val();
					if (settings["data"]["remote_function_args_type"] == "array") {
						var aux = parseArray( rfa.children(".remote_function_args") );
						settings["data"]["remote_function_args"] = aux && aux["data"] && aux["data"]["remote_function_args"] ? aux["data"]["remote_function_args"] : {};
					}
					else
						settings["data"]["remote_function_args"] = rfa.children("input.remote_function_args_code").val();
				}
			}
			
			if (!is_call_soap_client) {
				settings["result_type_type"] = task_html_elm.find(".result_type > select[name=result_type_type]").val();
				settings["result_type"] = settings["result_type_type"] == "options" ? task_html_elm.find(".result_type > select[name=result_type]").val() : task_html_elm.find(".result_type > input").val();
			}
			
			break;
	}
	
	return settings;
}

function parseArgs(html_elm, attr_name) {
	var aux = parseArray( html_elm.children(".args") );
	var args = [];
	
	if (aux && aux[attr_name])
		$.each(aux[attr_name], function(idx, arg) {
			var item = {
				"childs" : {
					"value" : [ {"value" : arg["value"]} ],
					"type" : [ {"value" : arg["type"]} ],
				}
			};
		
			args.push(item);
		});
	
	return args;
}

function parseArray(html_elm) {
	var query_string = jsPlumbWorkFlow.jsPlumbProperty.getPropertiesQueryStringFromHtmlElm(html_elm[0], "task_property_field");
	var settings = {};
	parse_str(query_string, settings);
	
	return settings;
}

function prepareMethodObj(method_obj) {
	method_obj = "" + method_obj;
	var static_pos = method_obj.indexOf("::");
	var non_static_pos = method_obj.indexOf("->");
	
	method_obj = method_obj.substr(0, 1) != '$' && (static_pos == -1 || (non_static_pos != -1 && static_pos > non_static_pos)) ? '$' + method_obj : method_obj;
	
	return method_obj;
}
