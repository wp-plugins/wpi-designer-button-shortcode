
;(function($, doc, win){	
	$(document).ready(function(){
		//"use strict";	
		var TOOLS=$(document).wpiTools();
		var DEBUG=$(document).wpiDebug();
		DEBUG.setState("global");
		
		$.fn.wpiCreateUI=function(){
			function WPiCreateUI(doc){
				
			};
			WPiCreateUI.prototype.createHolder=function(data){
				var section_names=[];
				var sections={};
				$.each(data, function(key, val){	
					var isset=TOOLS.issetArray('section',val);
					DEBUG.setState("Create Holder isset "+isset);
					if(TOOLS.issetArray('section',val)){
						DEBUG.setState("Create Holder data name "+val['section']);						
						var section=val['section'];						
						section_names.push(section);						
					};
				});					
				$.each(section_names, function(key, val){					
					sections[val]=[];
					sections[val]['id']=val.toLowerCase().replace(" ", "_");
					sections[val]['data']=[];
				});
				$.each(data, function(key, val){
					if(TOOLS.issetArray('section',val)){
						var section=val['section'];							
						sections[section]['data'].push(val);
					};	
				});					
				var out="";
				out+="<div class='wpiHolder wpi_2c wpi_x1c'>";
					out+="<div class='wpi_header'><span class='wpi_menu genericon genericon-menu'><div class='wpi_back genericon genericon-previous'></div></span><span class='wpi_heading'>Settings</span></div>";
					out+="<div class='wpi_content'><div class='wpi_content_holder'>";
						out+="<div class='wpi_sections'>";
						$.each(sections, function(key, val){
							DEBUG.setState("Create Holder section data : "+key);	
							out+="<div class='wpi_section' data-target_id='"+val['id']+"' data-target='"+val['id']+"'>"+key+"<div class='wpi_open genericon genericon-next'></div></div>";			
						});
						out+="</div>";
						out+="<div class='wpi_sections_content'>";
						$.each(sections, function(key, val){
							out+="<div class='wpi_section_content' id='"+val['id']+"'>";
							var args=[];
							$.each(val['data'], function(k, v){
								args.push({
									label:v['label'],
									name:v['name'],
									content:v['content'],	
								});
							});
							out+=CREATEUI.createAccordion(args);
							out+="</div>";
						});
						out+="</div>";					
					out+="</div></div>";
				out+="</div>";
				return out;
			};
			WPiCreateUI.prototype.createAccordion=function(data){
				var wpi_open="wpi_open";
				wpi_open="";
				var out="";
				out+="<div class='wpiAccordion'>";
					$.each(data, function(key, val){						
						out+="<li id='popup_"+val['name']+"' class='"+wpi_open+"'><h3>"+val['label']+"<i class='genericon genericon-next'></i></h3><div>"+val['content']+"</div></li>";
						if(wpi_open!="") wpi_open="";
					});				
				out+="</div>";	
				return out;
			}
			return new WPiCreateUI(this);
		};	
		$.fn.wpiCaptureData=function(){
			function WPiCaptureData(){
				
			};
			WPiCaptureData.prototype.select=function(args){
				var options=args['id'].find("option");
				var out=[];
				options.each(function(key,val){	
					out[key]={};
					out[key]['label']=$(val).text();
					out[key]['value']=$(val).val();
				});    
				return  out;
			};
			return new WPiCaptureData(this);
		};
		
		
		
		var CAPTUREDATA=$(document).wpiCaptureData();
		var CREATEUI=$(document).wpiCreateUI();
		
		$.fn.wpiModal=function(args){			
			function WPiModal(options){				
				this.init(options);
			}
			WPiModal.prototype.init=function(options){	
				var self=this;
				this.$modal=$(options.element);
				this.$modal_overlay=$(options.overlay);
				this.$modal_close=this.$modal.find(options.close);	
				this.$modal_content=this.$modal.find(".wpiModal_content");
				this.$modal_header=this.$modal.find(".wpiModal_header");
				
				this.$modal_close.click(function(){
					self.close_modal();														 
				});
			}
			WPiModal.prototype.close_modal=function(){		
				this.$modal_overlay.addClass("wpi_none");
				this.$modal.addClass("wpi_none");
			}
			WPiModal.prototype.open_modal=function(args){
				var defaults={					
					heading:"heading",
					content:"content",					
				};    
				var options=$.extend(defaults,args);  
				this.$modal_overlay.removeClass("wpi_none");
				this.$modal.removeClass("wpi_none");
				this.$modal_header.html(options.heading);
				this.$modal_content.html("").append(options.content);
			}
			var defaults={element:".wpiModal", overlay:".wpiModal_overlay", close:".wpiModal_close"};
			var options=$.extend(defaults,args);  
			return new WPiModal(options);
		};
		
		$.fn.wpiUI=function(){
			function WPiUI(el){
				this.$el=$(el);
				this.init();	
			}
			WPiUI.prototype.init=function(){			
				this.wpImage();
			}
			WPiUI.prototype.wpImage=function(){
				var self=this;
				this.$wp_image_buttons=this.$el.find(".wpi_wp_image_button");
				this.$wp_image_buttons.each(function(){	
					var button=$(this);
					var image=button.siblings(".wpi_wp_image");
					var remove_button=button.siblings(".wpi_wp_image_remove_button");
					var image_preview=button.siblings(".wpi_wp_image_preview");
					var image_no_preview=button.siblings(".wpi_wp_image_no_preview");
					button.click(function(event){
						event.preventDefault(); 
						window.send_to_editor = function(html) {
							imgurl = $('img',html).attr('src');
							image.val(imgurl);
							image_preview.attr("src", imgurl);
							image_no_preview.css({display:"none"});
							button.text("Change Image");
							image.trigger("change");
							tb_remove();			
						}				
						tb_show('', 'media-upload.php?post_id=1&type=image&TB_iframe=true');					
					});		
					remove_button.click(function(event){
						event.preventDefault(); 
						image.val("");
						button.text("Select Image");
						image_preview.attr("src","");
						image_no_preview.css({display:"block"});
						image.trigger("change");					
					});
				});
			}
			return new WPiUI(this);		
		};	
		
		$.fn.set_tabs=function(){		
			var SetTabs={
				el:"",
				tab:"",
				tab_content:"",
				tabs_content:"",
				init:function(el){				
					this.el=$(el);
					this.set_tabs();
					this.set_toggle();
				},
				test:function(){
					alert("test");
				},
				call_tab:function(call_tab){				
					var href=$(call_tab).attr("href");	
					this.tab.removeClass("active");				
					this.el.find('a[href="'+href+'"]').addClass("active");
					this.tab_content.addClass("wpi_none");			
					this.tabs_content.find(href).removeClass("wpi_none");
				},
				set_tabs:function(){
					this.tab=this.el.find("a.wpi_tab");
					this.tabs_content=this.el.siblings(".wpi_tabs_content");
					this.tab_content=this.tabs_content.find(".wpi_tab_content");
					var self=this;
					this.tab.click(function(event){
						event.preventDefault();
						var href=$(this).attr("href");					
						$(this).siblings().removeClass("active");
						$(this).addClass("active");
						self.tab_content.addClass("wpi_none");			
						self.tabs_content.find(href).removeClass("wpi_none");
						//alert(href);							
					});
					this.set_delete_tab();
				},
				set_delete_tab:function(){
					this.el.find(".wpi_delete_tab").click(function(event){
						event.preventDefault();
						var tab=$(this).attr("href");
						var content=$(tab).attr("href");
						$(tab).remove();
						$(content).remove();
						$(this).remove();
						//alert(href);							
					});
				},
				set_toggle:function(){	
					var self=this;
					if(!this.el.find("a.wpi_toggle_control").length) return;
					this.toggle_control=this.el.find("a.wpi_toggle_control");
					this.toggle_panel=this.el.find(".wpi_toggle_panel");
					
					this.toggle_control.click(function(event){
						event.preventDefault();							   
						if($(this).hasClass("wpi_toggle_control_down")){
							$(this).removeClass("wpi_toggle_control_down").addClass("wpi_toggle_control_up");
							$(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
							self.tabs_content.removeClass("wpi_toggle_panel_open").addClass("wpi_toggle_panel_close");
						}else{
							$(this).removeClass("wpi_toggle_control_up").addClass("wpi_toggle_control_down");
							$(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
							self.tabs_content.removeClass("wpi_toggle_panel_close").addClass("wpi_toggle_panel_open");
						}
					});
				},
			}	
			var set_tabs=SetTabs;
			set_tabs.init(this);		
			return set_tabs;
		};	
		
		$.fn.wpiHolder=function(args){   
			function WPiHolder(instance, args){  
				console.log("start");    
				this.instance=$(instance);         
				this.init(args);       
			};    
			WPiHolder.prototype.init=function(args){
				var defaults={
					el:this.instance,
					back:".wpi_back",
					heading:".wpi_heading",
					sections:".wpi_section",
					section_content:".wpi_section_content",
					content_holder:".wpi_content_holder",
				};      
				var options=$.extend(defaults,args);  
				this.$el=options.el;
				this.$back=this.$el.find(options.back);
				this.$content_holder=this.$el.find(options.content_holder);
				this.$heading=this.$el.find(options.heading);
				this.$sections=this.$el.find(options.sections);
				this.$section_content=this.$el.find(options.section_content);
				this.content_left="";
				this.back_left="";
				this.target="";
				this.activate();
			};    
			WPiHolder.prototype.activate=function(){
				var self=this; 
				this.$sections.each(function(){
					$(this).click(function(event){ 
						event.preventDefault(); 
						self.target=$(this).data("target");
						self.target_id=$(this).data("target_id");
						self.back_left="0px";
						self.content_left="-100%";         
						self.update();
					});
				}); 
				this.$back.click(function(event){
					event.preventDefault(); 
					self.target="Settings";
					self.target_id="";
					self.back_left="-50px";
					self.content_left="0%";         
					self.update();
				});        
			};    
			WPiHolder.prototype.update=function(){
				this.$back.css({left:this.back_left}); 
				this.$content_holder.css({"margin-left":this.content_left});                   
				this.$heading.html(this.target);
				this.$section_content.addClass("wpi_none");
				if(this.target_id!=""){
					this.$el.find("#"+this.target_id).removeClass("wpi_none");
				}
				DEBUG.setState("#"+this.target_id);
			}; 
			
			return this.each(function(){
				 new WPiHolder(this, args);				  
			});
			//return new WPiHolder(this, args);
		};		
		$.fn.wpiAccordion=function(el){
			DEBUG.setState("wpiAccordion");
			function WPiAccordion(el){      
				this.$el=$(el);
				this.init() ;      
			};
			WPiAccordion.prototype.init=function(){
				this.$headings=this.$el.find("li > h3");
				this.$contents=this.$el.find("li > div");
				if(this.$el.hasClass("wpi_disable_accordion")){
					this.$contents.parent().addClass("wpi_open");
					this.$headings.addClass("wpi_none");
				}else{				
					this.setElements();
				}
			};
			WPiAccordion.prototype.setElements=function(){
				this.$contents.css({overflow:"hidden"});
				this.zero_data=this.zeroData(); 
				var self=this;       
				this.$headings.click(function(){  
					var parent=$(this).parent();
					var content=$(this).siblings("div");        
					var content_backup_data=self.backupData(content,"");
					if(parent.hasClass("wpi_open")){
						content.animate(self.zero_data,200,function(){ 
							parent.removeClass("wpi_open");
							$(this).css(content_backup_data);            
						});          
					}else{   
						self.closeAll(content);  
						parent.addClass("wpi_open"); 
						content.css(self.zero_data);            
						content.animate(content_backup_data,200,function(){        
							$(this).css("height","auto");
						});
					};
				});      
			};
			WPiAccordion.prototype.closeAll=function(content){
				var self=this;
				var content=content;
				this.$headings.each(function(){         
					var element=$(this).siblings("div");
					var backup_data=self.backupData(element,"auto"); 
					$(this).siblings("div").not(content).animate(self.zero_data, 500, function(){
						$(this).parent().removeClass("wpi_open");
						$(this).css(backup_data); 
					});
				});
			};
			WPiAccordion.prototype.backupData=function(element, height){
				var data={};
				element.css({height:"auto"});      
				if(height!="auto"){height=element.innerHeight();}
				data['height']=height;
				data['padding-top']=element.css("padding-top");
				data['padding-bottom']=element.css("padding-bottom");
				return data;      
			};
			WPiAccordion.prototype.zeroData=function(){
				var data={};
				data['height']="0px";
				data['padding-top']="0px";
				data['padding-bottom']="0px";
				return data;      
			};    
			return this.each(function(){
				new WPiAccordion(this);
			});
		};	
		
		$.fn.wpiScroll=function(args){   
			var WPiScroll={ 				
				init:function(args){
					//alert(args.he.html());
					var se=args.se;
					var he=args.he;
					var hec=args.hec;
					var height=0;
					var se_parent=se.parents(".wpi_none");
					if(se_parent.hasClass("wpi_none")){
						se_parent.removeClass("wpi_none");
						height=hec.outerHeight();
						se_parent.addClass("wpi_none");
					};
					he.css({"overflow":"hidden", "transition":"all 0.3s"});
					hec.css({"transition":"all 0.3s"});
					he.css({"height":height}); 					
					$(window).resize(function(event){
						he.css({"height":"auto"});
						height=hec.outerHeight();   
						he.css({"height":height}); 
						DEBUG.setState(height);					
					});
					se.scroll(function(event){        
						var s=se.scrollTop();					
						$( "div:last b" ).text( "scrollTop:" + s );
						if(s>51){     
							hec.css({"opacity":0});     
							he.css({"height":"10px"});
						}else{
							hec.css({"opacity":1});
							he.css({"height":height});
							DEBUG.setState("height");	
						};
							
					});
				},	
			};
			return this.each(function(){     
				var defaults={se:$(this),he:"",hec:""};
				var options=$.extend(defaults,args);     
				var wpiScroll=WPiScroll.init(options);    
			});
		};		
		$.fn.wpiSwipe=function(arg,cb){
			var swipe={
				arg:arg,
				startPos:0,
				endPos:20,
				bodyStartPos:0,
				bodyEndPos:20,
				clicked:false,
				el:"",
				cb:cb,
				grabbingParent:arg['grabbingParent'],
				init:function(el){					
					this.el=$(el);	
					var self=this;
					this.el.parents("body").mousedown(function(event){
						self.bodyStartPos=event.pageX;							
					});
					this.el.parents("body").mouseup(function(event){
						self.bodyEndPos=event.pageX;						
						var dif=self.bodyEndPos-self.bodyStartPos;
						if(self.clicked==true){
							//alert(dif);
							var out=false;
							if(dif>20){out="p"}else if(dif<(-20)){out="n"}
							self.cb.call(this,out);
							self.el.parents(self.grabbingParent).removeClass("wpi_grabbing_parent");
							self.el.removeClass("wpi_grabbing");
							self.clicked=false;
						};
						
					});
					this.el.mousedown(function(event){						
						self.startPos=event.pageX;	
						self.el.parents(self.grabbingParent).addClass("wpi_grabbing_parent");
						self.el.addClass("wpi_grabbing");
						//alert(event.data.fn.bodyStartPos+" "+parentOffset.left);
						self.clicked=true;
					}).mouseup(function(event){						
						self.endPos=event.pageX;
						var dif=self.endPos-self.startPos;						
						//var aler=event.data.fn.startPos+" "+event.data.fn.endPos+' '+dif+' '+event.data.fn.arg.test;
						var out=false;
						if(dif>20){out="p"}else if(dif<(-20)){out="n"}
						self.cb.call(this,out);
						self.el.parents(self.grabbingParent).removeClass("wpi_grabbing_parent");
						self.el.removeClass("wpi_grabbing");
						self.clicked=false;
						
					});
				},
				down:function(){
					alert(this.endPos);
				},
			};
			return this.each(function(){
				$(this).addClass("wpi_grab");				
				var sw=swipe.init(this);							
			});
		};
		$.fn.wpiDynamic_colors=function(){
			var WPiDynamicColors={
				init:function(el){
					var output="";			
					var clrs=this.get_colors();
					for(i=0; i<clrs.length; i++){
						//var res=splitValue(clrs[i], 3);
						var res=clrs[i];
						output+="<div class='color' style='background-color:#"+clrs[i]+"'><div class='wpi_color_holder'><input type='text' value='"+res+"'/></div></div>";
					}
					$(el).html(output);					
					//return output;
				},			
				get_colors:function(){					
					var val=new Array("ffffff","eeeeee","cccccc","aaaaaa","888888","666666","444444","222222","111111","000000");
					var val1=new Array("ff","dd","bb","99","77","55","33","11");			
					var val3=new Array("00","33","66","99","cc","ff");
					var colors=[],count=0, html="";  
					for(i=0;i<val.length;i++){
						var c=val[ i ];
						colors[count]=c;				
						count++;
					}
					for(i=0;i<val1.length;i++){
						var a=val1[ i ];
						for(j=0;j<val1.length;j++){
							var b=val1[ j ];
							for(k=0;k<val3.length;k++){
								var c=val3[ k ];
								colors[count]=a+b+c;
								//var ele="<div style='background-color:#"+colors[c]+";'>"+colors[c]+"</div>";
								//html+=ele;
								count++;
							}//k loop
						}//j loop
					}//i loop
					return colors;					
				},
			}
			return this.each(function(){
				var el=this;
				var dynamic_colors=WPiDynamicColors.init(el);					
			});
		};
		//below function depends on $.fn.set_tabs();	
		$.fn.wpiProperties=function(args){
			var WPiProperties={
				el:"",
				callback:"",
				items:"",
				bar_items:{},
				bar_items_popup:{},
				bar_items_map:{},
				property_bar:"",
				bar_popup_overlay:"",
				$active_item:"",
				active_item_id:"",
				active_item_map:"",	
				return_property:"",
				return_value:"",
				popup_tabs:"",
				properties:["font-family","color", "font-size", "font-weight", "letter-spacing", "margin-top", "margin-bottom", "margin-left", "margin-right", "padding",  "border-width", "border-color",  "shadow-distance"],
				properties_data:{},
				init:function(args){
					this.$el=$(args['el']);	
					this.callback=args['callback'];	
					this.input_map=args['a2b_callback'].call();
					this.a2b_callback=args['a2b_callback'];	
					this.set_initial_variables();	
					this.data_arrays();	
					this.data_creation();
					this.set_after_variables();	
					this.add_property_bar();
					this.set_property_bar();
					this.set_items();					
					this.window_resize();
				},	
				set_initial_variables:function(){					
					this.items_class="wpi_layout_item";									
					this.popup_colors_variations_items_class="wpi_popup_colors_variations_item";
					this.popup_colors_items_class="wpi_popup_colors_item";
					this.popup_color_items_class="wpi_popup_colors_item";
					this.popup_text_items_class="wpi_popup_text_item";
					this.popup_font_family_items_class="wpi_popup_font_family_item";
					this.popup_font_size_items_class="wpi_popup_font_size_item";
					this.popup_settings_shadow_class="wpi_popup_settings_shadow";
					this.popup_font_weight_items_class="wpi_popup_font_weight_item";
					this.popup_letter_spacing_items_class="wpi_popup_letter_spacing_item";
					this.popup_margin_top_items_class="wpi_popup_margin_top_item";
					this.popup_margin_bottom_items_class="wpi_popup_margin_bottom_item";
					this.popup_margin_left_items_class="wpi_popup_margin_left_item";
					this.popup_margin_right_items_class="wpi_popup_margin_right_item";
					this.popup_padding_items_class="wpi_popup_padding_item";
					this.popup_border_width_items_class="wpi_popup_border_width_item";
					this.popup_shadow_distance_items_class="wpi_popup_shadow_distance_item";
					
					
					
					this.$items=this.$el.find("."+this.items_class);					
					this.$property_bar=$("<div class='wpi_property_bar'></div>");
					this.$bar_popup_overlay=$("<div class='wpi_property_bar_popup_overlay wpi_none'></div>");
					this.$bar_popup=$("<div class='wpi_property_bar_popup wpi_none'><div class='wpi_tabs'></div><div class='wpi_tabs_content'></div></div>");
					this.$bar_popup_close=$("<div class='wpi_property_bar_popup_close'>X</div>");
					this.$bar_item_color=$("<div class='wpi_property_bar_color wpi_property_bar_item'><a href='#wpi_popup_tab_color' class='wpi_call_tab'>Select Color</a></div>");
					this.$bar_item_font=$("<div class='wpi_property_bar_font-family wpi_property_bar_item'><a href='#wpi_popup_tab_font-family' class='wpi_call_tab'>Select font</a></div>");	
					this.$bar_item_settings=$("<div class='wpi_property_bar_settings wpi_property_bar_item'><a href='#wpi_popup_tab_settings' class='wpi_call_tab wpi_menu genericon genericon-menu'></a></div>");
					this.$popup_font_family=$("<div class='wpi_popup_font_family'></div>");
					this.$popup_colors=$("<div class='wpi_popup_colors'></div>");
					this.$popup_settings=$("<div class='wpi_popup_settings'></div>");
					this.$popup_colors_variations=$("<div class='wpi_popup_colors_variations'></div>");				
					this.$close_button=$("<div class='wpi_property_bar_close'>X</div>");
					
					
					this.colors=$(document).wpiColors(); 
					this.mainColors=this.colors.getMainColors();
					this.fonts=WPIDB['fonts']; 
					this.fontSizes=WPIDB['font_sizes'];
					this.fontWeights=WPIDB['font_weights'];
					this.letterSpacing=WPIDB['letter_spacing'];
					this.margin=WPIDB['margin'];
					this.borderWidth=WPIDB['border_width'];
					// this.fonts @create_popup_font_family(), this.mainColors @create_popup_colors,
				},				
				data_arrays:function(){
					this.bar_items['settings']=this.$bar_item_settings;
					this.bar_items['font-family']=this.$bar_item_font;					
					this.bar_items['color']=this.$bar_item_color;									
					this.bar_items_length=Object.keys(this.bar_items).length;
					
					this.bar_items_popup['settings']=this.$popup_settings;
					this.bar_items_map['settings']="settings";
					this.bar_items_popup['font-family']=this.$popup_font_family
					this.bar_items_map['font-family']="font-family";
					this.bar_items_popup['color']=this.$popup_colors
					this.bar_items_map['color']="color";				
					this.bar_items_popup_length=Object.keys(this.bar_items_popup).length;				
				
				},
				activate_properties:function(){
					var self=this;
					this.$property_bar.find(".wpi_property_bar_color").addClass("wpi_none");
					this.$property_bar.find(".wpi_property_bar_font-family").addClass("wpi_none");
					
					$(".wpi_popup_settings .wpi_section").addClass("wpi_none");
					
					$.each(this.properties, function(key,val){
						self.$bar_popup.find("#popup_"+val).addClass("wpi_none");			
					});
					$.each(this.active_item_map, function(k,v){
						var val=v['val'];
						var popup="";
						switch (k){
							case "text" : popup=k; break;
							case "font-family" :  popup=k; break;
							case "font-size" :  popup=k; break;
							case "font-weight" :  popup=k; break;
							case "letter-spacing" :  popup=k; break;
							case "margin-top" :  popup=k; break;
							case "margin-bottom" :  popup=k; break;
							case "margin-left" :  popup=k; break;
							case "margin-right" :  popup=k; break;
							case "padding" :  popup=k; break;
							case "color" :  popup=k; break;
							case "border-color" :  popup=k; break;
							case "border-width" :  popup=k; break;
							case "shadow-distance" :  popup=k; break;
						};
						var p=self.$bar_popup.find("#popup_"+popup);
						if(p.length) {							
							p.removeClass("wpi_none");
							section=p.parents(".wpi_section_content").attr("id");
							p.parents(".wpi_content_holder").find("[data-target='"+section+"']").removeClass("wpi_none");
						}						
						if(self.$property_bar.find(".wpi_property_bar_"+popup).length) self.$property_bar.find(".wpi_property_bar_"+popup).removeClass("wpi_none"); 
						
					});		
				},
				data_creation:function(){
					this.create_popup_font_family();
					this.create_popup_colors();
					this.create_settings();
				},
				set_after_variables:function(){				
					this.$popup_colors_items=this.$popup_colors.find("."+this.popup_colors_items_class);					
					this.$popup_font_family_items=this.$popup_font_family.find("."+this.popup_font_family_items_class);
					this.$popup_text_items=this.$popup_settings.find("."+this.popup_text_items_class);
					this.$popup_font_size_items=this.$popup_settings.find("."+this.popup_font_size_items_class);
					this.$popup_settings_shadow=this.$popup_settings.find("."+this.popup_settings_shadow_class);
					this.$popup_font_weight_items=this.$popup_settings.find("."+this.popup_font_weight_items_class);
					this.$popup_letter_spacing_items=this.$popup_settings.find("."+this.popup_letter_spacing_items_class);
					this.$popup_margin_top_items=this.$popup_settings.find("."+this.popup_margin_top_items_class);	
					this.$popup_margin_bottom_items=this.$popup_settings.find("."+this.popup_margin_bottom_items_class);
					this.$popup_margin_left_items=this.$popup_settings.find("."+this.popup_margin_left_items_class);
					this.$popup_margin_right_items=this.$popup_settings.find("."+this.popup_margin_right_items_class);
					this.$popup_padding_items=this.$popup_settings.find("."+this.popup_padding_items_class);
					this.$popup_color_items=this.$popup_settings.find("."+this.popup_color_items_class);	
					this.$popup_border_width_items=this.$popup_settings.find("."+this.popup_border_width_items_class);	
					this.$popup_shadow_distance_items=this.$popup_settings.find("."+this.popup_shadow_distance_items_class);
				},
				set_items:function(){				
					var self=this;
					this.$items.each(function(){
						$(this).click(function(event){							
							self.reset_variables();
							self.$active_item=$(this);							
							self.update();
							event.stopPropagation();
						});
					});					
				},	
				add_property_bar:function(){
					DEBUG.setState("add property bar");
					this.$el.append(this.$property_bar);
					this.add_bar_items();
					this.add_popup();
					this.add_popup_tabs();
					this.add_popup_tabs_content();
					this.$property_bar.addClass("wpi_none");	
				},			
				add_bar_items:function(){
					DEBUG.setState("add property bar : add bar items");
					var self=this;
					$.each(this.bar_items,function(key,val){					
						self.$property_bar.append(val);
					});					
					this.$bar_popup.find(".wpi_property_bar_item").addClass("wpi_none");
					this.$property_bar.append(this.$close_button);
					this.$close_button.click(function(){
						self.close_property_bar();
						self.close_popup(); 
					});				
				},			
				add_popup:function(){	
					var self=this;
					DEBUG.setState("add property bar : add popup");
					this.set_popup_overlay();
					this.$el.append(this.$bar_popup);
					this.$bar_popup.append(this.$bar_popup_close);				
					this.$bar_popup_close.click(function(){
						self.close_popup();														 
					});		
				},
				add_popup_tabs:function(){
					DEBUG.setState("add property bar : add popup tabs");
					var self=this;	
					var first_tab=1;
					$.each(this.bar_items_popup,function(key,val){
						 var tab_content_class="", active="", tab_class="";
						 if(first_tab==1) { tab_class="active"; first_tab++; }else{ tab_content_class="wpi_none"; }
						 var tab=$("<a href='#wpi_popup_tab_"+key+"' id='popup_"+key+"' class='wpi_tab "+tab_class+"'>"+key+"</a>");
						 var tab_content=$("<div id='wpi_popup_tab_"+key+"' class='wpi_tab_content "+tab_content_class+"'></div>");
						 self.$bar_popup.find(".wpi_tabs").append(tab);
						 self.$bar_popup.find(".wpi_tabs_content").append(tab_content);									
					});	
					this.popup_tabs=this.$bar_popup.find(".wpi_tabs").set_tabs();
					this.$property_bar.find(".wpi_call_tab").click(function(event){
						event.preventDefault();
						self.popup_tabs.call_tab(this);
						self.toggle_popup();											
					});
				},
				add_popup_tabs_content:function(){
					DEBUG.setState("add property bar : add popup tabs content");
					var self=this;
					var tabs_content=$('.wpi_tabs_content');
					$.each(this.bar_items_popup,function(key,val){
						 var tab_content=tabs_content.find("#wpi_popup_tab_"+key);
						 tab_content.append(val);
						 val.disableSelection();					
					});	
				},	
				set_property_bar:function(){
					DEBUG.setState("set property bar");
					this.set_popup_list_items();
					this.set_bar_position();
					this.close_property_bar();
				},
				toggle_popup:function(){
					this.close_popup();
					if(this.$bar_popup.hasClass("wpi_none")){
						this.$bar_popup_overlay.removeClass("wpi_none");
						this.$bar_popup.removeClass("wpi_none");					
					};
				},
				close_popup:function(){
					this.$bar_popup.addClass("wpi_none");	
					this.$bar_popup_overlay.addClass("wpi_none");
					this.$items.removeClass(this.items_class+"_active");
				},
				close_property_bar:function(){
					var self=this;
					var left=this.$property_bar.css("left");
					var top=parseInt(this.$property_bar.css("top"))-50;					
					this.$property_bar.animate({"top":top, "left":left, "opacity":0},200,function(){self.$property_bar.addClass("wpi_none");});	
				},
				set_popup_overlay:function(){
					var self=this;				
					this.$el.append(this.$bar_popup_overlay);
					this.$bar_popup_overlay.click(function(){
						self.close_popup();						  
					});
				},							
				create_popup_font_family:function(){
					var cls=this.popup_font_family_items_class;
					var out="";							
					$.each(this.fonts,function(key,val){
						var font_style="style='font-family:"+val+";'";
						out+="<div class='"+cls+"' data-name='"+val+"' "+font_style+"><div>"+val+"</div></div>";					
					});	
					var output=$(out);
					this.$popup_font_family.html(output);				
				},
				create_popup_colors:function(){	
					var cls=this.popup_colors_items_class;
					var out="";							
					$.each(this.mainColors,function(key,val){
						var color_style="style='background-color:"+val+";'";
						out+="<div class='"+cls+" "+cls+"_"+key+"'><div data-name='"+val+"' "+color_style+">"+val+"</div></div>";					
					});
					var output=$(out);
					this.$popup_colors.html(output);				
				},
				create_color:function(){	
					var cls=this.popup_color_items_class;
					var out="<div class='wpi_popup_color'>";							
					$.each(this.mainColors,function(key,val){
						var color_style="style='background-color:"+val+";'";
						out+="<div class='"+cls+" "+cls+"_"+key+"'><div data-name='"+val+"' "+color_style+">"+val+"</div></div>";					
					});
					out+="</div>";
					return out;								
				},
				create_borderWidth:function(){	
					var self=this;
					var out="";							
					$.each(this.borderWidth,function(key,val){					
						out+="<div class='"+self.popup_border_width_items_class+" button'><div data-name='"+val+"' >"+val+"</div></div>";					
					});	
					return out;						
				},
				create_shadowDistance:function(){	
					var self=this;
					var out="";							
					$.each(this.borderWidth,function(key,val){					
						out+="<div class='"+self.popup_shadow_distance_items_class+" button'><div data-name='"+val+"' >"+val+"</div></div>";					
					});	
					return out;						
				},
				create_text:function(){
					var out="";							
					out+="<div class='"+this.popup_text_items_class+"'>";
					out+="<div class='input'><textarea></textarea></div>";	
					out+="<div class='button'>Update</div>";	
					out+="</div>";	
					return out;
				},
				create_fontSize:function(){
					var self=this;
					var out="";							
					$.each(this.fontSizes,function(key,val){					
						out+="<div class='"+self.popup_font_size_items_class+" button'><div data-name='"+val+"' >"+val+"</div></div>";					
					});	
					return out;
				},
				create_fontWeight:function(){
					var self=this;
					var out="";							
					$.each(this.fontWeights,function(key,val){					
						out+="<div class='"+self.popup_font_weight_items_class+" button'><div data-name='"+val+"' >"+val+"</div></div>";					
					});
					return out;
				},
				create_letterSpacing:function(){
					var self=this;
					var out="";							
					$.each(this.letterSpacing,function(key,val){					
						out+="<div class='"+self.popup_letter_spacing_items_class+" button'><div data-name='"+val+"' >"+val+"</div></div>";					
					});
					return out;
				},
				create_marginTop:function(){
					var self=this;
					var out="";							
					$.each(this.margin,function(key,val){					
						out+="<div class='"+self.popup_margin_top_items_class+" button'><div data-name='"+val+"' >"+val+"</div></div>";					
					});
					return out;
				},
				create_marginBottom:function(){
					var self=this;
					var out="";							
					$.each(this.margin,function(key,val){					
						out+="<div class='"+self.popup_margin_bottom_items_class+" button'><div data-name='"+val+"' >"+val+"</div></div>";					
					});
					return out;
				},
				create_marginLeft:function(){
					var self=this;
					var out="";							
					$.each(this.margin,function(key,val){					
						out+="<div class='"+self.popup_margin_left_items_class+" button'><div data-name='"+val+"' >"+val+"</div></div>";					
					});
					return out;
				},
				create_marginRight:function(){
					var self=this;
					var out="";							
					$.each(this.margin,function(key,val){					
						out+="<div class='"+self.popup_margin_right_items_class+" button'><div data-name='"+val+"' >"+val+"</div></div>";					
					});
					return out;
				},
				create_padding:function(){
					var self=this;
					var out="";							
					$.each(this.margin,function(key,val){					
						out+="<div class='"+self.popup_padding_items_class+" button'><div data-name='"+val+"' >"+val+"</div></div>";					
					});
					return out;
				},
				create_settings:function(){
					var self=this;
					var out="";
					
					if(this.active_item_id!=""){
						DEBUG.setState("create settings shadow : "+this.active_item_id);
						if(TOOLS.issetArray('shadow',this.input_map[this.active_item_id])){						
							var shadow_data=CAPTUREDATA.select({id:$("#slide_heading_shadow_distance"),});
							var shadow="";
							$.each(shadow_data,function(key,val){					
								shadow+="<div class='"+self.popup_settings_shadow_class+" button'><div data-name='"+val['value']+"' >"+val['label']+"</div></div>";					
							});						
						};
					}
					var wpiHolder_data=[
						{"section":"Text Properties", "label":"Text", "name":"text","content":"<div>"+this.create_text()+"</div>",},
						{"section":"Text Properties", "label":"Font Size", "name":"font-size","content":"<div>"+this.create_fontSize()+"</div>",},
						{"section":"Text Properties", "label":"Font Weight", "name":"font-weight","content":"<div>"+this.create_fontWeight()+"</div>",},
						{"section":"Text Properties", "label":"Letter Spacing", "name":"letter-spacing","content":"<div>"+this.create_letterSpacing()+"</div>",},
						{"section":"Margin", "label":"Margin Top","name":"margin-top","content":"<div>"+this.create_marginTop()+"</div>",},
						{"section":"Margin", "label":"Margin Bottom","name":"margin-bottom","content":"<div>"+this.create_marginBottom()+"</div>",},
						{"section":"Margin", "label":"Margin Left","name":"margin-left","content":"<div>"+this.create_marginLeft()+"</div>",},
						{"section":"Margin", "label":"Margin Right","name":"margin-right","content":"<div>"+this.create_marginRight()+"</div>",},
						{"section":"Padding", "label":"Padding","name":"padding","content":"<div>"+this.create_padding()+"</div>",},	
						{"section":"Border", "label":"Border Width", "name":"border-width","content":"<div>"+this.create_borderWidth()+"</div>",},	
						{"section":"Border", "label":"Border Color", "name":"border-color","content":"<div>"+this.create_color()+"</div>",},	
						{"section":"Shadow", "label":"Shadow Distance", "name":"shadow-distance","content":"<div>"+this.create_shadowDistance()+"</div>",},						
					];
					out+=CREATEUI.createHolder(wpiHolder_data);					
					var output=$(out);
					this.$popup_settings.html(output);	
				},
				set_settings:function(){
					var self=this;
					$.each(this.active_item_map, function(k,v){
						var val=v['val'];
						switch (k){
							case "text" : self.$popup_text_items.find("textarea").val(val); break;
							case "font-size" : self.$popup_font_size_items.find("[data-name='"+val+"']").parent().addClass("wpi_popup_item_active").siblings().removeClass("wpi_popup_item_active"); break;
							case "font-weight" : self.$popup_font_weight_items.find("[data-name='"+val+"']").parent().addClass("wpi_popup_item_active").siblings().removeClass("wpi_popup_item_active"); break;
							case "letter-spacing" : self.$popup_letter_spacing_items.find("[data-name='"+val+"']").parent().addClass("wpi_popup_item_active").siblings().removeClass("wpi_popup_item_active"); break;
							case "margin-top" : self.$popup_margin_top_items.find("[data-name='"+val+"']").parent().addClass("wpi_popup_item_active").siblings().removeClass("wpi_popup_item_active"); break;
							case "margin-bottom" : self.$popup_margin_bottom_items.find("[data-name='"+val+"']").parent().addClass("wpi_popup_item_active").siblings().removeClass("wpi_popup_item_active"); break;
							case "margin-left" : self.$popup_margin_left_items.find("[data-name='"+val+"']").parent().addClass("wpi_popup_item_active").siblings().removeClass("wpi_popup_item_active"); break;
							case "margin-right" : self.$popup_margin_right_items.find("[data-name='"+val+"']").parent().addClass("wpi_popup_item_active").siblings().removeClass("wpi_popup_item_active"); break;
							case "padding" : self.$popup_padding_items.find("[data-name='"+val+"']").parent().addClass("wpi_popup_item_active").siblings().removeClass("wpi_popup_item_active"); break;
							case "border-color" : self.$popup_padding_items.find("[data-name='"+val+"']").parent().addClass("wpi_popup_item_active").siblings().removeClass("wpi_popup_item_active"); break;
							case "border-width" : self.$popup_padding_items.find("[data-name='"+val+"']").parent().addClass("wpi_popup_item_active").siblings().removeClass("wpi_popup_item_active"); break;
							case "shadow-distance" : self.$popup_padding_items.find("[data-name='"+val+"']").parent().addClass("wpi_popup_item_active").siblings().removeClass("wpi_popup_item_active"); break;
						};								  
					});
				},
				set_popup_list_items:function(){
					DEBUG.setState("add property bar : set popup list items");
					this.set_popup_settings_items();
					DEBUG.setState("add property bar : set popup list items - end");
				},
				set_popup_settings_items:function(){
					this.set_popup_item_click({items:this.$popup_text_items, property:"text", type:"textarea"});
					this.set_popup_item_click({items:this.$popup_font_size_items, property:"font-size"});
					this.set_popup_item_click({items:this.$popup_font_family_items, property:"font-family"});	
					this.set_popup_item_click({items:this.$popup_font_weight_items, property:"font-weight"});
					this.set_popup_item_click({items:this.$popup_letter_spacing_items, property:"letter-spacing"});	
					this.set_popup_item_click({items:this.$popup_margin_top_items, property:"margin-top"});	
					this.set_popup_item_click({items:this.$popup_margin_bottom_items, property:"margin-bottom"});
					this.set_popup_item_click({items:this.$popup_margin_left_items, property:"margin-left"});
					this.set_popup_item_click({items:this.$popup_margin_right_items, property:"margin-right"});
					this.set_popup_item_click({items:this.$popup_padding_items, property:"padding"});					
					this.set_popup_item_click({items:this.$popup_colors_items, property:"color", type:"colors"});	
					this.set_popup_item_click({items:this.$popup_color_items, property:"border-color", type:"colors"});	
					this.set_popup_item_click({items:this.$popup_border_width_items, property:"border-width"});
					this.set_popup_item_click({items:this.$popup_shadow_distance_items, property:"shadow-distance"});
				},
				getColorVariation:function(args){	
					var $item=args['item'], index=args['index'], property=args['property'];
					var color=$item.find("div").data("name");				
					this.mainColorVariations=this.colors.getVariations(color);
					var out="";		
					var cls=this.popup_colors_variations_items_class;
					$.each(this.mainColorVariations,function(k,v){
						$.each(v,function(key,val){
							var color_style="style='background-color:"+val+";'";
							out+="<div class='"+cls+" "+cls+"_"+key+"'><div data-name='"+val+"' "+color_style+">"+val+"</div></div>";					
						});
					});
					var output=$(out);
					this.$popup_colors_variations.html(output);
					var popup_colors_width=$item.parent().width();
					var width=$item.parent().find(".wpi_popup_colors_item_0").outerWidth();					
					var columns=Math.floor(popup_colors_width/126);
					var variation=(Math.ceil((index+1)/columns)*columns)-1;					
					if($item.parent().find(".wpi_popup_colors_item_"+variation).html()){					
						$item.parent().find(".wpi_popup_colors_item_"+variation).after(this.$popup_colors_variations);
					}else{					
						$item.parent().find(".wpi_popup_colors_item_"+index).after(this.$popup_colors_variations);
					}
					var $popup_colors_variations_items=$item.parent().find("."+cls);
					this.set_popup_item_click({"items":$popup_colors_variations_items, "property":property});					
				},
				set_popup_item_change:function(args){
					args['evt']="change";
					this.set_popup_item_event(args);
				},
				set_popup_item_click:function(args){
					args['evt']="click";
					this.set_popup_item_event(args);
				},
				set_popup_item_event:function(args){
					var defaults={items:"", property:"", type:"text", evt:"click"};
					var settings=$.extend(defaults, args);
					var self=this;
					$.each(settings.items, function(index, val){							
						if(settings.type=="textarea"){								
							$(this).find(".button").on(args['evt'],function(event){									
								self.return_property=settings.property;		
								self.return_value=$(this).parent().find("textarea").val();
								self.return_data();	
							});
						}else if(settings.type=="colors"){								
							$(this).click(function(event){							
								self.getColorVariation({"item":$(this),"index":index,"property": settings.property});							
							});
						}else{							
							$(this).on(args['evt'],function(event){
								self.return_property=settings.property;									
								self.return_value=$(this).text();
								self.return_data();	
							});	
						};						
					});	
				},				
				return_data:function(args){	
					this.$active_item.data(this.return_property,this.return_value);							
					DEBUG.setState(this.$active_item.data());
					var args={element:this.$active_item, property:this.return_property, value:this.return_value};	
					this.callback.call(this,args);				
					this.update_bar_item();
					this.close_popup();
					this.close_property_bar();
					return args;
				},
				update:function(){				
					this.active_item_id=this.$active_item.attr("id");
					this.$items.removeClass(this.items_class+"_active");
					this.$active_item.addClass(this.items_class+"_active");
					this.$property_bar.addClass("wpi_none");					
					if(this.active_item_map=this.input_map[this.active_item_id]){ 
						this.input_map=this.a2b_callback.call(); //get values from outside
						this.set_settings(); // set popup values
						this.activate_properties();
						this.$property_bar.removeClass("wpi_none");	
						this.update_bar_item();
						this.set_bar_position();
						$(window).trigger("resize");
					};
				},
				update_bar_item:function(){	
					if(this.active_item_map['color']){									
						this.popup_colors_active_item();					
					};
					if(this.active_item_map['font-family']){							
						this.popup_font_family_active_item();					
					};
					if(this.active_item_map['settings']){							
						//this.popup_settings_active_item();					
					};
				},	
				set_bar_position:function(){
					if(this.$active_item!=""){
						//var offset=this.$active_item.position();	
						//var margin_top= parseInt(this.$active_item.css("margin-top"));
						//var top=((offset.top-this.$property_bar.innerHeight())+ margin_top)-20;
						//var left=((this.$active_item.innerWidth()/2)+offset.left)-(this.$property_bar.innerWidth()/2);
						var args={
							p:this.$el, 
							e:this.$active_item, 
							o:this.$property_bar,
						};
						var position=$(document).wpiSetPosition(args);
						var top=position.top;
						var left=position.left;						
					}else{					
						var top=0;
						var left=((this.$el.innerWidth()/2));
					}	
					this.$property_bar.css({"top":top-20, "left": left,"opacity":0}).animate({"top":top, "left": left,"opacity":1},200);
				},	
				popup_font_family_active_item:function(){
					var font=this.$active_item.css("font-family");
					var cls=this.popup_font_family_items_class;
					this.$popup_font_family.find("."+cls).removeClass(cls+"_active");
					this.$popup_font_family.find("[data-name="+font+"]").addClass(cls+"_active");	
					this.$bar_item_font.find("a").html(font);
				},
				popup_colors_active_item:function(){				
					var getColor=this.$active_item.css("color");
					DEBUG.setState(getColor);
					var color=rgb2hex(this.$active_item.css("color"));				
					DEBUG.setState(color);	
					var cls=this.popup_colors_variations_items_class;
					this.$popup_colors_variations.find("."+cls).removeClass(cls+"_active");
					this.$popup_colors_variations.find("[data-name="+color+"]").parent().addClass(cls+"_active");				
					this.$bar_item_color.find("a").css({"background-color":color}).html(color);				
					
					DEBUG.setState(this.$bar_item_color.find("a").css("background-color"));
					
					function rgb2hex(rgb){
						rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
						//DEBUG.setState(rgb);
						return "#" + ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) + ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +  ("0" + parseInt(rgb[3],10).toString(16)).slice(-2);
					};
				},	
				popup_settings_active_item:function(){	
					var font=this.$active_item.css("font-family");
					var cls=this.popup_settings_items_class;
					this.$popup_settings.find("."+cls).removeClass(cls+"_active");
					this.$popup_settings.find("[data-name="+font+"]").addClass(cls+"_active");	
					this.$bar_item_font.find("a").html(font);	
				},	
				reset_variables:function(){
					this.return_property="";
					this.return_value="";
					this.$active_item="";
					this.active_item_id="";
					this.active_item_map="";
				},			
				window_resize:function(){
					var self=this;
					$(window).resize(function(){						
						self.set_bar_position();						
					});
				},
				
			}
			return this.each(function(){
				var defaults={el:this, callback:"", input_map:""};
				var options=$.extend(defaults, args);
				var properties=WPiProperties.init(options);							  
			});
		};	
			
		(function($){
			$.fn.wpi_admin_globals=function(){
				function WPiAdmin(){
					this.$wpi_admin= $("#wpi_admin");
					this.init();
				}
				WPiAdmin.prototype.init=function(){
					var self=this;     
					this.$header=this.$wpi_admin.find("#wpi_gs_header");
					this.$header_ha=this.$wpi_admin.find("#wpi_gs_header_ha");
					this.$header_bg=this.$wpi_admin.find("#wpi_gs_header_bg");
					this.$header_bg.css({"opacity":0});
					this.$header_childrens=this.$wpi_admin.find("img");
					this.$header_childrens.on("mouseover mouseout",function(e){
						e.stopPropagation();
					});
					this.$header_ha.on("mouseover",function(e){
						e.stopPropagation();
						self.$header_bg.finish().animate({"opacity":1},200);
					}).on("mouseout",function(e){ 
						e.stopPropagation();
						self.$header_bg.finish().animate({"opacity":0},200);				
					});
				}
				return new WPiAdmin();
			} 			
		})(jQuery);
		$.fn.disableSelection = function() {
			return this
					 .attr('unselectable', 'on')
					 .css('user-select', 'none')
					 .on('selectstart', false);
		};
		$.fn._escape=function(string){
			var Escape={		
				init:function(string) {
					var htmlEscapes={
					  '&': '&amp;',
					  '<': '&lt;',
					  '>': '&gt;',
					  '"': '&quot;',
					  "'": '&#x27;',
					  '/': '&#x2F;'
					};
					var htmlEscaper = /[&<>"'\/]/g;
					return ('' + string).replace(htmlEscaper, function(match) {
						return htmlEscapes[match];
					});
				}
			};
			var _escape=Escape.init(string);
			return _escape;
		};
	});
})(jQuery, document, window);