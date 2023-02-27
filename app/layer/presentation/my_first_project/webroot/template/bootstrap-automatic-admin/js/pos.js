var table_pagination_active = true;
var maximum_table_rows_shown = 100;

(function($) {
	"use strict"; // Start of use strict
	
	var curr_url = window.location.href;
	curr_url = curr_url.split("?");
	curr_url = curr_url[0];
	var parts = curr_url.split("/");
	var table_name = parts[parts.length - 2];
	var table_action = parts[parts.length - 1];
	var search_url_suffix = table_name + "/" + table_action;
	
	//Preparing back button
	if (typeof MyJSLib != "undefined") {
		var go_back_elm = $(".go-back");
		MyJSLib.BrowserHistoryHandler.init({"error_message" : null, "back_html_element" : go_back_elm});
		
		if (!MyJSLib.BrowserHistoryHandler.backHistoryExists())
			go_back_elm.hide();
	}
	
	//preparing main menus
	var collapses = $("#sidenavAccordion .nav-item .collapse");
	collapses.removeClass("show");
	
	collapses.find(".nav .nav-link").removeClass("active").each(function(idx, link) {
		link = $(link);
		var href = link.attr("href");
		
		if (href.indexOf(search_url_suffix) != -1)
			link.addClass("active").parent().closest(".collapse").addClass("show");
	});
	
	//preparing sub menus
	$("#sidebar-wrapper .list-group .list-group-item").removeClass("active").each(function(idx, link) {
		link = $(link);
		var href = link.attr("href");
		
		if (href.indexOf(search_url_suffix) != -1)
			link.addClass("active");
	});
	
	//preparing inputs
	prepareFields($(container));
	
	$(".tabs").tabs();
	
})(jQuery); // End of use strict

function onNewHtml(elm , new_item) {
	prepareHtml(new_item[0]);
	prepareFields(new_item);
}

function prepareFields(item) {
	//Only add date plugin if browser doesn't have a default date field or if Firefox (bc the Modernizr does not work properly in the new firefox browsers)
	var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') != -1;
	var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') != -1;
	
	if (typeof Modernizr == "undefined" || !Modernizr || !Modernizr.inputtypes.date || is_firefox) { 
		if (typeof item.datetimepicker != "undefined") {
			item.find('input[type="datetime"]').each(function(idx, input) {
				input = $(input);
				
				input.datetimepicker({
					controlType: 'select',
					oneLine: true,
					minDate: new Date(1970, 01, 01), //add min date to 1970-01-01, otherwise mysql will fail
					showSecond: true,
			   		dateFormat: input.attr('dateFormat') ? input.attr('dateFormat') : 'yy-mm-dd',
					timeFormat: input.attr('timeFormat') ? input.attr('timeFormat') : 'HH:mm:ss',
				});
			});
			
			item.find('input[type="date"]').each(function(idx, input) {
				input = $(input);
				
				input.datepicker({
					minDate: new Date(1970, 01, 01), //add min date to 1970-01-01, otherwise mysql will fail
					dateFormat: item.attr('dateFormat') ? item.attr('dateFormat') : 'yy-mm-dd',
				});
			});
			
			item.find('input[type="time"]').each(function(idx, input) {
				input = $(input);
				
				input.datetimepicker({
					showSecond: true,
			   		dateFormat: '',
					timeFormat: item.attr('timeFormat') ? item.attr('timeFormat') : 'HH:mm:ss'
				});
			});
		}
	}
	else if (typeof Modernizr != "undefined" && Modernizr && Modernizr.inputtypes.date && is_chrome) { 
		if (typeof item.datetimepicker != "undefined") {
			item.find('input[type="datetime"]').each(function(idx, input) {
				input = $(input);

				input.datetimepicker({
					controlType: 'select',
					oneLine: true,
					minDate: new Date(1970, 01, 01), //add min date to 1970-01-01, otherwise mysql will fail
					showSecond: true,
					dateFormat: input.attr('dateFormat') ? input.attr('dateFormat') : 'yy-mm-dd',
					timeFormat: input.attr('timeFormat') ? input.attr('timeFormat') : 'HH:mm:ss',
				});
			});
		}
	}
	else //Replace yyy-mm-dd hh:ii by yyy-mm-ddThh:ii if input is datetime-local
		item.find('input[type="datetime-local"]').each(function(idx, input) {
			input = $(input);
			var v = input.attr("value");
			 
			if (v && (/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2})(:([0-9]{1,2}))?$/).test(v))
				input.val( v.replace(' ', 'T') );
		});
        
	
	//preparing tables
	item.find(".list-container table.list-table").each(function(idx, table) {
		table = $(table);
		
		prepareTableSorting(table);
		prepareTableSearch(table);
		
		if (table_pagination_active && !tablePaginationAlreadyExists(table))
			prepareTablePagination(table, maximum_table_rows_shown);
	});
	
	//preparing editors
	createEditor( item.find('.form-group.editor textarea') );
}

