<?php
class WPiDesButSty{	
	
	public function __construct(){
		add_action("add_meta_boxes", array($this, "meta_box"));
		add_action("save_post",array($this,"save_post"));
		add_action("wp_enqueue_scripts", array($this, 'inline_styles'),21 );
		add_action("admin_enqueue_scripts", array($this, 'inline_styles'),21);
		
	}
	
	public function fields(){
		$icons_arr=WPiArray::get_icons_arr();
		$icons=WPiTools::array2object($icons_arr);
		$fonts=WPiDesButCommon::get_fonts_options();
		$font_weight=WPiTools::get_list(array("suffix"=>"","list"=>array(100,300,400,600,700)));
		$text_size_list=WPiTools::get_list(array("suffix"=>"px","list"=>array(8,9,10,12,14,16,18,20,23,26,28,30,32,36,40,44,48,52,66,72,80,100,120)));		
		$fields=array(		
			
			array("label"=>"Shape", "name"=>'shape', "type"=>"select", "section"=>"Button Shape", "group"=>"Button Shape", "value"=> "", 
				"list"=> array("rectangle"=>"Rectangle", "5px"=>"5px", "7px"=>"7px", "10px"=>"10px","15px"=>"15px","rounded"=>"Rounded")),
			array("label"=>"Padding", "name"=>'padding', "type"=>"select", "section"=>"Button Shape", "group"=>"Button Shape", "value"=> "", 
				"list"=> array("3px"=>"3px","5px"=>"5px","7px"=>"7px","10px"=>"10px", "15px"=>"15px", "20px"=>"20px", "30px"=>"30px","40px"=>"40px","50px"=>"50px")),
			array("label"=>"Display", "name"=>'display', "type"=>"select", "section"=>"Button Shape", "group"=>"Button Shape", "css_property"=>"display", "value"=> "", 
				"list"=> array("inline-block"=>"Inline Block ","block"=>"Block")),	
			array("label"=>"Min Width", "name"=>'min_width', "type"=>"text", "section"=>"Button Shape", "group"=>"Button Shape", "css_property"=>"min-width", "value"=> "", ),	
			array("label"=>"Font", "name"=>'font', "type"=>"select", "section"=>"Font Style", "group"=>"Font Style", "css_property"=>"font-family", "value"=> "", "list"=> $fonts),
			array("label"=>"Font Weight", "name"=>'font_weight', "type"=>"select", "section"=>"Font Style", "group"=>"Font Style", "css_property"=>"font-weight", "value"=> "", "list"=> $font_weight),
			array("label"=>"Text Size", "name"=>'text_size', "type"=>"select", "section"=>"Font Style", "group"=>"Font Style", "css_property"=>"font-size", "value"=> "",
				"list"=> $text_size_list),
			array("label"=>"Border Width", "name"=>'border_width', "type"=>"select", "section"=>"Border", "group"=>"Border", "css_property"=>"border-width", "value"=> "",				
				"list"=> array("0px"=>"No", "1px"=>"1px", "2px"=>"2px", "3px"=>"3px", "4px"=>"4px","5px"=>"5px","6px"=>"6px","7px"=>"7px","8px"=>"8px","9px"=>"9px","10px"=>"10px")),
			array("label"=>"Border Style", "name"=>'border_style', "type"=>"select", "section"=>"Border", "group"=>"Border", "css_property"=>"border-style", "value"=> "", 
				"list"=> array("solid"=>"Solid", "dashed"=>"Dashed", "dotted"=>"Dotted", "double"=>"Double","inset"=>"Inset","outset"=>"Outset")),
			array("label"=>"Border Sides", "name"=>'border_sides', "type"=>"select", "section"=>"Border", "group"=>"Border","value"=> "", 
				"list"=> array("all"=>"All Sides", "bottom"=>"Only Bottom",  "top"=>"Only Top", "left"=>"Only Left", "right"=>"Only Right")),
				
			array("label"=>"Texture", "name"=>'texture', "type"=>"select", "section"=>"Effects", "group"=>"Texture", "value"=> "", 
				"list"=> array("no"=>"No", "noise.png"=>"Noise", "checks.png"=>"Checks", "checks_small.png"=>"Checks Small")),	
			array("label"=>"Shadow", "name"=>'shadow', "type"=>"select", "section"=>"Effects", "group"=>"Shadow", "value"=> "", 
				"list"=> array("no"=>"No", "1px"=>"1px", "2px"=>"2px", "3px"=>"3px", "4px"=>"4px","5px"=>"5px","6px"=>"6px","7px"=>"7px","8px"=>"8px","9px"=>"9px","10px"=>"10px",)),
			array("label"=>"Shadow Type", "name"=>'shadow_type', "type"=>"select", "section"=>"Effects", "group"=>"Shadow", "value"=> "", 
				"list"=> array(""=>"outside", "inset"=>"inside",)),
			array("label"=>"Text Shadow", "name"=>'text_shadow', "type"=>"select", "section"=>"Effects", "group"=>"Shadow","value"=> "", 
				"list"=>  array("no"=>"No","1px"=>"1px", "2px"=>"2px", "3px"=>"3px", "4px"=>"4px","5px"=>"5px")),	
			array("label"=>"Glow Size", "name"=>'glow_size', "type"=>"select", "section"=>"Effects","group"=>"Glow", "css_property"=>"glow", "value"=> "", 
				"list"=> array("no"=>"No", "1px"=>"1px", "2px"=>"2px", "3px"=>"3px", "4px"=>"4px","5px"=>"5px","6px"=>"6px","7px"=>"7px","8px"=>"8px","9px"=>"9px","10px"=>"10px")),
			array("label"=>"Glow Color", "name"=>'glow_color', "type"=>"text", "section"=>"Effects","group"=>"Glow",  "css_property"=>"glow", "value"=> ""), 											
			
					
			array("label"=>"Text Color", "name"=>'text_color', "type"=>"text", "section"=>"Colors", "group"=>"Color","css_property"=>"color", "value"=> ""),
			array("label"=>"Text Hover Color", "name"=>'text_color_h', "type"=>"text", "section"=>"Colors", "group"=>"Color","css_property"=>"color", "value"=> ""),
			array("label"=>"Text Active Color", "name"=>'text_color_a', "type"=>"text", "section"=>"Colors", "group"=>"Color","css_property"=>"color", "value"=> ""), 
			  
			array("label"=>"Background Color", "name"=>'background_color', "type"=>"text", "section"=>"Colors","group"=>"Background Color",  "css_property"=>"background-color", "value"=> ""),
			array("label"=>"Background Hover Color", "name"=>'background_color_h', "type"=>"text", "section"=>"Colors", "group"=>"Background Color", "css_property"=>"background-color", "value"=> ""),
			array("label"=>"Background Active Color", "name"=>'background_color_a', "type"=>"text", "section"=>"Colors", "group"=>"Background Color","css_property"=>"background-color", "value"=> ""), 
			array("label"=>"Border Color", "name"=>'border_color', "type"=>"text", "section"=>"Colors", "group"=>"Border Color", "css_property"=>"border-color", "value"=> ""),
			array("label"=>"Border Hover Color", "name"=>'border_color_h', "type"=>"text", "section"=>"Colors", "group"=>"Border Color", "css_property"=>"border-color", "value"=> ""),
			array("label"=>"Border Active Color", "name"=>'border_color_a', "type"=>"text", "section"=>"Colors", "group"=>"Border Color", "css_property"=>"border-color", "value"=> ""),
		);
		
		return $fields;
	}
	
