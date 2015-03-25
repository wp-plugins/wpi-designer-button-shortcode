<?php
class WPiDesButSB{	
	public function __construct(){		
		add_action("add_meta_boxes", array($this, "meta_box"));
		add_action("save_post",array($this,"save_post"));
		add_action("wp_enqueue_scripts", array($this, 'inline_styles'),21 );
		add_action("admin_enqueue_scripts", array($this, 'inline_styles'),21);
	}	 
	public function fields(){
		$icons_arr=WPiArray::get_icons_arr();		
		$gap=WPiTools::get_list(array("suffix"=>"px","list"=>array(0,1,2,3,4,5,6,7,8,9,10)));
		
		$margin=WPiArray::get_margin();
		$margin=WPiTools::get_list(array("suffix"=>"px","list"=>$margin));	
		$show_hide=array(1=>"Show", 0=>"Hide");
			
		$style_ids=WPiDesButCommon::get_style_ids();
		$fields=array(			
			array("label"=>"Facebook", "name"=>'facebook', "type"=>"boolean",  "section"=>"Buttons", "group"=>"Facebook", "value"=> "", "list"=> $show_hide),
			array("label"=>"Twitter", "name"=>'twitter', "type"=>"boolean",  "section"=>"Buttons", "group"=>"Twitter", "value"=> "", "list"=> $show_hide),
			array("label"=>"LinkedIn", "name"=>'linkedin', "type"=>"boolean",  "section"=>"Buttons", "group"=>"Linkedin", "value"=> "", "list"=> $show_hide),
			array("label"=>"Pinterest", "name"=>'pinterest', "type"=>"boolean",  "section"=>"Buttons", "group"=>"Pinterest", "value"=> "", "list"=> $show_hide),
			array("label"=>"Google Plus", "name"=>'googleplus', "type"=>"boolean",  "section"=>"Buttons", "group"=>"Google Plus", "value"=> "", "list"=> $show_hide),
			array("label"=>"Tumblr", "name"=>'tumblr', "type"=>"boolean",  "section"=>"Buttons", "group"=>"tumblr", "value"=> "", "list"=> $show_hide),
			array("label"=>"Stumbleupon", "name"=>'stumbleupon', "type"=>"boolean",  "section"=>"Buttons", "group"=>"Stumbleupon", "value"=> "", "list"=> $show_hide),
			array("label"=>"Reddit", "name"=>'reddit', "type"=>"boolean",  "section"=>"Buttons", "group"=>"Reddit", "value"=> "", "list"=> $show_hide),
			array("label"=>"WordPress", "name"=>'wordpress', "type"=>"boolean",  "section"=>"Buttons", "group"=>"WordPress", "value"=> "", "list"=> $show_hide),
			array("label"=>"Email", "name"=>'email', "type"=>"boolean",  "section"=>"Buttons", "group"=>"Email", "value"=> "", "list"=> $show_hide),
			
			array("label"=>"Style Id", "name"=>'style_id', "type"=>"select",  "section"=>"Button Style",  "group"=>"Style","value"=> "", "list"=> $style_ids),
			
			array("label"=>"Share Text", "name"=>'share_text', "type"=>"text",  "section"=>"Text", "group"=>"Text", "value"=> ""),
								
			array("label"=>"Button Gap", "name"=>'button_gap', "type"=>"select", "section"=>"Spacing", "group"=>"Button Gap", "value"=> "", "list"=> $gap),	
			
			array("label"=>"Share Text", "name"=>'share_text_margin_bottom', "type"=>"select", "section"=>"Spacing", "group"=>"Shate Text", "css_property"=>"margin-bottom", "value"=> "", "list"=> $gap),			
			
			array("label"=>"Frame Padding Top", "name"=>'frame_padding_top', "type"=>"select", "section"=>"Spacing", "group"=>"Frame", "css_property"=>"padding-top","value"=> "", "list"=> $margin), 
			array("label"=>"Frame Padding Bottom", "name"=>'frame_padding_bottom', "type"=>"select", "section"=>"Spacing", "group"=>"Frame", "css_property"=>"padding-bottom","value"=> "", "list"=> $margin),
			array("label"=>"Frame Padding Left", "name"=>'frame_padding_left', "type"=>"select", "section"=>"Spacing", "group"=>"Frame", "css_property"=>"padding-left","value"=> "", "list"=> $margin),
			array("label"=>"Frame Padding right", "name"=>'frame_padding_right', "type"=>"select", "section"=>"Spacing", "group"=>"Frame", "css_property"=>"padding-right","value"=> "", "list"=> $margin),			
			
		);
		return $fields;
	}
	public function meta_box(){		
		add_meta_box("settings", "Style Settings", array($this, "meta_box_html"), "wpi_des_but_sb", "normal", "high");
	}
	
