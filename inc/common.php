<?php
class WPiDesButCommon{
	public static function get_button_style_class($style_id){
		$class=array();
		if($style_id!=""){
			$post=get_post($style_id);
			$shape=get_post_meta($style_id, "shape",true);
			$shadow=get_post_meta($style_id, "shadow",true);
			$text_shadow=get_post_meta($style_id, "text_shadow",true);
			$texture=get_post_meta($style_id, "texture",true);
			$padding=get_post_meta($style_id, "padding",true);
			$border_sides=get_post_meta($style_id, "border_sides",true);
			$icon=get_post_meta($style_id, "icon",true);
			
			if($shadow!="none"){$class["shadow"]="shadow wpi_shadow_".$shadow;}
			if($text_shadow!=""){$class['text_shadow_class']="wpi_text_shadow_".$text_shadow;}	
			if($texture!=""){$class['texture_class']="wpi_texture_".$texture;}		
			if($border_sides!=""){$class['border_sides_class']="wpi_border_".$border_sides;}			
			if($padding!=""){$class['padding_class']="wpi_padding_".$padding;}			
			if($shape!=""){$class['shape_class']="wpi_".$shape;	}
			if($icon!=""){$class['icon_class']="wpi_icon wpi_icon_".$icon;}
			if($style_id!=""){$class['class']="wpi_designer_button_".$style_id;}	
			
		}
		$classes=implode(" ",$class);
		return $classes;
	}		
	public  static function get_style_ids(){
		$args = array(			
			'post_type' => 'wpi_des_but_sty', 'post_status'=>array('publish'), 'numberposts'       => -1
		); 
		
		$style_ids=array();	
		
		$button_styles = get_posts($args);
		foreach ( $button_styles as $bs ) :			
			$style_ids[$bs->ID]= $bs->post_title;
        endforeach;	
		
		$preset_styles=WPiArray::get_preset_styles();
		foreach($preset_styles as $style_id){
			$style_ids["preset_".$style_id]= "preset_".$style_id;	
		}
		
		return	$style_ids;
	}
	public static function get_share_buttons_ids(){
		$args = array(			
			'post_type' => 'wpi_des_but_sb', 'post_status'=>array('publish'), 'numberposts'       => -1
		); 
		$ids=array();	
		$share_buttons = get_posts($args);
		foreach ($share_buttons as $sb ) :			
			$ids[$sb->ID]= $sb->post_title;
        endforeach;	
		return	$ids;
	}
	public static function get_styles(){		
		$style_ids=self::get_style_ids();		
		$output="";
		$r="";
		foreach($style_ids as $k => $v){
			$classes=WPiDesButCommon::get_button_style_class($k);			
			$output.="<div class='wpi_style_holder'><div id='wpi_db_sty_".$k."' class='wpi_style wpi_designer_button {$classes}' >Button <span class='wpi_id'>".$k."</span></div></div>";
			$r.=print_r($classes,true);
		}
		//$r=print_r($style_ids,true);
		return $output;
		//return $r;
	}
	public static function get_id($args){
		$defaults=array("id"=>0, "label"=>"label");
		$args=WPiTools::extend( $defaults, $args );		
		return "<div class='wpi_id wpi_icon wpi_icon_anchor'><i></i><div class='wpi_info'><div class='wpi_text'>".$args['id']."</div><div>".$args['label']."</div></div></div>";
	}
	public static function get_fonts_options(){
		$fonts_arr=WPiArray::get_fonts();
		$fonts_names=WPiTools::get_field_names($fonts_arr);
		$fonts=WPiTools::array2object($fonts_names);
		return $fonts;
	}
	public static function get_help_tab($args){
		$popup_content="<ol class='wpi_ol'>";
		$content="<div class='wpi_notes_content_holder'>";
		$c=0;
		foreach($args['notes'] as $note){
			if($c==0){$display="";}else{$display="wpi_none";}			
			$content.="<div class='wpi_note wpi_note_{$c} {$display}' >".$note."</div>";
			$popup_content.="<li>".$note."</li>";
			$c++;
		}
		$content.="</div>";
		$popup_content.="</ol>";
		$nav="<div class='wpi_notes_nav wpi_nav'>
		<span class='wpi_icon wpi_icon_previous wpi_no_text wpi_prev'><i></i><b>Prev</b></span>
		<span class='wpi_view_all'>View all</span>
		<span class='wpi_icon wpi_icon_next wpi_no_text wpi_next'><i></i><b>Next</b></span>
		</div>";
		$popup="<div class='wpi_popup wpi_none'>
		<div class='wpi_popup_nav'><div class='wpi_popup_nav_holder'><span class='wpi_designer_button wpi_back wpi_icon wpi_icon_previous'><i></i>Back</span></div></div>		
		<div class='wpi_popup_content'>".$popup_content."</div>
		</div>";
		$note="<div class='wpi_notes wpi_pn'><div class='wpi_notes_content'><span class='wpi_icon wpi_icon_book wpi_no_text'><i></i></span><span class='wpi_notes_title'>Guide</span>".$content."</div>".$nav."</div>";
		$help=$popup.$note;	
		return $help;
	}
}

?>