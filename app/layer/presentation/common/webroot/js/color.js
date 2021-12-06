var curColourIndex = typeof curColourIndex != "undefined" && curColourIndex >= 0 ? curColourIndex: 1;
var maxColourIndex = typeof maxColourIndex != "undefined" && maxColourIndex >= 0 ? maxColourIndex: 24;

if(typeof nextColor !== 'function') {
	function nextColor() {
		var R, G, B;
		R = parseInt(128 + Math.sin((curColourIndex * 3 + 0) * 1.3) * 128);
		G = parseInt(128 + Math.sin((curColourIndex * 3 + 1) * 1.3) * 128);
		B = parseInt(128 + Math.sin((curColourIndex * 3 + 2) * 1.3) * 128);
	
		curColourIndex = curColourIndex + 1;
	
		if (curColourIndex > maxColourIndex) {
			curColourIndex = 1;
		}
	
		return "rgb(" + R + "," + G + "," + B + ")";
	}
}

if(typeof randomColor !== 'function') {
	function randomColor() {
		var R, G, B;
	
		var max = 255;
		var min = 0;
	
		R = Math.floor(Math.random() * (max - min)) + min;
		G = Math.floor(Math.random() * (max - min)) + min;
		B = Math.floor(Math.random() * (max - min)) + min;
	
		return "rgb(" + R + "," + G + "," + B + ")";
	}
}

if(typeof getContrastYIQ !== 'function') {
	//http://24ways.org/2010/calculating-color-contrast/
	//alert( getContrastYIQ("#EF4444") ); //white
	function getContrastYIQ(hexcolor){
		hexcolor = hexcolor.substr(0, 1) == "#" ? hexcolor.substr(1) : hexcolor;
		
		var r = parseInt(hexcolor.substr(0, 2), 16);
		var g = parseInt(hexcolor.substr(2, 2), 16);
		var b = parseInt(hexcolor.substr(4, 2), 16);
		
		var yiq = ((r*299)+(g*587)+(b*114))/1000;
		
		return (yiq >= 128) ? 'black' : 'white';
	}
}

if(typeof getContrast50 !== 'function') {
	//http://24ways.org/2010/calculating-color-contrast/
	//alert( getContrast50("#EF4444") ); //black
	function getContrast50(hexcolor){
	    	hexcolor = hexcolor.substr(0, 1) == "#" ? hexcolor.substr(1) : hexcolor;
		
		return (parseInt(hexcolor, 16) > 0xffffff/2) ? 'black' : 'white';
	}
}

if(typeof hexToRgb !== 'function') {
	//alert( hexToRgb("#0033ff").g ); // "51";
	//alert( hexToRgb("#03f").g ); // "51";
	function hexToRgb(hex) {
	    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
	    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
	    hex = hex.replace(shorthandRegex, function(m, r, g, b) {
		return r + r + g + g + b + b;
	    });

	    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
	    
	    return result ? {
		r: parseInt(result[1], 16),
		g: parseInt(result[2], 16),
		b: parseInt(result[3], 16)
	    } : null;
	}
}

if(typeof rgbToHex !== 'function') {
	function componentToHex(c) {
		var hex = parseInt(c).toString(16);
		return hex.length == 1 ? "0" + hex : hex;
	}

	//alert( rgbToHex(0, 51, 255) ); // #0033ff
	function rgbToHex(r, g, b) {
		return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
	}
}

if(typeof backgroundRgbToHex !== 'function') {
	//alert( backgroundRgbToHex("rgb(0, 51, 255)") ); // #0033ff
	function backgroundRgbToHex(bg) {
		bg = bg.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
		return rgbToHex(bg[1], bg[2], bg[3]);
	}
}


//Leave this code here, because is adding the TRIM function to the IE browsers. Otherwise the browser gives errors.
if(typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, ''); 
  }
}

//Leave this code here, because is adding the hashCode function to all browsers.
if(typeof String.prototype.hashCode !== 'function') {
	String.prototype.hashCode = function(){
		var hash = 0;
		if (this.length == 0) return hash;
		for (i = 0; i < this.length; i++) {
			char = this.charCodeAt(i);
			hash = ((hash<<5)-hash)+char;
			hash = hash & hash; // Convert to 32bit integer
		}
		return hash;
	}
}

