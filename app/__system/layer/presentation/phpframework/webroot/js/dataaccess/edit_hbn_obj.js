var db_diagrams_data = {};
var saved_hbn_class_obj_id = null;

$(function () {
	$(window).unbind('beforeunload').bind('beforeunload', function () {
		if (isHibernateClassObjectChanged()) {
			if (window.parent && window.parent.iframe_overlay)
				window.parent.iframe_overlay.hide();
			
			return "If you proceed your changes won't be saved. Do you wish to continue?";
		}
		
		return null;
	});
	
	//init auto save
	addAutoSaveMenu(".top_bar li.dummy_elm_to_add_auto_save_options", "onToggleQueryAutoSave");
	addAutoConvertMenu(".top_bar li.dummy_elm_to_add_auto_save_options");
	enableAutoSave(onToggleQueryAutoSave);
	enableAutoConvert(onToggleAutoConvert);
	initAutoSave(".top_bar li.save a");
	
	//init ui
	var relationships_elm = $(".hbn_obj_relationships").children(".relationships");
	relationships_elm.find(".advanced_query_settings").addClass("active");
	relationships_elm.children(".update_automatically").first().attr("onClick", "updateHibernateObjectRelationshipsAutomatically(this)");
	
	$("#tabs").tabs();
	$("#tabs").show();
	
	$(".simple_settings").show();
	$(".advanced_settings").hide();
	
	$("#relationship_tab, #query_tab").click(function(originalEvent) {
		initAllQueryTasks();
	});
	
	//set saved_hbn_class_obj_id
	if ($(".edit_hbn_obj")[0])
		saved_hbn_class_obj_id = getHibernateClassObjectId();
});

function toggleHbnObjAdvancedSettings(elm) {
	elm = $(elm);
	var advanced_settings = $(".advanced_settings");
	var simple_settings = $(".simple_settings");
	var is_shown = elm.hasClass("active");
	
	if (is_shown) {
		elm.removeClass("active");
		advanced_settings.hide();
		simple_settings.show();
	}
	else {
		elm.addClass("active");
		advanced_settings.show();
		simple_settings.hide();
	}
}

/* START: HBN OBJ TABLE */
function getHbnObjTableFromDB(elm) {
	getTableFromDB(elm, updateHbnObjTableField);
}

function updateHbnObjTableField(elm) {
	updateTableField(elm);
	
	selected_table = $(MyFancyPopup.settings.targetField).val();//this will be used on the onDeleteQueryTable function
}
/* START: HBN OBJ TABLE */

/* START: EXTENDED CLASS */
function getExtendedClassFromFileManager(elm) {
	MyFancyPopup.init({
		elementToShow: $("#choose_dao_object_from_file_manager"),
		parentElement: document,
		
		targetField: $(elm).parent().children("select")[0],
		updateFunction: updateExtendedFieldFromFileManager
	});
	
	MyFancyPopup.showPopup();
}

function updateExtendedFieldFromFileManager(elm) {
	var node = daoObjsTree.getSelectedNodes();
	node = node[0];
	
	var file_path = null, is_hbn_obj = false;
	
	if (node) {
		var a = $(node).children("a");
		
		if (a) {
			file_path = a.attr("file_path");
			is_hbn_obj = a.children("i")[0].className.indexOf("hibernatemodel") != -1;
		}
	}
	
	if (file_path && is_hbn_obj) {
		file_path = "vendor.dao." + file_path.replace("/", ".").replace(".php", "");
		
		addSelectedFileToSelectField(MyFancyPopup.settings.targetField, file_path, file_path);
		
		MyFancyPopup.hidePopup();
	}
	else {
		alert("Invalid File selection.\nPlease choose an hibernate file and then click in the button.");
	}
}
/* END: EXTENDED CLASS */

/* START: IDS */
function addNewId(elm) {
	var html_obj = $(new_id_html);
	$(elm).parent().children(".fields").append(html_obj);
	
	return html_obj;
}

