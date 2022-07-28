function NewObject(prefix) {
    var me = this;
    this.count=0;
    
    me.SayHello=function(msg) {
	     me.count++;
	     alert(prefix+msg);
    };
    
    this.GetCount=function() {
	     return this.count;
    };
}

var obj = new NewObject("Message : \n ");
obj.SayHello("You are welcome.");

var XClass = {
	foo : function(y) {
		y += this.bar();
		
		return y;
	},
	
	bar : function() {
		return " World! \n";
	}
};

var x = XClass.foo("Hello");
alert(x);
