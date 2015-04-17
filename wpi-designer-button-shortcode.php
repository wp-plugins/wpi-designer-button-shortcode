<?php
/**
 * Plugin Name: WPi Designer Button Shortcode
 * Plugin URI: http://designerbutton.prali.in/
 * Description: Create Designer Buttons anywhere in wordpress using button shortcode [wpi_designer_button]
 * Version: 2.3.2
 * Author: wooprali
 * Author URI: http://wooprali.prali.in
 * Text Domain: wooprali
 * Domain Path: /locale/
 * Network: true
 * License: GPL2
 */
defined('ABSPATH') or die("No script kiddies please!");
if ( !defined('WPIDB_URL' ) ) {
	define( 'WPIDB_URL', plugin_dir_url( __FILE__ ) ); 
}
require_once("inc/array.php");
require_once("inc/functions.php");
require_once("inc/common.php");
require_once("inc/styles.php");
require_once("inc/buttons.php");
require_once("inc/slides.php");
require_once("inc/shareButtons.php");


class WPiDesignerButtonShortcode{

	const VERSION = '1.0.0';

	public function __construct(){
		add_action('init', array($this, 'load_plugin_textdomain' ) );
		add_action("init", array($this, "admin_options"));
		add_action("init", array($this, "js_wp_urls"));
		add_action("init", array($this, "editor_buttons"));
		add_action("admin_menu", array($this, 'register_admin_menu'));		
		
		add_action("wp_enqueue_scripts", array($this, "enqueue_scripts"),20 );
		add_action("admin_enqueue_scripts", array($this, "enqueue_scripts_admin"),20 );	
		
		add_shortcode("wpi_designer_button",array($this, "designer_button"));		
		
		add_filter('manage_wpi_des_but_posts_columns', array($this, 'buttons_head'), 10);
		add_action('manage_wpi_des_but_posts_custom_column', array($this, 'buttons_content'), 10, 2);
		add_filter('manage_wpi_des_but_sty_posts_columns', array($this, 'styles_head'), 10);
		add_action('manage_wpi_des_but_sty_posts_custom_column', array($this, 'styles_content'), 10, 2);
		add_filter('body_class', array($this, 'wpi_body_class' ));
		
		add_action("admin_enqueue_scripts", array($this, 'load_fonts'));
		add_action("wp_enqueue_scripts", array($this, 'load_fonts'));
		
		add_filter("the_content", array($this,"just"),20);		
	}
	public function just($content){
		$share_buttons=get_option("wpi_admin_"."global_settings_"."share_buttons");
		$share_buttons_set=get_option("wpi_admin_"."global_settings_"."share_buttons_set");
		$share_buttons_location=get_option("wpi_admin_"."global_settings_"."share_buttons_location");
		if(is_single()){
			if($share_buttons==1){
				if($share_buttons_location=="above"){
					$content=do_shortcode("[wpi_designer_button share_id=".$share_buttons_set."]").$content;
				}else{
					$content= $content.do_shortcode("[wpi_designer_button share_id=".$share_buttons_set."]");
				}
			}			
		}
		return  $content;
	}
	public function js_wp_urls(){
		wp_register_script( 'wpi_js_urls', WPIDB_URL.'inc/wpi_script.js' );
		wp_localize_script('wpi_js_urls', 'WPiURLS', array( 'WPIDB_URL' => WPIDB_URL )); 		
		
		$fonts_list=WPiArray::get_fonts();
		$fonts=WPiTools::get_field_names($fonts_list);		
		wp_localize_script('wpi_js_urls', 'WPIDB_FONTS', $fonts); 
		
		$font_sizes=WPiArray::get_font_sizes();
		$font_sizes=WPiTools::get_list(array("suffix"=>"px","list"=>$font_sizes,"keys"=>false));			
		wp_localize_script('wpi_js_urls', 'WPIDB_FONTS_SIZES', $font_sizes); 
		
		$font_weights=WPiArray::get_font_weights();
		$font_weights=WPiTools::get_list(array("list"=>$font_weights,"keys"=>false));			
		wp_localize_script('wpi_js_urls', 'WPIDB_FONTS_WEIGHTS', $font_weights);
		
		$letter_spacing=WPiArray::get_letter_spacing();
		$letter_spacing=WPiTools::get_list(array("suffix"=>"px","list"=>$letter_spacing,"keys"=>false));		
		wp_localize_script('wpi_js_urls', 'WPIDB_LETTER_SPACING', $letter_spacing); 
		
		$margin=WPiArray::get_margin();
		$margin=WPiTools::get_list(array("suffix"=>"px","list"=>$margin,"keys"=>false));		
		wp_localize_script('wpi_js_urls', 'WPIDB_MARGIN', $margin); 
		
		$border_width=WPiArray::get_border_width();
		$border_width=WPiTools::get_list(array("suffix"=>"px","list"=>$border_width,"keys"=>false));		
		wp_localize_script('wpi_js_urls', 'WPIDB_BORDER_WIDTH', $border_width); 
		
		$WPIDB=array(
			"fonts"=>$fonts,
			"font_sizes"=>$font_sizes,
			"font_weights"=>$font_weights,
			"letter_spacing"=>$letter_spacing,
			"margin"=>$margin,
			"border_width"=>$border_width,
		);
		wp_localize_script('wpi_js_urls', 'WPIDB', $WPIDB); 
		
		wp_enqueue_script( 'wpi_js_urls' );
	}
	public function load_fonts() {
		if(is_ssl()) $http="https:"; else $http="http:";
		
		$fonts=WPiArray::get_google_fonts();
		$fontList=array();
		foreach($fonts as $font){
			if(isset($font['var']) && $font['var']!=""){$var=":".$font['var'];}else{$var="";}
			$fontList[]=$font['name'].$var;
		};
		$fonts_i=implode("|",$fontList);
		wp_register_style( "wpi_fonts", $http."//fonts.googleapis.com/css?family=".$fonts_i);
		wp_enqueue_style( "wpi_fonts");	
	}
    
    
	public function editor_buttons(){
		add_filter('mce_buttons',array($this,'mce_buttons_register'));
		add_filter("mce_external_plugins",array($this,'mce_add_buttons'));
	}	
	public function mce_buttons_register($buttons){
		array_push( $buttons, '|', 'wpi' );
		return $buttons;
	}
	function mce_add_buttons( $plugin_array ) {
		$plugin_array['wpi_designer_button'] = WPIDB_URL . 'mce-plugin.js';
		return $plugin_array;
	}
	public function load_plugin_textdomain() {
		$plugin="wpi-designer-button-shortcode";
		$domain=$plugin;
		$locale = get_locale();
		
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );		
		