	public function meta_box(){		
		add_meta_box("settings", "Style Settings", array($this, "meta_box_html"), "wpi_des_but_sty", "normal", "high");
	}
	public function meta_box_html($post){
		wp_nonce_field("wpi_db_meta_box","wpi_db_meta_box_nonce");				
		$output= WPiTemplate::html($post->ID, $this->fields());	
		
		$style_id= WPiDesButCommon::get_id(array("id"=>$post->ID, "label"=>"Style ID"));
		$action="<div id='wpi_export'>Export</div><div id='wpi_restore' class='wpi_none'>Restore</div>";						
		$visual_header="<div id='wpi_visual_header'>".$action.$style_id."</div>";	
		
				
		$preview="<div  id='wpi_preview' class='button_wrap'><a class='wpi_designer_button' href='#'><i></i><span class='wpi_text'>Button</span></a></div>";				
		
		$button_type="<div id='wpi_button_type' class='wpi_header'><div id='wpi_button_type_holder' class='wpi_header_holder'><div id='wpi_icon_text' class='wpi_designer_button wpi_icon wpi_icon_videocamera  wpi_rounded'  title='Icon & Text'><i></i>Icon & Text</div><div id='wpi_only_text' class='wpi_designer_button wpi_icon wpi_icon_no wpi_rounded'  title='Text Only'>Only Text</div><div id='wpi_only_icon' class='wpi_designer_button wpi_no_text wpi_icon wpi_icon_videocamera wpi_rounded' title='Icon Only'><i></i></div></div></div>";	
		$presets="<div id='wpi_presets'></div>";		
		$presets_data=$button_type.$presets;
		$help=self::help();
		
		$args=array(
			array("id"=>"wpi_colors", "text"=>"Color Palette", "content"=>""), 
			array("id"=>"wpi_presets_data", "text"=>"Style Presets", "content"=>$presets_data), 
			array("id"=>"wpi_themes", "text"=>"Style Themes", "content"=>""),
			array("id"=>"wpi_help", "text"=>"Help", "content"=>$help, "active"=>true),
		);	
		$tabs=WPiTemplate::create_tabs($args);	
		
		$print="";
		$info="<div class='wpi_info'>".$print."</div>";	
		
		$content="<div class='wpi_db wpi_des_but_sty'>";			
			$content.="<div id='wpi_designer_button_box' class='wpi_row'>";		
			$content.="<div class='wpi_13 wpi_input'>".$output."</div>";
			$content.="<div class='wpi_23 wpi_visual'><div class='wpi_visual_holder'>";
				$content.=$visual_header;
				$content.=$preview;						
				$content.=$tabs;						
			$content.="</div></div>";	
		$content.="</div>";	
       
		echo $content;
	}
	public function save_post($post_id){
		if(!isset($_POST['wpi_db_meta_box_nonce'])) return;
		if(!wp_verify_nonce($_POST["wpi_db_meta_box_nonce"],"wpi_db_meta_box"))return;
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if(!current_user_can("edit_post", $post_id))return;	
		//$field_names=WPiTools::get_field_names($this->fields());	
		WPiData::update_post_meta($post_id, $this->fields());	
	}
	public function inline_styles() {		
		wp_enqueue_style(
			'custom-style',
			WPIDB_URL . 'custom_script.css'
		);		
		$custom_css = "";
		$args = array(			
			'post_type' => 'wpi_des_but_sty', 'post_status'=>array('publish'), 'numberposts'  => -1
		); 
		$button_styles = get_posts($args); 
			
		foreach ( $button_styles as $s ) :				
			$element=".wpi_designer_button_".$s->ID;
			//border
			$border_left="0px!important";	$border_right="0px!important"; $border_bottom="0px!important"; $border_top="0px!important";
			switch ($s->border_sides){
				case "all" : $border_left="";	$border_right=""; $border_bottom=""; $border_top=""; break;
				case "top" : $border_top="";	break;
				case "bottom" : $border_bottom="";	break;
				case "left" : $border_left="";	break;
				case "right" : $border_right="";	break;
			}	
			//padding
			$padding="0px";
			if($s->padding){ 
				$padding=str_replace("px","",$s->padding);
				$padding=$padding."px ".($padding*2)."px";
			}
			if( $s->texture=="" || $s->texture=="no") {			
				$texture="none";
			}else{
				$texture= "url(". WPIDB_URL ."images/".$s->texture.")" ;
			}
			if( $s->shadow=="" || $s->shadow=="no") { $shadow="0px"; }else{$shadow=$s->shadow;}
			if( $s->shadow_type!="") {$shadow_type=$s->shadow_type;}else{$shadow_type="";}
			if( $s->glow_size=="" || $s->glow_size=="no") {	$glow="0px";}else{$glow=$s->glow_size;}			
			if( $s->text_shadow=="" || $s->text_shadow=="no") {	$text_shadow="0px";}else{$text_shadow=$s->text_shadow;}
			if( $s->shape=="" || $s->shape=="rectangle") {	$radius="0px";}else{$radius=$s->shape;}
			if($s->background_color ==""){$s->background_color="transparent";}
			if($s->background_color_h ==""){$s->background_color_h="transparent";}
			if($s->background_color_a ==""){$s->background_color_a="transparent";}
			$classes=array(
				array(
					"element"=> $element,
					"styles"=> array(
						"font-family"=> $s->font."!important",	
						"font-size"=> $s->text_size."!important",	
						"font-weight"=> $s->font_weight."!important",	
						"border-width"=> $s->border_width."!important",	
						"border-left-width"=> $border_left,
						"border-right-width"=> $border_right,	
						"border-top-width"=> $border_top,						
						"border-bottom-width"=> $border_bottom,		
						"border-style"=> $s->border_style."!important",	
						"padding"=> $padding."!important",
						"display"=> $s->display."!important",
						"min-width"=> $s->min_width."!important",	
						"color"=> $s->text_color."!important",	
						"background-color"=> $s->background_color."!important",
						"border-color"=> $s->border_color."!important",
						"background-image"=> $texture."!important",
						"box-shadow-x"=> $shadow,
						"box-shadow-y"=> $shadow,
						"box-shadow-blur"=> $shadow,
						"box-shadow-inset"=> $shadow_type,
						"text-shadow-x"=> $text_shadow,
						"text-shadow-y"=> $text_shadow,
						"text-shadow-blur"=> $text_shadow,
						"box-shadow-x"=>"0px",
						"box-shadow-y"=>"0px",
						"box-shadow-blur"=>$glow,
						"border-radius"=>$radius."!important",
					),				
				),
				array(
					"element"=> $element.":hover",
					"styles"=> array(
						"color"=> $s->text_color_h."!important",	
						"background-color"=> $s->background_color_h."!important",
						"border-color"=> $s->border_color_h."!important",
					),				
				),	
				array(
					"element"=> $element.":active",
					"styles"=> array(
						"color"=> $s->text_color_a."!important",	
						"background-color"=> $s->background_color_a."!important",
						"border-color"=> $s->border_color_a."!important",
					),				
				),	
				array(
					"element"=> $element.".wpi_no_text",
					"styles"=> array(						
						"padding"=> $s->padding."!important",					
					),				
				),			
			);
			
			$custom_css.=WPiCss::build_css($classes);		
        endforeach;
		
		wp_add_inline_style( 'custom-style', $custom_css );
	}	
	public function help(){
		$help_args=array("notes"=>array("If you are a new user to this plugin then its best practice to start with Style Presets.", "Select button style from above style presets tab.", "Please enter Text Size in pixels like 24px.", "Enter color values like #ff33ff. It should contains total 7 characters including # symbol.", "If you don't know about color values then, You can copy color values from above color palette tab."));
		
		$help=WPiDesButCommon::get_help_tab($help_args);
		return $help;
	}
}
$styles_page=new WPiDesButSty;