function createHibernateObjectIdsAutomatically(elm) {
	$("#choose_db_table_or_attribute .db_attribute").hide();
	
	MyFancyPopup.init({
		elementToShow: $("#choose_db_table_or_attribute"),
		parentElement: document,
		
		targetField: elm,
		hideChooseAttributesField: true,
		updateFunction: updateHibernateObjectIdsAutomatically
	});
	
	MyFancyPopup.showPopup();
}

function updateHibernateObjectIdsAutomatically(elm) {
	var p = $(elm).parent().parent();
	var db_broker = p.find(".db_broker select").val();
	var db_driver = p.find(".db_driver select").val();
	var type = p.find(".type select").val();
	var db_table = p.find(".db_table select").val();
	
	db_table = db_table ? db_table.trim() : "";
	
	if (db_table != "") {
		MyFancyPopup.showLoading();
	
		var icon_add = $(MyFancyPopup.settings.targetField);
		var fields_elm = icon_add.parent().children(".fields");
		
		var table_attributes = getDBTableAttributesDetailedInfo(db_broker, db_driver, type, db_table);
		
		if (!jQuery.isEmptyObject(table_attributes)) {
			for (var attr_name in table_attributes) {
				var pk = table_attributes[attr_name]["primary_key"];
				
				if (pk == "true" || pk == "1") {
					var last_elm = addNewId(icon_add);
					last_elm.children("input.attr_name").val(attr_name);
					
					var attribute_props = table_attributes[attr_name];
					var el = attribute_props["extra"] ? ("" + attribute_props["extra"]).toLowerCase() : "";
					var is_auto_increment = attribute_props["auto_increment"] || 
									  el.indexOf("auto_increment") != -1 || 
									  el.indexOf("nextval") != -1 || 
									  (auto_increment_db_attributes_types && $.inArray(attribute_props["type"].toLowerCase(), auto_increment_db_attributes_types) != -1);
					
					last_elm.children("select.generator").val(is_auto_increment ? "" : "increment");
				}
			}
		}
	}
	
	MyFancyPopup.hidePopup();
}
/* END: IDS */

/* START: MAPS / CLASSES */
function onChangeParameterType(elm) {
	var v = $(elm).val();
	
	if (v == "class") {
		$(elm).parent().parent().children(".class").show();
		$(elm).parent().parent().children(".map").hide();
	}
	else {
		$(elm).parent().parent().children(".class").hide();
		$(elm).parent().parent().children(".map").show();
	}
}

function onChangeResultType(elm) {
	var v = $(elm).val();
	
	if (v == "class") {
		$(elm).parent().parent().children(".class").show();
		$(elm).parent().parent().children(".map").hide();
	}
	else {
		$(elm).parent().parent().children(".class").hide();
		$(elm).parent().parent().children(".map").show();
	}
}
/* END: MAPS / CLASSES */

/* START: UPDATE AUTOMATICALLY */
function createHibernateObjectAutomatically(elm) {
	var target_field = $(".edit_hbn_obj .data_access_obj > .name > input")[0];
	
	updateRelationshipsAutomatically(elm, target_field, updateHibernateObjectAutomatically);
}