	public function meta_box_html($post){
		wp_nonce_field("wpi_db_meta_box","wpi_db_meta_box_nonce");	
		$output= WPiTemplate::html($post->ID, $this->fields());	
		
		$classes=WPiDesButCommon::get_button_style_class($post->style_id);
		if($post->icon!=""){$icon_class="wpi_icon wpi_icon_".$post->icon;}else{$icon_class="";}			
		if(trim($post->text)==""){$no_text_class="wpi_no_text";}else{$no_text_class="";}
		
		$styles_list=WPiDesButCommon::get_styles();	
		$buttons_data=array(
			"facebook"=>array(
				"icon"=>"facebook-alt",	"color"=>""),
			"twitter"=>array(
				"icon"=>"twitter","color"=>""),
			"googleplus"=>array(
				"icon"=>"googleplus-alt","color"=>""),
			"linkedin"=>array(
				"icon"=>"linkedin","color"=>""),
			"pinterest"=>array(
				"icon"=>"pinterest","color"=>""),
			"tumblr"=>array(
				"icon"=>"tumblr","color"=>""),
			"stumbleupon"=>array(
				"icon"=>"stumbleupon","color"=>""),
			"reddit"=>array(
				"icon"=>"reddit","color"=>""),
			"wordpress"=>array(
				"icon"=>"wordpress","color"=>""),
			"email"=>array(
				"icon"=>"mail","color"=>""),
		);		
		$style_id= WPiData::get_post_meta($post->ID, "style_id");
		$button_id= "<div class='wpi_id wpi_icon wpi_icon_videocamera'><i></i><div class='wpi_info'><div class='wpi_text'>".$post->ID."</div><div>Button ID: </div></div></div>";		
		$button_id=WPiDesButCommon::get_id(array("id"=>$post->ID, "label"=>"Share ID"));
		$shortcode= "<div id='wpi_shortcode' class='wpi_icon wpi_icon_star'><i></i><div class='wpi_text'>[wpi_designer_button share_id=".$post->ID."]</div></div>";
		$links="<div id='wpi_links'><a href='post-new.php?post_type=wpi_des_but_sty'>Create New Style</a></div>";
		$preview="<div id='wpi_preview' class=' button_wrap'><div id='wpi_sb' class='wpi_share_buttons wpi_share_buttons_".$post->ID."'>";
		$preview.="<div id='wpi_sb_text' class='wpi_sb_text'>Share on :</div><ul>";
		$button_c=1;
		foreach($buttons_data as $k => $v){
			if($button_c==count($buttons_data)){
				$last="wpi_sb_last";
			}else{
				$last="";
			}
			$preview.="<li id='wpi_sb_".$k."' class='wpi_sb_".$k." ".$last."'><a class='wpi_designer_button wpi_designer_button_".$style_id." wpi_no_text wpi_icon wpi_icon_".$v['icon']."' href='#'><i></i></a></li>";
			$button_c++;
		}
		$preview.="</ul></div></div>";		
		
		$print="";
		$info="<div class='wpi_info'>".$print."</div>";		
		
		$help=self::help();	
		
		$args=array(
			array("id"=>"wpi_styles", "text"=>"Styles", "content"=>$styles_list), 			
			array("id"=>"wpi_help", "text"=>"Help", "content"=>$help, "active"=>true),
		);	
		$tabs=WPiTemplate::create_tabs($args);
		
		$visual_header="<div id='wpi_visual_header'>".$button_id."</div>";
		$action="";	
		
		$content="<div class='wpi_db wpi_des_but_sb'>";	
			$content.=$preview;				
			$content.="<div id='wpi_designer_button_box' class='wpi_row'>";		
			$content.="<div class='wpi_13 wpi_input'>".$output.$shortcode.$links."</div>";
			$content.="<div class='wpi_23 wpi_visual'><div class='wpi_visual_holder'>";
				$content.=$visual_header;
				$content.=$tabs;							
			$content.="</div></div>";	
		$content.="</div>";	
		
		echo $content;
	}
	public function current_page_url() {
		$pageURL = 'http';
		if( isset($_SERVER["HTTPS"]) ) {
			if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
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
			'post_type' => 'wpi_des_but_sb', 'post_status'=>array('publish'), 'numberposts'  => -1
		); 
		$slide_styles = get_posts($args); 
		foreach ( $slide_styles as $ss ) :
			/*if( $ss->background_custom_image!="") {			
				$background_image= "url(". $ss->background_custom_image.")" ;
			}else{
				$background_image=$ss->background_image=="no" ? "none" : "url(". WPIDB_URL ."images/".$ss->background_image.")" ;
			}		*/	
			
			
			$classes=array(
				array(
					"element"=> ".wpi_share_buttons_".$ss->ID,
					"styles"=> array(						
						"padding-top"=> $ss->frame_padding_top,	
						"padding-bottom"=> $ss->frame_padding_bottom,	
						"padding-left"=> $ss->frame_padding_left,	
						"padding-right"=> $ss->frame_padding_right,	
					),				
				),
				array(
					"element"=>".wpi_share_buttons_".$ss->ID." li",
					"styles"=>array(						
						"margin-right"=> $ss->button_gap,
						"margin-bottom"=> $ss->button_gap,						
					),				
				),			
			);
						
			$custom_css.=WPiCss::build_css($classes);				
        endforeach;		
		
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
$share_buttons_page=new WPiDesButSB;