function prepareTableSorting(table) {
	var getRowCellValue = function(tr, idx) { 
		var td = $(tr.children[idx]);
		return getCellValue(td);
	};
	
	var comparer = function(idx, asc) { 
		return function(a, b) { 
			return function(v1, v2) {
		   		return v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2);
	    		} (getRowCellValue(asc ? a : b, idx), getRowCellValue(asc ? b : a, idx));
		}
	};
	
	var tbody = table.children("tbody");
	var ths = table.find(" > thead > tr > th");
	var fields = ths.filter(".field");
	
	table.addClass("my-table-sorting");
	fields.addClass("sorting");
	ths.each(function(idx, th) {
		th = $(th);
		
		if (th.hasClass("field")) 
			th.bind('click', function() {
				table[0].asc = !table[0].asc;
				
				tbody.children("tr").toArray()
				  .sort(comparer(idx, table[0].asc))
				  .forEach(function(tr) { 
				  	tbody.append(tr);
				  });
				
				fields.addClass("sorting").removeClass("sorting-asc sorting-desc");
				
				if (table[0].asc)
					th.addClass("sorting-asc").removeClass("sorting-desc sorting");
				else
					th.addClass("sorting-desc").removeClass("sorting-asc sorting");
			});
	});
}

function prepareTableSearch(table) {
	var p = table.parent();
	var is_responsive = false;
	
	if (p.hasClass("table-responsive")) {
		p = p.parent();
		is_responsive = true;
	}
	
	var div = p.children(".my-table-search");
	
	if (!div[0]) {
		div = $('<div class="input-group mb-3 my-table-search"><span class="input-group-addon pt-1 pb-1 pl-2 pr-2 bg-light border border-right-0 rounded-left"><i class="fas fa-search align-middle"></i></span><input type="text" class="form-control" placeholder="Search in table..." title="Search in table"></div>');
		
		var input = div.children("input");
		input.bind("keyup", function() {
			searchInTable(table, input);
		});
		
		if (is_responsive)
			table.parent().before(div);
		else
			table.before(div);
	}
}

function prepareTablePagination(table, rows_shown) {
	rows_shown = $.isNumeric(rows_shown) ? rows_shown : 100;
	var p = table.parent();
	var is_responsive = false;
	
	if (p.hasClass("table-responsive")) {
		p = p.parent();
		is_responsive = true;
	}
	
	var elm_to_append = is_responsive ? table.parent() : table;
	var tbody = table.children("tbody");
	tbody = tbody[0] ? tbody : table;
	var trs = tbody.children("tr");
	var rows_total = trs.length;
	var num_pages = Math.ceil(rows_total / rows_shown);
	
	if (num_pages > 1) { //only create pagination if more than 1 page
		var tp = p.children(".my-table-pagination");
		
		if (!tp[0]) {
			var html = '<nav class="my-table-pagination mt-3"><ul class="pagination justify-content-center">';
			for(i = 0;i < num_pages;i++)
				html += '<li class="page-item"><a class="page-link" href="#" rel="' + i + '">' + (i + 1) + '</a></li>';
			html += '</ul></nav>';
			
			var tp = $(html);
			elm_to_append.after(tp);
			
			var lis = tp.find("li");
			lis.bind('click', function() {
				lis.removeClass('active');
				$(this).addClass('active');
				
				var curr_page = $(this).children("a").attr('rel');
				var start_item = curr_page * rows_shown;
				var end_item = start_item + rows_shown;
				
				trs.css('opacity', '0.0').hide();
				trs.slice(start_item, end_item).css('display', 'table-row').animate({opacity:1}, 300);
				//trs.hide().slice(start_item, end_item).css('display', 'table-row');
			});
		}
		else
			tp.show();
		
		trs.hide();
		trs.slice(0, rows_shown).show();
		tp.find("li").removeClass('active').first().addClass("active");
	}
	else
		trs.show();
}