function updateHibernateObjectAutomatically(elm) {
	var icon_add_automatically = MyFancyPopup.settings.targetField;
	
	if (confirm("This action may take a while.\nDo you wish to proceed?")) {
		var hbn_obj = $(icon_add_automatically).parent().parent();
		var tabs = hbn_obj.children("#tabs");
		
		var p = $(elm).parent().parent();
		var db_table = p.find(".db_table select").val();
		var with_maps = p.find(".map_option input").prop('checked');
		
		db_table = db_table ? db_table.trim() : "";
		
		if (db_table != "") {
			hbn_obj.children(".table").children("input").val(db_table);
			selected_table = db_table;
			
			//PREPARING IDS
			MyFancyPopup.settings.targetField = hbn_obj.children(".ids").children(".add")[0];
			updateHibernateObjectIdsAutomatically(elm);
			
			//PREPARING MAPS
			if (with_maps) {
				var parameters = tabs.children("#tabs-2").children(".parameters");
				var fields = parameters.children(".map").children("table").children(".fields");
				MyFancyPopup.settings.targetField = fields[0];
				MyFancyPopup.settings.mapType = "parameter";
				fields.html("");
				updateResultParameterMapFields(elm);
				
				var select = parameters.children(".type").children("select");
				select.val("map");
				onChangeParameterType(select[0]);
			
				var results = tabs.children("#tabs-3").children(".results");
				var fields = results.children(".map").children("table").children(".fields");
				MyFancyPopup.settings.targetField = fields[0];
				MyFancyPopup.settings.mapType = "result";
				fields.html("");
				updateResultParameterMapFields(elm);
				
				var select = results.children(".type").children("select");
				select.val("map");
				onChangeResultType(select[0]);
			}
			
			//PREPARING QUERIES
			MyFancyPopup.settings.targetField = tabs.children(".hbn_obj_queries").children(".relationships").children(".relationships_tabs").children("div").children(".add_relationship")[0];
			updateRelationshipsAutomaticallyForSelectedTable(elm, true);
		
			//PREPARING RELATIONSHIPS
			MyFancyPopup.settings.targetField = tabs.children(".hbn_obj_relationships").children(".relationships").children(".relationships_tabs").children("div").children(".add_relationship")[0];
			updateHibernateObjectRelationshipsAutomaticallyForSelectedTable(elm, true);
			
			MyFancyPopup.settings.targetField = null;
		}
	}
}

function updateHibernateObjectRelationshipsAutomatically(elm) {
	var target_field = $(elm).parent().children(".relationships_tabs").children("div").children(".add_relationship")[0];
	
	updateRelationshipsAutomatically(elm, target_field, updateHibernateObjectRelationshipsAutomaticallyForSelectedTable);
}

function updateHibernateObjectRelationshipsAutomaticallyForSelectedTable(elm, do_not_confirm) {
	MyFancyPopup.showLoading();
	$("#choose_db_table_or_attribute .button").hide();
	
	var p = $(elm).parent().parent();
	var db_broker = p.find(".db_broker select").val();
	var db_driver = p.find(".db_driver select").val();
	var type = p.find(".type select").val();
	var db_table = p.find(".db_table select").val();
	var with_maps = p.find(".map_option input").prop('checked');
	
	db_table = db_table ? db_table.trim() : "";
	
	if (db_table != "" && (do_not_confirm || confirm("This action may take a while.\nDo you wish to proceed?")) ) {
		var data = getBrokerHbnObjRelationships(db_broker, db_driver, type, db_table, with_maps, "relationships");
		//console.log(data);
		
		if (data) {
			var icon_add_relationship = $(MyFancyPopup.settings.targetField);
			var relationships_obj = icon_add_relationship.parent().parent();
			
			for (var rel_type in data) {
				if (rel_type == "result_map") {//RELATIONSHIP DON'T HAVE PARAMETER MAPS
					updateRelationshipsMapsAutomatically(data[rel_type], rel_type, relationships_obj);
				}
				else if(rel_type == "one_to_one" || rel_type == "many_to_one" || rel_type == "one_to_many" || rel_type == "many_to_many") {
					updateHibernateObjectRelationships(data[rel_type], rel_type, icon_add_relationship);
				}
			}
		}
	}
	
	MyFancyPopup.hidePopup();
	$("#choose_db_table_or_attribute .button").show();
}

