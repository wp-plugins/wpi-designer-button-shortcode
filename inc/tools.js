var debug_mode=1;
(function($){
	var DEBUG=$(document).wpiDebug();
	DEBUG.setState("tools");
	
	$.fn.wpiTools=function(){
		function WPiTools(){
			this.init();
		};
		WPiTools.prototype.init=function(){
			
		};
		WPiTools.prototype.hex2rgb=function(color){
			 if (result = /#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(color)) {
				return [parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16)];
			 }else{
				return [0,0,0];
			 }
		};
		WPiTools.prototype.rgb2hex=function(rgb){
			if(rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/)){			
				return "#" + ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) + ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +  ("0" + parseInt(rgb[3],10).toString(16)).slice(-2);
			}else{
				return "#999999";
			}
		}
		WPiTools.prototype.setColor=function(val){
			if(val!=""){
				val= val.replace(/ +/g, "");
				val= val.replace(/#/g, "");
				var str1 = "#";			
				val = str1.concat(val);				
			}
			return val;
		};
		WPiTools.prototype.issetArray=function( name, arr ) {			
			var ret=false;
			$.each(arr, function(k, v){					
				if( k==name ){	
					//DEBUG.setState("Create Holder section length "+k+" "+v);
					ret=true;
					return;
				};			
			});	
			return ret;						
		};
		
		return new WPiTools();		
	};	
	$.fn.wpiSetPosition=function(args){   
		function WPiSetPosition(args){
			var defaults={e:"",p:"", o:"", hPos:"center", vPos:"top", hOut:false, vOut:true, vGap:10, hGap:0};
			var args=$.extend(defaults,args);
			this.d=$(document);    
			this.p=args["p"];
			this.e=args["e"];
			this.o=args["o"]; 
			this.hPos=args["hPos"]; 
			this.vPos=args["vPos"]; 
			this.vGap=args["vGap"]; 
			this.hGap=args["hGap"]; 
			this.vOut=args["vOut"]; 
			this.hOut=args["hOut"];
			this.position={left:0, top:0};
			this.init();
		}
		WPiSetPosition.prototype.init=function(){
			this.pow=parseInt(this.p.outerWidth()); 
			this.poh=parseInt(this.p.outerHeight()); 
			this.eow=parseInt(this.e.outerWidth());
			this.eoh=parseInt(this.e.outerHeight());
			this.oow=parseInt(this.o.outerWidth()); 
			this.ooh=parseInt(this.o.outerHeight());
			
			var pt=parseInt(this.p.offset().top);
			var pl=parseInt(this.p.offset().left);
			var edt=parseInt(this.e.offset().top);
			var edl=parseInt(this.e.offset().left);
			
			this.et=edt-pt;
			this.el=edl-pl;   
			this.evc=this.eoh/2;
			this.pc=this.pow/2;
			this.oc=this.oow/2; 
			this.ovc=this.ooh/2;
			
			var hPos=this.setHPos();
			var vPos=this.setVPos();
			
			if(vPos!=0 && vPos!="") this.position['top']=vPos;
			if(hPos!=0 && hPos!="") this.position['left']=hPos;
		};
		WPiSetPosition.prototype.getPos=function(){
			return this.position;
		};
		WPiSetPosition.prototype.setHPos=function(){
			var out=0;
			if(this.hPos=="left"){
				if(this.hOut==true){       
				  outside=this.oow+this.hGap;
				}else{
				  outside=this.hGap*(-1);
				}
				out=this.el-outside;
			}else if(this.hPos=="right"){
				if(this.hOut==true){       
				  outside=this.hGap;
				}else{
				  outside=(this.oow+this.hGap)*(-1);
				}
				out=(this.el+this.eow)+outside;
			}else if(this.hPos=="center"){
				out=(this.pc-this.oc);
			}      
			return out;
		};
		WPiSetPosition.prototype.setVPos=function(){
			var outside=0;
			var out=0;			
			if(this.vPos=="bottom"){
				if(this.vOut==true){
				  outside=this.vGap;
				}else{
				  outside=(this.ooh+this.vGap)*(-1);
				}
				out=(this.et+this.eoh)+outside;
			}else if(this.vPos=="top"){
				if(this.vOut==true){       
				  outside=this.ooh+this.vGap;
				}else{
				  outside=this.vGap*(-1);
				}
				out=this.et-outside;
			}else if(this.vPos=="center"){
				out=(this.et+this.evc)-this.ovc;
			}else{       
				out=this.et-outside;
			}      
			return out;
		};    
		var object=new WPiSetPosition(args);
		return object.getPos();;
	};
	$.fn.wpiColors=function(){
		function WPiColors(){
			this.init();
		};
		WPiColors.prototype.init=function(){				
			this.color_levels=["00","11","22","33","44","55","66","77","88","99","aa","bb","cc","dd","ee","ff"];
			this.color_levels2=["0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"];
			this.codes={0:0, 1:1, 2:2, 3:3, 4:4, 5:5, 6:6, 7:7, 8:8, 9:9, "a":10, "b":11, "c": 12, "d":13, "e":14, "f":15};	
			
			this.levels={0:0, 1:1, 2:2,3:3,4:4,5:5,6:6,7:7,8:8,9:9,"a":10,"b":11,"c":12,"d":13,"e":14,"f":15};
			this.hexa={0:0, 1:1, 2:2,3:3,4:4,5:5,6:6,7:7,8:8,9:9,10:"a",11:"b",12:"c",13:"d",14:"e",15:"f"};
			this.mainColors=[];
			this.createMainColors();
			//return this.mainColors;
		};
		WPiColors.prototype.createMainColors=function(){
			var rgb=[0,1,2];
			var count=0;
			var c=0, n=1, p=2;
			var self=this;
			for(j=0; j<=15; j=j+3){
				rgb[0]=j;
				rgb[1]=j;
				rgb[2]=j;						
				this.mainColors[count++]="#"+this.color_levels[rgb[0]]+this.color_levels[rgb[1]]+this.color_levels[rgb[2]];
			};
			for(i=0; i<3; i++){
				for(j=0; j<=15; j=j+5){
					rgb[c]=15;
					rgb[n]=j;
					rgb[p]=0;						
					this.mainColors[count++]="#"+this.color_levels[rgb[0]]+this.color_levels[rgb[1]]+this.color_levels[rgb[2]];
				};
				for(j=13; j>=0; j=j-5){
					rgb[c]=j;
					rgb[n]=15;
					rgb[p]=0;						
					this.mainColors[count++]="#"+this.color_levels[rgb[0]]+this.color_levels[rgb[1]]+this.color_levels[rgb[2]];
				};
				c++; n++; p++;
				if(c==3)c=0;
				if(n==3)n=0;
				if(p==3)p=0;
			};
			//this.mainColors=["#ff0000", "#ffff00", "#ff00ff", "#00ffff"];
		};
		WPiColors.prototype.getMainColors=function(){
			return this.mainColors;
		};
		WPiColors.prototype.variations=function(color){
			var variations={};
			variations.darkness=new Array(color,"#eeeeee","#cccccc","#aaaaaa","#888888","#666666","#444444");
			variations.lightness=new Array("#ffffff","#eeeeee","#cccccc","#aaaaaa","#888888","#666666","#444444");
			variations.saturation=new Array("#ffffff","#eeeeee","#cccccc","#aaaaaa","#888888","#666666","#444444");
			variations.desaturation=new Array("#ffffff","#eeeeee","#cccccc","#aaaaaa","#888888","#666666","#444444");	
			return variations;
		};
		WPiColors.prototype.getVariations=function(color){			
			var colorNumbers=this.getColorNumbers(color);			
			var variations={lightness:[], darkness:[],desaturation:[]};
			var max_val=0;
			var min_val=0;
			max_val=colorNumbers[0];
			min_val=colorNumbers[0];
			if(colorNumbers[1]>max_val) max_val=colorNumbers[1];
			if(colorNumbers[2]>max_val) max_val=colorNumbers[2];
			if(colorNumbers[1]<min_val) min_val=colorNumbers[1];
			if(colorNumbers[2]<min_val) min_val=colorNumbers[2];
			var avg_val=max_val-((max_val-min_val)/2);
			
			for(i=0; i<10; i++){ 
				var colors1=[];
				var colors2=[];
				var colors3=[];				
				var percent=(i*10)/100;
				
				for(j=0;j<=2;j++){					
					colors1[j]=colorNumbers[j]+Math.round((165-colorNumbers[j])*percent);
					colors2[j]=colorNumbers[j]-Math.round(colorNumbers[j]*percent);					
				}				
				for(j=0;j<=2;j++){					
					if(colorNumbers[j]>avg_val){ 
						colors3[j]=Math.round(colorNumbers[j]-((colorNumbers[j]-avg_val)*percent));
					}else{ 
						colors3[j]=Math.round(colorNumbers[j]+((avg_val-colorNumbers[j])*percent));
					}
				}				
				variations.lightness[i]=this.getColorHexa(colors1); 				
				variations.darkness[i]=this.getColorHexa(colors2);				
				variations.desaturation[i]=this.getColorHexa(colors3);
				DEBUG.setState("deSaturation: "+colors3[0]+":"+colors3[1]+":"+colors3[2]);				
			}
			$.each(variations.lightness, function(key,val){				
				DEBUG.setState(val);
			});
			return variations;
		};
		WPiColors.prototype.getColorHexa=function(colors){      
			var hexa=[];
			var r=this.getDiv(colors[0]); 
			var g=this.getDiv(colors[1]); 
			var b=this.getDiv(colors[2]); 
			hexa[0]=r[0]; 
			hexa[1]=r[1]; 
			hexa[2]=g[0]; 
			hexa[3]=g[1];
			hexa[4]=b[0]; 
			hexa[5]=b[1];     
			var hexaColor="#"+hexa.join("");
			return hexaColor;
		};
		WPiColors.prototype.getDiv=function(val){      
			var out=[];
			var mod=val % 11; 
			var div=Math.round(val/11);      
			if(mod==0) mod=div;      
			out[0]=this.hexa[div];
			out[1]=this.hexa[mod];
			return out;   
		};    
		WPiColors.prototype.getColorNumbers=function(color){ 
			var self=this;
			color=color.replace("#","");      
			var colorLevels=[];
			var colorCodes=[];
			var splitColor=color.split("");
			$.each(splitColor, function(key,val){
				colorLevels[key]=self.levels[val];       
			});
			colorCodes[0]=(colorLevels[0]*10)+colorLevels[1];
			colorCodes[1]=(colorLevels[2]*10)+colorLevels[3]; 
			colorCodes[2]=(colorLevels[4]*10)+colorLevels[5]; 
			return colorCodes; 
		};
		WPiColors.prototype.addHash=function(){
				
		};
		WPiColors.prototype.removeHash=function(){
				
		};
		WPiColors.prototype.rgb2hex=function(rgb){
			rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);				
			return "#" + ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) + ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +  ("0" + parseInt(rgb[3],10).toString(16)).slice(-2);
		}
		return  new WPiColors();		
	}; /* >> WPiColors */
	
	$.fn.wpiCss=function(){
		function WPiCss(){			
			this.init();
			this.wpiTools=$(document).wpiTools();
		};
		WPiCss.prototype.init=function(){	
			
		};
		WPiCss.prototype.buildCss=function(elementClass){	
			var styles=elementClass['styles'];
			styles=this.checkCustomCss(styles);
			styles=this.setClass(styles);
			$.each(styles, function(key,val){
				styles[key]=val;						
			});	
			elementClass['element'].css(styles);			
		};
		WPiCss.prototype.checkCustomCss=function(styles){
			$.each(styles, function(key, val){
				if(key=="text-shadow-distance" && val!=""){					
					styles['text-shadow-x']=val;
					styles['text-shadow-y']=val;
					styles['text-shadow-blur']=val;
				};								
			});	
			return styles;
		};
		WPiCss.prototype.setClass=function(styles){
			styles=this.setShadow(styles);
			styles=this.setBlur(styles);
			return styles;
		};
		WPiCss.prototype.setShadow=function(styles){
			var shadow=false;
			var defaults={"text-shadow-color":"#000000", "text-shadow-opacity":0.3, "text-shadow-x":"1px", "text-shadow-y":"1px", "text-shadow-blur":"1px", "text-shadow-inset":""};
			$.each(defaults, function(key,val){
				if(typeof styles[key] !== "undefined" && styles[key]!=""){
					shadow=true;
				}
			});			
			var styles_dummy=$.extend(defaults, styles);
			styles=$.extend(styles, styles_dummy);
			var rgb=this.wpiTools.hex2rgb(styles['text-shadow-color']);
			var color="rgba("+rgb[0]+", "+rgb[1]+", "+rgb[2]+", "+styles['text-shadow-opacity']+")";
			var style=styles['text-shadow-inset']+" "+styles['text-shadow-x']+" "+styles['text-shadow-y']+" "+styles['text-shadow-blur']+" "+color;
			if(shadow==true){				
				styles['-webkit-text-shadow']=style;
				styles['-moz-text-shadow']=style;
				styles['-ms-text-shadow']=style;
				styles['-o-text-shadow']=style;
				styles['text-shadow']=style;
				DEBUG.setState(style);
			}
			return styles;
		};
		WPiCss.prototype.setBlur=function(styles){
			var blur=false;
			var defaults={"blur":"0px"};
			$.each(defaults, function(key,val){
				if(typeof styles[key] !== "undefined" && styles[key]!=""){
					blur=true;
				}
			});			
			var styles_dummy=$.extend(defaults, styles);
			styles=$.extend(styles, styles_dummy);	
			var style="blur("+styles['blur']+")";
			if(blur==true){				
				styles['-webkit-filter']=style;
				styles['-moz-filter']=style;
				styles['-ms-filter']=style;
				styles['-o-filter']=style;
				styles['filter']=style;
				DEBUG.setState("Blur: "+style);
			}
			return styles;
		};
		WPiCss.prototype.test=function(){
			alert(1);
		};
		return new WPiCss();
	}; /* WPiCss */
})(jQuery);