function tablePaginationAlreadyExists(table) {
	return table.parent().closest(".list-container").parent().children(".top-pagination, .bottom-pagination").children().length > 0;
}

function getCellValue(td) { 
	var f = td.find(".field-value");
	
	if (f[0])
		return f.text();
	
	f = td.find("input, select, textarea");
	
	if (f[0])
		return f.val();
	
	return td.text(); 
}

function searchInTable(table, input) {
	var filter = input.val().toUpperCase();
	var trs = table.find(" > tbody > tr");
	var table_dynamic_pagination_shown = table_pagination_active && !tablePaginationAlreadyExists(table);
	
	//reset the pagination styles
	if (table_dynamic_pagination_shown)
		trs.css('opacity', 1);
	
	//prepare trs
	if (filter.replace(/\s+/g, "") == "") {
		if (table_dynamic_pagination_shown)
			prepareTablePagination(table, maximum_table_rows_shown);
		else
			trs.show();
	}
	else {
		trs.hide();
		
		var pagination_parent = table.parent().hasClass("table-responsive") ? table.parent().parent() : table.parent();
		pagination_parent.find(".my-table-pagination").hide();
		
		$.each(trs, function(idx, tr) {
			tr = $(tr);
			var tds = tr.children("td");
			var found = false;
			
			$.each(tds, function(idy, td) {
				td = $(td);
				
				if (td.hasClass("field")) {
					var v = getCellValue(td);
					
					if (v.toUpperCase().indexOf(filter) > -1) {
						found = true;
						return false;
					}
				}
			});
			
			if (found)
				tr.show();
			else
				tr.hide();
		});
	}
}