function updateHibernateObjectRelationships(relationships, rel_type, icon_add_relationship) {
	if (relationships) {
		if (!$.isArray(relationships)) {
			relationships = [relationships];
		}
		
		var existent_rel_ids = getExistentRelationshipIds(icon_add_relationship);
		//var relationships_obj = icon_add_relationship.parent().children(".rels");
		
		for (var i = 0; i < relationships.length; i++) {
			var relationship = relationships[i];
			var rel_id = relationship["name"] ? relationship["name"] : "";
			
			if (rel_id == "" || $.inArray(rel_id, existent_rel_ids) == -1) {
				var aux = addRelationshipBlock(icon_add_relationship[0], 1);
				var relationship_obj = aux[0];
				var rand_number = aux[1];
				
				var select = relationship_obj.children(".rel_type").children("select");
				select.val(rel_type);
				updateRelationshipType(select[0], rand_number);
				
				relationship_obj.children(".rel_name").children("input").val(rel_id);
				relationship_obj.children(".result_class_id").children("input").val(relationship["result_class"] ? relationship["result_class"] : "");
				relationship_obj.children(".result_map_id").children("input").val(relationship["result_map"] ? relationship["result_map"] : "");
				
				var query_settings = relationship_obj.children(".query").children("div").children(".query_select").children(".query_settings").first();
				var data = {
					"attributes": relationship["attribute"] && $.isArray(relationship["attribute"]) ? relationship["attribute"] : [ relationship["attribute"] ], 
					"keys": relationship["key"] && $.isArray(relationship["key"]) ? relationship["key"] : [ relationship["key"] ], 
					"conditions": relationship["condition"] && $.isArray(relationship["condition"]) ? relationship["condition"] : [ relationship["condition"] ],
					"groups_by": relationship["group_by"] && $.isArray(relationship["group_by"]) ? relationship["group_by"] : [ relationship["group_by"] ],
					"sorts": relationship["sort"] && $.isArray(relationship["sort"]) ? relationship["sort"] : [ relationship["sort"] ],
					"start": relationship["start"],
					"limit": relationship["limit"],
				};
				
				updateQuerySettingsFields(data, query_settings, "select", rand_number);
				
				toggleQuery(relationship_obj.children(".header_buttons").children(".minimize")[0]);
			}
		}
	}
}
/* END: UPDATE AUTOMATICALLY */

/* START: SAVE ALL */
function getHibernateClassObjectId() {
	var obj = getHibernateClassObject();
	return $.md5(JSON.stringify(obj));
}

function isHibernateClassObjectChanged() {
	var new_hbn_class_obj_id = getHibernateClassObjectId();
	
	return saved_hbn_class_obj_id != new_hbn_class_obj_id;
}

function getHibernateClassObject() {
	var data_access_elm = $(".data_access_obj");
	var hbn_obj = {};
	
	//PREPARING MAIN ATTRIBUTES
	var name = data_access_elm.children(".name").children("input").val().trim();
	var table = data_access_elm.children(".table").children("input").val().trim();
	var ext_value = data_access_elm.children(".extends").children("select").val().trim();
	
	var hbn_obj = {
		"name": name,
		"table": table,
		"extends": ext_value,
	};
	
	//PREPARING IDS
	var ids = [];
	var items = data_access_elm.children(".ids").children(".fields").children(".id");
	for (var i = 0; i < items.length; i++) {
		var item = $(items[i]);
		
		var attr_name = item.children("input").val().trim();
		var generator = item.children("select").val();
		
		if (attr_name != "") {
			var id = {
				"column": attr_name, 
			};
		
			if (generator != "") {
				id["generator"] = {"type": generator};
			}
			
			ids.push(id);
		}
	}
	hbn_obj["id"] = ids;
	
	var tabs = data_access_elm.children("#tabs");
	
	//PREPARING INCLUDES
	var main_includes_elm = tabs.children("#tabs-1").children(".includes");
	var includes = getUserIncludesObj(main_includes_elm);
	hbn_obj["import"] = includes;
	
	//PREPARING MAIN PARAMETER MAP
	var map = tabs.children("#tabs-2").children(".map").first();
	var map_type = map.children(".type").children("select").val();
	
	if (map_type == "class") {
		var parameter_class = map.children(".class").children("input").val().trim();
		
		if (parameter_class) 
			hbn_obj["parameter_class"] = parameter_class;
	}
	else
		hbn_obj["parameter_map"] = getUserMapObj( map.children(".map"), "parameter" );
	
	//PREPARING MAIN RESULT MAP
	var map = tabs.children("#tabs-3").children(".map").first();
	var map_type = map.children(".type").children("select").val();
	
	if (map_type == "class") {
		var result_class = map.children(".class").children("input").val().trim();
		
		if (result_class) 
			hbn_obj["resut_class"] = result_class;
	}
	else
		hbn_obj["result_map"] = getUserMapObj( map.children(".map"), "result" );
	
	//PREPARING RELATIONSHIPS
	var main_relationships_elm = tabs.children("#tabs-4").children(".relationships").first();
	var main_relationships_obj = getUserRelationshipsObj(main_relationships_elm);
	hbn_obj["relationships"] = main_relationships_obj;
	
	//PREPARING QUERIES
	var main_queries_elm = tabs.children("#tabs-5").children(".relationships").first();
	var main_queries_obj = getUserRelationshipsObj(main_queries_elm);
	hbn_obj["queries"] = main_queries_obj;
	
	return hbn_obj;
}

