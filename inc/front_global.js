var debug_mode=1;
;(function($){
	$.fn.wpiDebug=function(){
		function Debug(el){
			this.$el=el;
			this.mode=1;
			this.state="";
			this.init();	
		};
		Debug.prototype.init=function(){
			this.mode=debug_mode;
			this.setState("debug");
		};
		Debug.prototype.setState=function(state){
			if(this.mode==1){
				this.state=state;
				console.log(this.state);
			}
		};		
		return new Debug(this);
	};
	
	var DEBUG=$(document).wpiDebug();
	
	$.fn.wpiTicker=function(options){			
		function WPiTicker(el,options){
			this.delay=options['delay'];
			this.animate=options['animate'];
			this.$el=$(el);
			this.$items=this.$el.children();
			this.itemFirst=this.$items.first();			
			this.itemsLength=0;
			this.elHeight=0;			
			this.itemMargin=0;						
			this.animation="";	
			if(this.animate=="play"){
				this.init_front();	
			}
		};
		WPiTicker.prototype.init=function(){
			DEBUG.setState("wpiTicker Init");					
			this.parentPadding=parseInt(this.getStyle(this.$el,"padding"));	
			this.initStyle();
			this.setHeight();	
			this.tickerAnimation();	
			this.resize();	
		};	
		WPiTicker.prototype.init_front=function(){
			DEBUG.setState("wpiTicker Init_front");					
			this.parentPadding=parseInt(this.$el.css("padding"));			
			if(!this.checkItemsLength_front()) return;	
			this.initStyle();
			this.setHeight();	
			this.tickerAnimation();	
			this.resize();
		};		
		WPiTicker.prototype.checkItemsLength_front=function(){	
			this.itemsLength=this.$items.length;
			if(this.itemsLength<=1) {
				this.animate=false;  
				return false;
			}			
			return true;
		}			
		WPiTicker.prototype.checkItems=function(){				
			var length=this.$items.length;
			this.$items.each(function(){
				if($(this).first().text()=="") {
					$(this).css({"display":"none"});
					length=length-1;					
				}else{
					$(this).css({"display":"block"});
				}			
			});				
			this.itemsLength=length;
			if(this.itemsLength<=1){
				this.$el.css({"overflow":"visible"});				
			}
			
			DEBUG.setState("wpiTicker itemsLength"+length);
		}
		WPiTicker.prototype.tickerAnimation=function(){
			if(this.animate=="stop"){
				this.stopAnimation();				
			}else{				
				this.startAnimation();
			}
		};
		WPiTicker.prototype.stopAnimation=function(){	
			clearInterval(this.animation);	
			this.$el.css({height:"auto", "padding":this.parentPadding});
			this.$items.css({height:"auto","line-height":"auto", "padding":"0px"});
			this.itemFirst.siblings().css({ "display":"none"});
			this.itemMargin=0;
			this.setMargin(this)
		}
		WPiTicker.prototype.startAnimation=function(){	
			clearInterval(this.animation);					
			this.checkItems();	
			this.setTicker();
			var self=this;	
			this.animation=setInterval(function(){self.setMargin(self);}, this.delay);
		}		
		WPiTicker.prototype.setTicker=function(){	
			this.$el.css({"height":this.elHeight,"padding":"0px"});
      		this.$items.css({"height":this.elHeight});	
			this.$items.children().css({"line-height":"1.2em", "display": "inline-block","vertical-align": "middle"});
			this.totalMargin=this.elHeight*(this.itemsLength-1);			
		};
		WPiTicker.prototype.setHeight=function(){	
			var self=this;
			var maxHeight=0;
			this.$items.css({"height":"auto","line-height":"auto","padding":this.parentPadding});			
			this.$items.each(function(){
				var height=($(this).outerHeight()*(1))+20;
				if(height>maxHeight) maxHeight=height;	
				DEBUG.setState("setHeight maxHeight: "+maxHeight+" parentPadding: "+$(this).css("padding")+" height: "+height );
			});
			
			this.elHeight=maxHeight;			
			
		};
		WPiTicker.prototype.setMargin=function(self){			
			self.itemFirst.css({"margin-top":self.itemMargin});
			self.itemMargin=self.itemMargin-self.elHeight;
			if (self.itemMargin<(self.totalMargin*-1)){self.itemMargin=0;}			
		};
		WPiTicker.prototype.resize=function(){
			var self=this;
			var elHeight=this.elHeight;
			$(window).resize(function(event){
				self.setHeight();
				self.tickerAnimation();				
			}).trigger("resize");			
		};		
		WPiTicker.prototype.update=function(args){				
			var defaults={parentPadding:0, animate:"stop"};
			args=$.extend(defaults,args);
			this.animate=args["animate"];
			this.parentPadding=parseInt(args["parentPadding"]);
			this.setHeight();	
			this.tickerAnimation();
			DEBUG.setState("wpiTicker animate: "+this.animate+" parentPadding: "+this.parentPadding);					
		};		
		WPiTicker.prototype.initStyle=function(){	
			this.itemFirst.css({display:"block",transition:"margin-top 0.3s ease-in-out"});	
			this.$el.css({position:"relative", overflow:"hidden"});			
		}	
		WPiTicker.prototype.getStyle=function($el, s){	
			var styleVal="";			
			var st=$el.attr("style").replace(" ","");			
			var split=st.split(";");
			var styles={};
			
			$.each(split, function(key,val){ 				
				var spil=val.split(":");  				
				if(spil[1]!=""){						
					var k=spil[0].replace(" ","");				
					if(k==s){						
						styleVal=spil[1];	
						return true;
					}       
				}
			}); 
			DEBUG.setState("wpiTicker padding"+styleVal);
			return styleVal;
		}
		
		var defaults={store:false,animate:"stop", delay:2500};
		var options=$.extend(defaults, options);
		if(options['store']==true){
			return new WPiTicker(this,options);		
		}else{
			return this.each(function(){
				new WPiTicker(this,options);
			});
		}
	}
		  
})(jQuery);