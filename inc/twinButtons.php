<?php
class WPiDesButTB{	
	public function __construct(){		
		add_action("add_meta_boxes", array($this, "meta_box"));
		add_action("save_post",array($this,"save_post"));
		add_action("wp_enqueue_scripts", array($this, 'inline_styles'),21 );
	}	 
	public function fields(){
		$icons_arr=WPiArray::get_icons_arr();
		$icons=WPiTools::array2object($icons_arr);
				
		$style_ids=WPiDesButCommon::get_style_ids();
		$fields=array(		
			array("label"=>"Style Id", "name"=>'style_id', "type"=>"select",  "section"=>"Style Section",  "group"=>"Style","value"=> "", "list"=> $style_ids),
			array("label"=>"Left Button Text", "name"=>'left_button_text', "type"=>"text",  "section"=>"Text Section", "group"=>"Text", "value"=> ""),
			array("label"=>"Right Button Text", "name"=>'right_button_text', "type"=>"text",  "section"=>"Text Section", "group"=>"Text", "value"=> ""),
			array("label"=>"Left Button Icon", "name"=>'left_button_icon', "type"=>"select",  "section"=>"Icon Section", "group"=>"Text", "value"=> "", "list"=> $icons),
			array("label"=>"Right Button Icon", "name"=>'right_button_icon', "type"=>"select",  "section"=>"Icon Section", "group"=>"Text", "value"=> "", "list"=> $icons),
			array("label"=>"Left Button Link", "name"=>'left_button_link', "type"=>"text", "section"=>"Link Section", "group"=>"Link", "value"=> ""),
			array("label"=>"Right Button Link", "name"=>'right_button_link', "type"=>"text", "section"=>"Link Section", "group"=>"Link", "value"=> ""),
			array("label"=>"Icon Position", "name"=>'icon_position', "type"=>"select",  "section"=>"Icon Section", "group"=>"Text", "value"=> "", "list"=>  array("left"=>"Left","right"=>"Right")),			
			array("label"=>"Target", "name"=>'target', "type"=>"select", "section"=>"Link Section", "group"=>"Link", "value"=> "", "list"=> array("self"=>"Self","_blank"=>"New Window")),	
			array("label"=>"Min Width", "name"=>'min_width', "type"=>"text",  "section"=>"Style Section",  "group"=>"Style","value"=> "",),		
		);
		return $fields;
	}
	public function meta_box(){		
		add_meta_box("settings", "Style Settings", array($this, "meta_box_html"), "wpi_des_but_tb", "normal", "high");
	}
	
	public function meta_box_html($post){
		wp_nonce_field("wpi_db_meta_box","wpi_db_meta_box_nonce");	
		$output= WPiTemplate::html($post->ID, $this->fields());	
		
		$classes=WPiDesButCommon::get_button_style_class($post->style_id);
		if($post->icon_position=="right"){			
			$icon_position="wpi_icon_right";
		}else{				
			$icon_position="wpi_icon_left";
		}	
		if($post->left_button_icon!=""){$left_button_icon_class="wpi_icon wpi_icon_".$post->left_button_icon." ".$icon_position;}else{$left_button_icon_class="";}
		if($post->right_button_icon!=""){$right_button_icon_class="wpi_icon wpi_icon_".$post->right_button_icon." ".$icon_position;}else{$right_button_icon_class="";}			
		if(trim($post->text)==""){$no_text_class="wpi_no_text";}else{$no_text_class="";}
		
		$styles_list=WPiDesButCommon::get_styles();	
		$modal= WPiTemplate::create_modal();	
		
		$button_id= "<div class='wpi_id wpi_icon wpi_icon_videocamera'><i></i><div class='wpi_info'><div class='wpi_text'>".$post->ID."</div><div>Twin ID: </div></div></div>";		
		$button_id=WPiDesButCommon::get_id(array("id"=>$post->ID, "label"=>"Twin ID"));
		$shortcode= "<div id='wpi_shortcode' class='wpi_icon wpi_icon_star'><i></i><div class='wpi_text'>[wpi_designer_button twin_id=".$post->ID."]</div></div>";
		$links="<div id='wpi_links'><a href='post-new.php?post_type=wpi_des_but_sty'>Create New Style</a></div>";
		
		$preview="<div id='wpi_preview' class=' button_wrap'><div id='wpi_tb' class='wpi_twin_buttons  wpi_twin_buttons_".$post->ID."''><a class='wpi_designer_button wpi_left_button {$classes} {$left_button_icon_class} {$no_text_class}' href='#'><i class='wpi_icon_l'></i><span class='wpi_text'>".$post->left_button_text."</span><i class='wpi_icon_r'></i><span class='wpi_or_txt'>or</span></a><a class='wpi_designer_button wpi_right_button {$classes} {$right_button_icon_class} {$no_text_class}' href='#'><i class='wpi_icon_l'></i><span class='wpi_text'>".$post->right_button_text."</span><i class='wpi_icon_r'></i></a></div></div>";
		
		
		$print="";
		$info="<div class='wpi_info'>".$print."</div>";		
		
		$help=self::help();	
		
		$args=array(
			array("id"=>"wpi_styles", "text"=>"Styles", "content"=>$styles_list), 
			array("id"=>"wpi_icons", "text"=>"icons", "content"=>""), 
			array("id"=>"wpi_help", "text"=>"Help", "content"=>$help, "active"=>true),
		);	
		$tabs=WPiTemplate::create_tabs($args);
		
		$visual_header="<div id='wpi_visual_header'>".$button_id."</div>";
		$action="";	
		
		$content="<div class='wpi_db wpi_des_but_tb'>";			
			$content.="<div id='wpi_designer_button_box' class='wpi_row'>";		
			$content.="<div class='wpi_13 wpi_input'>".$output.$shortcode.$links."</div>";
			$content.="<div class='wpi_23 wpi_visual'><div class='wpi_visual_holder'>";
				$content.=$visual_header;
				$content.=$preview;							
				$content.=$tabs;	
				$content.=$modal;					
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
	public function get_twin() {	
		
		$custom_css = "";
		$args = array(			
			'post_type' => 'wpi_des_but_tb', 'post_status'=>array('publish'), 'numberposts'  => -1
		); 
		$twin_button_styles = get_posts($args); 
					
		foreach ( $twin_button_styles as $s ) :				
			$element=".wpi_twin_button_".$s->ID." .wpi_designer_button";			
			if( $s->shadow=="" || $s->shadow=="no") { $shadow="0px"; }else{$shadow=$s->shadow;}			
			$classes=array(
				array(
					"element"=> $element,
					"styles"=> array(
						"min-width"=> $s->min_width."!important",
					),				
				),				
			);
			
			$custom_css.=WPiCss::build_css($classes);		
        endforeach;
		return $custom_css;		
	}	
	public function inline_styles() {		
		wp_enqueue_style(
			'custom-style',
			WPIDB_URL . 'custom_script.css'
		);		
		$custom_css.=$this->get_twin();		
		wp_add_inline_style( 'custom-style', $custom_css );
	}			
	public function help(){
		$help_args=array("notes"=>array(
		"To add icon to the button select icon from above 'Icons' tab", 
		"To apply style to button select your created style from 'Styles' tab.",
		"Copy generated shortcode from left panel and paste it in any post/page.",
		));
		$help=WPiDesButCommon::get_help_tab($help_args);
		return $help;
	}
}
$twin_buttons_page=new WPiDesButTB;