function saveHibernateObject() {
	var hbn_obj = getHibernateClassObject();
	var error_msg = validateHibernateObject(hbn_obj);
	
	if (error_msg == "") {
		prepareAutoSaveVars();
		
		var new_hbn_class_obj_id = getHibernateClassObjectId();
		
		//only saves if object is different
		if (saved_hbn_class_obj_id != new_hbn_class_obj_id) {
			var new_obj_id = hbn_obj["name"];
			hbn_obj = {"class": hbn_obj};
		
			saveDataAccessObject(hbn_obj, new_obj_id, {
				"force": true,
				on_success: function(obj, new_obj_id, data) {
					//update saved_hbn_class_obj_id
					saved_hbn_class_obj_id = new_hbn_class_obj_id;
					
					//checks if name changed
					var is_id_different = old_obj_id && new_obj_id && old_obj_id.trim() != new_obj_id.trim();
					
					//If id is different, reload page with right id
					if (is_id_different) {
						if (window.parent && typeof window.parent.refreshLastNodeParentChilds == "function")
							window.parent.refreshLastNodeParentChilds();
						
						var url = "" + document.location;
						url = url.replace(/(&|\?)obj=[^&]*/g, "$1obj=" + new_obj_id);
						
						document.location = url;
					}
				}
			});
		}
		else if (!is_from_auto_save) {
			StatusMessageHandler.showMessage("Nothing to save.");
		}
		else
			resetAutoSave();
	}
	else
		alert("Error:" + error_msg);
}

function validateHibernateObject(hbn_obj) {
	var error_msg = "";
	
	if (!isUserRelationshipObjValid(hbn_obj["name"])) 
		error_msg += "\n- Hibernate object name is empty or contains invalid characters";
	
	if (!isUserRelationshipObjValid(hbn_obj["table"]))
		error_msg += "\n- Hibernate object table is empty or contains invalid characters";
	
	error_msg += validateUserRelationshipsObj(hbn_obj["relationships"]);
	error_msg += validateUserRelationshipsObj(hbn_obj["queries"]);
	
	return error_msg;
}
/* END: SAVE ALL */

/* START: INIT QUERIES TAB */
function initQueriesTab(elm) {
	var is_init = $(elm).attr("is_init");
	
	if (is_init != 1) {
		$(elm).attr("is_init", 1);
		
		var a = $(".hbn_obj_queries .query_tabs .query_sql_tab a").first();
		a.attr("not_create_sql_from_ui", 1);
		a.click();
		a.removeAttr("not_create_sql_from_ui");
	}
}
/* END: INIT QUERIES TAB */