function createEditor(items) {
    $.each(items, function(idx, textarea) {
        if (textarea) {
            textarea = $(textarea);
            
            var h = parseInt(textarea.css("height"));
            var mh = parseInt(textarea.css("max-height"));
            mh = mh > 0 ? mh : $(window).height() - 200;
            h = h > 0 && h < mh ? h : mh;
            h = h > 200 ? h : 200;
        
            var upload_url = textarea.attr("editor-upload-url");
                
           var menubar = textarea[0].hasAttribute("menubar") ? (textarea.attr("menubar") == "" || textarea.attr("menubar") == 0 || textarea.attr("menubar").toLowerCase() == "false" ? false : true) : true;
           var toolbar = textarea[0].hasAttribute("toolbar") ? textarea.attr("toolbar") : 'bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect fontselect fontsizeselect | forecolor backcolor | bullist numlist | outdent indent | link unlink image media | blockquote';
           
           var tinymce_opts = {
               //theme: 'modern',
               height: h,
               plugins: [
                 'advlist autolink link image lists charmap preview hr anchor pagebreak',
                 'searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking',
                 'table contextmenu directionality emoticons paste textcolor textcolor colorpicker textpattern',
               ],
               toolbar: toolbar, 
               menubar: menubar,
               toolbar_items_size: 'small',
               image_title: false, // disable title field in the Image dialog
               image_description: false,// disable title field in the Image dialog
               convert_urls: false,// disable convert urls for images and other urls, this is, the url that the user inserts is the url that will be in the HTML.
           }
           
           if (upload_url) {
               tinymce_opts.paste_data_images = true;//enable direct paste of images and drag and drop.
               tinymce_opts.automatic_uploads = true;// enable automatic uploads of images represented by blob or data URIs
               tinymce_opts.file_picker_types = 'image';// here we add custom filepicker only to Image dialog
               
               tinymce_opts.file_picker_callback = function(cb, value, meta) {// and here's our custom image picker
                   var input = document.createElement('input');
                   input.setAttribute('type', 'file');
                   input.setAttribute('accept', 'image/*');
                   
                   // Note: In modern browsers input[type="file"] is functional without even adding it to the DOM, but that might not be the case in some older or quirky browsers like IE, so you might want to add it to the DOM just in case, and visually hide it. And do not forget do remove it once you do not need it anymore.
                   input.onchange = function() {
                       var file = this.files[0];
                       
                       // Note: Now we need to register the blob in TinyMCEs image blob registry. In the next release this part hopefully won't be necessary, as we are looking to handle it internally.
                       var id = 'userblobid' + (new Date()).getTime();//id will correspond to the uploaded file name.
                       if (file.name) {
                           id = file.name;
                           var pos = id.lastIndexOf(".");
                           id = pos != -1 ? id.substr(0, pos) : id;//remove extension
                       }
                       
                       var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                       var blobInfo = blobCache.create(id, file);
                       blobCache.add(blobInfo);
                       
                       // call the callback and populate the Title field with the file name
                       cb(blobInfo.blobUri(), { title: file.name });
                   };
                   
                   //input must be added to DOM, otherwise click event on IE and safari doesn't work.
                   input = $(input);
                   input.css("display", "none");
                   textarea.parent().append(input);
                   input.trigger('click');
               };
               
               tinymce_opts.images_upload_handler = function (blobInfo, success, failure) {// and here's our custom image  upload handler
                   if (("" + blobInfo.id()).indexOf('blobid') !== 0) {
                       // Show progress for the active editor
                       tinymce.activeEditor.setProgressState(true);

                       var xhr, formData;
                       
                       xhr = new XMLHttpRequest();
                       xhr.withCredentials = false;
                       xhr.open('POST', upload_url);
                       
                       xhr.onerror = function() {
                           failure("Image upload failed due to a XHR Transport error. Code: " + xhr.status);
                       };
                       
                       xhr.onload = function() {
                           var json;
                           
                           if (xhr.status != 200) {
                               failure('HTTP Error: ' + xhr.status);
                           
                               // Hide progress for the active editor
                               tinymce.activeEditor.setProgressState(false);
                               return;
                           }
                           
                           if (!xhr.responseText) {
                               failure('Invalid null response');
                           
                               // Hide progress for the active editor
                               tinymce.activeEditor.setProgressState(false);
                               return;
                           }
                           
                           try {
                               json = JSON.parse(xhr.responseText);
                           }
                           catch(e) {
                               json = null;
                           }
                           
                           if (!json || typeof json.url != 'string') {
                               failure('Invalid JSON: ' + xhr.responseText);
                           
                               // Hide progress for the active editor
                               tinymce.activeEditor.setProgressState(false);
                               return;
                           }
                           
                           success(json.url);
                           
                           // Hide progress for the active editor
                           tinymce.activeEditor.setProgressState(false);
                       };
                       
                       formData = new FormData();
                       formData.append('some_post_variable', 1);//bc the upload url must be a non-empty POST request
                       formData.append('image', blobInfo.blob(), blobInfo.filename());
                       
                       xhr.send(formData);
                   }
               };
           }
           
           textarea.tinymce(tinymce_opts);
   		
   		//set form on submit function. TinyMCE already does this, but only after our formChecker runs, which returns an error bc our formChecker detects that the textarea is empty.
   		var f = textarea.parent().closest('form');
   		var os = f.attr('onSubmit');
   		if (!os || os.indexOf('tinyMCE.triggerSave()') == -1)
   		    f.attr('onSubmit', 'tinyMCE.triggerSave();' + (os ? os : ''));
       }
    });
}
