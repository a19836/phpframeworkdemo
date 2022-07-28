function MyHandler() {
	var me = this;
	
	me.security_code = 'security_action_code';
	
	me.getAjaxRequestResult = function(url, parameters) {
		try {
			if(url && url.indexOf("#") == -1) {
				if(me.security_code) {
					var security_code_value = MyJSLib.CookieHandler.getCookie(me.security_code);
					security_code_value = security_code_value ? security_code_value : 0;
					parameters = parameters && typeof(parameters) == "string" ? parameters : "";
					parameters += "&" + encodeURIComponent(me.security_code) + "=" + encodeURIComponent(security_code_value);
				}
				return MyLib.AjaxHandler.posts(url, {parameters : parameters});
			}
		}catch(e) {
			alert(e && e.message ? e.message : e);
		}
	};
	
	me.extrudes = function(obj) {
		if(obj) {
			if(MyLib.isObject(obj)) {
				for(key in obj) {
					eval("me." + key + " = obj." + key);
				}
				
				if(MyLib.isFunction(me.setMe))
					me.setMe(me);
				else 
					alert("Obj "+obj+" should contain the setMe function!");
			}
		}
	};
}
