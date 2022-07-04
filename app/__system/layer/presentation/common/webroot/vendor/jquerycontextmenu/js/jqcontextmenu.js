/* jQuery Context Menu
* Created: Dec 16th, 2009 by DynamicDrive.com. This notice must stay intact for usage 
* Author: Dynamic Drive at http://www.dynamicdrive.com/
* Visit http://www.dynamicdrive.com/ for full source code
* 
* http://www.dynamicdrive.com/dynamicindex1/contextmenu.htm
* 
* Some other changes were made by Joao Pinto - jplpinto.com
*/

var jquerycontextmenu = {
	//arrowpath: 'img/arrow.gif', //full URL or path to arrow image
	contextmenuoffsets: [1, -1], //additional x and y offset from mouse cursor for contextmenus
	
	//***** NO NEED TO EDIT BEYOND HERE
	
	selectedevent:null,
	debug:false,
	
	builtcontextmenuids: [], //ids of context menus already built (to prevent repeated building of same context menu)
	
	getselectedeventtarget:function() {
		if (this.selectedevent) {
			return this.selectedevent.target;
		}
		return null;
	},
	
	positionul:function($, $ul, e){
		var istoplevel=$ul.hasClass('jqcontextmenu'); //Bool indicating whether $ul is top level context menu DIV
		var docrightedge=$(document).scrollLeft()+$(window).width()-40; //40 is to account for shadows in FF
		var docbottomedge=$(document).scrollTop()+$(window).height()-40;
		if (istoplevel){ //if main context menu DIV
			var x=e.pageX+this.contextmenuoffsets[0]; //x pos of main context menu UL
			var y=e.pageY+this.contextmenuoffsets[1];
			x=(x+$ul.data('dimensions').w > docrightedge)? docrightedge-$ul.data('dimensions').w : x; //if not enough horizontal room to the ridge of the cursor
			y=(y+$ul.data('dimensions').h > docbottomedge)? docbottomedge-$ul.data('dimensions').h : y;
		}
		else{ //if sub level context menu UL
			var $parentli=$ul.data('$parentliref');
			var parentlioffset=$parentli.offset();
			var x=$ul.data('dimensions').parentliw; //x pos of sub UL
			var y=0;

			x=(parentlioffset.left+x+$ul.data('dimensions').w > docrightedge)? x-$ul.data('dimensions').parentliw-$ul.data('dimensions').w : x; //if not enough horizontal room to the ridge parent LI
			y=(parentlioffset.top+$ul.data('dimensions').h > docbottomedge)? y-$ul.data('dimensions').h+$ul.data('dimensions').parentlih : y;
		}
		$ul.css({left:x, top:y});
	},
	
	showbox:function($, $contextmenu, e){
		$contextmenu.show();
		
		var interval = $contextmenu.data("interval");
		if (interval)
			clearInterval(interval);
		
		interval = setInterval(function() {
			if (!$contextmenu.is(":hover")) {
				jquerycontextmenu.hidebox($, $contextmenu);
			}
		}, 5000);
		
		$contextmenu.data("interval", interval);
		
		this.selectedevent = e;
	},

	hidebox:function($, $contextmenu){
		var interval = $contextmenu.data("interval");
		if (interval)
			clearInterval(interval);
		
		$contextmenu.find('ul').andSelf().hide(); //hide context menu plus all of its sub ULs
	},
	
	buildcontextmenu:function($, $menu){
		if ($menu && $menu.get(0)) {
			$menu.css({display:'block', visibility:'hidden'}).appendTo(document.body);
			$menu.data('dimensions', {w:$menu.outerWidth(), h:$menu.outerHeight()}); //remember main menu's dimensions
			var $lis=$menu.find("ul").parent(); //find all LIs within menu with a sub UL
			$lis.each(function(i){
				var $li=$(this).css({zIndex: 1000+i});
				var $subul=$li.find('ul:eq(0)').css({display:'block'}); //set sub UL to "block" so we can get dimensions
				$subul.data('dimensions', {w:$subul.outerWidth(), h:$subul.outerHeight(), parentliw:this.offsetWidth, parentlih:this.offsetHeight});
				$subul.data('$parentliref', $li); //cache parent LI of each sub UL
				$li.data('$subulref', $subul); //cache sub UL of each parent LI
				$li.children("a:eq(0)").append( //add arrow images
					//'<img src="'+jquerycontextmenu.arrowpath+'" class="rightarrowclass" style="border:0;" />'
					'<div class="rightarrowclass"></div>'
				);
				$li.bind('mouseenter', function(e){ //show sub UL when mouse moves over parent LI
					var $targetul=$(this).data('$subulref');
					if ($targetul.queue().length<=1){ //if 1 or less queued animations
						jquerycontextmenu.positionul($, $targetul, e);
						$targetul.show();
					}
				});
				$li.bind('mouseleave', function(e){ //hide sub UL when mouse moves out of parent LI
					$(this).data('$subulref').hide();
				})
			});
			$menu.find('ul').andSelf().css({display:'none', visibility:'visible'}); //collapse all ULs again
			this.builtcontextmenuids.push($menu.get(0).id); //remember id of context menu that was just built
			
			$menu.bind('mouseleave', function(e) {
				jquerycontextmenu.hidebox($, $menu);
			});
		}
	},

	init:function($, $target, $contextmenu, options){
		if (jQuery.inArray($contextmenu.get(0).id, this.builtcontextmenuids) == -1) {//if this context menu hasn't been built yet
			this.buildcontextmenu($, $contextmenu);
			
			/*$(document).bind("click", function(e){
				if (e.button==0){ //hide all context menus (and their sub ULs) when left mouse button is clicked
					jquerycontextmenu.hidebox($, $('.jqcontextmenu'));
				}
			});*/
		}
		
		if ($target.parents().filter('ul.jqcontextmenu').length > 0) {//if $target matches an element within the context menu markup, don't bind oncontextmenu to that element
			return ;
		}
		
		var context_menu_func = function(e) {
			if (e.preventDefault) e.preventDefault(); 
			else e.returnValue = false;
		
			var status = true;
		
			if (options && options.callback) {
	  			var func = options.callback;

				if (typeof func == "function") {
					status = func($target, $contextmenu, e) ? true : false;
				}
			}
		
			if (status) {
				jquerycontextmenu.hidebox($, $contextmenu); //hide the context menu (and their sub ULs)
				jquerycontextmenu.positionul($, $contextmenu, e);
				jquerycontextmenu.showbox($, $contextmenu, e);
				jquerycontextmenu.hidebox($, $('.jqcontextmenu:not(#' + $contextmenu.attr("id") + ')'));//hide all context menus (and their sub ULs) except the current one
			}
		
			return false;
		};
	
		//console.log($target.data('events').contextmenu);
		$target.bind("contextmenu", context_menu_func);
		
		//For mobile devices: (It requires the jquery.taphold.js to be included)
		if (!options || options.notaphold != true)
			$target.on("taphold", {duration: 1500}, function(e) {
				//if draggable don't show menu
				if ($target.is('.ui-draggable-dragging'))
					return false;
				
				//disable future click events
				$target.css("pointer-events", "none");
				
				//Fix bug of the TapHold event for Mobile devices and Chrome, which don't have the pageX and pageY attributes.
				if ( (!e.hasOwnProperty("pageX") || !e.hasOwnProperty("pageY")) && 
				     (e.originalEvent && e.originalEvent.touches && e.originalEvent.touches[0])
				   ) {
					var touch = e.originalEvent.touches[0];
					e.pageX = touch.pageX;
					e.pageY = touch.pageY;
				}
				
				context_menu_func(e);
				
				setTimeout(function() {
					//re-enable future click events
					$target.css("pointer-events", "auto");
				}, 500);
				
				return false;
			});
	}
};

jQuery.fn.addcontextmenu=function(contextmenuid, options){
	var $ = jQuery;
	
	return this.each(function(){ //return jQuery obj
		var $target = $(this);
		var $contextmenu = $('#'+contextmenuid);
		
		if ($contextmenu && $contextmenu.get(0)) {
			jquerycontextmenu.init($, $target, $contextmenu, options);
		}
		else if (jquerycontextmenu.debug) {
			console.log("#" + contextmenuid + " html element is undefined!");
		
		}
	});
};


//Usage: $(elementselector).addcontextmenu('id_of_context_menu_on_page')
