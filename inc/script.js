(function($){
	$(document).ready(function(){
		var DEBUG=$(document).wpiDebug();		
		var TOOLS=$(document).wpiTools();
		var CSS=$(document).wpiCss();
		var UI=$(document).wpiUI();
		var CREATEUI=$(document).wpiCreateUI();
		DEBUG.setState("Script");
		
		var $wpi_export_tabs_count=0;
		var $wpi_db_theme_count=0;
		var $wpi_db_icons_count=0;
		var $wpi_db_icons_arr=[];
		
		var box=$("#wpi_designer_button_box");
		var preview_button=$("#wpi_preview .wpi_designer_button");	
		preview_button.data("shadow",{});
		var restore=box.find("#wpi_restore");
		var _export=box.find("#wpi_export");
		
		var admin=$("#wpi_admin");
		if(admin.length){
			var Globals=$(document).wpi_admin_globals();	
		}
		var styles=box.find("#wpi_styles");
		var icons=box.find("#wpi_icons");
		var colors=$("#wpi_colors");
		
		var icon_text=box.find("#wpi_icon_text");
		var only_text=box.find("#wpi_only_text");
		var only_icon=box.find("#wpi_only_icon");

		//styles page
		var sty_el=$( ".wpi_des_but_sty" );
		var sty_ids=["shape","padding","shadow", "shadow_type","text_shadow", "border_sides", "glow_size", "glow_color", "texture", "text_size", "font", "font_weight", "border_width", "border_style", "display", "text_color", "background_color", "border_color", "text_color_h", "background_color_h", "border_color_h", "text_color_a", "background_color_a", "border_color_a"];
		var sty=get_elements(sty_ids);
		var $sty_defaults=get_defaults(sty_ids);
		
		var sty_map={
			init:function(){
				var self=this;
				this.prepare_preview();
				sty['shape'].change(function(){	self.shape(); });
				sty['padding'].change(function(){ self.padding(); });
				sty['shadow'].change(function(){ self.shadow(); });
				sty['shadow_type'].change(function(){ self.shadow(); });
				sty['glow_size'].change(function(){ self.glow(); });
				sty['glow_color'].change(function(){ self.glow_color(); });
				sty['texture'].change(function(){ self.texture(); });
				sty['text_shadow'].change(function(){ self.text_shadow(); });
				sty['border_sides'].change(function(){ self.border_sides(); });	
				sty['border_width'].change(function(){ self.border_width(); self.border_sides();});	
				sty['display'].change(function(){ self.display(); });
				sty['border_style'].change(function(){ self.border_style(); });
				sty['text_size'].change(function(){ self.text_size(); });	
				sty['font'].change(function(){ self.font(); });
				sty['font_weight'].change(function(){ self.font_weight(); });	
			},
			prepare_preview:function(){
				this.shape();
				this.padding();
				this.shadow();
				this.glow(); 
				this.glow_color();
				this.texture();
				this.border_sides();
				this.border_width();
				this.display();
				this.border_style();
				this.text_size();
				this.font();
				this.font_weight();
			},
			shape:function(){
				var radius=set_radius(sty['shape'].val());
				preview_button.css("border-radius", radius);
			},
			padding:function(){
				var padding="";
				if(preview_button.hasClass("wpi_no_text")){					
					padding=sty['padding'].val();
				}else{					
					padding=set_padding(sty['padding'].val());
				}
				preview_button.css("padding", padding);
			},
			shadow:function(){
				var distance="0px";
				if(sty['shadow'].val()!="no") distance=sty['shadow'].val();								
				var shadow=set_shadow({x:distance, y:distance, blur:distance, inset:sty['shadow_type'].val()});				
				shadow=set_multi_shadow({el:preview_button, type:"shadow",style:shadow});				
				preview_button.css("box-shadow", shadow);
			},
			glow:function(){
				var distance=sty['glow_size'].val();				
				var shadow=set_shadow({x:"0px", y:"0px", blur:distance, color:sty['glow_color'].val()});
				shadow=set_multi_shadow({el:preview_button, type:"glow",style:shadow});				
				preview_button.css("box-shadow", shadow);
			},
			glow_color:function(){
				var val= TOOLS.setColor(sty['glow_color'].val());
				var color=sty['glow_color'].val(val);
				var distance=sty['glow_size'].val();				
				var shadow=set_shadow({x:"0px", y:"0px", blur:distance, color:color});
				shadow=set_multi_shadow({el:preview_button, type:"glow",style:shadow});				
				preview_button.css("box-shadow", shadow);
			},
			texture:function(){				
				var url=set_background_image(sty['texture'].val());
				preview_button.css("background-image", url );	
			},
			text_shadow:function(){
				var distance=sty['text_shadow'].val();
				var text_shadow=set_shadow({x:distance, y:distance, blur:distance});
				preview_button.css("text-shadow",text_shadow);
			},
			border_sides:function(){
				var bs=set_border_sides({border_sides: sty['border_sides'].val(), border_width: sty['border_width'].val() });
				var css={
					"border-left-width":bs['border_left'],
					"border-right-width":bs['border_right'],
					"border-top-width":bs['border_top'],
					"border-bottom-width":bs['border_bottom'],
				};					
				preview_button.css(css);
			},
			border_width:function(){
				preview_button.css({"border-width":sty['border_width'].val()});
			},
			display:function(){						
				preview_button.css({"display":sty['display'].val()});
			},
			border_style:function(){
				preview_button.css({"border-style":sty['border_style'].val()});
			},
			text_size:function(){					
				preview_button.css({"font-size":sty['text_size'].val()});
			},
			font:function(){
				preview_button.css({"font-family":sty['font'].val()});	
			},
			font_weight:function(){
				preview_button.css({"font-weight":sty['font_weight'].val()});	
			},
		};
		
		
		//buttons page
		var but_el=$( ".wpi_des_but" );
		var but_ids=["icon","text","style_id", "icon_position"];
		var but=get_elements(but_ids);
		
		var but_map={
			defaults:{},
			init:function(){
				this.defaults=get_defaults(but_ids);
				this.prepare_preview();	
				export_fn(this.defaults);				
				but['icon'].change({fn:this},function(event){ event.data.fn.icon();});
				but['icon_position'].change({fn:this},function(event){ event.data.fn.icon_position();});
				but['style_id'].change({fn:this},function(event){ event.data.fn.style_id();});
				but['text'].keyup({fn:this},function(event){ event.data.fn.text();});
			},
			prepare_preview:function(){
				this.icon();	
				this.style_id();	
				this.text();
				this.icon_position();	
			},
			apply_preset:function(style){
				var id=$(style).find(".wpi_id").text();
				var no_text_class="";
				var icon_position="wpi_icon_left";
				but['style_id'].val(id);
				if(but['icon_position'].val()=="right"){
					icon_position="wpi_icon_right";
				}
				var icon_class="wpi_icon wpi_icon_"+but['icon'].val()+" "+icon_position;
				var classes=$(style).attr("class");	
				if(but['text'].val()==""){no_text_class="wpi_no_text";}
				preview_button.attr("class",classes+" "+icon_class+" "+no_text_class);
			},
			icon:function(){
				remove_class("icon");				
				var icon_position="wpi_icon_left";				
				if(but['icon_position'].val()=="right"){
					icon_position="wpi_icon_right";
				}
				preview_button.addClass("wpi_icon wpi_icon_"+but['icon'].val()+" "+icon_position);					
			},
			icon_position:function(){
				remove_class("icon");					
				var icon_position="wpi_icon_left";				
				if(but['icon_position'].val()=="right"){
					icon_position="wpi_icon_right";
				}
				preview_button.addClass("wpi_icon wpi_icon_"+but['icon'].val()+" "+icon_position);				
			},
			text:function(){				
				preview_button_text(but['text'].val());
			},
			style_id:function(){
				var no_text_class="";
				var icon_class="wpi_icon wpi_icon_"+but['icon'].val();
				var element="#wpi_db_sty_"+but['style_id'].val();
				var classes=$(element).attr("class");	
				if(but['text'].val()==""){no_text_class="wpi_no_text";}
				preview_button.attr("class",classes+" "+icon_class+" "+no_text_class);				
			},	
		};	
		
		//share buttons page
		var sb_el=$( ".wpi_des_but_sb" );
		var sb_buttons_ids=["facebook","twitter", "googleplus","pinterest","linkedin", "tumblr", "stumbleupon", "reddit", "wordpress", "email"];
		var sb_ids=["style_id","share_text","button_gap",'frame_padding_top','frame_padding_bottom','frame_padding_left','frame_padding_right'];
		var sb=get_elements($.merge(sb_ids, sb_buttons_ids));		
		var sb_div_ids=["wpi_sb","wpi_sb_text","wpi_sb_facebook","wpi_sb_twitter","wpi_sb_googleplus", "wpi_sb_linkedin","wpi_sb_pinterest","wpi_sb_tumblr","wpi_sb_stumbleupon","wpi_sb_reddit","wpi_sb_wordpress", "wpi_sb_email"];
		var sb_div=get_elements(sb_div_ids);
		var sb_map={			
			defaults:{},
			init:function(){
				var self=this;
				this.prepare_preview();	
				this.set_styles();
				sb['style_id'].change(function(){self.style_id();});
				sb['share_text'].keyup(function(){self.share_text();});
				sb['button_gap'].change(function(){self.button_gap();});
				sb['frame_padding_top'].change(function(){self.frame_padding_top();});
				sb['frame_padding_bottom'].change(function(){self.frame_padding_bottom();});
				sb['frame_padding_left'].change(function(){self.frame_padding_left();});
				sb['frame_padding_right'].change(function(){self.frame_padding_right();});
				
				$.each(sb_buttons_ids, function(k,v){
					sb[v].change(function(){self.display_state("wpi_sb_"+v,v);});	
				});
				
			},	
			prepare_preview:function(){
				var self=this;
				this.style_id();
				this.share_text();
				this.button_gap();
				this.frame_padding_top();
				this.frame_padding_bottom();
				this.frame_padding_left();
				this.frame_padding_right();
				$.each(sb_buttons_ids, function(k,v){					
					self.display_state("wpi_sb_"+v,v);
				});				
			},			
			display_state:function(el,input){				
				if (sb[input].val()==0){
					sb_div[el].addClass("wpi_none");
				}else{
					sb_div[el].removeClass("wpi_none");
				}	
			},	
			share_text:function(){	
				sb_div['wpi_sb_text'].html(sb['share_text'].val());	
				this.display_state('wpi_sb_text', 'share_text');							
			},
			button_gap:function(){				
				sb_div['wpi_sb'].find("li").css({"margin-right":sb['button_gap'].val()});
				sb_div['wpi_sb'].find("li").css({"margin-bottom":sb['button_gap'].val()});
				sb_div['wpi_sb'].find("li.wpi_sb_last").css({"margin-right":"0px"});
			},	
			frame_padding_top:function(){				
				sb_div['wpi_sb'].css({"padding-top":sb['frame_padding_top'].val()});				
			},	
			frame_padding_bottom:function(){				
				sb_div['wpi_sb'].css({"padding-bottom":sb['frame_padding_bottom'].val()});				
			},
			frame_padding_left:function(){				
				sb_div['wpi_sb'].css({"padding-left":sb['frame_padding_left'].val()});				
			},
			frame_padding_right:function(){				
				sb_div['wpi_sb'].css({"padding-right":sb['frame_padding_right'].val()});				
			},
			style_id:function(){
				var no_text_class="";
				var element="#wpi_db_sty_"+sb['style_id'].val();				
				var classes=$(element).attr("class");	
				no_text_class="wpi_no_text";
				sb_div['wpi_sb_facebook'].find("a").attr("class",classes+" wpi_icon wpi_icon_facebook-alt "+no_text_class);
				sb_div['wpi_sb_twitter'].find("a").attr("class",classes+" wpi_icon wpi_icon_twitter "+no_text_class);
				sb_div['wpi_sb_googleplus'].find("a").attr("class",classes+" wpi_icon wpi_icon_googleplus-alt "+no_text_class);
				sb_div['wpi_sb_linkedin'].find("a").attr("class",classes+" wpi_icon wpi_icon_linkedin "+no_text_class);
				sb_div['wpi_sb_pinterest'].find("a").attr("class",classes+" wpi_icon wpi_icon_pinterest "+no_text_class);
				sb_div['wpi_sb_tumblr'].find("a").attr("class",classes+" wpi_icon wpi_icon_tumblr "+no_text_class);
				sb_div['wpi_sb_stumbleupon'].find("a").attr("class",classes+" wpi_icon wpi_icon_stumbleupon "+no_text_class);
				sb_div['wpi_sb_reddit'].find("a").attr("class",classes+" wpi_icon wpi_icon_reddit "+no_text_class);
				sb_div['wpi_sb_wordpress'].find("a").attr("class",classes+" wpi_icon wpi_icon_wordpress "+no_text_class);
				sb_div['wpi_sb_email'].find("a").attr("class",classes+" wpi_icon wpi_icon_mail "+no_text_class);
			},
			set_styles:function(){
				var self=this;
				styles.find(".wpi_style").click(function(){	
					var id=$(this).find(".wpi_id").text();					
					sb['style_id'].val(id);					
					self.style_id();
				});					
			},
		}
		
		//slides page
		var sli_el=$( ".wpi_des_but_sli" );
		var sli_ids=["slide_heading","slide_heading_2","slide_heading_3", "slide_heading_font", "slide_heading_size", "slide_heading_font_weight", "slide_heading_line_height", "slide_heading_letter_spacing", "slide_heading_color", "slide_heading_margin_top","slide_heading_margin_left", "slide_heading_margin_right", "slide_heading_margin_bottom",  "slide_heading_padding", "slide_heading_border_width", "slide_heading_border_color","slide_heading_background_color", "slide_heading_shadow_distance", "slide_text", "slide_text_font", "slide_text_size", "slide_text_font_weight", "slide_text_color", "slide_text_margin_top","slide_text_margin_left","slide_text_margin_right", "button_text","button_margin_top", "button_margin_bottom","slide_footer_text","slide_footer_padding","slide_footer_text_size", "background_color", "background_image",  "background_custom_image", "background_repeat_image", "background_image_opacity","background_image_blur",  "icon", "style_id", "frame_height","frame_width","frame_margin_left","frame_margin_right"];
		var sli=get_elements(sli_ids);		
		var sli_div_ids=["wpi_slide","wpi_slide_heading","wpi_slide_text","wpi_slide_image","wpi_slide_button","wpi_slide_footer"];
		var sli_div=get_elements(sli_div_ids);
		
		var sli_map={			
			defaults:{},
			input_map:{},
			classes:{},
			init:function(){
				this.defaults=get_defaults(sli_ids);
				this.set_css();
				this.initialPreviewSet=0;
				this.headingTicker=sli_div["wpi_slide_heading"].wpiTicker({store:true});
				this.prepare_preview();	
				this.map();					
				export_fn(this.defaults);
				sli['slide_heading'].keyup({fn:this},function(event){ event.data.fn.heading();});
				sli['slide_heading_2'].keyup({fn:this},function(event){ event.data.fn.heading_2();});
				sli['slide_heading_3'].keyup({fn:this},function(event){ event.data.fn.heading_3();});
				sli['slide_heading_font'].change({fn:this},function(event){	event.data.fn.heading_font();});	
				sli['slide_heading_size'].change({fn:this},function(event){	event.data.fn.heading_size();});
				sli['slide_heading_font_weight'].change({fn:this},function(event){	event.data.fn.heading_font_weight();});
				sli['slide_heading_line_height'].change({fn:this},function(event){	event.data.fn.heading_line_height();});
				sli['slide_heading_letter_spacing'].change({fn:this},function(event){	event.data.fn.heading_letter_spacing();});
				sli['slide_heading_color'].change({fn:this},function(event){ event.data.fn.heading_color();});
				sli['slide_heading_background_color'].change({fn:this},function(event){ event.data.fn.heading_background_color();});
				sli['slide_heading_margin_top'].change({fn:this},function(event){ event.data.fn.heading_margin_top();});
				sli['slide_heading_margin_left'].change({fn:this},function(event){ event.data.fn.heading_margin_left();});
				sli['slide_heading_margin_right'].change({fn:this},function(event){ event.data.fn.heading_margin_right();});
				sli['slide_heading_margin_bottom'].change({fn:this},function(event){ event.data.fn.heading_margin_bottom();});
				sli['slide_heading_padding'].change({fn:this},function(event){ event.data.fn.heading_padding();});
				sli['slide_heading_border_width'].change({fn:this},function(event){ event.data.fn.heading_border_width();});
				sli['slide_heading_border_color'].keyup({fn:this},function(event){ event.data.fn.heading_border_color();});
				sli['slide_heading_shadow_distance'].change({fn:this},function(event){ event.data.fn.heading_shadow_distance();});
				sli['slide_text'].keyup({fn:this},function(event){ event.data.fn.text();});
				sli['slide_text_font'].change({fn:this},function(event){ event.data.fn.text_font();});	
				sli['slide_text_size'].change({fn:this},function(event){ event.data.fn.text_size();});
				sli['slide_text_font_weight'].change({fn:this},function(event){ event.data.fn.text_font_weight();});
				sli['slide_text_color'].change({fn:this},function(event){ event.data.fn.text_color();});
				sli['slide_text_margin_top'].change({fn:this},function(event){ event.data.fn.text_margin_top();});
				sli['slide_text_margin_left'].change({fn:this},function(event){ event.data.fn.text_margin_left();});
				sli['slide_text_margin_right'].change({fn:this},function(event){ event.data.fn.text_margin_right();});
				sli['icon'].change({fn:this},function(event){ event.data.fn.icon();});	
				sli['style_id'].change({fn:this},function(event){ event.data.fn.style_id();});
				sli['button_text'].keyup({fn:this},function(event){	event.data.fn.button_text();});	
				sli['button_margin_top'].change({fn:this},function(event){ event.data.fn.button_margin_top();});
				sli['button_margin_bottom'].change({fn:this},function(event){ event.data.fn.button_margin_bottom();});
				sli['slide_footer_text'].change({fn:this},function(event){ event.data.fn.footer_text();});
				sli['slide_footer_text_size'].change({fn:this},function(event){ event.data.fn.footer_text_size();});
				sli['slide_footer_padding'].change({fn:this},function(event){ event.data.fn.footer_padding();});
				sli['background_color'].change({fn:this},function(event){ event.data.fn.background_color();});
				sli['background_image'].change({fn:this},function(event){ event.data.fn.background_image();});
				sli['background_custom_image'].change({fn:this},function(event){ event.data.fn.background_image();});
				sli['background_repeat_image'].change({fn:this},function(event){ event.data.fn.background_repeat_image();});
				sli['background_image_opacity'].change({fn:this},function(event){ event.data.fn.background_image_opacity();});
				sli['background_image_blur'].change({fn:this},function(event){ event.data.fn.background_image_blur();});
				sli['frame_height'].change({fn:this},function(event){ event.data.fn.frame_height();});
				sli['frame_width'].keyup({fn:this},function(event){ event.data.fn.frame_width();});
				sli['frame_margin_left'].change({fn:this},function(event){ event.data.fn.frame_margin_left();});
				sli['frame_margin_right'].change({fn:this},function(event){ event.data.fn.frame_margin_right();});
				
				var input_map=this.input_map;
				var self=this;				
				function prepare_inputs(args){
					self.prepare_inputs(args);					
				}
				function a2b_data(){					
					return self.a2b_data();
				}
				sli_div["wpi_slide"].wpiProperties({input_map: input_map, callback : prepare_inputs, a2b_callback : a2b_data }); //global function
				
				//$(document).wpiTicker();
			},
			set_css:function(){
				this.classes={};
				var self=this;
				$.each(sli_div_ids,function(key,val){
					self.classes[val]={};
					DEBUG.setState(key+":"+val);					
					self.classes[val]['element']=sli_div[val];
					self.classes[val]['styles']={};
				});
			},
			build_css:function(args){
				this.check_css(args);
				var element=args['element'];
				var style=args['style'];
				var value=args['val'];
				this.classes[element]['styles'][style]=value;
				CSS.buildCss(this.classes[element]);				
			},
			check_css:function(args){				
				/*if(args['style']=="text-shadow-distance"){
					this.classes[args['element']]['styles']['text-shadow-x']=args['val'];
					this.classes[args['element']]['styles']['text-shadow-y']=args['val'];
					this.classes[args['element']]['styles']['text-shadow-blur']=args['val'];
				};	*/				
			},
			prepare_preview:function(){
				this.update_headingTicker();
				this.heading();
				this.heading_2();
				this.heading_3();
				this.heading_font();
				this.heading_size();
				this.heading_font_weight();
				this.heading_line_height();
				this.heading_letter_spacing();
				this.heading_color();
				this.heading_background_color();
				this.heading_margin_top();
				this.heading_margin_left();
				this.heading_margin_right();
				this.heading_margin_bottom();
				this.heading_padding();
				this.heading_border_width();
				this.heading_border_color();
				this.heading_shadow_distance();
				this.text();
				this.text_font();
				this.text_size();
				this.text_font_weight();
				this.text_color();
				this.text_margin_top();
				this.text_margin_left();
				this.text_margin_right();
				this.icon();
				this.style_id();
				this.button_text();
				this.button_margin_top();				
				this.button_margin_bottom();
				this.footer_text();
				this.footer_padding();
				this.footer_text_size();
				this.background_image();
				this.background_repeat_image();
				this.background_image_opacity();
				this.background_image_blur();
				this.background_color();
				this.frame_height();
				this.frame_width();
				this.frame_margin_left();
				this.frame_margin_right();
				if(this.initialPreviewSet==0){
					this.initialPreviewSet=1;
					this.headingTicker.init();
					var parentPadding=sli['slide_heading_padding'].val();
					var animate="stop";
					if(sli['slide_heading_2'].val()!=""|| sli['slide_heading_3'].val()!=""){
						animate="play";
					}
					this.headingTicker.update({"animate":animate, "parentPadding": parentPadding});
				}
			},
			map:function(){	
				this.input_map={
					'wpi_slide_heading' : {
						'text': {							
							el: sli['slide_heading'], val:"",
						},
						'font-family': {
							el: sli['slide_heading_font'], val:"",
						},
						'color':{
							el: sli['slide_heading_color'], val:"",
						},
						'shadow' : {
							el: sli['slide_heading_shadow_distance'], val:"",
						},
						'font-size' : {
							el: sli['slide_heading_size'], val:"",
						},
						'font-weight' : {
							el: sli['slide_heading_font_weight'], val:"",
						},
						'letter-spacing' : {
							el: sli['slide_heading_letter_spacing'], val:"",
						},	
						'margin-top' : {
							el: sli['slide_heading_margin_top'], val:"",
						},
						'margin-left' : {
							el: sli['slide_heading_margin_left'], val:"",
						},
						'margin-right' : {
							el: sli['slide_heading_margin_right'], val:"",
						},
						'margin-bottom' : {
							el: sli['slide_heading_margin_bottom'], val:"",
						},
						'padding' : {
							el: sli['slide_heading_padding'], val:"",
						},
						'border-color' : {
							el: sli['slide_heading_border_color'], val:"",
						},
						'border-width' : {
							el: sli['slide_heading_border_width'], val:"",
						},
						'shadow-distance' : {
							el: sli['slide_heading_shadow_distance'], val:"",
						},
					},
					'wpi_slide_text' : {
						'text' : {
							el: sli['slide_text'], val:"",
						},
						'font-family' : {
							el: sli['slide_text_font'], val:"",
						},
						'font-size' : {
							el: sli['slide_text_size'], val:"",
						},
						'font-weight' : {
							el: sli['slide_text_font_weight'], val:"",
						},
						'margin-top' : {
							el: sli['slide_text_margin_top'], val:"",
						},	
						'margin-left' : {
							el: sli['slide_text_margin_left'], val:"",
						},
						'margin-right' : {
							el: sli['slide_text_margin_right'], val:"",
						},
						'color' : {
							el: sli['slide_text_color'], val:"",
						},
					},
					'wpi_slide_button' : {
						'text': {							
							el: sli['button_text'], val:"",
						},
						'margin-top' : {
							el: sli['button_margin_top'], val:"",
						},
						'margin-bottom' : {
							el: sli['button_margin_bottom'], val:"",
						},
					},
					'wpi_slide_footer' : {			
						'font-size' : {
							el: sli['slide_footer_text_size'], val:"",
						},
						'padding' : {
							el: sli['slide_footer_padding'], val:"",
						},
					},
				};					
			},
			test:function(){
				alert(1);
			},
			prepare_inputs:function(args){				
				var id=$(args['element']).attr("id");
				DEBUG.setState("external"+args['element'].data(args['property']));
				var property=args['property'];
				var value=args['value'];
				this.input_map[id][property]['el'].val(value);
				this.input_map[id][property]['el'].trigger("change");
				this.prepare_preview();	
			},	
			a2b_data:function(){	
				var self=this;
				$.each(self.input_map, function(key,val){
					$.each(val, function(k,v){
						self.input_map[key][k]['val'] = self.input_map[key][k]['el'].val();
					});						
				});				
				return this.input_map;
			},	
			update_headingTicker:function(){
				if(this.initialPreviewSet!=0){	
					var parentPadding=sli['slide_heading_padding'].val();
					var animate="stop";
					if(sli['slide_heading_2'].val()!=""|| sli['slide_heading_3'].val()!=""){
						animate="play";
					}
					this.headingTicker.update({"animate":animate, "parentPadding": parentPadding});
				}				
			},
			heading:function(){
				sli_div['wpi_slide_heading'].find(".wpi_heading_1").children().html(sli['slide_heading'].val());
				this.update_headingTicker();
			},
			heading_2:function(){
				sli_div['wpi_slide_heading'].find(".wpi_heading_2").children().html(sli['slide_heading_2'].val());	
				this.update_headingTicker();
			},
			heading_3:function(){
				sli_div['wpi_slide_heading'].find(".wpi_heading_3").children().html(sli['slide_heading_3'].val());	
				this.update_headingTicker();				
			},			
			heading_font:function(){
				sli_div['wpi_slide_heading'].css({"font-family":sli['slide_heading_font'].val()});
				this.update_headingTicker();
			},
			heading_size:function(){
				sli_div['wpi_slide_heading'].css({"font-size":sli['slide_heading_size'].val()});
				this.update_headingTicker();
			},
			heading_font_weight:function(){
				sli_div['wpi_slide_heading'].css({"font-weight":sli['slide_heading_font_weight'].val()});	
				this.update_headingTicker();
			},
			heading_line_height:function(){
				sli_div['wpi_slide_heading'].css({"line-height":sli['slide_heading_line_height'].val()});
				this.update_headingTicker();
			},
			heading_letter_spacing:function(){
				sli_div['wpi_slide_heading'].css({"letter-spacing":sli['slide_heading_letter_spacing'].val()});	
				this.update_headingTicker();
			},
			heading_padding:function(){
				sli_div['wpi_slide_heading'].css({"padding":sli['slide_heading_padding'].val()});	
				this.update_headingTicker();
			},
			heading_color:function(){
				var val= TOOLS.setColor(sli['slide_heading_color'].val());
				sli['slide_heading_color'].val(val);
				sli_div['wpi_slide_heading'].css({"color":val});				
			},
			heading_margin_top:function(){
				sli_div['wpi_slide_heading'].css({"margin-top":sli['slide_heading_margin_top'].val()});				
			},
			heading_margin_left:function(){
				sli_div['wpi_slide_heading'].css({"margin-left":sli['slide_heading_margin_left'].val()});				
			},
			heading_margin_right:function(){
				sli_div['wpi_slide_heading'].css({"margin-right":sli['slide_heading_margin_right'].val()});				
			},
			heading_margin_bottom:function(){
				sli_div['wpi_slide_heading'].css({"margin-bottom":sli['slide_heading_margin_bottom'].val()});				
			},
			heading_border_width:function(){
				sli_div['wpi_slide_heading'].css({"border-width":sli['slide_heading_border_width'].val()});				
			},
			heading_border_color:function(){
				sli_div['wpi_slide_heading'].css({"border-color":sli['slide_heading_border_color'].val()});				
			},	
			heading_background_color:function(){
				sli_div['wpi_slide_heading'].css({"background-color":sli['slide_heading_background_color'].val()});				
			},
			heading_shadow_distance:function(){				
				this.build_css({ element:'wpi_slide_heading', style:"text-shadow-distance", val: sli['slide_heading_shadow_distance'].val()});
			},			
			text:function(){
				sli_div['wpi_slide_text'].html(sli['slide_text'].val());				
			},
			text_font:function(){
				sli_div['wpi_slide_text'].css({"font-family":sli['slide_text_font'].val()});				
			},
			text_size:function(){
				sli_div['wpi_slide_text'].css({"font-size":sli['slide_text_size'].val()});				
			},
			text_font_weight:function(){
				sli_div['wpi_slide_text'].css({"font-weight":sli['slide_text_font_weight'].val()});				
			},
			text_color:function(){
				var val= TOOLS.setColor(sli['slide_text_color'].val());
				sli['slide_text_color'].val(val);
				sli_div['wpi_slide_text'].css({"color":val});				
			},
			text_margin_top:function(){
				sli_div['wpi_slide_text'].css({"margin-top":sli['slide_text_margin_top'].val()});				
			},
			text_margin_left:function(){
				sli_div['wpi_slide_text'].css({"margin-left":sli['slide_text_margin_left'].val()});				
			},
			text_margin_right:function(){
				sli_div['wpi_slide_text'].css({"margin-right":sli['slide_text_margin_right'].val()});				
			},
			icon:function(){
				remove_class("icon");
				preview_button.addClass("wpi_icon wpi_icon_"+sli['icon'].val());			
			},			
			style_id:function(){
				var no_text_class="";
				var icon_class="";
				if(sli['icon'].val()) icon_class="wpi_icon wpi_icon_"+sli['icon'].val();
				var element="#wpi_db_sty_"+sli['style_id'].val();
				var classes=$(element).attr("class");	
				if(sli['button_text'].val()==""){no_text_class="wpi_no_text";}
				preview_button.attr("class",classes+" "+icon_class+" "+no_text_class);				
			},	
			button_text:function(){					
				var val=sli['button_text'].val();				
				preview_button_text(val);
			},
			button_margin_top:function(){
				sli_div['wpi_slide_button'].css({"margin-top":sli['button_margin_top'].val()});				
			},
			button_margin_bottom:function(){
				sli_div['wpi_slide_button'].css({"margin-bottom":sli['button_margin_bottom'].val()});				
			},
			footer_text:function(){
				if(sli['slide_footer_text'].val()!=""){
					sli_div['wpi_slide_footer'].removeClass("wpi_none");
					sli_div['wpi_slide_footer'].html(sli['slide_footer_text'].val());
				}else{
					sli_div['wpi_slide_footer'].addClass("wpi_none");
				}
			},
			footer_padding:function(){
				sli_div['wpi_slide_footer'].css({"padding":sli['slide_footer_padding'].val()});				
			},
			footer_text_size:function(){
				sli_div['wpi_slide_footer'].css({"font-size":sli['slide_footer_text_size'].val()});				
			},
			background_image:function(){
				if(sli['background_custom_image'].val()!=""){
					var url="url("+sli['background_custom_image'].val()+")";					
				}else{
					var url=this.no_image(sli['background_image'].val());
				}				
				sli_div['wpi_slide_image'].css({"background-image":url});
			},
			background_repeat_image:function(){
				var url=this.no_image(sli['background_repeat_image'].val());
				sli_div['wpi_slide'].css({"background-image":url});
			},
			background_image_opacity:function(){
				var val=(sli['background_image_opacity'].val()/100);				
				sli_div['wpi_slide_image'].css({"opacity":val});
			},
			background_image_blur:function(){
				this.build_css({ element:'wpi_slide_image', style:"blur", val: sli['background_image_blur'].val()});
			},
			background_color:function(){
				var val= TOOLS.setColor(sli['background_color'].val());
				sli['background_color'].val(val);
				sli_div['wpi_slide'].css({"background-color":val});		
			},
			frame_height:function(){
				sli_div['wpi_slide'].css({"min-height":sli['frame_height'].val()});
			},
			frame_width:function(){
				var val=sli['frame_width'].val();
				if(val!="" && val!=0){
					val=parseInt(val)+"px";
				}else{
					val="";
				}
				sli['frame_width'].val(val);
				sli_div['wpi_slide'].css({"width":val});
			},
			frame_margin_left:function(){
				var val=sli['frame_margin_left'].val();
				if(val=="0px" || val=="") val="auto";
				sli_div['wpi_slide'].css({"margin-left":val});
			},
			frame_margin_right:function(){
				var val=sli['frame_margin_left'].val();
				if(val=="0px" || val=="") val="auto";
				sli_div['wpi_slide'].css({"margin-right":val});

			},
			no_image:function(val){				
				var url="";
				if(val=="no" || val==""){
					url="none";		
				}else{
					url="url("+(WPiURLS.WPIDB_URL)+"images/"+val+")";		
				}	
				return url;
			},
			apply_sli_preset:function(args){
				set_inputs(args,this.defaults);
				this.prepare_preview();
			},
			apply_preset:function(style){
				var id=$(style).find(".wpi_id").text();
				var no_text_class="";
				sli['style_id'].val(id);
				var icon_class="wpi_icon wpi_icon_"+sli['icon'].val();
				var classes=$(style).attr("class");	
				if(sli['button_text'].val()==""){no_text_class="wpi_no_text";}
				preview_button.attr("class",classes+" "+icon_class+" "+no_text_class);
			},
		};	
		
		
	
			
		function get_elements(ids){
			var elements={};
			$.each(ids,function(key,val){
				elements[val]=$("#"+val);			
			});
			return elements;
		};
		function get_defaults(ids){
			var defaults={};
			$.each(ids,function(key,val){
				defaults[val]="";			
			});
			return defaults;
		};
		function current_args(defaults){
			var fields={};
			$.each(defaults, function(key, val) {				
				fields[key]=$("#"+key).val();
			});			
			return fields;
		};
		function set_inputs(args,defaults){			
			var defaults=current_args(defaults);			
			var args=$.extend(defaults, args );
			$.each(args, function(key, val) { 
				$("#"+key).val(val);
			});			
		};	
		
		

		icon_text.click(function(){
			preview_button.removeClass("wpi_no_text wpi_icon_no");
			preview_button.addClass("wpi_icon wpi_icon_videocamera");
			if(sty['padding'].val()!=""){				
				padding=set_padding(sty['padding'].val());
				preview_button.css("padding", padding);
			}
		});
		only_text.click(function(){
			preview_button.removeClass("wpi_no_text wpi_icon_videocamera");
			preview_button.addClass("wpi_icon wpi_icon_no");
			if(sty['padding'].val()!=""){	
				padding=set_padding(sty['padding'].val());
				preview_button.css("padding", padding);
			}
		});
		only_icon.click(function(){
			preview_button.removeClass("wpi_icon_no");
			preview_button.addClass("wpi_icon wpi_icon_videocamera wpi_no_text");		
			padding=sty['padding'].val();	
			preview_button.css("padding", padding);
		});
		
		
		var $classes={};	
		var $normal={};
		var $hover={};
		var $active={};		
		var $states=[
			{"element": sty['text_color'], "prop": 'color', "state": 'normal','type':'color' },
			{"element": sty['background_color'], "prop": 'background-color', "state": 'normal' ,'type':'color'},
			{"element": sty['border_color'], "prop": 'border-color', "state": 'normal','type':'color'},
			{"element": sty['text_color_h'], "prop": 'color', "type": 'hover', "state": 'hover','type':'color'},
			{"element": sty['background_color_h'], "prop": 'background-color', "state": 'hover','type':'color'},
			{"element": sty['border_color_h'], "prop": 'border-color', "state": 'hover','type':'color'},
			{"element": sty['text_color_a'], "prop": 'color', "type": 'hover', "state": 'active','type':'color'},
			{"element": sty['background_color_a'], "prop": 'background-color', "state": 'active','type':'color'},
			{"element": sty['border_color_a'], "prop": 'border-color', "state": 'active','type':'color'}
		];
				
		function create_remove_class(base, arr){			
			var remove_class="";
			$.each(arr,function(key,val){
				remove_class+=base+val+" ";		
			});				
			return remove_class;
		}
		function remove_all_class(types){
			$.each(types,function(key,val){
				remove_class(val);								  
			});
		}
		
		function remove_class(type){
			var icon="wpi_icon wpi_icon_left wpi_icon_right "+create_remove_class("wpi_icon_",$wpi_db_icons_arr);
			if(type=="icon"){
				preview_button.removeClass(icon);
			}
		}		
		
		//Events
		
		
		preview_button.click(function(event){			
			event.preventDefault();
		});
		$(".wpi_designer_button.wpi_display").click(function(event){			
			event.preventDefault();
		});
		
		if ( sty_el.length ) {	//style admin
			
			export_fn($sty_defaults);
			preview_button.mouseover(function(){
				$(this).css($hover);			
			}).mouseout(function(){
				$(this).css($normal);		
			}).mousedown(function(){
				$(this).css($active);		
			}).mouseup(function(){
				$(this).css($hover);		
			});		
			restore.click(function(event){			
				event.preventDefault();			
				set_style("default", $saved_args );			
			});
			
			
			sty_map.init();
		}else if ( but_el.length) {			
			but_map.init();	
		}else if ( sli_el.length) {
			sli_map.init();			
		}else if ( sb_el.length) {
			sb_map.init();			
		}
		
		function preview_button_text(val){
			if(val==""){
				preview_button.addClass('wpi_no_text');	
			}else{
				preview_button.removeClass('wpi_no_text');		
			}
			preview_button.find('.wpi_text').html(val);	
		}
		
		if ( admin.length) {	
			$(".wpi_tabs").set_tabs();	
			$(".wpiHolder").wpiHolder({back:".wpi_back"});
			$(".wpiAccordion").wpiAccordion();
		}
		if ( box.length) {	
			var tabs=$(".wpi_visual_holder .wpi_tabs").set_tabs();	
			$(".wpiHolder").wpiHolder({back:".wpi_back"});
			$(".wpiAccordion").wpiAccordion();
		}
		
		if ( $( ".wpi_db" ).length ) {			
			colors.wpiDynamic_colors();			
		}
		
		if ( sty_el.length ) {
			
			$saved_args=current_args($sty_defaults);			
			set_style("default", $saved_args );	
			
		}else if ( but_el.length && styles.length ) {	
			styles.find(".wpi_style").click(function(){				
				but_map.apply_preset(this);
			});		 
		}else if ( sli_el.length && styles.length ) {	
			styles.find(".wpi_style").click(function(){				
				sli_map.apply_preset(this);
			});		 
		}
		
		
		function set_style(element, args ){
			if(element!="default"){
				$(element).click(function(){			
					set_preset(args);
					restore.removeClass("wpi_none");
				});	
			}else{
				
				set_preset(args);				
				restore.addClass("wpi_none");
			}
		};		
		function set_multi_shadow(args){			
			var defaults={el:"", type:"", style:""};
			args=$.extend(defaults,args);
			if(args['el']=="" || args['type']==""  || args['style']=="") return "";	
			
			var shadow={};
			var multi=[];
			var c=0;
			var data=args['el'].data("shadow");
			data[args['type']]=args['style'];					
			data=args['el'].data("shadow");
			$.each(data,function(key,val){
				if(val!=""){
					shadow[key]=val;
					multi[c++]=val;
				}
			});
			args['el'].data("shadow",shadow);
			shadow=multi.join(", ");	/**/			
			return shadow;			
		}
		function set_shadow(args){			
			var shadow="";			
			var defaults={x:"0px", y:"0px", blur:"0px", color:"rgba(0,0,0,0.3)", inset:""};
			args=$.extend(defaults, args);
			if(args['x']=="no"){
				args['x']="0px"; 
			}else if(args['y']=="no"){
				args['y']="0px"; 
			}else if( args['blur']=="no"){
				args['blur']="0px"; 
			}
			shadow=args['inset']+" "+args['x']+" "+args['y']+" "+args['blur']+" "+args['color'];
			return shadow;			
		}	
		function set_radius(radius){
			if(radius=="rectangle"){				
				radius="0px";
			}else if(radius=="rounded"){				
				radius="100px";
			}else{				
				radius=radius;
			}
			return radius;
		}
		function set_padding(padding){
			padding=padding.replace("px", "");
			padding	= padding+"px "+(padding*2)+"px";
			return padding;
		}
		function set_background_image(image){
			if(image!="" && image!="no"){
				image="url("+(WPiURLS.WPIDB_URL)+"images/"+image+")";
			}else{
				image=" ";
			}
			return image;
		}
		
		function set_border_sides(args){
			var data={border_left:"0px",border_right:"0px", border_top:"0px", border_bottom:"0px"};			
			switch (args.border_sides){
				case "all": data['border_left']=args.border_width; data['border_right']=args.border_width; data['border_top']=args.border_width; data['border_bottom']=args.border_width; break;
				case "left": data['border_left']=args.border_width; break;
				case "right": data['border_right']=args.border_width; break;
				case "top": data['border_top']=args.border_width; break;
				case "bottom": data['border_bottom']=args.border_width; break;
			}
			return data;
		}
		
		function set_preset(args){	
		
			var defaults=current_args($sty_defaults);			
			var args=$.extend(defaults, args );
			$.each(args, function(key, val) { 
				$("#"+key).val(val);
			});
			
			var shadow=set_shadow({x:args.shadow, y:args.shadow, blur:args.shadow, inset:args.shadow_type});
			shadow=set_multi_shadow({el:preview_button, type:"shadow",style:shadow});	
			var glow=set_shadow({x:"0px", y:"0px", blur:args.glow_size, color:args.glow_color});
			shadow=set_multi_shadow({el:preview_button, type:"glow",style:glow});
			
			var text_shadow=set_shadow({x:args.text_shadow, y:args.text_shadow, blur:args.text_shadow});			
			var texture=set_background_image(args.texture);
			var bs=set_border_sides({border_sides:args.border_sides, border_width: args.border_width});
			var padding="";
			if(preview_button.hasClass("wpi_no_text")){					
				padding=args.padding;
			}else{					
				padding=set_padding(args.padding);
			}
			var radius=set_radius(args.shape);			
			var common={
				"font-family":args.font,
				"font-size":args.text_size,
				"font-weight":args.font_weight,
				"padding":padding,
				"border-width":args.border_width,
				"border-style":args.border_style,
				"border-left-width":bs['border_left'],
				"border-right-width":bs['border_right'],
				"border-top-width":bs['border_top'],
				"border-bottom-width":bs['border_bottom'],
				"background-image":texture,
				"box-shadow":shadow,
				"text-shadow":text_shadow,	
				"border-radius":radius,	
				"display":args.display				
			};
			$normal={
				"background-color":args.background_color, 
				"color":args.text_color,
				"border-color":args.border_color		
			};	
			$hover={
				"background-color":args.background_color_h, 
				"color":args.text_color_h, 
				"border-color":args.border_color_h
			};
			$active={
				"background-color":args.background_color_a, 
				"color":args.text_color_a, 
				"border-color":args.border_color_a
			};
			preview_button.addClass($classes);	
			preview_button.css(common);
			preview_button.css($normal);
			
			stateChange($states);			
		}
		function set_icon(element,val){			
			$(element).click(function(){			
				set_inputs({icon:val}, $sty_defaults);			
				remove_class("icon");
				$classes="";			
				$classes+=" wpi_icon wpi_icon_"+val;	
				preview_button.addClass($classes);
			});				
		}
		
		function create_presets(element, args){
			
			var defaults=$sty_defaults;			
			var args=$.extend(defaults, args );			
			var shadows=[];	
			var shadow=set_shadow({x:args.shadow, y:args.shadow, blur:args.shadow, inset:args.shadow_type});
			var glow=set_shadow({x:"0px", y:"0px", blur:args.glow_size, color:args.glow_color});
			if(shadow!="") shadows.push(shadow);	
			if(glow!="") shadows.push(glow);
			shadow=shadows.join(", ");
			
			var text_shadow=set_shadow({x:args.text_shadow, y:args.text_shadow, blur:args.text_shadow});
			var texture=set_background_image(args.texture);
			var bs=set_border_sides({border_sides:args.border_sides, border_width: args.border_width});
			var padding=set_padding(args.padding);
			var radius=set_radius(args.shape);			
			var common={				
				"font-size":args.text_size,
				"font-family":args.font,
				"font-weight":args.font_weight,
				"padding":padding,
				"border-width":args.border_width,
				"border-style":args.border_style,
				"border-left-width":bs['border_left'],
				"border-right-width":bs['border_right'],
				"border-top-width":bs['border_top'],
				"border-bottom-width":bs['border_bottom'],
				"background-image":texture,
				"box-shadow":shadow,
				"text-shadow":text_shadow,
				"border-radius":radius,	
				"display":args.display
			};
			
			var normal={
				"background-color":args.background_color, 
				"color":args.text_color,
				"border-color":args.border_color		
			};	
			$(element).css(common);
			$(element).css(normal);			
		}	
		$.fn.create_presets=function(index,name,args){
			$("#wpi_presets").append($("<div class='wpi_preset_holder' ><div id='wpi_db_pre_"+index+"' class='wpi_designer_button'>"+get_brand(index)+"</div></div>"));			
			set_style("#wpi_db_pre_"+index, args );
			args['display']="inline-block";
			create_presets("#wpi_db_pre_"+index, args );			
		};
		$.fn.create_sli_presets=function(index,name,args){
			$("#wpi_sli_presets").append($("<div class='wpi_preset_holder' ><div id='wpi_db_sli_pre_"+index+"' ><img src='"+(WPiURLS.WPIDB_URL)+"images/thumbnails/a"+index+".png'/><div></div>"));
			$("#wpi_db_sli_pre_"+index).click(function(){
				sli_map.apply_sli_preset(args);
			});					
		};
		function get_brand(index){	
			var position=0;
			var brands=["facebook", "Twitter", "Infosys", "WordPress", "Tata", "Amazon", "Ebay", "Flipcart", "Fashion", "Mashable", "Nokia", "Nasa"];
			position=  index % brands.length;
			return brands[position];
					
		}
		$.fn.create_themes=function( args){
			var output="<div id='wpi_db_the_"+$wpi_db_theme_count+"' class='wpi_theme wpi_theme_col"+args.length+"'><div class='wpi_theme_holder'>";
				output+="<div class='wpi_theme_color_holder'>";
				$.each(args,function(key,val){
					output+="<div class='wpi_theme_color' style='background:"+val+"'></div>";	 
				});	
				output+="</div>";
				output+="<div class='wpi_theme_title'>Theme "+$wpi_db_theme_count+"</div>";
			output+="</div></div>";
			$("#wpi_themes").append($(output));			
			args=create_theme_css(args);
			set_style("#wpi_db_the_"+$wpi_db_theme_count, args );
			
			$wpi_db_theme_count++;
		};
		$.fn.create_icons=function( val){			
			var output="<div id='wpi_db_ico_"+$wpi_db_icons_count+"' class='wpi_icons_holder'>";			
			output+="<div class='wpi_icon'><div class='genericon genericon-"+val+"'><div class='wpi_icon_title'>"+val+"</div></div></div>";	 
			output+="</div>";
			$("#wpi_icons").append($(output));				
			set_icon("#wpi_db_ico_"+$wpi_db_icons_count, val);
			
			$wpi_db_icons_count++;
			
		};
		
		$.fn.set_icons_arr=function(icons){
			$wpi_db_icons_arr=icons;
		}
		
		function create_theme_css(args){
			var properties={};			
			var text=args[0];	
			var tones=get_tones(args[1]);
			var background_h=tones['lighter'];
			var background_a=tones['darker'];
			var background=args[1];
			var border=args[2];
			var glow=args[2];
			
			
			properties['text_color']=text;
			properties['background_color']=background;
			properties['border_color']=border;
			properties['text_color_h']=text;
			properties['background_color_h']=background_h;
			properties['border_color_h']=border;
			properties['text_color_a']=text;
			properties['background_color_a']=background_a;
			properties['border_color_a']=border;
			
			properties['glow_color']=glow;
			return properties;
		}
		
		$("#wpi_colors .color input").live('mouseup', function() { $(this).select(); });
		
		
		function stateChange(args){
			
			for(i=0; i<args.length; i++){							
				args[i]['element'].change({element: args[i]},function(event){
					if(	event.data.element['type']=="color"){
						val= TOOLS.setColor($( this ).val());
						$( this ).val(val);
					}
					val= $( this ).val();	
					//alert(val);
					if(event.data.element['state']=="normal"){
						preview_button.css(event.data.element['prop'], val);
						$normal[event.data.element['prop']]=val;
					}else if(event.data.element['state']=="active"){						
						$active[event.data.element['prop']]=val;
					}else{
						$hover[event.data.element['prop']]=val;		
					}									
				});
			};			
		};
		
		
		//global functions
		
		$(".wpi_notes, #wpi_presets, #wpi_help ").disableSelection();
			
		var args={he:$("#wpi_presets_data .wpi_header"), hec:$("#wpi_presets_data .wpi_header_holder")};
		$("#wpi_presets").wpiScroll(args);
		
		var htmlEscapes = {
		  '&': '&amp;',
		  '<': '&lt;',
		  '>': '&gt;',
		  '"': '&quot;',
		  "'": '&#x27;',
		  '/': '&#x2F;'
		};
		var htmlEscaper = /[&<>"'\/]/g;
		function _escape(string) {
		  return ('' + string).replace(htmlEscaper, function(match) {
			return htmlEscapes[match];
		  });
		};
		
		function export_style(defaults){			
			var args=current_args(defaults);
			
			var style="{<br>";
			$.each(args, function(key, val) { 
				var val=$()._escape(val);
				style+=key+":'"+val+"',<br>";
			});
			style+="}";
			
			return style;
		};
		function export_fn(defaults){
			_export.click(function(event){			
				event.preventDefault();	
				var num=$wpi_export_tabs_count++;
				$(".wpi_visual_holder .wpi_tabs").append($("<a id='del_export_tab"+num+"' href='#export_tab"+num+"' class='wpi_tab'>Export "+num+"</a><a href='#del_export_tab"+num+"' class='wpi_delete_tab wpi_icon wpi_icon_close wpi_no_text'><i></i></a>"));
				$(".wpi_visual_holder .wpi_tabs_content").append($("<div id='export_tab"+num+"' class='wpi_export_tab_content wpi_tab_content wpi_none'></div>"));	
				var export_data=export_style(defaults);
				$("#export_tab"+num).html(export_data);			
				tabs.set_tabs();
			});
		}
		
		//ui
		
		var $WPiPN={				
			cur:0,
			nxt:0,
			prv:0,
			total:0,
			init:function(total){					
				this.total=total;
				this.set_pn("s");
			},
			next:function(){
				var out=this.nxt;
				this.set_pn("n");
				return out;
			},
			prev:function(){
				var out=this.prv;
				this.set_pn("p");
				return out;
			},
			current:function(){
				var out=this.cur;
				return out;
			},
			set_pn:function(i){
				if(i=="n"){
					if((this.nxt+1)==(this.total)){
						this.prv=this.cur;
						this.cur=this.nxt;
						this.nxt=0;
					}else{
						this.prv=this.cur;
						this.cur=this.nxt;
						this.nxt=this.nxt+1;
					}
				}else if(i=="p"){
					if((this.prv-1)==(-1)){
						this.nxt=this.cur;
						this.cur=this.prv;
						this.prv=this.total-1;
					}else{
						this.nxt=this.cur;
						this.cur=this.prv;
						this.prv=this.prv-1;
					}
				}else if(i=="s"){						
					this.nxt=1;
					this.cur=0;
					this.prv=this.total-1;
				}
			},
		};
		function set_pn(args){
			
			var pn=$(args['parent']);
			var next=pn.find(args['next']);
			var prev=pn.find(args['prev']);
			var notes_nav=pn.find(".wpi_notes_nav");			
			var notes_holder=pn.find(args['holder']);	
			var notes=notes_holder.find(args['items']);	
			var view_all=pn.find(".wpi_view_all");
			var popup=pn.siblings(".wpi_popup");
			var popup_back=popup.find(".wpi_back");
			
			var wpi_pn=$WPiPN;			
			wpi_pn.init(notes.length);
			
			next.click(function(){
				var go=wpi_pn.next();
				notes.addClass("wpi_none");
				notes_holder.find(args['prefix']+go).removeClass("wpi_none");
			});	
			prev.click(function(){
				var go=wpi_pn.prev();
				notes.addClass("wpi_none");
				notes_holder.find(args['prefix']+go).removeClass("wpi_none");
			});	
			view_all.click(function(){				
				popup.removeClass("wpi_none");				
			});
			popup_back.click(function(){				
				popup.addClass("wpi_none");				
			});
			notes_nav.mouseenter(function(){
				$(this).addClass("wpi_hover");
			}).mouseleave(function(){
				$(this).removeClass("wpi_hover");
			});
			function goswipe(action){
				//alert(action);
				if(action=="n"){
					var go=wpi_pn.next();
				}else if(action=="p"){
					var go=wpi_pn.prev();
				}else{
					return;
				}
				notes.addClass("wpi_none");
				notes_holder.find(args['prefix']+go).removeClass("wpi_none");
			};
			var swipe_args={
				test:23,
				grabbingParent:args['parent'],
			};
			$(args['holder']).wpiSwipe(swipe_args, goswipe);
		};
		
		var args={ parent:".wpi_pn", holder:".wpi_notes_content_holder", items:".wpi_note", prefix:".wpi_note_", next:".wpi_next", prev:".wpi_prev",};
		set_pn(args);
		
		//Helpers
		
		
		//get_tones("#cf8790");
		function get_index(char, arr){
			var index=1;
			for(var i=0; i<arr.length;i++){
				if(char==arr[i]){
					index=i;
				}
			}
			return index;
		}
		function get_tones(color){
			var values=new Array(0,1,2,3,4,5,6,7,8,9,"a","b","c","d","e","f");
			var lighter="";
			var darker="";
			var str=color.toLowerCase();
			var res = str.split("");
			for(var i=0; i<res.length; i++){
				var val=res[i];	
				var position=get_index(val, values);						
				if(val=="#"){
					lighter+=val;	
					darker+=val;
				}else{					
					if(val=="f" || val=="F"){
						lighter+=val;					
					}else{
						//lighter+=val;
						lighter+=values[position+1];
					}
					if(val==0){
						darker+=val;					
					}else{
						//darker+=val;	
						darker+=values[position-1];
					}
				}
				
			};			
			//alert(lighter+ " "+color+" "+darker);
			return {lighter:lighter, darker:darker};			
		}	
	});
}(jQuery));