		load_textdomain( $plugin, trailingslashit( WP_LANG_DIR ) . $domain.'-' . $locale . '.mo' );
		load_plugin_textdomain( $plugin, false, WPIDB_URL . 'languages/' );
	}
	public function admin_options(){
		$data1=array(
			"post_type"=>"wpi_des_but_sty",
			"args"=>array(
				"labels"=>array(
					"name"=>"Styles",
					"singular"=>"Style"
					),
				"description"=>"",
				"menu_icon"=>WPIDB_URL."logo.png",
				"show_in_menu"=>"wpi_admin_page",
				),
			"remove_support"=>array("editor"),			
		);
		WPiData::register_post_type($data1);
		
		$data2=array(
			"post_type"=>"wpi_des_but",
			"args"=>array(
				"labels"=>array(
					"name"=>"Buttons",
					"singular"=>"Button"
					),
				"description"=>"",
				"menu_icon"=>WPIDB_URL."logo.png",
				"show_in_menu"=>"wpi_admin_page",
				),
			"remove_support"=>array("editor"),			
		);
		WPiData::register_post_type($data2);		
		
		$data2=array(
			"post_type"=>"wpi_des_but_sli",
			"args"=>array(
				"labels"=>array(
					"name"=>"Call To Action",
					"singular"=>"Call To Action Buttons"
					),
				"description"=>"",
				"show_in_menu"=>"wpi_admin_page",
				),
			"remove_support"=>array("editor"),			
		);
		WPiData::register_post_type($data2);
		$data3=array(
			"post_type"=>"wpi_des_but_sb",
			"args"=>array(
				"labels"=>array(
					"name"=>"Share Buttons",
					"singular"=>"Share Buttons"
					),
				"description"=>"",
				"show_in_menu"=>"wpi_admin_page",
				),
			"remove_support"=>array("editor"),			
		);
		WPiData::register_post_type($data3);					
	}
	public function register_admin_menu(){
		global $wpi_gs_page;
		add_menu_page( "WPi", "WPi", 'manage_options', "wpi_admin_page", array($this, "admin_page"), WPIDB_URL."logo.png", 90 );
		$wpi_gs_page=add_submenu_page("wpi_admin_page", "Global Settings", "Global Settings", 'manage_options', "global_settings", array($this, "global_settings_page") ); 
	}
	
	
	public function global_settings_page(){
		$share_buttons_ids=WPiDesButCommon::get_share_buttons_ids();
		$fields=array(	
			array("label"=>"Share Buttons Enabled?", "name"=>'share_buttons', "type"=>"select",  "section"=>"Share Buttons", "group"=>"Share Buttons", "value"=> "", "list"=>array("0"=>"Disable", "1"=>"Enable")),		
			array("label"=>"Share Buttons Set", "name"=>'share_buttons_set', "type"=>"select",  "section"=>"Share Buttons Set", "group"=>"Share Buttons Set", "value"=> "", "list"=>$share_buttons_ids),
			array("label"=>"Share Button Above/Below Content", "name"=>'share_buttons_location', "type"=>"select",  "section"=>"Share Buttons Location", "group"=>"Share Buttons Location", "value"=> "", "list"=>array("above"=>"Above", "below"=>"Below"), "default"=>"below"),
		);		
		$share_buttons= WPiTemplate::html_option("global_settings", $fields);
		$help=self::help();		
		$args=array(
			array("id"=>"wpi_share_buttons", "text"=>"Share Buttons", "content"=>$share_buttons), 			
			array("id"=>"wpi_help", "text"=>"Help", "content"=>$help, "active"=>true),
		);	
		$tabs=WPiTemplate::create_tabs($args);		
		$out='
		<div id="wpi_admin">
		  <div id="wpi_gs_header">
			<div id="wpi_gs_heading"><img src="'.WPIDB_URL.'images/PRALI_dg.png"/><span>PRALI</spna></div>
			<div id="wpi_gs_product_logo"><img src="'.WPIDB_URL.'images/product_logo.png"/></div>
			<div id="wpi_gs_header_bg"></div>
			<div id="wpi_gs_header_ha"></div>
		  </div>  
		  <div id="wpi_gs_content">
			<div id="wpi_gs_content_inner">
			  <div id="wpi_gs_content_header">Global Settings</div>
			  <div id="wpi_gs_content_content"><form method="post" action="admin.php?page=global_settings">'.$tabs.get_submit_button().'</form></div>
			</div>
		  </div>
		</div>';
		echo $out;
	}
	public function help(){
		$help_args=array("notes"=>array(
		"To create Share Button Sets, first create button Style and next you can create Share Button Sets", 
		"Here you can find all global settings related to WPi Plugins in one place ",
		"You can copy generated shortcode from left panel and paste it in any post/page.",
		));
		$help=WPiDesButCommon::get_help_tab($help_args);
		return $help;
	}
	public function admin_page(){
		return 123;
	}
	public function enqueue_scripts(){
		wp_enqueue_style("wpi_designer_button", WPIDB_URL."style.css",array(),NULL, NULL);
		wp_enqueue_style("wpi_designer_button_preset_styles", WPIDB_URL."preset_styles.css",array(),NULL, NULL);	
		wp_enqueue_style("wpi_designer_button_genericons", WPIDB_URL."genericons/genericons/genericons.css",array(),NULL, NULL);
		wp_enqueue_script("wpi_front_global_script",	WPIDB_URL."inc/front_global.js","jQuery");	
		wp_enqueue_script("wpi_front_script",	WPIDB_URL."inc/front_script.js","jQuery");			
	}	
	public function enqueue_scripts_admin($hook){	
		global $post_type;
		global $wpi_gs_page;
		//echo $hook;
		if ( !in_array($hook, array('post-new.php','post.php', 'edit.php', "wpi_page_global_settings"))) return; 		
   		if(in_array($post_type,array("wpi_des_but_sli", "wpi_des_but_sty", "wpi_des_but_sb", "wpi_des_but")) || in_array($hook, array("wpi_page_global_settings"))) {
		
		}else{
			return;
		}
		wp_enqueue_style("wpi_designer_button", WPIDB_URL."style.css",array(),NULL, NULL);	
		wp_enqueue_style("wpi_designer_button_preset_styles", WPIDB_URL."preset_styles.css",array(),NULL, NULL);	
		wp_enqueue_style("wpi_designer_button_admin", WPIDB_URL."admin_style.css",array(),NULL, NULL);	
		wp_enqueue_style("wpi_designer_button_genericons", WPIDB_URL."genericons/genericons/genericons.css",array(),NULL, NULL);	
		wp_enqueue_script("wpi_front_global_script",	WPIDB_URL."inc/front_global.js","jQuery");		
		wp_enqueue_script("wpi_tools_script",	WPIDB_URL."inc/tools.js","jQuery");	
		wp_enqueue_script("wpi_global_script",	WPIDB_URL."inc/global.js","jQuery");			
		wp_enqueue_script("wpi_designer_button_script",	WPIDB_URL."inc/script.js","jQuery");		
		wp_enqueue_script("wpi_designer_button_presets_script",	WPIDB_URL."presets.js","jQuery");
		wp_enqueue_script("wpi_designer_button_themes_script",	WPIDB_URL."themes.js","jQuery");
		wp_enqueue_script("wpi_designer_button_icons_script",	WPIDB_URL."icons.js","jQuery");
		wp_enqueue_script("wpi_designer_button_sli_presets_script",	WPIDB_URL."sli_presets.js","jQuery");	
		
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
	}		
	public function designer_button($atts, $content=""){
		
		$defaults=array("id"=>"", "style_id"=>"", "slide_id"=>"", "share_id"=>"", 'link'=>'#', 'text'=>'button', "target"=>"", "icon"=>"", "display"=>false, "icon_position"=>"left");
		$atts=shortcode_atts($defaults, $atts, "wpi_designer_button");
		$button="";
		$output="";
		
		if($atts['slide_id']!=""){			
			$post=get_post($atts['slide_id']);
			$vars=array("slide_heading", "slide_heading_2", "slide_heading_3", "slide_text", "background_color", "background_image", "button_text", "button_link", "target", "slide_footer_text", "icon", "style_id");
			$slide_data=WPiData::get_post_meta($atts['slide_id'], $vars);
			$atts=array_merge($atts,$slide_data);
			$atts['link']=$atts['button_link'];
			$atts['text']=$atts['button_text'];	
			$slide_class="wpi_slide_".$atts['slide_id'];
			//$output.=print_r($atts,true);		
		}else if($atts['id']!=""){
			$post=get_post($atts['id']);					
			$atts['text']=get_post_meta($atts['id'], "text",true);	
			$atts['link']=get_post_meta($atts['id'], "link",true);
			$atts['target']=get_post_meta($atts['id'], "target",true);
			$atts['icon']=get_post_meta($atts['id'], "icon",true);
			$atts['icon_position']=get_post_meta($atts['id'], "icon_position",true);
			$atts['style_id']= get_post_meta($atts['id'], "style_id",true);	
		}
		
		$classes=WPiDesButCommon::get_button_style_class($atts['style_id']);
		if($atts['icon_position']=="right"){
			$left_icon="";
			$right_icon="<i class=''></i>";
			$icon_position="wpi_icon_right";
		}else{
			$left_icon="<i class=''></i>";
			$right_icon="";		
			$icon_position="wpi_icon_left";
		}		
		if($atts['icon']!=""){$icon_class="wpi_icon wpi_icon_".$atts['icon']." ".$icon_position; }else{$icon_class="";}	
		if($atts['display']==true){$display_class="wpi_display";}else{$display_class="";}
		if(trim($atts['text'])==""){$no_text_class="wpi_no_text";}else{$no_text_class="";}
		
		$target=strtolower(trim($atts['target']));	
		if($target=="new window" || $target=="newwindow" || $target=="_blank"){
			$atts['target']=$target;
		}else{
			$atts['target']="";
		}
		
		$button.="<a href='".$atts['link']."' class='wpi_designer_button {$classes} {$icon_class} {$display_class} {$no_text_class}' target='".$atts['target']."' >".$left_icon."<span class='wpi_text'>".$atts['text']."</span>".$right_icon."</a>";
		
		if($atts['slide_id']!=""){
			$output.="<div class='wpi_slide {$slide_class}'>";
			$output.="<div class='wpi_slide_image'></div>";
			$output.="<div id='wpi_slide_content' >";	
				$output.="<div class='wpi_slide_heading'>";
				if($atts['slide_heading']!="") {
					$output.="<div><span>".$atts['slide_heading']."</span></div>";
				}
				if($atts['slide_heading_2']!="") {
					$output.="<div><span style='display:none'>".$atts['slide_heading_2']."</span></div>";
				}
				if($atts['slide_heading_3']!="") {
					$output.="<div><span style='display:none'>".$atts['slide_heading_3']."</span></div>";
				}
				$output.="</div>";
				$output.="<div class='wpi_slide_text'>".$atts['slide_text']."</div>";
				if($atts['text']!=""){
					$output.=$button;
				}
			$output.="</div>";
			$output.="<div class='wpi_slide_footer' >".$atts['slide_footer_text']."</div>";
			$output.="</div>";
		}else if($atts['share_id']!=""){
			$style_id=get_post_meta($atts['share_id'], "style_id",true);				
			$link=urlencode(self::current_page_url());			
			$title= the_title_attribute('echo=0');
			$fromurl="";
			$desc="";
			$imagelink="";
			$buttons_data=array(
				"facebook"=>array("link"=>"https://www.facebook.com/sharer/sharer.php?u=".$link,
					"icon"=>"facebook-alt",	"color"=>"", "title"=>"Share on  Facebook"),
				"twitter"=>array("link"=>"https://twitter.com/intent/tweet?source=".$link."&text=".$title."&via=".$fromurl,
					"icon"=>"twitter","color"=>"", "title"=>"Tweet"),
				"googleplus"=>array("link"=>"https://plus.google.com/share?url=".$link,
					"icon"=>"googleplus-alt","color"=>"", "title"=>"Share on Google+"),
				"linkedin"=>array("link"=>"http://www.linkedin.com/shareArticle?mini=true&url=".$link."&title=".$title."&summary=".$desc."&source=".$fromurl,
					"icon"=>"linkedin","color"=>"", "title"=>"Share on LinkedIn"),
				"pinterest"=>array("link"=>"http://pinterest.com/pin/create/button/?url=".$link."&media=".$imagelink."&description=".$desc,
					"icon"=>"pinterest","color"=>"", "title"=>"Pin it"),
				"tumblr"=>array("link"=>"http://www.tumblr.com/share?v=3&u=".$link."&t=".$title."&s=",
					"icon"=>"tumblr","color"=>"", "title"=>"Post on Tumblr"),
				"stumbleupon"=>array("link"=>"http://www.stumbleupon.com/submit?url=".$link,
					"icon"=>"stumbleupon","color"=>"", "title"=>"Share on  Stumbleupon"),
				"reddit"=>array("link"=>"http://www.reddit.com/submit?url=".$link."&title=".$title,
					"icon"=>"reddit","color"=>"", "title"=>"Submit to Reddit"),
				"wordpress"=>array("link"=>"http://wordpress.com/press-this.php?u=".$link."&t=".$title."&s=".$desc."&i=".$imagelink,
					"icon"=>"wordpress","color"=>"", "title"=>"Publish on WordPress"),
				"email"=>array("link"=>"mailto:?subject=".$title."&body=".$desc.":".$link,
					"icon"=>"mail","color"=>"", "title"=>"Email"),
			);
			
			$share_buttons="<div class='wpi_share_buttons wpi_share_buttons_".$atts['share_id']."'>
				<div id='wpi_sb_text' class='wpi_sb_text'>Share on :</div><ul>";
			foreach($buttons_data as $k => $v){
				$state=get_post_meta($atts['share_id'], $k,true);
				if($state==1) $display="" ; else $display="wpi_none"; 
				$share_buttons.="<li class='wpi_sb_".$k." ".$display."'><a href='".$v['link']."' target='_blank' title='Share on  Facebook' class='wpi_designer_button wpi_designer_button_{$style_id} wpi_no_text wpi_icon wpi_icon_".$v['icon']."' href='#'><i></i></a></li>";
			}
			$share_buttons.="</ul></div>";
						
			$output.=$share_buttons;
		}else{			
			$output.=$button.$atts['slide_id'];	
		}	
		return $output;
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
	public function ssid_column($cols) {
		$cols['ssid'] = 'ID';
		return $cols;
	}
	
	 
	// CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
	public function buttons_head($defaults) {		
		$defaults['ssid'] = 'ID';
		$defaults['button_style'] = 'Button';
		$defaults['shortcode'] = 'Shortcode';
		return $defaults;
	}
	public function buttons_content($column_name, $post_ID) {
		if ($column_name == 'ssid') {
			echo $post_ID;
		}
		if ($column_name == 'button_style') {			
			$content="[wpi_designer_button display='true' id={$post_ID}]";
			echo do_shortcode( $content );
		}
		if ($column_name == 'shortcode') {			
			$content="[wpi_designer_button id={$post_ID}]";
			echo $content ;
		}
	}
	public function styles_head($defaults) {		
		$defaults['ssid'] = 'Style_ID';
		$defaults['style'] = 'Style';
		return $defaults;
	}
	public function styles_content($column_name, $post_ID) {
		if ($column_name == 'ssid') {
			echo $post_ID;
		}
		if ($column_name == 'style') {
			$content="[wpi_designer_button style_id={$post_ID}]";
			echo do_shortcode( $content );
		}
	}
	
	public function wpi_body_class( $classes ) {		
		$classes[] = 'wpi_db';		
		return $classes;
	}
	
}
$wpi_designer_button_shortcode=new WPiDesignerButtonShortcode;
?>