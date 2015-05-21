<?php
class WPiDesButSli{
	public function __construct(){
		add_action("add_meta_boxes", array($this, "add_meta_box"));
		add_action("save_post",array($this,"save_post"));	
		add_action("wp_enqueue_scripts", array($this, 'inline_styles'),21 );
		add_action("admin_enqueue_scripts", array($this, 'inline_styles'),21);
		add_action("init", array($this, "js_wp_urls"));
	}
	public function js_wp_urls(){
		/*$fonts_list=WPiArray::get_fonts();
		$fonts=WPiTools::get_field_names($fonts_list);
		wp_register_script( 'wpi_js_urls', WPIDB_URL.'inc/wpi_script.js'  );
		wp_localize_script('wpi_js_urls', 'WPIDB_FONTS', $fonts);*/ 		
	}	
	public function fields(){
		$icons_arr=WPiArray::get_icons_arr();
		$icons=WPiTools::array2object($icons_arr);
		$fonts=WPiDesButCommon::get_fonts_options();
		
		$font_sizes=WPiArray::get_font_sizes();
		$text_size_list=WPiTools::get_list(array("suffix"=>"px","list"=>$font_sizes));
		
		$font_weights=WPiArray::get_font_weights();
		$font_weights=WPiTools::get_list(array("list"=>$font_weights));
		
		$letter_spacing=WPiArray::get_letter_spacing();
		$letter_spacing=WPiTools::get_list(array("suffix"=>"px","list"=>$letter_spacing));
		
		$margin=WPiArray::get_margin();
		$margin=WPiTools::get_list(array("suffix"=>"px","list"=>$margin));	
		
		$withMinusMargin=array_merge($margin,array("-10px"=>"-10px","-20px"=>"-20px","-30px"=>"-30px","-40px"=>"-40px","-50px"=>"-50px","-60px"=>"-60px","-70px"=>"-70px","-80px"=>"-80px","-90px"=>"-90px","-100px"=>"-100px"));
		
		$border_width=WPiArray::get_border_width();
		$border_width=WPiTools::get_list(array("suffix"=>"px","list"=>$border_width));
				
		$images=WPiTools::array2object(array("no", "Henry_Marion.jpg","mypad.jpg","dust.png","sea.jpg", "reb_blue_bricks.jpg","coffee_food.jpg","jelly_fish.jpg","yummy.jpg","mountains_sea.jpg","Food-From.jpg","black_bg.jpg","vintage.jpg","black_shape.png","Creative-Stationary.jpg","triangles.png","triangles2.png","connected_circles.png", "bottom_lighting.png","blur1.png","city1.jpg","forest1.jpg", "message_icons.png", "star_rays.png"));
		$repeat_images=WPiTools::array2object(array("no","grey_texture.png","wood_texture.jpg","squares45.png","noise2.png"));		
		//$border_width=array("0px"=>"No", "1px"=>"1px", "2px"=>"2px", "3px"=>"3px", "4px"=>"4px","5px"=>"5px","6px"=>"6px","7px"=>"7px","8px"=>"8px","9px"=>"9px","10px"=>"10px");
		$text_shadow_distance=WPiTools::get_list(array("suffix"=>"px","list"=>array(0,1,2,3,4,5,6,7,8,9,10)));	
		
		$opacity=WPiTools::get_list(array("list"=>array(100,90,80,70,60,50,40,30,20,10,0)));
		$style_ids=WPiDesButCommon::get_style_ids();	
		$slide_height=WPiTools::get_list(array("suffix"=>"px","list"=>array(100, 150, 200, 250, 300,400,500,600,700,800)));
				
		$fields=array(				
			array("label"=>"Slide Heading", "name"=>'slide_heading', "type"=>"textarea", "section"=>"Text", "group"=>"Heading", "value"=> "",),
			array("label"=>"Slide Sub Heading", "name"=>'slide_text', "type"=>"textarea", "section"=>"Text", "group"=>"Sub Heading", "value"=> "", ),
			array("label"=>"Slide Footer Text", "name"=>'slide_footer_text', "type"=>"textarea", "section"=>"Text",  "group"=>"Footer", "value"=> "", ),
			array("label"=>"Button Text", "name"=>'button_text', "type"=>"text", "section"=>"Text",  "group"=>"Button", "value"=> "", ),		
			array("label"=>"Slide Heading_2", "name"=>'slide_heading_2', "type"=>"text", "section"=>"Text", "group"=>"Multiple Headings (Animated)", "value"=> "",),
			array("label"=>"Slide Heading_3", "name"=>'slide_heading_3', "type"=>"text", "section"=>"Text", "group"=>"Multiple Headings (Animated)", "value"=> "",),
			
			array("label"=>"Slide Heading Font", "name"=>'slide_heading_font', "type"=>"select", "section"=>"Fonts", "group"=>"Heading", "css_property"=>"font-family", "value"=> "", "list"=> $fonts),
			array("label"=>"Slide Heading Size", "name"=>'slide_heading_size', "type"=>"select", "section"=>"Fonts", "group"=>"Heading", "css_property"=>"font-size", "value"=> "", "list"=> $text_size_list),
			array("label"=>"Slide Heading Font Weight", "name"=>'slide_heading_font_weight', "type"=>"select", "section"=>"Fonts", "group"=>"Heading", "css_property"=>"font-weight", "value"=> "", "list"=> $font_weights),
			array("label"=>"Slide Heading line Height", "name"=>'slide_heading_line_height', "type"=>"select", "section"=>"Fonts", "group"=>"Heading", "css_property"=>"line-height", "value"=> "", "list"=> array("1.2em"=>"1.2","1.1em"=>"1.1", "1em"=>"1", "0.9em"=>"0.9", "0.8em"=>"0.8", "0.7em"=>"0.7", "0.6em"=>"0.6")),
			array("label"=>"Slide Heading Letter Spacing", "name"=>'slide_heading_letter_spacing', "type"=>"select", "section"=>"Fonts", "group"=>"Heading", "css_property"=>"letter-spacing", "value"=> "", "list"=> $letter_spacing),
			
			array("label"=>"Slide Text Font", "name"=>'slide_text_font', "type"=>"select", "section"=>"Fonts", "group"=>"Sub Heading", "css_property"=>"font-family", "value"=> "",
			"list"=> $fonts),			
			array("label"=>"Slide Text Size", "name"=>'slide_text_size', "type"=>"select", "section"=>"Fonts", "group"=>"Sub Heading", "css_property"=>"font-size", "value"=> "", "list"=> $text_size_list),	
			array("label"=>"Slide Text Font Weight", "name"=>'slide_text_font_weight', "type"=>"select", "section"=>"Fonts", "group"=>"Sub Heading", "css_property"=>"font-weight", "value"=> "","list"=> $font_weights),
			array("label"=>"Slide Footer Text Size", "name"=>'slide_footer_text_size', "type"=>"select", "section"=>"Fonts",  "group"=>"Footer",  "css_property"=>"font-size", "value"=> "", "list"=> $text_size_list),		
			
			array("label"=>"Slide Heading color", "name"=>'slide_heading_color', "type"=>"text", "section"=>"Colors", "group"=>"Heading", "css_property"=>"color", "value"=> ""),
			array("label"=>"Slide Heading Border Color", "name"=>'slide_heading_border_color', "type"=>"text", "section"=>"Colors", "group"=>"Heading", "css_property"=>"border-color", "value"=> ""),
			array("label"=>"Slide Heading Background Color", "name"=>'slide_heading_background_color', "type"=>"text", "section"=>"Colors", "group"=>"Heading", "css_property"=>"background-color", "value"=> ""),
			array("label"=>"Slide Text color", "name"=>'slide_text_color', "type"=>"text", "section"=>"Colors", "group"=>"Sub heading", "css_property"=>"color", "value"=> ""),
			array("label"=>"Background Color", "name"=>'background_color', "type"=>"text", "section"=>"Colors",  "group"=>"Background", "css_property"=>"background-color", "value"=> "",),	
			array("label"=>"Slide Heading Margin Top", "name"=>'slide_heading_margin_top', "type"=>"select", "section"=>"Spacing", "group"=>"Heading", "css_property"=>"margin-top", "value"=> "", "list"=> $margin),
			array("label"=>"Slide Heading Margin Left", "name"=>'slide_heading_margin_left', "type"=>"select", "section"=>"Spacing", "group"=>"Heading", "css_property"=>"margin-left", "value"=> "", "list"=> $margin),
			array("label"=>"Slide Heading Margin Right", "name"=>'slide_heading_margin_right', "type"=>"select", "section"=>"Spacing", "group"=>"Heading", "css_property"=>"margin-right", "value"=> "", "list"=> $margin),
			array("label"=>"Slide Heading Margin Bottom", "name"=>'slide_heading_margin_bottom', "type"=>"select", "section"=>"Spacing", "group"=>"Heading", "css_property"=>"margin-bottom", "value"=> "", "list"=> $margin),
			array("label"=>"Slide Heading Padding", "name"=>'slide_heading_padding', "type"=>"select", "section"=>"Spacing", "group"=>"Heading", "css_property"=>"padding", "value"=> "", "list"=> $margin),
			array("label"=>"Slide Text Margin Top", "name"=>'slide_text_margin_top', "type"=>"select", "section"=>"Spacing", "group"=>"Sub Heading", "css_property"=>"margin-top", "value"=> "", "list"=> $margin),
			array("label"=>"Slide Text Margin Left", "name"=>'slide_text_margin_left', "type"=>"select", "section"=>"Spacing", "group"=>"Sub Heading", "css_property"=>"margin-left", "value"=> "", "list"=> $margin),
			array("label"=>"Slide Text Margin Right", "name"=>'slide_text_margin_right', "type"=>"select", "section"=>"Spacing", "group"=>"Sub Heading", "css_property"=>"margin-right", "value"=> "", "list"=> $margin),
			array("label"=>"Button Margin top", "name"=>'button_margin_top', "type"=>"select", "section"=>"Spacing", "group"=>"Button", "css_property"=>"margin-top", "value"=> "", "list"=> $margin),
			array("label"=>"Button Margin Bottom", "name"=>'button_margin_bottom', "type"=>"select", "section"=>"Spacing", "group"=>"Button", "css_property"=>"margin-bottom", "value"=> "", "list"=> $margin),	
			array("label"=>"Slide Footer padding", "name"=>'slide_footer_padding', "type"=>"select", "section"=>"Spacing",  "group"=>"Footer", "value"=> "", "list"=> $margin),
			array("label"=>"Frame Margin Left", "name"=>'frame_margin_left', "type"=>"select", "section"=>"Spacing", "group"=>"Frame", "css_property"=>"margin-left", "value"=> "", "list"=> $withMinusMargin),
			array("label"=>"Frame Margin Right", "name"=>'frame_margin_right', "type"=>"select", "section"=>"Spacing", "group"=>"Frame", "css_property"=>"margin-right", "value"=> "", "list"=> $withMinusMargin),
				
			array("label"=>"Slide Heading Border Width", "name"=>'slide_heading_border_width', "type"=>"select", "section"=>"Effects", "group"=>"Borders", "css_property"=>"border-width", "value"=> "", "list"=> $border_width),
			array("label"=>"Slide Heading Shadow", "name"=>'slide_heading_shadow_distance', "type"=>"select", "section"=>"Effects", "group"=>"Shadows",  "css_property"=>"text-shadow-distance", "value"=> "", "list"=> $text_shadow_distance),
			
			
			
			
			
			array("label"=>"Button Style", "name"=>'style_id', "type"=>"select", "section"=>"Button Settings",  "group"=>"Style", "value"=> "", "list"=> $style_ids),			
			array("label"=>"Button link", "name"=>'button_link', "type"=>"text", "section"=>"Button Settings",  "group"=>"Link", "value"=> "", ),			
			array("label"=>"Button Icon", "name"=>'icon', "type"=>"select",  "section"=>"Button Settings",  "group"=>"Icon", "value"=> "", "list"=> $icons),
			array("label"=>"Target", "name"=>'target', "type"=>"select", "section"=>"Button Settings",  "group"=>"Link", "value"=> "", "list"=> array("self"=>"Self","_blank"=>"New Window")),
					
			array("label"=>"Custom Image", "name"=>'background_custom_image', "type"=>"wp_image", "section"=>"Frame",  "group"=>"Background Image", "css_property"=>"background-image", "value"=> "",),	
			array("label"=>"Preset Image", "name"=>'background_image', "type"=>"select", "section"=>"Frame",  "group"=>"Background Image", "css_property"=>"background-image", "value"=> "", "list"=> $images),
			array("label"=>"Image Opacity", "name"=>'background_image_opacity', "type"=>"select", "section"=>"Frame",  "group"=>"Background Image",  "css_property"=>"opacity", "value"=> "", "list"=> $opacity),
			array("label"=>"Image Blur", "name"=>'background_image_blur', "type"=>"select", "section"=>"Frame",  "group"=>"Background Image Effects",  "css_property"=>"blur", "value"=> "", "list"=> $text_shadow_distance),		
			array("label"=>"Background Repeat Image", "name"=>'background_repeat_image', "type"=>"select", "section"=>"Frame",  "group"=>"Repeat Image", "css_property"=>"background-image", "value"=> "", "list"=> $repeat_images),
			array("label"=>"Frame Height", "name"=>'frame_height', "type"=>"select", "section"=>"Frame", "group"=>"Frame Height", "css_property"=>"min-height", "default"=>"500px", "value"=> "", "list"=> $slide_height),
			array("label"=>"Frame Width", "name"=>'frame_width', "type"=>"text", "section"=>"Frame", "group"=>"Frame Width", "css_property"=>"width", "value"=> "",),
			
			
		);		
		return $fields;
	}
	public function add_meta_box(){
		add_meta_box("settings", "Slide Settings", array($this, "meta_box_html"), "wpi_des_but_sli", "normal", "high");	
	}
	public function meta_box_html($post){
		wp_nonce_field("wpi_db_meta_box", "wpi_db_meta_box_nonce");
		$output=WPiTemplate::html($post->ID, $this->fields());
		
		$slide_id= WPiDesButCommon::get_id(array("id"=>$post->ID, "label"=>"Slide ID"));
		$action="<div id='wpi_export'>Export</div><div id='wpi_restore' class='wpi_none'>Restore</div>";						
		$visual_header="<div id='wpi_visual_header'>".$action.$slide_id."</div>";
		
		$shortcode= "<div id='wpi_shortcode' class='wpi_icon wpi_icon_star'><i></i><div class='wpi_text'>[wpi_designer_button slide_id=".$post->ID."]</div></div>";
		
		$styles_list=WPiDesButCommon::get_styles();			
				
		$preview="<div  id='wpi_preview' class='button_wrap'><div id='wpi_slide' class='wpi_layout wpi_layout_has_property'>";
		$preview.="<div id='wpi_slide_image' class='wpi_layout_item wpi_layout_has_property'></div>";
		$preview.="<div id='wpi_slide_content' >";			
			$preview.="<div id='wpi_slide_heading'  class='wpi_layout_item wpi_layout_has_property'>
				<div class='wpi_heading_1'><span>Heading</span></div>
				<div class='wpi_heading_2'><span>Heading</span></div>
				<div class='wpi_heading_3'><span>Heading</span></div>
			</div>";
			$preview.="<div id='wpi_slide_text'  class='wpi_layout_item wpi_layout_has_property'>Running Text</div>";		
			$preview.="<div id='wpi_slide_button'  class='wpi_layout_item wpi_layout_has_property'><a class='wpi_designer_button' href='#'  ><i></i><span class='wpi_text'>Button</span></a></div>";
		$preview.="</div>";
		$preview.="<div id='wpi_slide_footer'  class='wpi_layout_item wpi_layout_has_property'>Footer Text</div>";		
		$preview.="</div></div>";	
	
		$help=self::help();	
		$args=array(
			array("id"=>"wpi_colors", "text"=>"Color Palette", "content"=>""), 
			array("id"=>"wpi_styles", "text"=>"Button Styles", "content"=>$styles_list),
			array("id"=>"wpi_icons", "text"=>"icons", "content"=>""),
			array("id"=>"wpi_sli_presets", "text"=>"Presets", "content"=>""),  			
			array("id"=>"wpi_help", "text"=>"Help", "content"=>$help, "active"=>true),
		);	
		$tabs=WPiTemplate::create_tabs($args);	
		
		$content="<div class='wpi_db wpi_des_but_sli'>";
			$content.=$preview;				
			$content.="<div id='wpi_designer_button_box' class='wpi_row'>";		
			$content.="<div class='wpi_13 wpi_input'>".$output.$shortcode."</div>";
			$content.="<div class='wpi_23 wpi_visual'><div class='wpi_visual_holder'>";
				$content.=$visual_header;									
				$content.=$tabs;						
			$content.="</div></div>";	
		$content.="</div>";	       	
		echo $content;
	}
	public function save_post($post_id){
		if(!isset($_POST['wpi_db_meta_box_nonce'])) return;
		if(!wp_verify_nonce($_POST['wpi_db_meta_box_nonce'], "wpi_db_meta_box")) return;
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if(!current_user_can("edit_post", $post_id)) return;
		//$field_names=WPiTools::get_field_names($this->fields());	
		WPiData::update_post_meta($post_id, $this->fields());
	}	
	public function inline_styles() {
		wp_enqueue_style(
			'custom-style',
			WPIDB_URL . 'custom_script.css'
		);
		$custom_css=$this->get_slides();
		wp_add_inline_style( 'custom-style', $custom_css );
	}
	public function get_slides() {			
		$custom_css = "";
		$args = array(			
			'post_type' => 'wpi_des_but_sli', 'post_status'=>array('publish'), 'numberposts'  => -1
		); 
		$slide_styles = get_posts($args); 
		foreach ( $slide_styles as $ss ) :
			if( $ss->background_custom_image!="") {			
				$background_image= "url(". $ss->background_custom_image.")" ;
			}else{
				$background_image=$ss->background_image=="no" ? "none" : "url(". WPIDB_URL ."images/".$ss->background_image.")" ;
			}		
			
			if( $ss->background_repeat_image=="" || $ss->background_repeat_image=="no") {			
				$ss->background_repeat_image="none";
			}else{
				$ss->background_repeat_image= "url(". WPIDB_URL ."images/".$ss->background_repeat_image.")" ;
			}
			$slide_footer_display= $ss->slide_footer_text=="" ? "none" : " ";
			if($ss->frame_margin_left=="0px") $ss->frame_margin_left="auto";
			if($ss->frame_margin_right=="0px") $ss->frame_margin_right="auto";
			
			$classes=array(
				array(
					"element"=> ".wpi_slide_".$ss->ID,
					"styles"=> array(
						"min-height"=> $ss->frame_height,
						"width"=> $ss->frame_width,	
						"background-color"=> $ss->background_color,
						"background-image"=> $ss->background_repeat_image,
						"color"=> "#ffffff",
						"margin-left"=> $ss->frame_margin_left,	
						"margin-right"=> $ss->frame_margin_right,	
					),				
				),
				array(
					"element"=>".wpi_slide_".$ss->ID." .wpi_slide_heading",
					"styles"=>array(
						"font-size"=> $ss->slide_heading_size,	
						"font-weight"=> $ss->slide_heading_font_weight,
						"font-family"=> $ss->slide_heading_font,
						"color"=> $ss->slide_heading_color,
						"background-color"=> $ss->slide_heading_background_color,
						"margin-top"=> $ss->slide_heading_margin_top,
						"margin-left"=> $ss->slide_heading_margin_left,
						"margin-right"=> $ss->slide_heading_margin_right,
						"margin-bottom"=> $ss->slide_heading_margin_bottom,
						"padding"=> $ss->slide_heading_padding,
						"border-width"=> $ss->slide_heading_border_width,
						"border-color"=> $ss->slide_heading_border_color,
						"line-height"=> $ss->slide_heading_line_height,
						"letter-spacing"=> $ss->slide_heading_letter_spacing,
						"text-shadow-x"=> $ss->slide_heading_shadow_distance,
						"text-shadow-y"=> $ss->slide_heading_shadow_distance,
						"text-shadow-blur"=> $ss->slide_heading_shadow_distance,
					),				
				),
				array(
					"element"=>".wpi_slide_".$ss->ID." .wpi_slide_text",
					"styles"=>array(
						"font-size"=> $ss->slide_text_size,
						"font-weight"=> $ss->slide_text_font_weight,
						"font-family"=> $ss->slide_text_font,	
						"color"=> $ss->slide_text_color,	
						"margin-top"=> $ss->slide_text_margin_top,
						"margin-left"=> $ss->slide_text_margin_left,
						"margin-right"=> $ss->slide_text_margin_right,	
					),				
				),
				array(
					"element"=>".wpi_slide_".$ss->ID." .wpi_slide_image",
					"styles"=>array(
						"left"=> "0px",	
						"top"=> "0px",	
						"background-image"=> $background_image,
						"blur"=> $ss->background_image_blur,
						"opacity"=> ($ss->background_image_opacity/100),
					),				
				),
				array(
					"element"=>".wpi_slide_".$ss->ID." .wpi_designer_button",
					"styles"=>array(
						"margin-top"=> $ss->button_margin_top,
						"margin-bottom"=> $ss->button_margin_bottom,						
					),				
				),
				array(
					"element"=>".wpi_slide_".$ss->ID." .wpi_slide_footer",
					"styles"=>array(
						"padding"=>$ss->slide_footer_padding,
						"display"=>$slide_footer_display,
						"font-size"=> $ss->slide_footer_text_size,
					),				
				),
			
			);
						
			$custom_css.=WPiCss::build_css($classes);				
        endforeach;		
		
		return $custom_css;
	}		
	public function help(){
		$help_args=array("notes"=>array(
		"First create button style before creating call to action button", 
		"To add icon to the button select icon from above 'Icons' tab", 
		"To apply style to button select your created style from 'Styles' tab.",
		"Copy generated shortcode from left panel and paste it in any post/page.",
		));
		$help=WPiDesButCommon::get_help_tab($help_args);
		return $help;
	}
}
$slides_page=new WPiDesButSli;
?>