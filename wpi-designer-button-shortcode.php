<?php
/**
 * Plugin Name: WPi Designer Button Shortcode
 * Plugin URI: http://wooprali.prali.in/plugins/wpi-designer-button-shortcode
 * Description: Create Designer Buttons anywhere in wordpress using button shortcode [wpi_designer_button]
 * Version: 1.0.0
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
		$fonts=WPiArray::get_google_fonts();
		$fontList=array();
		foreach($fonts as $font){
			if(isset($font['var']) && $font['var']!=""){$var=":".$font['var'];}else{$var="";}
			$fontList[]=$font['name'].$var;
		};
		$fonts_i=implode("|",$fontList);
		wp_register_style( "wpi_fonts", "http://fonts.googleapis.com/css?family=".$fonts_i);
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
	}
	public function register_admin_menu(){
		add_menu_page( "WPi", "WPi", 'manage_options', "wpi_admin_page", array($this, "admin_page"), WPIDB_URL."logo.png", 90 );
	}
	public function admin_page(){
		return 123;
	}
	public function enqueue_scripts(){
		wp_enqueue_style("wpi_designer_button", WPIDB_URL."style.css",array(),NULL, NULL);
		wp_enqueue_style("wpi_designer_button_preset_styles", WPIDB_URL."preset_styles.css",array(),NULL, NULL);	
		wp_enqueue_script("wpi_front_global_script",	WPIDB_URL."inc/front_global.js","jQuery");	
		wp_enqueue_script("wpi_front_script",	WPIDB_URL."inc/front_script.js","jQuery");			
	}	
	public function enqueue_scripts_admin($hook){	
		global $post_type;
		if ( !in_array($hook, array('post-new.php','post.php', 'edit.php'))) return; 		
   		if(!in_array($post_type,array("wpi_des_but_sli", "wpi_des_but_sty", "wpi_des_but"))) return;
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
		
		$defaults=array("id"=>"", "style_id"=>"", "slide_id"=>"", 'link'=>'#', 'text'=>'button', "target"=>"", "icon"=>"", "display"=>false);
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
			$atts['style_id']= get_post_meta($atts['id'], "style_id",true);	
		}
		
		$classes=WPiDesButCommon::get_button_style_class($atts['style_id']);		
		if($atts['icon']!=""){$icon_class="wpi_icon wpi_icon_".$atts['icon'];}else{$icon_class="";}	
		if($atts['display']==true){$display_class="wpi_display";}else{$display_class="";}
		if(trim($atts['text'])==""){$no_text_class="wpi_no_text";}else{$no_text_class="";}
		
		$target=strtolower(trim($atts['target']));	
		if($target=="new window" || $target=="newwindow" || $target=="_blank"){
			$atts['target']=$target;
		}else{
			$atts['target']="";
		}
		$button.="<a href='".$atts['link']."' class='wpi_designer_button {$classes} {$icon_class} {$display_class} {$no_text_class}' target='".$atts['target']."' ><i></i><span class='wpi_text'>".$atts['text']."</span></a>";
		
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
				$output.=$button;
			$output.="</div>";
			$output.="<div class='wpi_slide_footer' >".$atts['slide_footer_text']."</div>";
			$output.="</div>";
		}else{			
			$output.=$button.$atts['slide_id'];	
		}	
		return $output;
	}
	
	
	public function ssid_column($cols) {
		$cols['ssid'] = 'ID';
		return $cols;
	}
	
	 
	// CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
	public function buttons_head($defaults) {		
		$defaults['ssid'] = 'ID';
		$defaults['button_style'] = 'Button';
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