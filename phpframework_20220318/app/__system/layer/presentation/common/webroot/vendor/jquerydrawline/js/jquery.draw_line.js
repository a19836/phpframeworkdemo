var id=0;

function drawLineOnMouseClick() {
    var linkLine = $('<div id="new-link-line-' + id + '" class="new-link-line">').appendTo('body');
    
    linkLine
        .css('top', $('#origin-point').offset().top + $('#origin-point').outerWidth() / 2)
        .css('left', $('#origin-point').offset().left + $('#origin-point').outerHeight() / 2);
    
    $(document).bind('mousedown.link', function(event) {
    	   if(event.which != 3) {
            $(document).mousemove(linkMouseMoveEvent);
        }
        else {
        	endLinkMode();
        }
    });
	
    $(document).bind('mouseup.link', function(event) {
       callback(event);
       
       id++;
      
       $(document).mousemove(function(){});
       $(document).bind('mousedown.link', function(event) {});
       $(document).bind('mouseup.link', function(event) {});
    	  $(document).bind('keydown.link', function(event) {});
    });

    $(document).bind('keydown.link', function(event) {
        // ESCAPE key pressed
        if(event.keyCode == 27) {
            endLinkMode();
        }
    });
}
    
function linkMouseMoveEvent(event) {
    if($('#new-link-line-' + id).length > 0) {
        var originX = $('#origin-point').offset().left + $('#origin-point').outerWidth() / 2;
        var originY = $('#origin-point').offset().top + $('#origin-point').outerHeight() / 2;
        
        var length = Math.sqrt((event.pageX - originX) * (event.pageX - originX) 
            + (event.pageY - originY) * (event.pageY - originY));
    
        var angle = 180 / 3.1415 * Math.acos((event.pageY - originY) / length);
        if(event.pageX > originX)
            angle *= -1;
    
        $('#new-link-line-' + id)
            .css('height', length)
            .css('-webkit-transform', 'rotate(' + angle + 'deg)')
            .css('-moz-transform', 'rotate(' + angle + 'deg)')
            .css('-o-transform', 'rotate(' + angle + 'deg)')
            .css('-ms-transform', 'rotate(' + angle + 'deg)')
            .css('transform', 'rotate(' + angle + 'deg)');
    }
}
    
function endLinkMode() {
    $('#new-link-line-' + id).remove();
    $(document).unbind('mousemove.link').unbind('click.link').unbind('keydown.link');
}

function callback(event) {
	//alert(event.pageX + ":" + event.pageY);
}
